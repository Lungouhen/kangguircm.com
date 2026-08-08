<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmailTemplateController extends Controller
{
    public function index(): View
    {
        $templates = EmailTemplate::latest()->paginate(15);
        return view('email.templates.index', compact('templates'));
    }

    public function create(): View
    {
        return view('email.templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function show(EmailTemplate $emailTemplate): View
    {
        return view('email.templates.show', compact('emailTemplate'));
    }

    public function edit(EmailTemplate $emailTemplate): View
    {
        return view('email.templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
        ]);

        $emailTemplate->update($validated);

        return redirect()->route('email-templates.show', $emailTemplate)
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate): RedirectResponse
    {
        $emailTemplate->delete();

        return redirect()->route('email-templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
}
