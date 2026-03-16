<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware; // 👈 IMPORTA EL MIDDLEWARE

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 1. Confiar en Cloudflare para que reconozca la URL externa
        $middleware->trustProxies(at: '*');

        // 2. Excepciones de seguridad para el túnel
        $middleware->validateCsrfTokens(except: [
            '/auth/buscar-preguntas',
            '/auth/verificar-respuestas',
            '/auth/recuperacion'
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
