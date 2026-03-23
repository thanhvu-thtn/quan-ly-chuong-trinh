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
        //
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            // 1. Cài một thông báo lỗi vào Session tạm thời
            session()->flash('error', 'Bạn cần đăng nhập để truy cập vào khu vực này!');
            
            // 2. Trả về đích đến
            return route('auth.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
