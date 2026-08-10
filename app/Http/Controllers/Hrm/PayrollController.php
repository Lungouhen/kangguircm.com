<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollController extends Controller
{
    public function index(): View { return view('hrm.payrolls.index',['payrolls'=>Payroll::with('employee.user')->latest()->paginate(15)]); }
    public function create(): View { return view('hrm.payrolls.create',['employees'=>Employee::with('user')->where('status',Employee::STATUS_ACTIVE)->get()]); }

    public function store(Request $request): RedirectResponse
    {
        $data=$this->validated($request);
        $payroll=new Payroll($data);
        $payroll->net_salary=$payroll->calculateNetSalary();
        $payroll->status=Payroll::STATUS_PENDING;
        $payroll->save();
        return redirect()->route('admin.hrm.payrolls.index')->with('success','Payroll record created.');
    }

    public function show(Payroll $payroll): View { return view('hrm.payrolls.show',['payroll'=>$payroll->load('employee.user')]); }

    public function update(Request $request, Payroll $payroll): RedirectResponse
    {
        $payroll->fill($this->validated($request));
        $payroll->net_salary=$payroll->calculateNetSalary();
        $payroll->save();
        return redirect()->route('admin.hrm.payrolls.index')->with('success','Payroll updated.');
    }

    public function destroy(Payroll $payroll): RedirectResponse
    {
        abort_if($payroll->status===Payroll::STATUS_PAID,422,'Paid payroll cannot be deleted.');
        $payroll->delete();
        return back()->with('success','Payroll deleted.');
    }

    public function generate(Payroll $payroll): RedirectResponse
    {
        $payroll->markAsPaid();
        return back()->with('success','Payroll marked as paid.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'employee_id'=>['required','exists:employees,id'],
            'period_start'=>['required','date'],
            'period_end'=>['required','date','after_or_equal:period_start'],
            'pay_date'=>['required','date','after_or_equal:period_end'],
            'basic_salary'=>['required','numeric','min:0'],
            'allowances'=>['nullable','numeric','min:0'],
            'overtime_pay'=>['nullable','numeric','min:0'],
            'bonus'=>['nullable','numeric','min:0'],
            'deductions'=>['nullable','numeric','min:0'],
            'tax'=>['nullable','numeric','min:0'],
            'social_security'=>['nullable','numeric','min:0'],
            'notes'=>['nullable','string','max:2000'],
        ]);
    }
}
