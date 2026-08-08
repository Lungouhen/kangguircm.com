<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceRepository
{
    public function clockIn(Employee $employee, string $ipAddress, string $userAgent): Attendance
    {
        return DB::transaction(function () use ($employee, $ipAddress, $userAgent) {
            return Attendance::create([
                'employee_id' => $employee->id,
                'clock_in' => now(),
                'clock_in_ip' => $ipAddress,
                'clock_in_user_agent' => $userAgent,
                'status' => 'present',
            ]);
        });
    }

    public function clockOut(Attendance $attendance): Attendance
    {
        return DB::transaction(function () use ($attendance) {
            $attendance->update([
                'clock_out' => now(),
            ]);

            return $attendance;
        });
    }

    public function getTodayAttendance(Employee $employee): ?Attendance
    {
        return Attendance::where('employee_id', $employee->id)
            ->whereDate('clock_in', today())
            ->whereNull('clock_out')
            ->first();
    }

    public function getEmployeeAttendance(Employee $employee, int $days = 30): Collection
    {
        return Attendance::where('employee_id', $employee->id)
            ->orderBy('clock_in', 'desc')
            ->limit($days)
            ->get();
    }

    public function calculateWorkingHours(Attendance $attendance): float
    {
        if (!$attendance->clock_out) {
            return 0.0;
        }

        $clockIn = new \DateTime($attendance->clock_in);
        $clockOut = new \DateTime($attendance->clock_out);
        
        $interval = $clockIn->diff($clockOut);
        $hours = (float)$interval->format('%H.%i');
        
        return $hours;
    }
}
