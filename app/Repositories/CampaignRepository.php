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
            'html_content' => $data['content'],
            'text_content' => strip_tags($data['content']),
            'from_name' => $data['from_name'],
            'from_email' => $data['from_email'],
            'status' => 'draft',
            'created_by' => $data['created_by'],
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
                Mail::raw($campaign->html_content, function ($message) use ($subscriber, $campaign) {
                    $message->to($subscriber->email)
                            ->subject($campaign->subject)
                            ->from($campaign->from_email, $campaign->from_name);
                });

                $campaign->emailTrackings()->create([
                    'subscriber_id' => $subscriber->id,
                    'sent_at' => now(),
                    'status' => 'sent',
                ]);

                $campaign->increment('sent_count');
            } catch (\Exception $e) {
                $campaign->emailTrackings()->create([
                    'subscriber_id' => $subscriber->id,
                    'sent_at' => now(),
                    'status' => 'failed',
                ]);

                $campaign->increment('bounced_count');
            }
        }

        $campaign->update([
            'status' => Campaign::STATUS_SENT,
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
