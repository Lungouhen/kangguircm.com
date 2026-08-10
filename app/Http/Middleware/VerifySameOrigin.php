<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifySameOrigin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethodSafe()) {
            return $next($request);
        }

        $expectedHost = strtolower($request->getHost());
        foreach (['Origin', 'Referer'] as $header) {
            if (!$value = $request->headers->get($header)) {
                continue;
            }
            $host = strtolower((string) parse_url($value, PHP_URL_HOST));
            abort_unless($host !== '' && hash_equals($expectedHost, $host), 403, 'Cross-origin request rejected.');
        }

        return $next($request);
    }
}
