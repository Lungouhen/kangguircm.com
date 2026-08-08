<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Repositories\EmployeeRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeRepository $employeeRepository
    ) {}

    public function index(): View
    {
        $employees = $this->employeeRepository->paginate(15);
        return view('hrm.employees.index', compact('employees'));
    }

    public function show(int $id): View
    {
        $employee = $this->employeeRepository->find($id);
        
        if (!$employee) {
            abort(404);
        }
        
        return view('hrm.employees.show', compact('employee'));
    }

    public function clockIn(int $employeeId): RedirectResponse
    {
        $employee = $this->employeeRepository->find($employeeId);
        
        if (!$employee) {
            abort(404);
        }
        
        $this->employeeRepository->clockIn($employee);
        
        return redirect()
            ->route('admin.employees.show', $employeeId)
            ->with('success', 'Clock-in recorded successfully.');
    }

    public function clockOut(int $employeeId): RedirectResponse
    {
        $employee = $this->employeeRepository->find($employeeId);
        
        if (!$employee) {
            abort(404);
        }
        
        $this->employeeRepository->clockOut($employee);
        
        return redirect()
            ->route('admin.employees.show', $employeeId)
            ->with('success', 'Clock-out recorded successfully.');
    }
}
