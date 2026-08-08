<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceRepository
{
    public function clockIn(int $userId, string $ipAddress, string $userAgent, ?string $latitude = null, ?string $longitude = null): Attendance
    {
        return Attendance::create([
            'user_id' => $userId,
            'clock_in' => now(),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'status' => 'present',
        ]);
    }

    public function clockOut(int $userId): Attendance
    {
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('clock_in', today())
            ->whereNull('clock_out')
            ->firstOrFail();

        $attendance->update([
            'clock_out' => now(),
        ]);

        return $attendance->fresh();
    }

    public function getTodayAttendance(int $userId): ?Attendance
    {
        return Attendance::where('user_id', $userId)
            ->whereDate('clock_in', today())
            ->first();
    }

    public function getMonthlyStats(int $userId, string $year, string $month): array
    {
        $attendances = Attendance::where('user_id', $userId)
            ->whereYear('clock_in', $year)
            ->whereMonth('clock_in', $month)
            ->get();

        return [
            'total_days' => $attendances->count(),
            'on_time' => $attendances->filter(fn($a) => $a->clock_in->hour < 9)->count(),
            'late' => $attendances->filter(fn($a) => $a->clock_in->hour >= 9)->count(),
            'total_hours' => $attendances->sum(fn($a) => $a->clock_in ? $a->clock_out?->diffInMinutes($a->clock_in) / 60 : 0),
        ];
    }

    public function getDailyLogs(string $date): \Illuminate\Database\Eloquent\Collection
    {
        return Attendance::whereDate('clock_in', $date)
            ->with('user.employee')
            ->orderBy('clock_in')
            ->get();
    }
}
