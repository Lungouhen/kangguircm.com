<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use App\Services\VisitTracker;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackPageVisits
{
    public function __construct(private readonly VisitTracker $tracker) {}

    public function handle(Request $request, Closure $next): Response
    {
        $response=$next($request);
        if (!$this->shouldTrack($request,$response)) return $response;
        try { $this->tracker->record($request); }
        catch (Throwable $e) { Log::warning('Anonymous page analytics recording failed.',['exception'=>get_class($e)]); }
        return $response;
    }

    private function shouldTrack(Request $request, Response $response): bool
    {
        if (!config('analytics.enabled') || !SiteSetting::valueOf('analytics_enabled', true) || !$request->isMethod('GET') || $request->user() || !$response->isSuccessful()) return false;
        if (config('analytics.respect_dnt') && $request->header('DNT')==='1') return false;
        if (config('analytics.require_consent')) {
            $consent=json_decode((string)$request->cookie('cms_consent'),true);
            if (!is_array($consent)||!($consent['analytics']??false)) return false;
        }
        if (!str_contains((string)$response->headers->get('Content-Type'),'text/html')) return false;
        foreach (config('analytics.exclude_paths',[]) as $pattern) if ($request->is($pattern)) return false;
        return true;
    }
}
