@extends('layouts.admin')
@section('title','Generate Payroll')
@section('content')
<div class="max-w-3xl mx-auto"><a href="{{ route('admin.hrm.payrolls.index') }}">&larr; Back to Payroll</a><form action="{{ route('admin.hrm.payrolls.store') }}" method="POST" class="card p-6 mt-6 space-y-4">@csrf
<label><span class="form__label">Employee</span><select name="employee_id" class="form__select" required>@foreach($employees as $employee)<option value="{{ $employee->id }}">{{ $employee->user?->name??$employee->employee_code }} — {{ $employee->position }}</option>@endforeach</select></label>
<div class="grid md:grid-cols-3 gap-4"><label><span class="form__label">Period start</span><input type="date" name="period_start" class="form__input" required></label><label><span class="form__label">Period end</span><input type="date" name="period_end" class="form__input" required></label><label><span class="form__label">Pay date</span><input type="date" name="pay_date" class="form__input" required></label></div>
<div class="grid md:grid-cols-2 gap-4">@foreach(['basic_salary'=>'Basic salary','allowances'=>'Allowances','overtime_pay'=>'Overtime pay','bonus'=>'Bonus','deductions'=>'Deductions','tax'=>'Tax','social_security'=>'Social security'] as $name=>$label)<label><span class="form__label">{{ $label }}</span><input type="number" min="0" step="0.01" name="{{ $name }}" value="{{ old($name,0) }}" class="form__input" @if($name==='basic_salary') required @endif></label>@endforeach</div>
<label><span class="form__label">Notes</span><textarea name="notes" class="form__textarea">{{ old('notes') }}</textarea></label><div class="flex justify-end"><button class="btn btn--primary">Create payroll</button></div></form></div>
@endsection
