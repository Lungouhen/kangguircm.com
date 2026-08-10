<?php

declare(strict_types=1);
namespace Tests\Feature;
use App\Jobs\SendNewLeadNotifications;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
class FormBuilderTest extends TestCase
{
 use RefreshDatabase;
 public function test_dynamic_form_validates_encrypts_and_creates_lead_notification(): void
 {
  Queue::fake();$form=Form::create(['name'=>'Lead','slug'=>'lead','purpose'=>'lead','is_active'=>true,'consent_required'=>true,'consent_text'=>'I agree','policy_version'=>'1.0','create_lead'=>true]);
  $form->fields()->createMany([['name'=>'name','label'=>'Name','type'=>'text','is_required'=>true,'sort_order'=>1,'width'=>6],['name'=>'email','label'=>'Email','type'=>'email','is_required'=>true,'sort_order'=>2,'width'=>6]]);
  $this->post(route('forms.submit',$form->slug),['fields'=>['name'=>'Jane','email'=>'jane@example.com'],'consent'=>'1'])->assertRedirect();
  $submission=FormSubmission::firstOrFail();$this->assertSame('jane@example.com',$submission->payload['email']);
  $raw=(string)DB::table('form_submissions')->value('payload');$this->assertStringNotContainsString('jane@example.com',$raw);
  Queue::assertPushed(SendNewLeadNotifications::class);
 }
 public function test_dynamic_form_rejects_invalid_email(): void
 {
  $form=Form::create(['name'=>'Contact','slug'=>'contact-form','purpose'=>'contact','is_active'=>true,'consent_required'=>false]);
  $form->fields()->create(['name'=>'email','label'=>'Email','type'=>'email','is_required'=>true,'sort_order'=>1,'width'=>12]);
  $this->from('/')->post(route('forms.submit',$form->slug),['fields'=>['email'=>'invalid']])->assertRedirect('/')->assertSessionHasErrors('fields.email');
  $this->assertDatabaseCount('form_submissions',0);
 }
}
