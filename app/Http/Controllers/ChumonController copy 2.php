<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// app/Http/Controllers/ChumonController.php
use App\Models\ChumonKihon;
use App\Models\ChumonMeisai;
use App\Models\ChumonStart;
use App\Models\Shohin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmedMail;

class ChumonController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'shohinno' => 'required',
            'suryo' => 'required|numeric|min:0.01',
        ]);

        $hsid = Auth::user()->hsid;

        // ① 入力中の注文基本を取得 or 作成
        $kihon = ChumonKihon::firstOrCreate(
            ['hsid' => $hsid, 'urikatano' => '02', 'status' => 0],
            ['shoridate' => now()]
        );

        // ② 既存の同一商品明細を探す
        $meisai = ChumonMeisai::where('kihonno', $kihon->kihonno)
            ->where('shohinno', $request->shohinno)
            ->first();

        if ($meisai) {
            // ③ あれば数量加算
            ChumonMeisai::where('kihonno', $kihon->kihonno)
                ->where('shohinno', $request->shohinno)
                ->update([
                    'suryo' => $meisai->suryo + $request->suryo,
                ]);
        } else {
            // ④ なければ新規明細
            $maxMeisaiNo = ChumonMeisai::where('kihonno', $kihon->kihonno)
                ->max('meisaino') ?? 0;

            $shohin = Shohin::findOrFail($request->shohinno);

            ChumonMeisai::create([
                'kihonno' => $kihon->kihonno,
                'meisaino' => $maxMeisaiNo + 1,
                'shohinno' => $request->shohinno,
                'suryo' => $request->suryo,
                'tanka' => $shohin->tanka,
                'hyojitanka' => $shohin->hyojitanka,
                'tani' => $shohin->tani,
                'urikatano' => '02',
            ]);
        }

        return redirect('/shohin')->with('success', '商品を追加しました');
    }

    /** 注文内容確認 */
    public function index()
    {
        $kihon = ChumonKihon::where('hsid', Auth::user()->hsid)
            ->where('status', 0)
            ->with(['meisai.shohin'])
            ->first();

        return view('chumon.index', compact('kihon'));
    }

    /** 数量変更 */
    public function update(Request $request)
    {
        $request->validate([
            'kihonno'  => 'required',
            'meisaino' => 'required',
            'suryo'    => 'required|integer|min:1',
        ]);

    ChumonMeisai::where('kihonno', $request->kihonno)
        ->where('meisaino', $request->meisaino)
        ->update([
            'suryo' => $request->suryo,
        ]);

        return redirect('/chumon')->with('success', '数量を変更しました');
    }

    /** 明細削除 */
    public function delete(Request $request)
    {
        $request->validate([
            'kihonno'  => 'required',
            'meisaino' => 'required',
        ]);

        ChumonMeisai::where('kihonno', $request->kihonno)
            ->where('meisaino', $request->meisaino)
            ->delete();

        return redirect('/chumon')->with('success', '明細を削除しました');
    }

    public function confirm(Request $request)
    {
        $hsid = Auth::user()->hsid;

        try {

            $kihon = null;
            $meisaiList = null;

            DB::transaction(function () use ($hsid, &$kihon, &$meisaiList) {

                $kihon = ChumonKihon::where('hsid', $hsid)
                    ->where('status', 0)
                    ->lockForUpdate()
                    ->first();

                if (!$kihon) {
                    throw new \RuntimeException('入力中の注文がありません。');
                }

                $meisaiList = ChumonMeisai::where('kihonno', $kihon->kihonno)
                    ->with('shohin')
                    ->get();

                if ($meisaiList->isEmpty()) {
                    throw new \RuntimeException('注文明細がありません。');
                }

                $errors = [];

                // 在庫チェック
                foreach ($meisaiList as $meisai) {
                    $shohin = Shohin::where('shohinno', $meisai->shohinno)
                        ->lockForUpdate()
                        ->first();

                    if ($shohin->stock < $meisai->suryo) {
                        $lack = $meisai->suryo - $shohin->stock;
                        $errors[] =
                            "「{$shohin->shohinname2}」が {$lack} 個不足しています"
                            . "（在庫 {$shohin->stock}）";
                    }
                }

                if (!empty($errors)) {
                    throw new \RuntimeException(implode("\n", $errors));
                }

                // 在庫引当
                foreach ($meisaiList as $meisai) {
                    Shohin::where('shohinno', $meisai->shohinno)
                        ->decrement('stock', $meisai->suryo);
                }

                // ✅ 注文確定（今処理中のものを更新）
                $kihon->status = 1;
                $kihon->save();
            });

            // ✅ トランザクション外でメール送信
Mail::to(Auth::user()->email)
    ->send(new OrderConfirmedMail($kihon, $meisaiList));

Mail::to('nishiyama-kenichi-vr@shumei.or.jp')
    ->send(new OrderConfirmedMail($kihon, $meisaiList));

        } catch (\RuntimeException $e) {
            return redirect('/chumon')
                ->withErrors(explode("\n", $e->getMessage()));
        }

        return redirect('/shohin')->with('success', '注文を確定しました');
    }

    public function history()
    {
        $historyList = ChumonKihon::where('hsid', Auth::user()->hsid)
            ->where('status', 1)
            ->with(['meisai.shohin'])
            ->with([
                'meisai.shohin',
                'meisai.uriage'   // ← これを追加
            ])
            ->orderByDesc('shoridate')
            ->get();

        return view('chumon.history', compact('historyList'));
    }

public function addMulti(Request $request)
{
    $items = $request->input('items');

    if (!$items || !is_array($items)) {
        return back()->withErrors(['数量が送信されていません']);
    }

    $hsid = Auth::user()->hsid;

    $kihon = ChumonKihon::firstOrCreate(
        ['hsid' => $hsid, 'urikatano' => '02', 'status' => 0],
        ['shoridate' => now()]
    );

    $hasValidItem = false;

    foreach ($items as $shohinno => $qty) {

        $qty = (float)$qty;

        if ($qty <= 0) continue;

        // 🔴 ここで必ず取得する
        $shohin = Shohin::findOrFail($shohinno);

        // 🔴 ここで min チェック
        if ($qty < $shohin->min) {
            return back()->withErrors([
                "{$shohin->shohinname2} の数量は {$shohin->min} 以上で入力してください"
            ]);
        }

        // 在庫チェック
        //if ($qty > $shohin->stock) {
        //    return back()->withErrors([
        //        "{$shohin->shohinname2} の在庫を超えています"
        //    ]);
        //}

        $hasValidItem = true;

        $meisai = ChumonMeisai::where('kihonno', $kihon->kihonno)
            ->where('shohinno', $shohinno)
            ->first();

        if ($meisai) {

            // 🔵 save() を使わない
            ChumonMeisai::where('kihonno', $kihon->kihonno)
                ->where('shohinno', $shohinno)
                ->update([
                    'suryo' => $meisai->suryo + $qty
                ]);

        } else {

            $maxMeisaiNo = ChumonMeisai::where('kihonno', $kihon->kihonno)
                ->max('meisaino') ?? 0;

            //$shohin = Shohin::findOrFail($shohinno);
            $shohin = ChumonStart::findOrFail($shohinno, $startdate);

            ChumonMeisai::create([
                'kihonno'   => $kihon->kihonno,
                'meisaino'  => $maxMeisaiNo + 1,
                'shohinno'  => $shohinno,
                'startdate' => $shohin->startdate,
                'suryo'     => $qty,
                'tanka'     => $shohin->tanka,
                'hyojitanka'=> $shohin->hyojitanka,
                'tani'      => $shohin->tani,
                'urikatano' => '02',
            ]);
        }
    }

    if (!$hasValidItem) {
        return back()->withErrors(['数量が入力されていません']);
    }

    return redirect('/shohin')->with('success', '商品を追加しました');
}

}
