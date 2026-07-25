<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // Register your route middleware here
        $middleware->alias([
            // Add this if you're using Spatie Permission package
            'permission' => PermissionMiddleware::class,
            'role'       => RoleMiddleware::class,
            
            // Example of other common middlewares
            // 'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // If you want to apply middleware to specific route groups
        $middleware->web(append: [
                 //
        ]);



    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

    