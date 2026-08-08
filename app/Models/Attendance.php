<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'total_hours',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
        'total_hours' => 'decimal:2',
    ];

    public const STATUS_PRESENT = 'present';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_LATE = 'late';
    public const STATUS_HALF_DAY = 'half_day';

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function calculateHours(): float
    {
        if (!$this->clock_in || !$this->clock_out) {
            return 0.0;
        }

        $totalSeconds = $this->clock_out->diffInSeconds($this->clock_in);
        $breakSeconds = 0;

        if ($this->break_start && $this->break_end) {
            $breakSeconds = $this->break_end->diffInSeconds($this->break_start);
        }

        $workSeconds = $totalSeconds - $breakSeconds;
        return round($workSeconds / 3600, 2);
    }

    public function isLate(string $startTime = '09:00'): bool
    {
        if (!$this->clock_in) {
            return false;
        }

        $start = \Carbon\Carbon::parse($this->date . ' ' . $startTime);
        return $this->clock_in->gt($start);
    }
}
