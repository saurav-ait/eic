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
   ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'checkLogin' => \App\Http\Middleware\CheckAuth::class,
            'guestOnly'  => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'role'       => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\SetSecurityHeaders::class,
        ]);

        $middleware->validateCsrfTokens(except: []);
    })
    
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
