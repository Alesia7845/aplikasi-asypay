<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsSiswa;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => IsAdmin::class,
            'is_siswa' => IsSiswa::class, // Menambahkan koma di sini
        ]);

        $middleware->validateCsrfTokens(
            except: [
                'midtrans/notification', // Tambahkan route webhook Midtrans di sini
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
