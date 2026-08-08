<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;

class CampaignRepository
{
    public function create(array $data): Campaign
    {
        return Campaign::create([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'content' => $data['content'],
            'template_id' => $data['template_id'] ?? null,
            'status' => $data['scheduled_at'] ? 'scheduled' : 'draft',
            'scheduled_at' => $data['scheduled_at'] ?? null,
        ]);
    }

    public function sendCampaign(Campaign $campaign, array $listIds): void
    {
        $subscribers = Subscriber::whereIn('id', function ($query) use ($listIds) {
            $query->select('subscriber_id')
                ->from('list_subscriber')
                ->whereIn('subscriber_list_id', $listIds);
        })->where('is_verified', true)
          ->where('is_bounced', false)
          ->get();

        $campaign->update(['status' => 'sending']);

        foreach ($subscribers as $subscriber) {
            // Simulate sending - in production use queue job
            Mail::raw($campaign->content, function ($message) use ($subscriber, $campaign) {
                $message->to($subscriber->email)
                        ->subject($campaign->subject);
            });

            $campaign->emailTracking()->create([
                'subscriber_id' => $subscriber->id,
                'sent_at' => now(),
                'status' => 'sent',
            ]);
        }

        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'total_sent' => $subscribers->count(),
        ]);
    }

    public function getStats(Campaign $campaign): array
    {
        $tracking = $campaign->emailTracking();
        
        return [
            'sent' => $tracking->whereNotNull('sent_at')->count(),
            'opened' => $tracking->whereNotNull('opened_at')->count(),
            'clicked' => $tracking->whereNotNull('clicked_at')->count(),
            'bounced' => $tracking->where('status', 'bounced')->count(),
            'open_rate' => $tracking->count() > 0 
                ? round(($tracking->whereNotNull('opened_at')->count() / $tracking->count()) * 100, 2) 
                : 0,
            'click_rate' => $tracking->count() > 0 
                ? round(($tracking->whereNotNull('clicked_at')->count() / $tracking->count()) * 100, 2) 
                : 0,
        ];
    }
}
