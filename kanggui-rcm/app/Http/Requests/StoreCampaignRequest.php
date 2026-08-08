<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Assuming authenticated users can create campaigns
    }

    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'sender_name' => 'required|string|max:100',
            'sender_email' => 'required|email|max:255',
            'list_ids' => 'required|array|min:1',
            'list_ids.*' => 'exists:subscriber_lists,id',
            'template_id' => 'nullable|exists:email_templates,id',
            'scheduled_at' => 'nullable|date|after:now',
        ];
    }

    public function messages(): array
    {
        return [
            'list_ids.required' => 'You must select at least one subscriber list.',
            'list_ids.array' => 'Selected lists must be valid.',
            'scheduled_at.after' => 'Scheduled time must be in the future.',
        ];
    }
}
