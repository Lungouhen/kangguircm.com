<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Campaign;
use App\Models\Employee;
use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'posts' => Post::count(),
            'published_posts' => Post::published()->count(),
            'draft_posts' => Post::draft()->count(),
            'post_views' => Post::sum('views'),
            'subscribers' => Subscriber::count(),
            'campaigns' => Campaign::count(),
            'campaigns_sent' => Campaign::where('status', Campaign::STATUS_SENT)->count(),
            'emails_sent' => Campaign::sum('sent_count'),
            'emails_opened' => Campaign::sum('opened_count'),
            'emails_clicked' => Campaign::sum('clicked_count'),
            'employees' => Employee::count(),
            'active_employees' => Employee::where('status', Employee::STATUS_ACTIVE)->count(),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'recentPosts' => Post::with('author')->latest()->limit(5)->get(),
            'todayAttendance' => Attendance::with('employee.user')->whereDate('date', today())->latest('clock_in')->get(),
        ]);
    }
}
