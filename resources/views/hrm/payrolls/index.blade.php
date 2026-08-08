@extends('layouts.admin')

@section('title', 'Payroll Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Payroll Records</h1>
        <a href="{{ route('admin.hrm.payrolls.create') }}" 
           class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Payroll
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <!-- Payroll Table -->
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gross Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deductions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payrolls as $payroll)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $payroll->employee->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $payroll->employee->employee_code }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($payroll->period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($payroll->period_end)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($payroll->gross_salary, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                            ${{ number_format($payroll->deductions, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            ${{ number_format($payroll->net_salary, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payroll->status === 'paid')
                                <span class="badge-success">Paid</span>
                            @elseif($payroll->status === 'pending')
                                <span class="badge-warning">Pending</span>
                            @else
                                <span class="badge-secondary">{{ ucfirst($payroll->status) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.hrm.payrolls.show', $payroll) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">View</a>
                            @if($payroll->status === 'pending')
                                <form action="{{ route('admin.hrm.payrolls.generate', $payroll) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Mark Paid</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No payroll records found. Create your first payroll record.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($payrolls->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
