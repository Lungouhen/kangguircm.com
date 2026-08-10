<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceRepository
{
    public function clockIn(int $employeeId, array $data = []): Attendance
    {
        return DB::transaction(fn () => Attendance::query()->updateOrCreate(
            ['employee_id' => $employeeId, 'date' => today()],
            ['clock_in' => now(), 'status' => Attendance::STATUS_PRESENT, ...$data]
        ));
    }

    public function clockOut(int $attendanceId, array $data = []): Attendance
    {
        return DB::transaction(function () use ($attendanceId, $data): Attendance {
            $attendance = Attendance::findOrFail($attendanceId);
            $attendance->update(['clock_out' => now(), ...$data]);
            $attendance->update(['total_hours' => $attendance->fresh()->calculateHours()]);
            return $attendance->fresh();
        });
    }

    public function getTodayAttendances(int $employeeId)
    {
        return Attendance::where('employee_id', $employeeId)->whereDate('date', today())->latest('clock_in')->get();
    }

    public function getMonthlyAttendances(int $employeeId, string $month)
    {
        return Attendance::where('employee_id', $employeeId)
            ->whereYear('date', substr($month, 0, 4))
            ->whereMonth('date', substr($month, 5, 2))
            ->orderByDesc('date')->get();
    }
}
