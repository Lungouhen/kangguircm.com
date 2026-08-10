<?php

declare(strict_types=1);
namespace Tests\Feature;
use App\Models\LegalPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ConsentTest extends TestCase
{
 use RefreshDatabase;
 public function test_visitor_choice_is_versioned_and_cookie_is_set(): void
 {
  LegalPolicy::create(['type'=>'privacy','title'=>'Privacy Policy','slug'=>'privacy-policy','version'=>'2.0','effective_at'=>today(),'content'=>'Reviewed policy text','is_published'=>true]);
  $this->postJson(route('consent.store'),['analytics'=>true,'marketing'=>false,'preferences'=>false,'action'=>'customize'])->assertOk()->assertCookie('cms_consent');
  $this->assertDatabaseHas('visitor_consents',['analytics'=>1,'marketing'=>0,'policy_version'=>'2.0','action'=>'customize']);
 }
 public function test_only_published_effective_policy_is_public(): void
 {
  $policy=LegalPolicy::create(['type'=>'terms','title'=>'Terms','slug'=>'terms','version'=>'1.0','effective_at'=>today(),'content'=>'Terms text','is_published'=>true]);
  $this->get(route('legal.show',$policy->slug))->assertOk()->assertSee('Terms text');
 }
}
