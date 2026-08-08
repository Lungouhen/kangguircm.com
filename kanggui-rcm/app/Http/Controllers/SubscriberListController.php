<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\SubscriberList;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SubscriberListController extends Controller
{
    public function index(): View
    {
        $lists = SubscriberList::withCount('subscribers')->latest()->paginate(15);
        return view('email.lists.index', compact('lists'));
    }

    public function create(): View
    {
        return view('email.lists.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        SubscriberList::create($validated);

        return redirect()->route('subscriber-lists.index')
            ->with('success', 'Subscriber list created successfully.');
    }

    public function show(SubscriberList $subscriberList): View
    {
        $subscriberList->loadCount('subscribers');
        $subscribers = $subscriberList->subscribers()->latest()->paginate(20);
        
        return view('email.lists.show', compact('subscriberList', 'subscribers'));
    }

    public function edit(SubscriberList $subscriberList): View
    {
        return view('email.lists.edit', compact('subscriberList'));
    }

    public function update(Request $request, SubscriberList $subscriberList): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subscriberList->update($validated);

        return redirect()->route('subscriber-lists.show', $subscriberList)
            ->with('success', 'Subscriber list updated successfully.');
    }

    public function destroy(SubscriberList $subscriberList): RedirectResponse
    {
        $subscriberList->delete();

        return redirect()->route('subscriber-lists.index')
            ->with('success', 'Subscriber list deleted successfully.');
    }
}
