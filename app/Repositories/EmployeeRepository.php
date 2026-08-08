<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository
{
    public function __construct(
        private Employee $model
    ) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model
            ->with(['user', 'department'])
            ->latest()
            ->paginate($perPage);
    }

    public function find(int $id): ?Employee
    {
        return $this->model
            ->with(['user', 'department', 'attendances', 'leaveRequests'])
            ->find($id);
    }

    public function create(array $data): Employee
    {
        return $this->model->create([
            'user_id' => $data['user_id'],
            'employee_number' => $data['employee_number'],
            'department' => $data['department'] ?? null,
            'position' => $data['position'] ?? null,
            'hire_date' => $data['hire_date'],
            'salary' => $data['salary'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'status' => $data['status'] ?? 'active',
        ]);
    }

    public function clockIn(Employee $employee): Attendance
    {
        return Attendance::create([
            'employee_id' => $employee->id,
            'clock_in' => now(),
            'clock_in_ip' => request()->ip(),
        ]);
    }

    public function clockOut(Employee $employee): Attendance
    {
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereNull('clock_out')
            ->whereDate('clock_in', today())
            ->latest()
            ->first();

        if (!$attendance) {
            throw new \Exception('No clock-in record found for today.');
        }

        $attendance->update([
            'clock_out' => now(),
            'clock_out_ip' => request()->ip(),
        ]);

        return $attendance->fresh();
    }

    public function getTodayAttendance(): array
    {
        return Attendance::whereDate('clock_in', today())
            ->with('employee')
            ->get()
            ->groupBy('employee_id')
            ->map(function ($records) {
                return $records->sortByDesc('clock_in')->first();
            })
            ->values()
            ->all();
    }
}
