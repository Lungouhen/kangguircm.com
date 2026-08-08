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
        $user = auth()->user();
        
        // Aggregate statistics based on user role
        $stats = [
            'posts_count' => Post::count(),
            'posts_views' => Post::sum('views'),
            'subscribers_count' => Subscriber::where('is_active', true)->count(),
            'campaigns_sent' => Campaign::where('status', 'sent')->count(),
            'employees_count' => Employee::where('is_active', true)->count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'today_attendance' => Attendance::whereDate('clock_in', today())->count(),
        ];

        // Recent activity
        $recentPosts = Post::with('author')->latest()->limit(5)->get();
        $recentCampaigns = Campaign::latest()->limit(5)->get();
        $pendingLeaves = LeaveRequest::with('employee.user')->where('status', 'pending')->latest()->limit(5)->get();

        return view('dashboard', compact('stats', 'recentPosts', 'recentCampaigns', 'pendingLeaves'));
    }
}
