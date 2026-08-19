<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Définir des alias de middleware
        $middleware->alias(['role' => App\Http\Middleware\RoleMiddleware::class]);

        // Applique la langue choisie (session) à chaque requête web
        $middleware->web(append: [App\Http\Middleware\SetLocale::class]);

        // Derrière un reverse proxy (Traefik/Dokploy, Nginx…) : faire confiance
        // aux en-têtes X-Forwarded-* pour détecter le bon schéma (https) et l'IP réelle.
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
