<?php

declare(strict_types=1);

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $templates = EmailTemplate::latest()->paginate(15);
        return view('email.templates.index', compact('templates'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('email.templates.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('email.templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function show(EmailTemplate $template): \Illuminate\View\View
    {
        return view('email.templates.show', compact('template'));
    }

    public function edit(EmailTemplate $template): \Illuminate\View\View
    {
        return view('email.templates.edit', compact('template'));
    }

    public function update(Request $request, EmailTemplate $template): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
        ]);

        $template->update($validated);

        return redirect()->route('email.templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $template): \Illuminate\Http\RedirectResponse
    {
        $template->delete();

        return redirect()->route('email.templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
}
