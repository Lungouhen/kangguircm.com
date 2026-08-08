@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Posts</div>
        <div class="stat-value">{{ $stats['posts'] ?? 0 }}</div>
        <div class="stat-change positive">+12% from last month</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Subscribers</div>
        <div class="stat-value">{{ $stats['subscribers'] ?? 0 }}</div>
        <div class="stat-change positive">+8% from last month</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Email Campaigns</div>
        <div class="stat-value">{{ $stats['campaigns'] ?? 0 }}</div>
        <div class="stat-change positive">+3 this week</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Employees</div>
        <div class="stat-value">{{ $stats['employees'] ?? 0 }}</div>
        <div class="stat-change">Active staff</div>
    </div>
</div>

<div class="card">
    <div class="card-header">Quick Actions</div>
    <div class="card-body">
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('admin.cms.posts.create') }}" class="btn btn-primary">New Post</a>
            <a href="{{ route('admin.email.campaigns.create') }}" class="btn btn-primary">New Campaign</a>
            <a href="{{ route('admin.hrm.employees.create') }}" class="btn btn-primary">Add Employee</a>
            <a href="{{ route('admin.cms.media.index') }}" class="btn btn-outline">Media Library</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Recent Activity</div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>New post published</td>
                    <td>CMS Editor</td>
                    <td>Today, 10:30 AM</td>
                    <td><span class="badge badge-success">Published</span></td>
                </tr>
                <tr>
                    <td>Email campaign sent</td>
                    <td>Email Manager</td>
                    <td>Yesterday, 3:45 PM</td>
                    <td><span class="badge badge-info">Sent</span></td>
                </tr>
                <tr>
                    <td>Leave request approved</td>
                    <td>HR Manager</td>
                    <td>Yesterday, 9:15 AM</td>
                    <td><span class="badge badge-success">Approved</span></td>
                </tr>
                <tr>
                    <td>New subscriber added</td>
                    <td>System</td>
                    <td>Aug 6, 2024</td>
                    <td><span class="badge badge-success">Active</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
