<?php

declare(strict_types=1);
namespace App\Jobs;
use App\Models\MarketingLead;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
class SendLeadToCrmWebhook implements ShouldQueue,ShouldBeUnique
{
 use Dispatchable,InteractsWithQueue,Queueable,SerializesModels;
 public int $tries=4;public int $timeout=30;
 public function __construct(public readonly int $leadId){$this->onQueue('integrations');}
 public function uniqueId(): string{return 'crm-lead:'.$this->leadId;}public function backoff(): array{return[30,120,600];}
 public function handle(): void
 {
  if(!config('integrations.crm_webhook.enabled'))return;$lead=MarketingLead::find($this->leadId);if(!$lead)return;
  $url=(string)config('integrations.crm_webhook.url');$secret=(string)config('integrations.crm_webhook.secret');if(!filter_var($url,FILTER_VALIDATE_URL)||$secret==='')return;
  $payload=['event'=>'marketing_lead.created','id'=>$lead->id,'name'=>$lead->name,'email'=>$lead->email,'phone'=>$lead->phone,'organization'=>$lead->organization,'specialty'=>$lead->specialty,'source'=>$lead->source,'created_at'=>$lead->created_at->toIso8601String()];
  $json=json_encode($payload,JSON_THROW_ON_ERROR);Http::withHeaders(['X-RCM-Signature'=>'sha256='.hash_hmac('sha256',$json,$secret),'Idempotency-Key'=>'lead-'.$lead->id])->timeout((int)config('integrations.crm_webhook.timeout',10))->retry([500,1500],throw:true)->withBody($json,'application/json')->post($url)->throw();
 }
}
