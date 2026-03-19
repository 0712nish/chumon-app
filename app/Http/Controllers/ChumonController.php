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
use Carbon\Carbon;

class ChumonController extends Controller
{
    /*
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
    */

    /** 注文内容確認 */
    public function index()
    {
        $kihon = ChumonKihon::where('hsid', Auth::user()->hsid)
            ->where('status', 0)
            ->with(['meisai.suryoRules']) //
            ->first();

        return view('chumon.index', compact('kihon'));
    }

    /** 数量変更 */
    public function update(Request $request)
    {
        $request->validate([
            'kihonno'  => 'required',
            'meisaino' => 'required',
            'suryo'    => 'required|numeric|min:0',
        ]);

        // 明細取得
        $meisai = ChumonMeisai::where('kihonno', $request->kihonno)
            ->where('meisaino', $request->meisaino)
            ->firstOrFail();

        // 在庫取得
        $start = ChumonStart::where('shohinno', $meisai->shohinno)
            ->where('startdate', $meisai->startdate)
            ->firstOrFail();

        // 単位変換（kg → g）
        $stock = $start->stock;
        if ($start->tani === 'g') {
            $stock = $start->stock * 1000;
        }

        // 在庫チェック
        if ($request->suryo > $stock) {
            return back()->withErrors([
                "{$start->shohinname2} の在庫が不足しています"
            ]);
        }

        // 数量更新
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
                    //->with('start')
                    ->get();

                if ($meisaiList->isEmpty()) {
                    throw new \RuntimeException('注文明細がありません。');
                }

                $errors = [];

                // 在庫チェック
                foreach ($meisaiList as $meisai) {

                    $start = ChumonStart::where('shohinno', $meisai->shohinno)
                        ->where('startdate', $meisai->startdate)
                        ->lockForUpdate()
                        ->first();

                    // 在庫（gに統一）
                    $stock = $start->stock;
                    $qty   = $meisai->suryo;

                    if ($start->tani === 'g') {
                        $stock = $start->stock * 1000; // kg → g
                    }

                    if ($qty > $stock) {

                        // 不足量（g）
                        $lack = $qty - $stock;

                        // 表示用（kg変換）
                        if ($start->tani === 'g') {
                            $lackView  = rtrim(rtrim($lack / 1000, '0'), '.') . ' kg';
                            $stockView = rtrim(rtrim($start->stock, '0'), '.') . ' kg';
                        } else {
                            $lackView  = rtrim(rtrim($lack, '0'), '.') . ' ' . $meisai->tani;
                            $stockView = rtrim(rtrim($start->stock, '0'), '.') . ' ' . $start->tani;
                        }

                        $errors[] =
                            "「{$meisai->shohinname2}」が {$lackView} 不足しています"
                            . "（在庫 {$stockView}）";
                    }
                }

                if (!empty($errors)) {
                    throw new \RuntimeException(implode("\n", $errors));
                }

                // 在庫引当
                foreach ($meisaiList as $meisai) {

                    $start = ChumonStart::where('shohinno', $meisai->shohinno)
                        ->where('startdate', $meisai->startdate)
                        ->lockForUpdate()
                        ->first();

                    $qty = $meisai->suryo;

                    // g → kg に変換
                    if ($start->tani === 'g') {
                        $qty = $meisai->suryo / 1000;
                    }

                    ChumonStart::where('shohinno', $meisai->shohinno)
                        ->where('startdate', $meisai->startdate)
                        ->decrement('stock', $qty);
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
        // 3か月前の月の1日
        $fromDate = Carbon::now()
        ->subMonths(3)
        ->startOfMonth();

        $historyList = ChumonKihon::where('hsid', Auth::user()->hsid)
            ->where('status', 1)
            ->whereDate('shoridate', '>=', $fromDate) // 過去3ヶ月分
            ->with([
                'meisai'
            ])
            //->orderByDesc('shoridate')
            ->orderByDesc('kihonno')
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

    foreach ($items as $item) {

        $qty = (float)($item['qty'] ?? 0);

        if ($qty <= 0) continue;

        $shohinno = $item['shohinno'];
        $startdate = $item['startdate'];

        $start = ChumonStart::where('shohinno', $shohinno)
            ->where('startdate', $startdate)
            ->firstOrFail();

        //if ($qty < $start->min) {
        //    return back()->withErrors([
        //        "{$start->shohinname2} の数量は {$start->min} 以上で入力してください"
        //    ]);
        //}

        $hasValidItem = true;

        $meisai = ChumonMeisai::where('kihonno', $kihon->kihonno)
            ->where('shohinno', $shohinno)
            ->where('startdate', $startdate)
            ->first();

        if ($meisai) {

            ChumonMeisai::where('kihonno', $kihon->kihonno)
                ->where('shohinno', $shohinno)
                ->where('startdate', $startdate)
                ->update([
                    //'suryo' => DB::raw("suryo + $qty")
                    'suryo' => $qty
                ]);

        } else {

            $maxMeisaiNo = ChumonMeisai::where('kihonno', $kihon->kihonno)
                ->max('meisaino') ?? 0;

            ChumonMeisai::create([
                'kihonno'   => $kihon->kihonno,
                'meisaino'  => $maxMeisaiNo + 1,
                'shohinno'  => $shohinno,
                'startdate' => $startdate,
                'shohinname2'  => $start->shohinname2,
                'suryo'     => $qty,
                'tanka'     => $start->tanka,
                'hyojitanka'=> $start->hyojitanka,
                'tani'      => $start->tani,
                'suryoruleno' => $start->suryoruleno,
                'min'      => $start->min,
                //'stock'      => $start->stock,
                //'step'      => $start->step,
                'biko'      => $start->biko,
                'urikatano' => '02',
            ]);
        }

        $stock = $start->stock;

        // 単位変換  単位がgでも、在庫の中は、kgになっている
        if ($start->tani === 'g') {
            $stock = $start->stock * 1000; // kg → g に変換
        }

        // 比較  プルダウン上がkgでも、$qtyの中は、gになっている
        if ($qty > $stock) {
            return back()->withErrors([
                "{$start->shohinname2} の在庫が不足しています"
            ]);
        }
        
    }

    if (!$hasValidItem) {
        return back()->withErrors(['数量が入力されていません']);
    }

    return redirect('/shohin')->with('success', '商品を追加しました');
}

}
