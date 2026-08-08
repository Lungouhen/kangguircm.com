@extends('layouts.admin')

@section('title', 'Payroll Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Payroll Management</h1>
    <a href="{{ route('hrm.payroll.create') }}" class="btn-primary">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create Payroll
    </a>
</div>

@if(session('success'))
    <div class="alert-success mb-6">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Employee</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Period</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Base Salary</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Bonus</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Deductions</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Net Salary</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Status</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $payroll)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                    <td class="py-3 px-4">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $payroll->employee->user->name }}</div>
                        <div class="text-sm text-gray-500">{{ $payroll->employee->employee_id }}</div>
                    </td>
                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($payroll->period_start)->format('M d') }} - 
                        {{ \Carbon\Carbon::parse($payroll->period_end)->format('M d, Y') }}
                    </td>
                    <td class="py-3 px-4 text-right text-gray-900 dark:text-white">${{ number_format($payroll->base_salary, 2) }}</td>
                    <td class="py-3 px-4 text-right text-green-600">+${{ number_format($payroll->bonus ?? 0, 2) }}</td>
                    <td class="py-3 px-4 text-right text-red-600">-${{ number_format($payroll->deductions ?? 0, 2) }}</td>
                    <td class="py-3 px-4 text-right font-bold text-gray-900 dark:text-white">${{ number_format($payroll->net_salary, 2) }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $payroll->status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($payroll->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="{{ route('hrm.payroll.show', $payroll) }}" class="text-blue-600 hover:text-blue-800">View</a>
                        @if($payroll->status === 'pending')
                        <form action="{{ route('hrm.payroll.approve', $payroll) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="text-green-600 hover:text-green-800">Approve</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-8 text-center text-gray-500">No payroll records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($payrolls->hasPages())
    <div class="mt-4">
        {{ $payrolls->links() }}
    </div>
    @endif
</div>
@endsection
