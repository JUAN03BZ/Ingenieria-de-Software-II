<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias de middlewares para usarlos en rutas, p.ej. 'role:alcalde'
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // (Opcional) agregar a grupos web/api o globales si lo necesitas:
        // $middleware->web(append: [ ... ]);
        // $middleware->api(append: [ ... ]);
        // $middleware->append(\App\Http\Middleware\SomethingGlobal::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
