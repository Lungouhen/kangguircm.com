<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // CMS Statistics
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $totalViews = Post::sum('view_count');

        // Email Marketing Statistics
        $totalSubscribers = Subscriber::count();
        $totalCampaigns = Campaign::count();
        $sentCampaigns = Campaign::where('status', 'sent')->count();
        $totalSent = Campaign::sum('sent_count');
        $totalOpened = Campaign::sum('open_count');
        $totalClicked = Campaign::sum('click_count');

        // HRM Statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('is_active', true)->count();
        $pendingLeaveRequests = LeaveRequest::where('status', 'pending')->count();
        $todayAttendances = DB::table('attendances')
            ->whereDate('date', today())
            ->count();

        // Recent Activity
        $recentPosts = Post::with('author')
            ->latest()
            ->limit(5)
            ->get();

        $recentLeaveRequests = LeaveRequest::with(['employee.user'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPosts',
            'publishedPosts',
            'draftPosts',
            'totalViews',
            'totalSubscribers',
            'totalCampaigns',
            'sentCampaigns',
            'totalSent',
            'totalOpened',
            'totalClicked',
            'totalEmployees',
            'activeEmployees',
            'pendingLeaveRequests',
            'todayAttendances',
            'recentPosts',
            'recentLeaveRequests'
        ));
    }
}
