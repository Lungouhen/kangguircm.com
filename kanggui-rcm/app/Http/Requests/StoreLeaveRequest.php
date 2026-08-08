<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type' => 'required|string|in:annual,sick,maternity,paternity,unpaid',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'reason' => 'required|string|min:10|max:1000',
            'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after' => 'End date must be after start date.',
            'start_date.after_or_equal' => 'Start date cannot be in the past.',
            'reason.min' => 'Please provide a detailed reason for your leave request.',
        ];
    }
}
