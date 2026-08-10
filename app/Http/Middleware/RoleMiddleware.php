<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->hasRole($role)) {
            Log::warning('Role boundary denied access.', [
                'user_id' => $request->user()->id,
                'required_role' => $role,
                'route' => $request->route()?->getName(),
            ]);
            abort(403, 'You are not authorized to access this resource.');
        }

        return $next($request);
    }
}
