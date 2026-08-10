<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizePublicResponse
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (!$request->isMethodCacheable() || $request->user() || !$response->isSuccessful()) {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type');
        if (!str_contains($contentType, 'text/html')) {
            return $response;
        }

        $content = (string) $response->getContent();
        if (str_contains($content, 'name="_token"')) {
            $response->headers->set('Cache-Control', 'private, no-store');

            return $response;
        }

        $etag = '"'.hash('xxh128', $content).'"';
        $response->headers->set('ETag', $etag);
        $response->headers->set('Cache-Control', 'public, max-age=300, stale-while-revalidate=600');
        $response->headers->set('Vary', 'Accept-Encoding');

        if ($request->headers->get('If-None-Match') === $etag) {
            $response->setNotModified();
        }

        return $response;
    }
}
