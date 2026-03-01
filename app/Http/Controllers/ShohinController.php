<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/ShohinController.php
use App\Models\Shohin;

class ShohinController extends Controller
{
    public function index()
    {
        $shohinList = Shohin::where('is_on_sale', 1)
            ->orderBy('shohinno')
            ->get();

        //return view('shohin.index', compact('shohins'));
        return view('shohin.index', compact('shohinList'));
    }
}
