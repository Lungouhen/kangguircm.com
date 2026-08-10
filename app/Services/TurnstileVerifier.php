<?php

declare(strict_types=1);
namespace App\Services;
use Illuminate\Support\Facades\Http;
class TurnstileVerifier
{
 public function enabled(): bool{return (bool)config('integrations.turnstile.enabled');}
 public function verify(?string $token,?string $ip): bool
 {
  if(!$this->enabled())return true;if(!$token||!config('integrations.turnstile.secret_key'))return false;
  $response=Http::asForm()->timeout((int)config('integrations.turnstile.timeout',5))->retry(2,250,throw:false)->post('https://challenges.cloudflare.com/turnstile/v0/siteverify',['secret'=>config('integrations.turnstile.secret_key'),'response'=>$token,'remoteip'=>$ip]);
  return $response->successful()&&$response->json('success')===true;
 }
}
