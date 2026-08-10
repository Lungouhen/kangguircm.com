@extends('layouts.admin')
@section('title', 'Payslip')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between">
        <a href="{{ route('admin.hrm.payrolls.index') }}" class="text-blue-600">&larr; Back</a>
        <button onclick="window.print()" class="btn-primary">Print Payslip</button>
    </div>
    <div class="bg-white shadow rounded-lg p-8 border-t-4 border-blue-600">
        <div class="text-center mb-6"><h1 class="text-2xl font-bold text-gray-900">PAYSLIP</h1><p class="text-gray-500">{{ $payroll->period_start->format('M j').' – '.$payroll->period_end->format('M j, Y') }}</p></div>
        <div class="grid grid-cols-2 gap-4 mb-6 border-b pb-4">
            <div><span class="font-medium">Employee:</span> {{ $payroll->employee->user->name }}</div>
            <div><span class="font-medium">Employee ID:</span> {{ $payroll->employee->employee_code }}</div>
            <div><span class="font-medium">Department:</span> {{ $payroll->employee->department }}</div>
            <div><span class="font-medium">Position:</span> {{ $payroll->employee->position }}</div>
        </div>
        <table class="w-full mb-6">
            <thead class="bg-gray-50"><tr><th class="text-left py-2">Earnings</th><th class="text-right">Amount</th></tr></thead>
            <tbody>
                <tr class="border-b"><td class="py-2">Base Salary</td><td class="text-right">${{ number_format($payroll->basic_salary, 2) }}</td></tr>
                <tr class="border-b"><td class="py-2">Allowances</td><td class="text-right text-green-600">+${{ number_format($payroll->allowances, 2) }}</td></tr>
                <tr class="border-b"><td class="py-2 font-bold">Total Earnings</td><td class="text-right font-bold">${{ number_format($payroll->basic_salary + $payroll->allowances, 2) }}</td></tr>
            </tbody>
        </table>
        <table class="w-full mb-6">
            <thead class="bg-gray-50"><tr><th class="text-left py-2">Deductions</th><th class="text-right">Amount</th></tr></thead>
            <tbody><tr class="border-b"><td class="py-2">Deductions</td><td class="text-right text-red-600">-${{ number_format($payroll->deductions, 2) }}</td></tr></tbody>
        </table>
        <div class="border-t-2 pt-4"><div class="flex justify-between items-center"><span class="text-xl font-bold">Net Pay</span><span class="text-2xl font-bold text-blue-600">${{ number_format($payroll->net_salary, 2) }}</span></div></div>
        <div class="mt-6 text-center text-sm text-gray-500"><p>Status: <span class="font-semibold">{{ ucfirst($payroll->status) }}</span></p><p>Generated on: {{ $payroll->created_at->format('M d, Y') }}</p></div>
    </div>
</div>
@endsection
