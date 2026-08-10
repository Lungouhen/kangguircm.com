<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendNewLeadNotifications;
use App\Models\MarketingLead;
use App\Models\NotificationDelivery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationDeliveryController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.notifications.index',['deliveries'=>NotificationDelivery::query()->when($request->filled('channel'),fn($q)=>$q->where('channel',$request->string('channel')))->when($request->filled('status'),fn($q)=>$q->where('status',$request->string('status')))->latest()->paginate(30)->withQueryString()]);
    }
    public function retry(NotificationDelivery $delivery): RedirectResponse
    {
        abort_unless($delivery->notifiable_type===MarketingLead::class,422,'Unsupported notification type.');
        $delivery->update(['status'=>NotificationDelivery::PENDING,'failure_code'=>null,'failed_at'=>null]);
        SendNewLeadNotifications::dispatch($delivery->notifiable_id);
        return back()->with('success','Notification retry queued.');
    }
}
