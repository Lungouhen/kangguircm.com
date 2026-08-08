<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use App\Models\Attendance;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $payrolls = Payroll::with('employee.user')->latest()->paginate(15);
        return view('hrm.payroll.index', compact('payrolls'));
    }

    public function create(): \Illuminate\View\View
    {
        $employees = Employee::with('user')->get();
        return view('hrm.payroll.create', compact('employees'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        
        // Calculate days worked from attendance
        $daysWorked = Attendance::where('employee_id', $employee->id)
            ->betweenDates($validated['period_start'], $validated['period_end'])
            ->count();

        $dailyRate = $validated['base_salary'] / 30;
        $calculatedSalary = $dailyRate * $daysWorked;
        
        $totalBonus = $validated['bonus'] ?? 0;
        $totalDeductions = $validated['deductions'] ?? 0;
        $netSalary = $calculatedSalary + $totalBonus - $totalDeductions;

        Payroll::create([
            'employee_id' => $employee->id,
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'base_salary' => $calculatedSalary,
            'bonus' => $totalBonus,
            'deductions' => $totalDeductions,
            'net_salary' => $netSalary,
            'status' => 'pending',
        ]);

        return redirect()->route('hrm.payroll.index')
            ->with('success', 'Payroll record created successfully.');
    }

    public function show(Payroll $payroll): \Illuminate\View\View
    {
        return view('hrm.payroll.show', compact('payroll'));
    }

    public function approve(Payroll $payroll): \Illuminate\Http\RedirectResponse
    {
        $payroll->update(['status' => 'paid']);
        
        return redirect()->route('hrm.payroll.index')
            ->with('success', 'Payroll approved and marked as paid.');
    }
}
