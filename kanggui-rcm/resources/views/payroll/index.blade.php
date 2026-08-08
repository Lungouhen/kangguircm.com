@extends('layouts.admin')
@section('title', 'Payroll')
@section('content')
<h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Payroll Management</h1>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Employee</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Period</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Basic Salary</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Allowances</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Deductions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Net Pay</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($payrolls as $payroll)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payroll->employee->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $payroll->period }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${{ number_format($payroll->basic_salary, 2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${{ number_format($payroll->allowances, 2) }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${{ number_format($payroll->deductions, 2) }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($payroll->net_pay, 2) }}</td>
                <td class="px-6 py-4 text-right text-sm"><a href="#" class="text-blue-600 hover:text-blue-900">View Slip</a></td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No payroll records</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
