@extends('layouts.admin')
@section('title', 'Employees')
@section('content')
<div class="md:flex md:items-center md:justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Employees</h1>
    <a href="#" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Employee</a>
</div>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Department</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Position</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($employees as $employee)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $employee->user->name ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $employee->department ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $employee->position ?? 'N/A' }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span></td>
                <td class="px-6 py-4 text-right text-sm"><a href="#" class="text-blue-600 hover:text-blue-900">View</a></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No employees found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
