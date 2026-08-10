<?php

declare(strict_types=1);
namespace App\Http\Controllers;
use App\Jobs\SendLeadToCrmWebhook;
use App\Jobs\SendNewLeadNotifications;
use App\Services\TurnstileVerifier;
use Illuminate\Validation\ValidationException;
use App\Models\Form;
use App\Models\MarketingLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class FormSubmissionController extends Controller
{
 public function store(Request $request,Form $form,TurnstileVerifier $captcha): JsonResponse|RedirectResponse
 {
  abort_unless($form->is_active,404);if(!$captcha->verify($request->input('cf-turnstile-response'),$request->ip()))throw ValidationException::withMessages(['captcha'=>'Verification failed. Please try again.']);$form->load('fields');$rules=['website'=>['nullable','max:0']];
  foreach($form->fields as $field){$rule=[$field->is_required?'required':'nullable'];$rule[]=match($field->type){'email'=>'email','number'=>'numeric','date'=>'date','checkbox'=>'boolean',default=>'string'};if($field->min_length)$rule[]='min:'.$field->min_length;if($field->max_length)$rule[]='max:'.$field->max_length;if(in_array($field->type,['select','radio'],true))$rule[]=Rule::in($field->options??[]);$rules['fields.'.$field->name]=$rule;}
  if($form->consent_required)$rules['consent']=['accepted'];$validated=$request->validate($rules);$payload=[];foreach($form->fields as $field)$payload[$field->name]=$validated['fields'][$field->name]??null;
  $submission=$form->submissions()->create(['payload'=>$payload,'source'=>$request->input('source','form:'.$form->slug),'landing_page'=>$request->input('landing_page',$request->fullUrl()),'visitor_hash'=>hash_hmac('sha256',(string)$request->ip(),(string)config('app.key')),'consent_text'=>$form->consent_required?$form->consent_text:null,'policy_version'=>$form->policy_version,'consented_at'=>$form->consent_required?now():null]);
  if($form->create_lead){$lead=MarketingLead::create(['name'=>(string)($payload['name']??$payload['full_name']??'Website visitor'),'email'=>(string)($payload['email']??'unknown@example.invalid'),'phone'=>$payload['phone']??null,'organization'=>$payload['organization']??$payload['practice']??null,'specialty'=>$payload['specialty']??null,'message'=>$payload['message']??null,'source'=>'form:'.$form->slug,'landing_page'=>$submission->landing_page,'consent'=>$form->consent_required,'ip_hash'=>$submission->visitor_hash]);SendNewLeadNotifications::dispatch($lead->id)->afterCommit();SendLeadToCrmWebhook::dispatch($lead->id)->afterCommit();}
  $message=$form->success_message?:'Thank you. Your submission has been received.';return $request->expectsJson()?response()->json(['message'=>$message],201):back()->with('success',$message);
 }
}
