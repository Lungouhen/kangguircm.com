<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\NotificationDelivery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookController extends Controller
{
    public function verify(Request $request): Response
    {
        $token=(string)config('notifications.whatsapp.verify_token');
        $valid=$token!=='' && $request->query('hub_mode')==='subscribe' && hash_equals($token,(string)$request->query('hub_verify_token'));
        abort_unless($valid,403,'Webhook verification failed.');
        return response((string)$request->query('hub_challenge'),200,['Content-Type'=>'text/plain']);
    }

    public function handle(Request $request): JsonResponse
    {
        $secret=(string)config('notifications.whatsapp.app_secret');
        $signature=(string)$request->header('X-Hub-Signature-256');
        abort_if($secret===''||!str_starts_with($signature,'sha256='),403,'Invalid webhook signature.');
        $expected='sha256='.hash_hmac('sha256',$request->getContent(),$secret);
        abort_unless(hash_equals($expected,$signature),403,'Invalid webhook signature.');
        foreach ((array)$request->input('entry',[]) as $entry) foreach ((array)($entry['changes']??[]) as $change) foreach ((array)($change['value']['statuses']??[]) as $status) {
            $id=$status['id']??null; $state=$status['status']??null;
            if (!is_string($id)||!in_array($state,['sent','delivered','read','failed'],true)) continue;
            $delivery=NotificationDelivery::where('provider_message_id',$id)->first(); if (!$delivery) continue;
            $rank=['pending'=>0,'sent'=>1,'delivered'=>2,'read'=>3,'failed'=>4];
            if ($state!=='failed' && ($rank[$state]??0)<($rank[$delivery->status]??0)) continue;
            $updates=['status'=>$state];
            if ($state==='sent') $updates['sent_at']=now();
            if ($state==='delivered') $updates['delivered_at']=now();
            if ($state==='read') $updates['read_at']=now();
            if ($state==='failed') { $updates['failed_at']=now(); $updates['failure_code']='meta_webhook_failed'; }
            $delivery->update($updates);
        }
        return response()->json(['received'=>true]);
    }
}
