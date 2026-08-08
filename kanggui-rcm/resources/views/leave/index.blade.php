@extends('layouts.admin')
@section('title', 'Leave Requests')
@section('content')
<div class="md:flex md:items-center md:justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Leave Requests</h1>
    <a href="{{ route('leave.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Request Leave</a>
</div>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($requests as $req)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ ucfirst($req->leave_type) }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $req->start_date }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $req->end_date }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $req->status === 'approved' ? 'bg-green-100 text-green-800' : ($req->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">{{ ucfirst($req->status) }}</span></td>
                <td class="px-6 py-4 text-right text-sm"><a href="#" class="text-blue-600 hover:text-blue-900">View</a></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No leave requests</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
