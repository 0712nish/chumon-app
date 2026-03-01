<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ShohinController;
use App\Http\Controllers\ChumonController;
use App\Http\Controllers\AccountController;

//Route::get('/', function () {
//    return view('auth.login');
//})->name('login');
Route::get('/', function () {
    return redirect('/shohin');
});

// routes/web.php
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/shohin', [ShohinController::class, 'index']);
    Route::get('/chumon', [ChumonController::class, 'index']);
    Route::post('/chumon/add', [ChumonController::class, 'add']);
    Route::post('/chumon/update', [ChumonController::class, 'update']);
    Route::post('/chumon/delete', [ChumonController::class, 'delete']);
    Route::post('/chumon/confirm', [ChumonController::class, 'confirm']);

});

Route::get('/chumon/history', [ChumonController::class, 'history']);

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'edit']);
    Route::post('/account/update', [AccountController::class, 'update']);
});

Route::post('/chumon/add-multi', [ChumonController::class, 'addMulti']);
