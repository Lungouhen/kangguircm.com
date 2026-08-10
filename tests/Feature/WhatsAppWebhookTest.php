<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\MarketingLead;
use App\Models\NotificationDelivery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhatsAppWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_delivery_webhook_updates_delivery_status(): void
    {
        config(['notifications.whatsapp.app_secret'=>'app-secret']);
        $lead=MarketingLead::create(['name'=>'Test','email'=>'test@example.com','consent'=>true,'source'=>'test']);
        $delivery=NotificationDelivery::create(['event'=>'marketing_lead.created','notifiable_type'=>MarketingLead::class,'notifiable_id'=>$lead->id,'channel'=>'whatsapp','provider'=>'meta','recipient_hash'=>str_repeat('a',64),'recipient_masked'=>'*******0123','status'=>'sent','provider_message_id'=>'wamid.1']);
        $payload=['entry'=>[['changes'=>[['value'=>['statuses'=>[['id'=>'wamid.1','status'=>'delivered']]]]]]]];
        $json=json_encode($payload);
        $signature='sha256='.hash_hmac('sha256',$json,'app-secret');
        $this->call('POST','/webhooks/whatsapp',[],[],[],['CONTENT_TYPE'=>'application/json','HTTP_X_HUB_SIGNATURE_256'=>$signature],$json)->assertOk();
        $this->assertSame(NotificationDelivery::DELIVERED,$delivery->fresh()->status);
        $this->assertNotNull($delivery->fresh()->delivered_at);
    }

    public function test_invalid_webhook_signature_is_rejected(): void
    {
        config(['notifications.whatsapp.app_secret'=>'app-secret']);
        $this->withHeader('X-Hub-Signature-256','sha256=invalid')->postJson('/webhooks/whatsapp',['entry'=>[]])->assertForbidden();
    }
}
