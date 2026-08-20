<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //$middleware->append(\App\Http\Middleware\BasicAuth::class); 2026.8.20ベーシック認証を中止
        //$middleware->trustProxies(at: '*'); この行は、最初からコメントアウト2026.？
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
