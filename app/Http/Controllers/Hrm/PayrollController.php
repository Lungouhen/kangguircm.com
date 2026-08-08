<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::with(['employee.user'])
            ->latest()
            ->paginate(15);

        return view('hrm.payrolls.index', compact('payrolls'));
    }

    public function create()
    {
        $employees = Employee::where('is_active', true)->get();
        return view('hrm.payrolls.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        
        // Calculate totals
        $grossSalary = (float)$validated['base_salary'] + (float)($validated['allowances'] ?? 0) + (float)($validated['bonus'] ?? 0);
        $netSalary = $grossSalary - (float)($validated['deductions'] ?? 0);

        $payroll = Payroll::create([
            'employee_id' => $validated['employee_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'base_salary' => $validated['base_salary'],
            'allowances' => $validated['allowances'] ?? 0,
            'deductions' => $validated['deductions'] ?? 0,
            'bonus' => $validated['bonus'] ?? 0,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.hrm.payrolls.index')
            ->with('success', 'Payroll record created successfully.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load(['employee.user']);
        return view('hrm.payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $employees = Employee::where('is_active', true)->get();
        return view('hrm.payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'base_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
        ]);

        $grossSalary = (float)$validated['base_salary'] + (float)($validated['allowances'] ?? 0) + (float)($validated['bonus'] ?? 0);
        $netSalary = $grossSalary - (float)($validated['deductions'] ?? 0);

        $payroll->update([
            'employee_id' => $validated['employee_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'base_salary' => $validated['base_salary'],
            'allowances' => $validated['allowances'] ?? 0,
            'deductions' => $validated['deductions'] ?? 0,
            'bonus' => $validated['bonus'] ?? 0,
            'gross_salary' => $grossSalary,
            'net_salary' => $netSalary,
        ]);

        return redirect()->route('admin.hrm.payrolls.index')
            ->with('success', 'Payroll record updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()->route('admin.hrm.payrolls.index')
            ->with('success', 'Payroll record deleted successfully.');
    }

    public function generate(Payroll $payroll)
    {
        $payroll->update(['status' => 'paid']);

        return back()->with('success', 'Payroll marked as paid.');
    }
}
