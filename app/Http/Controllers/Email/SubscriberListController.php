<?php

declare(strict_types=1);

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\SubscriberList;
use Illuminate\Http\Request;

class SubscriberListController extends Controller
{
    public function index()
    {
        $lists = SubscriberList::withCount('subscribers')->latest()->get();
        return view('email.lists.index', compact('lists'));
    }

    public function create()
    {
        return view('email.lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscriber_lists,name',
            'description' => 'nullable|string',
        ]);

        SubscriberList::create($validated);

        return redirect()->route('admin.email.lists.index')
            ->with('success', 'Subscriber list created successfully.');
    }

    public function show(SubscriberList $list)
    {
        $list->load(['subscribers' => function ($query) {
            $query->latest()->limit(50);
        }]);

        return view('email.lists.show', compact('list'));
    }

    public function edit(SubscriberList $list)
    {
        return view('email.lists.edit', compact('list'));
    }

    public function update(Request $request, SubscriberList $list)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:subscriber_lists,name,' . $list->id,
            'description' => 'nullable|string',
        ]);

        $list->update($validated);

        return redirect()->route('admin.email.lists.index')
            ->with('success', 'Subscriber list updated successfully.');
    }

    public function destroy(SubscriberList $list)
    {
        $list->delete();

        return redirect()->route('admin.email.lists.index')
            ->with('success', 'Subscriber list deleted successfully.');
    }
}
