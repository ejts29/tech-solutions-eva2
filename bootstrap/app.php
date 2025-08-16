<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // se Define las rutas de la aplicación 
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', 
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //se Registra las alias de middleware 
        $middleware->alias([
            'jwt.verify' => \App\Http\Middleware\JwtMiddleware::class,
            'jwt'        => \App\Http\Middleware\JwtMiddleware::class, // opcional, alias corto
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
