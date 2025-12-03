<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckLive;
use App\Http\Middleware\EnsureSignin;
use App\Http\Middleware\DisableBack;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'checkLive' => CheckLive::class,
            'ensureSignin' => EnsureSignin::class,
            'disableBack' => DisableBack::class,
        ]);
        // Disable encryption for specific cookies
        $middleware->encryptCookies(except: [
            'notshow_breeze2025YearEnd',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
