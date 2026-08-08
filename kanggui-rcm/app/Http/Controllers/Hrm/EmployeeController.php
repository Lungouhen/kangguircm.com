<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(): View
    {
        $employees = Employee::with(['user.role'])->latest()->paginate(15);
        return view('hrm.employees.index', compact('employees'));
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('employee')->get();
        return view('hrm.employees.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'employee_code' => ['required', 'string', 'max:20', 'unique:employees'],
            'department' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:100'],
            'hire_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        Employee::create($data);

        return redirect()
            ->route('hrm.employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee): View
    {
        $employee->load(['user.role', 'attendances', 'leaveRequests']);
        return view('hrm.employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $users = User::whereDoesntHave('employee')->get();
        return view('hrm.employees.edit', compact('employee', 'users'));
    }

    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'employee_code' => ['required', 'string', 'max:20', "unique:employees,employee_code,{$employee->id}"],
            'department' => ['required', 'string', 'max:100'],
            'position' => ['required', 'string', 'max:100'],
            'hire_date' => ['required', 'date'],
            'salary' => ['required', 'numeric', 'min:0'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $employee->update($data);

        return redirect()
            ->route('hrm.employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()
            ->route('hrm.employees.index')
            ->with('success', 'Employee record deleted successfully.');
    }
}
