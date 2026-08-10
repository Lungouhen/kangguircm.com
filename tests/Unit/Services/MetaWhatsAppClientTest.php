<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\WhatsApp\MetaWhatsAppClient;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MetaWhatsAppClientTest extends TestCase
{
    public function test_it_sends_only_an_approved_template_payload(): void
    {
        config(['notifications.whatsapp.access_token'=>'secret','notifications.whatsapp.phone_number_id'=>'123','notifications.whatsapp.graph_version'=>'v23.0']);
        Http::fake(['graph.facebook.com/*'=>Http::response(['messages'=>[['id'=>'wamid.test']]],200)]);
        $result=app(MetaWhatsAppClient::class)->sendTemplate('+14155550123','new_website_lead','en_US');
        $this->assertSame('wamid.test',$result->messageId);
        Http::assertSent(fn($request)=>$request['type']==='template'&&$request['template']['name']==='new_website_lead'&&!array_key_exists('text',$request->data()));
    }
}
