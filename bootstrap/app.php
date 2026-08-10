<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \App\Providers\AppServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: ['webhooks/whatsapp']);
        $middleware->web(prepend: [
            \App\Http\Middleware\SecureHeaders::class,
            \App\Http\Middleware\RejectLargeRequests::class,
            \App\Http\Middleware\VerifySameOrigin::class,
            \App\Http\Middleware\ApplySeoRedirects::class,
        ]);
        $middleware->web(append: [
            \App\Http\Middleware\ApplyThemeSettings::class,
            \App\Http\Middleware\ShareCmsData::class,
            \App\Http\Middleware\AuditSecurityEvents::class,
            \App\Http\Middleware\OptimizePublicResponse::class,
            \App\Http\Middleware\TrackPageVisits::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
