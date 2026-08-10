<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditSecurityEvents
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->user() && !$request->isMethodSafe()) {
            Log::notice('Authenticated state-changing request.', [
                'user_id' => $request->user()->id,
                'route' => $request->route()?->getName(),
                'method' => $request->method(),
                'status' => $response->getStatusCode(),
                'ip_hash' => hash('sha256', (string) $request->ip()),
            ]);
        }

        return $response;
    }
}
