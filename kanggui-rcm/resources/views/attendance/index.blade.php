@extends('layouts.admin')
@section('title', 'Attendance')
@section('content')
<h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Attendance Tracking</h1>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
    <form method="POST" action="{{ route('attendance.clock-in') }}" class="flex items-center space-x-4">
        @csrf
        <button type="submit" name="action" value="clock_in" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">Clock In</button>
        <button type="submit" name="action" value="clock_out" class="px-6 py-3 bg-red-600 text-white rounded-md hover:bg-red-700 font-semibold">Clock Out</button>
    </form>
</div>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Clock In</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Clock Out</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($attendances as $att)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $att->date }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $att->clock_in ?? '-' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $att->clock_out ?? '-' }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Present</span></td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No attendance records</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
