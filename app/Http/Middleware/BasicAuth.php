<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicAuth
{
    public function handle($request, Closure $next)
    {
        $user = env('BASIC_AUTH_USER');
        $pass = env('BASIC_AUTH_PASSWORD');

        if (
            !isset($_SERVER['PHP_AUTH_USER']) ||
            $_SERVER['PHP_AUTH_USER'] !== $user ||
            $_SERVER['PHP_AUTH_PW'] !== $pass
        ) {
            header('WWW-Authenticate: Basic realm="Restricted Area"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }

        return $next($request);
    }
}

