<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $attendances = Attendance::with(['employee.user'])
            ->latest('check_in')
            ->paginate(20);

        return view('hrm.attendance.index', compact('attendances'));
    }

    public function clockIn(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        // Check if already clocked in today
        $today = now()->startOfDay();
        $existingClockIn = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', today())
            ->first();

        if ($existingClockIn) {
            return back()->with('error', 'You have already clocked in today.');
        }

        Attendance::create([
            'employee_id' => $employee->id,
            'check_in' => now(),
            'check_in_ip' => $request->ip(),
            'check_in_user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Clocked in successfully at ' . now()->format('H:i:s'));
    }

    public function clockOut(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        // Find today's clock-in
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('check_in', today())
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return back()->with('error', 'No clock-in record found for today.');
        }

        $attendance->update([
            'check_out' => now(),
            'check_out_ip' => $request->ip(),
            'check_out_user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Clocked out successfully at ' . now()->format('H:i:s'));
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load(['employee.user']);
        return view('hrm.attendance.show', compact('attendance'));
    }
}
