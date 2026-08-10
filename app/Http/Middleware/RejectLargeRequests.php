<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RejectLargeRequests
{
    private const MAX_BYTES = 10_485_760;

    public function handle(Request $request, Closure $next): Response
    {
        $length = (int) $request->server('CONTENT_LENGTH', 0);
        abort_if($length > self::MAX_BYTES, 413, 'Request payload is too large.');

        return $next($request);
    }
}
