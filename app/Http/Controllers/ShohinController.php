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

        return view('shohin.index', compact('shohinList'));
    }
}
