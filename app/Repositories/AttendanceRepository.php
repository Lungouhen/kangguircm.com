<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceRepository
{
    public function clockIn(int $employeeId, array $data = []): Attendance
    {
        return DB::transaction(function () use ($employeeId, $data) {
            return Attendance::create([
                'employee_id' => $employeeId,
                'clock_in' => now(),
                'clock_in_ip' => request()->ip(),
                'clock_in_user_agent' => request()->userAgent(),
                'date' => today(),
                ...$data,
            ]);
        });
    }

    public function clockOut(int $attendanceId, array $data = []): Attendance
    {
        return DB::transaction(function () use ($attendanceId, $data) {
            $attendance = Attendance::findOrFail($attendanceId);
            
            $attendance->update([
                'clock_out' => now(),
                'clock_out_ip' => request()->ip(),
                'clock_out_user_agent' => request()->userAgent(),
                ...$data,
            ]);

            return $attendance;
        });
    }

    public function getTodayAttendances(int $employeeId)
    {
        return Attendance::where('employee_id', $employeeId)
            ->where('date', today())
            ->latest()
            ->get();
    }

    public function getMonthlyAttendances(int $employeeId, string $month)
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->orderBy('date', 'desc')
            ->get();
    }
}
