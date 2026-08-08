<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index(): View
    {
        $payrolls = Payroll::with('employee.user')->latest()->paginate(15);
        return view('hrm.payrolls.index', compact('payrolls'));
    }

    public function create(): View
    {
        $employees = Employee::with('user')->where('is_active', true)->get();
        return view('hrm.payrolls.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        // Calculate working days from attendance
        $attendanceDays = Attendance::where('employee_id', $validated['employee_id'])
            ->whereBetween('clock_in', [$validated['period_start'], $validated['period_end']])
            ->count();

        $netSalary = bcadd(
            bcsub((string)$validated['base_salary'], (string)($validated['deductions'] ?? 0), 2),
            (string)($validated['bonus'] ?? 0),
            2
        );

        DB::transaction(function () use ($validated, $netSalary, $attendanceDays) {
            Payroll::create([
                'employee_id' => $validated['employee_id'],
                'period_start' => $validated['period_start'],
                'period_end' => $validated['period_end'],
                'base_salary' => $validated['base_salary'],
                'bonus' => $validated['bonus'] ?? 0,
                'deductions' => $validated['deductions'] ?? 0,
                'net_salary' => $netSalary,
                'working_days' => $attendanceDays,
                'status' => 'pending',
            ]);
        });

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll record created successfully.');
    }

    public function show(Payroll $payroll): View
    {
        $payroll->load('employee.user');
        return view('hrm.payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll): View
    {
        $employees = Employee::with('user')->where('is_active', true)->get();
        return view('hrm.payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
        ]);

        $netSalary = bcadd(
            bcsub((string)$validated['base_salary'], (string)($validated['deductions'] ?? 0), 2),
            (string)($validated['bonus'] ?? 0),
            2
        );

        $payroll->update([
            'employee_id' => $validated['employee_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'base_salary' => $validated['base_salary'],
            'bonus' => $validated['bonus'] ?? 0,
            'deductions' => $validated['deductions'] ?? 0,
            'net_salary' => $netSalary,
        ]);

        return redirect()->route('payrolls.show', $payroll)
            ->with('success', 'Payroll record updated successfully.');
    }

    public function destroy(Payroll $payroll): RedirectResponse
    {
        $payroll->delete();

        return redirect()->route('payrolls.index')
            ->with('success', 'Payroll record deleted successfully.');
    }

    public function generate(Payroll $payroll): RedirectResponse
    {
        if ($payroll->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending payrolls can be generated.');
        }

        $payroll->update(['status' => 'generated']);

        return redirect()->route('payrolls.show', $payroll)
            ->with('success', 'Payroll generated successfully.');
    }
}
