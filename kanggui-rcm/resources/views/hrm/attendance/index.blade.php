@extends('layouts.admin')

@section('title', 'Attendance')
@section('page-title', 'Attendance Tracking')

@section('content')
<div class="space-y-6">
    {{-- Clock In/Out Panel --}}
    @if(auth()->user()->employee)
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold">Welcome, {{ auth()->user()->name }}</h2>
                    <p class="text-blue-100 mt-1">{{ auth()->user()->employee->position }} - {{ auth()->user()->employee->department }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="text-sm text-blue-100">Current Time</div>
                        <div class="text-3xl font-bold" id="currentTime">{{ now()->format('H:i:s') }}</div>
                        <div class="text-sm text-blue-100">{{ now()->format('M d, Y') }}</div>
                    </div>
                    
                    @php
                        $todayAttendance = \App\Models\Attendance::where('employee_id', auth()->user()->employee->id)
                            ->whereDate('check_in', today())
                            ->first();
                    @endphp
                    
                    @if(!$todayAttendance)
                        <form action="{{ route('hrm.attendance.clock-in') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-600 rounded-lg font-semibold transition-colors">
                                Clock In
                            </button>
                        </form>
                    @elseif(!$todayAttendance->check_out)
                        <form action="{{ route('hrm.attendance.clock-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 rounded-lg font-semibold transition-colors">
                                Clock Out
                            </button>
                        </form>
                    @else
                        <div class="px-6 py-3 bg-white/20 rounded-lg font-semibold">
                            ✓ Clocked In: {{ $todayAttendance->check_in->format('H:i') }} | Out: {{ $todayAttendance->check_out->format('H:i') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
            <p class="text-yellow-700">No employee record found. Please contact HR to create your employee profile.</p>
        </div>
    @endif

    {{-- Attendance Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Attendance Records</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Check Out</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($attendances as $attendance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $attendance->employee->user->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500">{{ $attendance->employee->employee_code }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $attendance->check_in->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $attendance->check_in->format('H:i:s') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($attendance->check_out)
                                {{ number_format($attendance->check_in->diffInMinutes($attendance->check_out) / 60, 2) }} hrs
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->check_out)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Complete
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    In Progress
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            No attendance records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($attendances->hasPages())
        <div class="bg-white px-4 py-3 rounded-lg shadow">
            {{ $attendances->links() }}
        </div>
    @endif
</div>

<script>
// Update time every second
setInterval(function() {
    const now = new Date();
    document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', { hour12: false });
}, 1000);
</script>
@endsection
