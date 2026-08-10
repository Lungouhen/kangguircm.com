<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository
{
    public function __construct(private readonly Employee $model) {}

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->newQuery()->with('user')->latest()->paginate($perPage);
    }

    public function find(int $id): ?Employee
    {
        return $this->model->newQuery()->with(['user','manager.user','attendances','leaveRequests'])->find($id);
    }

    public function create(array $data): Employee
    {
        return $this->model->newQuery()->create([
            'user_id' => $data['user_id'] ?? null,
            'employee_code' => $data['employee_code'],
            'department' => $data['department'],
            'position' => $data['position'],
            'hire_date' => $data['hire_date'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
            'emergency_phone' => $data['emergency_phone'] ?? null,
            'status' => $data['status'] ?? Employee::STATUS_ACTIVE,
        ]);
    }

    public function clockIn(Employee $employee): Attendance
    {
        return Attendance::query()->updateOrCreate(
            ['employee_id'=>$employee->id,'date'=>today()],
            ['clock_in'=>now(),'status'=>Attendance::STATUS_PRESENT]
        );
    }

    public function clockOut(Employee $employee): Attendance
    {
        $attendance=Attendance::where('employee_id',$employee->id)->whereDate('date',today())->whereNull('clock_out')->firstOrFail();
        $attendance->update(['clock_out'=>now()]);
        $attendance->update(['total_hours'=>$attendance->fresh()->calculateHours()]);
        return $attendance->fresh();
    }

    public function getTodayAttendance(): array
    {
        return Attendance::whereDate('date',today())->with('employee.user')->orderByDesc('clock_in')->get()->all();
    }
}
