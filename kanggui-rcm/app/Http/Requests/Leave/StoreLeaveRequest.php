<?php

declare(strict_types=1);

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type' => ['required', 'in:annual,sick,maternity,paternity,unpaid'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'reason' => ['required', 'string', 'min:20'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->user()->employee) {
                $availableDays = $this->user()->employee->leaveBalances()
                    ->where('leave_type', $this->input('leave_type'))
                    ->sum('days_available');

                $requestedDays = now()->parse($this->input('start_date'))
                    ->diffInDays(now()->parse($this->input('end_date'))) + 1;

                if ($requestedDays > $availableDays && $this->input('leave_type') !== 'unpaid') {
                    $validator->errors()->add(
                        'start_date',
                        "Insufficient leave balance. Available: {$availableDays} days, Requested: {$requestedDays} days."
                    );
                }
            }
        });
    }
}
