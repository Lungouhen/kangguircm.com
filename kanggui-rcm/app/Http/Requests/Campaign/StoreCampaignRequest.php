<?php

declare(strict_types=1);

namespace App\Http\Requests\Campaign;

use Illuminate\Foundation\Http\FormRequest;

class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'template_id' => ['nullable', 'exists:email_templates,id'],
            'list_ids' => ['required', 'array', 'min:1'],
            'list_ids.*' => ['exists:subscriber_lists,id'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'list_ids.required' => 'Please select at least one subscriber list.',
            'list_ids.min' => 'Please select at least one subscriber list.',
        ];
    }
}
