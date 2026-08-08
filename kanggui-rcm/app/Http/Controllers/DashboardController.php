<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = request()->user();

        // CMS Stats
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $totalViews = Post::sum('view_count');

        // Email Stats
        $totalSubscribers = Subscriber::count();
        $totalCampaigns = Campaign::count();
        $sentCampaigns = Campaign::where('status', 'sent')->count();

        // HRM Stats
        $totalEmployees = Employee::count();
        $pendingLeaves = LeaveRequest::where('status', 'pending')->count();
        $todayAttendance = Attendance::whereDate('check_in', today())->count();

        // Recent Activity
        $recentPosts = Post::latest()->limit(5)->get(['id', 'title', 'status', 'created_at']);
        $recentLeaves = LeaveRequest::with('employee.user')
            ->latest()
            ->limit(5)
            ->get(['id', 'employee_id', 'leave_type', 'status', 'created_at']);

        return view('admin.dashboard', compact(
            'totalPosts',
            'publishedPosts',
            'totalViews',
            'totalSubscribers',
            'totalCampaigns',
            'sentCampaigns',
            'totalEmployees',
            'pendingLeaves',
            'todayAttendance',
            'recentPosts',
            'recentLeaves'
        ));
    }
}
