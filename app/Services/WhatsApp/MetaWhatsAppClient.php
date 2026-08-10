<?php

declare(strict_types=1);

namespace App\Services\WhatsApp;

use App\Contracts\WhatsAppClient;
use App\Data\WhatsAppSendResult;
use App\Exceptions\WhatsAppDeliveryException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class MetaWhatsAppClient implements WhatsAppClient
{
    public function sendTemplate(string $recipient, string $template, string $language): WhatsAppSendResult
    {
        $token=(string)config('notifications.whatsapp.access_token');
        $phoneId=(string)config('notifications.whatsapp.phone_number_id');
        if ($token===''||$phoneId==='') throw new WhatsAppDeliveryException('configuration_missing');
        $recipient=preg_replace('/\D+/','',$recipient)??'';
        if (!preg_match('/^[1-9][0-9]{7,14}$/',$recipient)) throw new WhatsAppDeliveryException('invalid_recipient');
        try {
            $response=Http::baseUrl('https://graph.facebook.com/'.config('notifications.whatsapp.graph_version'))
                ->withToken($token)->acceptJson()->asJson()
                ->connectTimeout(config('notifications.whatsapp.connect_timeout'))->timeout(config('notifications.whatsapp.timeout'))
                ->retry([500,1500],throw:false)
                ->post("{$phoneId}/messages",[
                    'messaging_product'=>'whatsapp','recipient_type'=>'individual','to'=>$recipient,'type'=>'template',
                    'template'=>['name'=>$template,'language'=>['code'=>$language]],
                ]);
        } catch (ConnectionException) {
            throw new WhatsAppDeliveryException('connection_failed');
        }
        if (!$response->successful()) {
            $code=(string)($response->json('error.code')??'provider_error');
            throw new WhatsAppDeliveryException('meta_'.$code);
        }
        $id=$response->json('messages.0.id');
        if (!is_string($id)||$id==='') throw new WhatsAppDeliveryException('missing_message_id');
        return new WhatsAppSendResult($id);
    }
}
