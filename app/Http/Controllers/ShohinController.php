<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChumonStart;
use Carbon\Carbon;

class ShohinController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $shohinList = ChumonStart::with([
                //'shohin.suryoRules'   // ← 追加
                'suryoRules'
            ])
            ->where('startdate', '<=', $today)
            ->where('enddate', '>=', $today)
            ->orderBy('startdate')
            ->orderBy('shohinno')
            ->get();

        //データ加工（在庫表示の整形）
        foreach ($shohinList as $s) {
            if ($s->tani === 'g') {
                $s->stock_view = rtrim(rtrim($s->stock, '0'), '.') . ' kg';
            } else {
                $s->stock_view = rtrim(rtrim($s->stock, '0'), '.') . ' ' . $s->tani;
            }
        }

        return view('shohin.index', compact('shohinList'));
    }
}
