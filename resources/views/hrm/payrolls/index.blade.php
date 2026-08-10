@extends('layouts.admin')
@section('title', 'Payroll Management')
@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Payroll Management</h1>
        <a href="{{ route('admin.hrm.payrolls.create') }}" class="btn-primary">Generate Payroll</a>
    </div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Base Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Allowances</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deductions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Pay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payrolls as $payroll)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payroll->period_start->format('M j').' – '.$payroll->period_end->format('M j, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payroll->employee->user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($payroll->basic_salary, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">+${{ number_format($payroll->allowances, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">-${{ number_format($payroll->deductions, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${{ number_format($payroll->net_salary, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($payroll->status) }}</span></td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"><a href="{{ route('admin.hrm.payrolls.show', $payroll) }}" class="text-blue-600 hover:text-blue-900">View</a></td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-10 text-center text-gray-500">No payroll records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
