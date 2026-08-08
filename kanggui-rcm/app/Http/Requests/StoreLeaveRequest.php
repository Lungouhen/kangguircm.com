<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->employee !== null;
    }

    public function rules(): array
    {
        return [
            'leave_type' => ['required', 'string', 'in:annual,sick,maternity,paternity,unpaid'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'reason' => ['required', 'string', 'min:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'end_date.after' => 'The end date must be after the start date.',
            'reason.min' => 'Please provide a detailed reason for your leave request (at least 20 characters).',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->user()->employee) {
                $startDate = $this->input('start_date');
                $endDate = $this->input('end_date');
                
                if ($startDate && $endDate) {
                    $daysRequested = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;
                    $availableBalance = $this->user()->employee->leaveBalances()
                        ->where('year', date('Y'))
                        ->sum('available_days');

                    if ($daysRequested > $availableBalance && $this->input('leave_type') === 'annual') {
                        $validator->errors()->add('start_date', 'Insufficient leave balance. Available: ' . $availableBalance . ' days.');
                    }
                }
            }
        });
    }
}
