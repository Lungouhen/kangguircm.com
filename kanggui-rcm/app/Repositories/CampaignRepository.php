<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CampaignRepository
{
    public function getAll(): Collection
    {
        return Campaign::with('template')->latest()->get();
    }

    public function find(int $id): ?Campaign
    {
        return Campaign::with(['template', 'lists.subscribers'])->find($id);
    }

    public function create(array $data): Campaign
    {
        return DB::transaction(function () use ($data) {
            $campaign = Campaign::create([
                'name' => $data['name'],
                'subject' => $data['subject'],
                'content' => $data['content'],
                'email_template_id' => $data['template_id'] ?? null,
                'status' => $data['scheduled_at'] ? 'scheduled' : 'draft',
                'scheduled_at' => $data['scheduled_at'] ?? null,
            ]);

            if (isset($data['list_ids'])) {
                $campaign->lists()->attach($data['list_ids']);
            }

            return $campaign;
        });
    }

    public function send(Campaign $campaign): bool
    {
        return DB::transaction(function () use ($campaign) {
            // Get all subscribers from selected lists
            $subscribers = Subscriber::whereHas('lists', function ($query) use ($campaign) {
                $query->whereIn('subscriber_lists.id', $campaign->lists->pluck('id'));
            })
            ->where('is_active', true)
            ->where('is_verified', true)
            ->get();

            $sentCount = 0;
            foreach ($subscribers as $subscriber) {
                // Simulate sending email (in production, dispatch a job)
                $this->sendEmail($campaign, $subscriber);
                $sentCount++;
            }

            $campaign->update([
                'status' => 'sent',
                'sent_count' => $sentCount,
                'sent_at' => now(),
            ]);

            return true;
        });
    }

    protected function sendEmail(Campaign $campaign, Subscriber $subscriber): void
    {
        // In production, this would dispatch a job to send via SMTP/API
        // For now, we just track that it was "sent"
        DB::table('email_tracking')->insert([
            'campaign_id' => $campaign->id,
            'subscriber_id' => $subscriber->id,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function trackOpen(int $trackingId): void
    {
        DB::table('email_tracking')
            ->where('id', $trackingId)
            ->update(['opened_at' => now()]);
    }

    public function trackClick(int $trackingId, string $url): void
    {
        DB::table('email_tracking')
            ->where('id', $trackingId)
            ->update([
                'clicked_at' => now(),
                'click_url' => $url,
            ]);
    }
}
