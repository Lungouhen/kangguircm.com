<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'year',
        'annual_leave',
        'sick_leave',
        'personal_leave',
        'taken_annual',
        'taken_sick',
        'taken_personal',
    ];

    protected $casts = [
        'year' => 'integer',
        'annual_leave' => 'decimal:2',
        'sick_leave' => 'decimal:2',
        'personal_leave' => 'decimal:2',
        'taken_annual' => 'decimal:2',
        'taken_sick' => 'decimal:2',
        'taken_personal' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getRemainingAnnualAttribute(): float
    {
        return max(0, $this->annual_leave - $this->taken_annual);
    }

    public function getRemainingSickAttribute(): float
    {
        return max(0, $this->sick_leave - $this->taken_sick);
    }

    public function getRemainingPersonalAttribute(): float
    {
        return max(0, $this->personal_leave - $this->taken_personal);
    }
}
