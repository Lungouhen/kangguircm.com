<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\SeoRedirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplySeoRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->path(), '/');
        if ($redirect = SeoRedirect::remember($path)) {
            SeoRedirect::query()->whereKey($redirect['id'])->increment('hits');

            return redirect($redirect['destination_path'], $redirect['status_code']);
        }

        return $next($request);
    }
}
