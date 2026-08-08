@extends('layouts.admin')
@section('title', 'Employee Profile')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.hrm.employees.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to Employees</a>
        <div class="space-x-2">
            <a href="{{ route('admin.hrm.employees.edit', $employee) }}" class="btn-primary">Edit</a>
            <form action="{{ route('admin.hrm.attendances.clock-in', $employee) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Clock In</button>
            </form>
        </div>
    </div>
    <div class="bg-white shadow overflow-hidden rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-900">{{ $employee->user->name }}</h1>
            <p class="text-gray-500">{{ $employee->position }} - {{ $employee->department }}</p>
        </div>
        <div class="px-6 py-5 grid grid-cols-2 gap-4">
            <div><span class="font-medium">Employee ID:</span> <span class="text-gray-600">{{ $employee->employee_id }}</span></div>
            <div><span class="font-medium">Email:</span> <span class="text-gray-600">{{ $employee->user->email }}</span></div>
            <div><span class="font-medium">Phone:</span> <span class="text-gray-600">{{ $employee->phone ?? 'N/A' }}</span></div>
            <div><span class="font-medium">Hire Date:</span> <span class="text-gray-600">{{ $employee->hire_date }}</span></div>
            <div><span class="font-medium">Salary:</span> <span class="text-gray-600">${{ number_format($employee->salary, 2) }}</span></div>
            <div><span class="font-medium">Status:</span> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $employee->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ ucfirst($employee->status) }}</span></div>
        </div>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Attendance</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead><tr><th class="text-left text-xs font-medium text-gray-500 uppercase">Date</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Clock In</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Clock Out</th><th class="text-left text-xs font-medium text-gray-500 uppercase">Hours</th></tr></thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($employee->attendances()->latest()->take(5)->get() as $attendance)
                <tr>
                    <td class="py-2">{{ $attendance->date }}</td>
                    <td>{{ $attendance->clock_in }}</td>
                    <td>{{ $attendance->clock_out ?? '-' }}</td>
                    <td>{{ $attendance->hours_worked ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center py-4 text-gray-500">No attendance records</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
