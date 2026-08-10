<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->hasPermission($permission)) {
            Log::warning('Permission denied.', [
                'user_id' => $request->user()->id,
                'permission' => $permission,
                'route' => $request->route()?->getName(),
            ]);
            abort(403, 'You are not authorized to perform this action.');
        }

        return $next($request);
    }
}
