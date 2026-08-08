@extends('layouts.admin')

@section('title', 'Leave Requests')
@section('page-title', 'Leave Requests')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-2">
            @foreach(['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $status => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $status === 'all' ? null : $status]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                          {{ (request('status', 'all') === $status) 
                              ? 'bg-blue-600 text-white' 
                              : 'bg-white text-gray-700 hover:bg-gray-100' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        @if(auth()->user()->employee)
            <a href="{{ route('hrm.leave.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Request Leave
            </a>
        @endif
    </div>

    {{-- Leave Balances (for employees) --}}
    @if(auth()->user()->employee)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @php
                $balances = \App\Models\LeaveBalance::where('employee_id', auth()->user()->employee->id)->get();
            @endphp
            @forelse($balances as $balance)
                <div class="bg-white p-4 rounded-lg shadow">
                    <div class="text-sm text-gray-500 capitalize">{{ str_replace('_', ' ', $balance->leave_type) }} Balance</div>
                    <div class="text-2xl font-bold 
                        {{ $balance->remaining_days < 3 ? 'text-red-600' : ($balance->remaining_days < 7 ? 'text-yellow-600' : 'text-green-600') }}">
                        {{ $balance->remaining_days }} / {{ $balance->total_days }}
                    </div>
                    <div class="text-xs text-gray-400 mt-1">Days remaining</div>
                </div>
            @empty
                <div class="bg-blue-50 p-4 rounded-lg col-span-4">
                    <p class="text-blue-700 text-sm">No leave balance records found. Contact HR for allocation.</p>
                </div>
            @endforelse
        </div>
    @endif

    {{-- Requests Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Leave Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Days</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($leaveRequests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $request->employee->user->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $request->employee->employee_code }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 capitalize">
                                {{ str_replace('_', ' ', $request->leave_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $request->total_days }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $request->reason }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-800' }} capitalize">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('hrm.leave.show', $request) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if(auth()->user()->hasRole(['admin', 'hr_manager']) && $request->status === 'pending')
                                <form action="{{ route('hrm.leave.approve', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                </form>
                                <form action="{{ route('hrm.leave.reject', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No leave requests found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($leaveRequests->hasPages())
        <div class="bg-white px-4 py-3 rounded-lg shadow">
            {{ $leaveRequests->links() }}
        </div>
    @endif
</div>
@endsection
