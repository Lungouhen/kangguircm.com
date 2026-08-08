<?php

declare(strict_types=1);

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\SubscriberList;
use Illuminate\Http\Request;

class SubscriberListController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $lists = SubscriberList::withCount('subscribers')->latest()->paginate(15);
        return view('email.lists.index', compact('lists'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('email.lists.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscriber_lists',
            'description' => 'nullable|string',
        ]);

        SubscriberList::create($validated);

        return redirect()->route('email.lists.index')
            ->with('success', 'Subscriber list created successfully.');
    }

    public function edit(SubscriberList $list): \Illuminate\View\View
    {
        return view('email.lists.edit', compact('list'));
    }

    public function update(Request $request, SubscriberList $list): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscriber_lists,name,' . $list->id,
            'description' => 'nullable|string',
        ]);

        $list->update($validated);

        return redirect()->route('email.lists.index')
            ->with('success', 'Subscriber list updated successfully.');
    }

    public function destroy(SubscriberList $list): \Illuminate\Http\RedirectResponse
    {
        $list->delete();

        return redirect()->route('email.lists.index')
            ->with('success', 'Subscriber list deleted successfully.');
    }
}
