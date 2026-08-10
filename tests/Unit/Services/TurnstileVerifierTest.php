<?php

declare(strict_types=1);
namespace Tests\Unit\Services;
use App\Services\TurnstileVerifier;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
class TurnstileVerifierTest extends TestCase
{
 public function test_disabled_provider_passes_without_network_call(): void{config(['integrations.turnstile.enabled'=>false]);Http::fake();$this->assertTrue(app(TurnstileVerifier::class)->verify(null,'127.0.0.1'));Http::assertNothingSent();}
 public function test_enabled_provider_requires_successful_verification(): void{config(['integrations.turnstile.enabled'=>true,'integrations.turnstile.secret_key'=>'secret']);Http::fake(['challenges.cloudflare.com/*'=>Http::response(['success'=>true])]);$this->assertTrue(app(TurnstileVerifier::class)->verify('token','127.0.0.1'));}
}
