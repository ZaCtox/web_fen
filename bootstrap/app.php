<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'redirect.authenticated' => \App\Http\Middleware\RedirectIfAuthenticatedToDashboard::class,
            'role.api' => \App\Http\Middleware\RoleApiMiddleware::class,
            'handle.cors' => \App\Http\Middleware\HandleCors::class,
            'incident.modify' => \App\Http\Middleware\IncidentModificationMiddleware::class,
        ]);
        
    })
    



    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();


