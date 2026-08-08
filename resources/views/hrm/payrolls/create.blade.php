@extends('layouts.admin')
@section('title', 'Generate Payroll')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6"><a href="{{ route('admin.hrm.payrolls.index') }}" class="text-blue-600">&larr; Back to Payroll</a></div>
    <form action="{{ route('admin.hrm.payrolls.store') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Select Employee</label>
            <select name="employee_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->user->name }} - {{ $employee->position }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Pay Period</label>
            <input type="text" name="period" required placeholder="e.g., August 2024" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('period', date('F Y')) }}">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div><label class="block text-sm font-medium text-gray-700">Base Salary</label><input type="number" step="0.01" name="base_salary" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700">Allowances</label><input type="number" step="0.01" name="allowances" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        </div>
        <div><label class="block text-sm font-medium text-gray-700">Deductions</label><input type="number" step="0.01" name="deductions" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        <div class="flex justify-end"><button type="submit" class="btn-primary">Generate Payslip</button></div>
    </form>
</div>
@endsection
