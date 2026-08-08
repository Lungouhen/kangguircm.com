<?php

declare(strict_types=1);

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\SubscriberList;
use App\Models\EmailTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::with(['lists'])
            ->latest()
            ->paginate(15);

        return view('email.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $lists = SubscriberList::orderBy('name')->get();
        $templates = EmailTemplate::orderBy('name')->get();

        return view('email.campaigns.create', compact('lists', 'templates'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'subject' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'from_name' => ['required', 'string', 'max:100'],
            'from_email' => ['required', 'email', 'max:255'],
            'list_ids' => ['required', 'array', 'min:1'],
            'list_ids.*' => ['exists:subscriber_lists,id'],
            'template_id' => ['nullable', 'exists:email_templates,id'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
        ]);

        $data['status'] = $request->has('send_now') ? 'sent' : 'draft';
        $data['user_id'] = $request->user()->id;

        $campaign = Campaign::create($data);
        $campaign->lists()->sync($data['list_ids']);

        if ($request->has('send_now')) {
            // In production, dispatch a job here
            $campaign->update([
                'sent_at' => now(),
                'total_recipients' => $campaign->getTotalRecipientsCount(),
            ]);
        }

        return redirect()
            ->route('email.campaigns.index')
            ->with('success', $request->has('send_now') ? 'Campaign sent successfully!' : 'Campaign created successfully.');
    }

    public function show(Campaign $campaign): View
    {
        $campaign->load(['lists.subscribers', 'user']);
        return view('email.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign): View
    {
        $lists = SubscriberList::orderBy('name')->get();
        $templates = EmailTemplate::orderBy('name')->get();

        return view('email.campaigns.edit', compact('campaign', 'lists', 'templates'));
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'subject' => ['required', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'from_name' => ['required', 'string', 'max:100'],
            'from_email' => ['required', 'email', 'max:255'],
            'list_ids' => ['required', 'array', 'min:1'],
            'list_ids.*' => ['exists:subscriber_lists,id'],
            'template_id' => ['nullable', 'exists:email_templates,id'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
        ]);

        $campaign->update($data);
        $campaign->lists()->sync($data['list_ids']);

        return redirect()
            ->route('email.campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()
            ->route('email.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function send(Campaign $campaign): RedirectResponse
    {
        if ($campaign->status !== 'draft') {
            return back()->with('error', 'Only draft campaigns can be sent.');
        }

        // In production, dispatch a job here
        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
            'total_recipients' => $campaign->getTotalRecipientsCount(),
        ]);

        return back()->with('success', 'Campaign is being sent!');
    }
}
