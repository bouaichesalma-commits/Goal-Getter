<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\JwtMiddleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register your custom JWT middleware
        $middleware->alias([
           // 'jwt.auth' => \App\Http\Middleware\JwtMiddleware::class,
            'jwt' => \App\Http\Middleware\JwtMiddleware::class, // Shorter alias
        ]);
        
        // Also register the package middleware if needed
        // $middleware->alias([
        //     'jwt.package' => \PHPOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class,
        // ]);
    })
    ->withExceptions(Function (Exceptions $exceptions):void {
        //
    })->create();