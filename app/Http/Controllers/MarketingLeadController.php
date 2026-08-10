<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\SendLeadToCrmWebhook;
use App\Jobs\SendNewLeadNotifications;
use App\Services\TurnstileVerifier;
use Illuminate\Validation\ValidationException;
use App\Models\MarketingLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MarketingLeadController extends Controller
{
    public function store(Request $request, TurnstileVerifier $captcha): JsonResponse|RedirectResponse
    {
        if (!$captcha->verify($request->input('cf-turnstile-response'), $request->ip())) throw ValidationException::withMessages(['captcha'=>'Verification failed. Please try again.']);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'organization' => ['nullable', 'string', 'max:180'],
            'specialty' => ['nullable', 'string', 'max:120'],
            'provider_count' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'billing_model' => ['nullable', 'string', 'max:80'],
            'monthly_claims' => ['nullable', 'string', 'max:80'],
            'primary_challenge' => ['nullable', 'string', 'max:160'],
            'message' => ['nullable', 'string', 'max:3000'],
            'preferred_contact_time' => ['nullable', 'string', 'max:80'],
            'source' => ['nullable', 'string', 'max:80'],
            'landing_page' => ['nullable', 'string', 'max:500'],
            'utm_source' => ['nullable', 'string', 'max:120'],
            'utm_medium' => ['nullable', 'string', 'max:120'],
            'utm_campaign' => ['nullable', 'string', 'max:180'],
            'consent' => ['accepted'],
            'website' => ['nullable', 'max:0'], // Honeypot.
        ]);

        unset($data['website']);
        $data['source'] ??= 'website';
        $data['ip_hash'] = hash_hmac('sha256', (string) $request->ip(), (string) config('app.key'));
        $lead=MarketingLead::create($data);
        SendNewLeadNotifications::dispatch($lead->id)->afterCommit();
        SendLeadToCrmWebhook::dispatch($lead->id)->afterCommit();

        $message = 'Thank you. An RCM specialist will contact you shortly.';

        return $request->expectsJson()
            ? response()->json(['message' => $message], 201)
            : back()->with('success', $message);
    }
}
