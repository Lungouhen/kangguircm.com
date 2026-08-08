<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hrm;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(): View
    {
        $user = request()->user();

        if ($user->hasRole(['admin', 'hr_manager'])) {
            $leaveRequests = LeaveRequest::with(['employee.user'])
                ->latest()
                ->paginate(20);
        } else {
            $employee = $user->employee;
            $leaveRequests = $employee 
                ? LeaveRequest::where('employee_id', $employee->id)->latest()->paginate(20)
                : collect();
        }

        return view('hrm.leave.index', compact('leaveRequests'));
    }

    public function create(): View
    {
        $employee = request()->user()->employee;
        
        if (!$employee) {
            abort(403, 'No employee record found.');
        }

        $balances = LeaveBalance::where('employee_id', $employee->id)->get();

        return view('hrm.leave.create', compact('employee', 'balances'));
    }

    public function store(Request $request): RedirectResponse
    {
        $employee = $request->user()->employee;

        if (!$employee) {
            return back()->with('error', 'No employee record found.');
        }

        $data = $request->validate([
            'leave_type' => ['required', 'string', 'in:annual,sick,maternity,paternity,unpaid'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'reason' => ['required', 'string', 'max:500'],
            'attachment' => ['nullable', 'file', 'max:2048'],
        ]);

        // Calculate days
        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $endDate = \Carbon\Carbon::parse($data['end_date']);
        $days = $startDate->diffInDays($endDate) + 1;

        // Check balance for annual leave
        if ($data['leave_type'] === 'annual') {
            $balance = LeaveBalance::where('employee_id', $employee->id)
                ->where('leave_type', 'annual')
                ->first();

            if (!$balance || $balance->remaining_days < $days) {
                return back()->with('error', 'Insufficient annual leave balance.');
            }
        }

        $data['employee_id'] = $employee->id;
        $data['total_days'] = $days;
        $data['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('leave/attachments', 'public');
        }

        LeaveRequest::create($data);

        return redirect()
            ->route('hrm.leave.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    public function show(LeaveRequest $leaveRequest): View
    {
        $leaveRequest->load(['employee.user']);
        return view('hrm.leave.show', compact('leaveRequest'));
    }

    public function approve(LeaveRequest $leaveRequest): RedirectResponse
    {
        $this->authorize('approve', $leaveRequest);

        $leaveRequest->update(['status' => 'approved']);

        // Deduct from balance if annual leave
        if ($leaveRequest->leave_type === 'annual') {
            $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                ->where('leave_type', 'annual')
                ->first();

            if ($balance) {
                $balance->decrement('remaining_days', $leaveRequest->total_days);
            }
        }

        return back()->with('success', 'Leave request approved.');
    }

    public function reject(LeaveRequest $leaveRequest): RedirectResponse
    {
        $this->authorize('reject', $leaveRequest);

        $leaveRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Leave request rejected.');
    }
}
