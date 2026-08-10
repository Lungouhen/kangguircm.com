<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketingLead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketingLeadController extends Controller
{
    public function index(Request $request): View
    {
        $leads = MarketingLead::query()
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $summary = [
            'total' => MarketingLead::count(),
            'new' => MarketingLead::where('status', 'new')->count(),
            'qualified' => MarketingLead::where('status', 'qualified')->count(),
            'this_month' => MarketingLead::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.marketing-leads.index', compact('leads', 'summary'));
    }

    public function show(MarketingLead $marketingLead): View
    {
        return view('admin.marketing-leads.show', compact('marketingLead'));
    }

    public function update(Request $request, MarketingLead $marketingLead): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,contacted,qualified,proposal,won,lost'],
        ]);
        $marketingLead->update($data);

        return back()->with('success', 'Lead status updated.');
    }
}
