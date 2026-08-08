<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;

class CampaignRepository
{
    public function __construct(
        private Campaign $model
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['creator', 'lists'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Campaign
    {
        return $this->model->with(['creator', 'lists.subscribers'])->find($id);
    }

    public function create(array $data): Campaign
    {
        $campaign = $this->model->create([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'content_html' => $data['content_html'],
            'content_text' => $data['content_text'] ?? null,
            'from_name' => $data['from_name'],
            'from_email' => $data['from_email'],
            'status' => 'draft',
            'user_id' => $data['user_id'],
        ]);

        if (isset($data['lists'])) {
            $campaign->lists()->sync($data['lists']);
        }

        return $campaign;
    }

    public function send(Campaign $campaign): bool
    {
        $campaign->update(['status' => 'sending']);

        $subscribers = $this->getUniqueSubscribers($campaign);

        foreach ($subscribers as $subscriber) {
            try {
                Mail::raw($campaign->content_html, function ($message) use ($subscriber, $campaign) {
                    $message->to($subscriber->email)
                            ->subject($campaign->subject)
                            ->from($campaign->from_email, $campaign->from_name);
                });

                $campaign->tracking()->create([
                    'subscriber_id' => $subscriber->id,
                    'sent_at' => now(),
                    'status' => 'sent',
                ]);

                $campaign->increment('sent_count');
            } catch (\Exception $e) {
                $campaign->tracking()->create([
                    'subscriber_id' => $subscriber->id,
                    'sent_at' => now(),
                    'status' => 'failed',
                ]);

                $campaign->increment('bounced_count');
            }
        }

        $campaign->update([
            'status' => 'completed',
            'sent_at' => now(),
        ]);

        return true;
    }

    private function getUniqueSubscribers(Campaign $campaign): Collection
    {
        $subscriberIds = [];

        foreach ($campaign->lists as $list) {
            foreach ($list->subscribers as $subscriber) {
                $subscriberIds[$subscriber->id] = $subscriber;
            }
        }

        return collect($subscriberIds)->values();
    }
}
