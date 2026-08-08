<?php

declare(strict_types=1);

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::latest()->get();
        return view('email.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('email.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
        ]);

        EmailTemplate::create($validated);

        return redirect()->route('admin.email.templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function show(EmailTemplate $template)
    {
        return view('email.templates.show', compact('template'));
    }

    public function edit(EmailTemplate $template)
    {
        return view('email.templates.edit', compact('template'));
    }

    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'body_text' => 'nullable|string',
        ]);

        $template->update($validated);

        return redirect()->route('admin.email.templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.email.templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
}
