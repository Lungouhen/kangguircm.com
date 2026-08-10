<?php

declare(strict_types=1);

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\SubscriberList;
use App\Repositories\CampaignRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(
        private CampaignRepository $campaignRepository
    ) {}

    public function index(): View
    {
        $campaigns = $this->campaignRepository->paginate(15);
        return view('email.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $lists = SubscriberList::all();
        return view('email.campaigns.create', compact('lists'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'lists' => ['nullable', 'array'],
            'lists.*' => ['integer', 'exists:subscriber_lists,id'],
        ]);
        $data['created_by'] = $request->user()->id;
        $data['from_name'] = config('mail.from.name', config('app.name'));
        $data['from_email'] = config('mail.from.address', 'noreply@example.com');

        $campaign = $this->campaignRepository->create($data);

        return redirect()
            ->route('admin.email.campaigns.index')
            ->with('success', "Campaign {$campaign->name} created.");
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load(['creator', 'lists']);

        return view('email.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign): View
    {
        $lists = SubscriberList::all();

        return view('email.campaigns.create', compact('campaign', 'lists'));
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'lists' => ['nullable', 'array'],
            'lists.*' => ['integer', 'exists:subscriber_lists,id'],
        ]);

        $campaign->update([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'html_content' => $data['content'],
        ]);
        $campaign->lists()->sync($data['lists'] ?? []);

        return redirect()->route('admin.email.campaigns.show', $campaign)
            ->with('success', 'Campaign updated.');
    }

    public function send(int $id): RedirectResponse
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            abort(404);
        }

        $this->campaignRepository->send($campaign);

        return redirect()
            ->route('admin.email.campaigns.index')
            ->with('success', 'Campaign is being sent.');
    }
}
