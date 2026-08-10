<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Contracts\WhatsAppClient;
use App\Exceptions\WhatsAppDeliveryException;
use App\Models\MarketingLead;
use App\Models\NotificationDelivery;
use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendNewLeadNotifications implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries=4;
    public int $timeout=30;
    public bool $failOnTimeout=true;

    public function __construct(public readonly int $leadId) { $this->onQueue('notifications'); }
    public function uniqueId(): string { return 'new-lead:'.$this->leadId; }
    public function uniqueFor(): int { return 3600; }
    public function backoff(): array { return [30,120,600]; }

    public function handle(WhatsAppClient $whatsApp): void
    {
        $lead=MarketingLead::find($this->leadId);
        if (!$lead) return;
        $failure=null;
        if (config('notifications.lead.email_enabled') && SiteSetting::valueOf('notifications_email_enabled',true)) {
            foreach (config('notifications.lead.email_recipients',[]) as $recipient) try { $this->email($lead,$recipient); } catch(Throwable $e) { $failure=$e; }
        }
        if (config('notifications.lead.whatsapp_enabled') && SiteSetting::valueOf('notifications_whatsapp_enabled',false)) {
            foreach (config('notifications.whatsapp.recipients',[]) as $recipient) try { $this->whatsapp($lead,$recipient,$whatsApp); } catch(Throwable $e) { $failure=$e; }
        }
        if ($failure) throw $failure;
    }

    private function email(MarketingLead $lead,string $recipient): void
    {
        if (!filter_var($recipient,FILTER_VALIDATE_EMAIL)) return;
        $delivery=$this->delivery($lead,'email','smtp',$recipient,null);
        if (in_array($delivery->status,[NotificationDelivery::SENT,NotificationDelivery::DELIVERED,NotificationDelivery::READ],true)) return;
        $delivery->increment('attempts');
        try {
            $url=route('admin.marketing-leads.show',$lead);
            Mail::raw("A new website lead has been received.\n\nOrganization: ".($lead->organization?:'Not provided')."\nSpecialty: ".($lead->specialty?:'Not provided')."\nSource: {$lead->source}\n\nReview securely: {$url}\n\nDo not forward sensitive lead details outside the CMS.",function($message)use($recipient){$message->to($recipient)->subject('New website lead received');});
            $delivery->update(['status'=>NotificationDelivery::SENT,'sent_at'=>now(),'failure_code'=>null,'failed_at'=>null]);
        } catch(Throwable $e) {
            $delivery->update(['status'=>NotificationDelivery::FAILED,'failure_code'=>'mail_delivery_failed','failed_at'=>now()]);
            throw $e;
        }
    }

    private function whatsapp(MarketingLead $lead,string $recipient,WhatsAppClient $client): void
    {
        $template=(string)config('notifications.whatsapp.template');
        $delivery=$this->delivery($lead,'whatsapp','meta',$recipient,$template);
        if (in_array($delivery->status,[NotificationDelivery::SENT,NotificationDelivery::DELIVERED,NotificationDelivery::READ],true)) return;
        $delivery->increment('attempts');
        try {
            $result=$client->sendTemplate($recipient,$template,(string)config('notifications.whatsapp.template_language'));
            $delivery->update(['status'=>NotificationDelivery::SENT,'provider_message_id'=>$result->messageId,'sent_at'=>now(),'failure_code'=>null,'failed_at'=>null]);
        } catch(WhatsAppDeliveryException $e) {
            $delivery->update(['status'=>NotificationDelivery::FAILED,'failure_code'=>$e->failureCode,'failed_at'=>now()]);
            throw $e;
        }
    }

    private function delivery(MarketingLead $lead,string $channel,string $provider,string $recipient,?string $template): NotificationDelivery
    {
        $normalized=$channel==='whatsapp'?(preg_replace('/\D+/','',$recipient)??''):strtolower(trim($recipient));
        return NotificationDelivery::firstOrCreate([
            'event'=>'marketing_lead.created','notifiable_type'=>MarketingLead::class,'notifiable_id'=>$lead->id,
            'channel'=>$channel,'recipient_hash'=>hash_hmac('sha256',$normalized,(string)config('app.key')),
        ],['provider'=>$provider,'recipient_masked'=>$this->mask($normalized),'template'=>$template,'status'=>NotificationDelivery::PENDING]);
    }

    private function mask(string $value): string
    {
        $length=strlen($value); return $length<=4?str_repeat('*',$length):str_repeat('*',max(4,$length-4)).substr($value,-4);
    }
}
