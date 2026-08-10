<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#175cd3">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'KangGui RCM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        :root {
            --color-primary: #2563eb;
            --color-primary-hover: #1d4ed8;
            --color-secondary: #64748b;
            --color-success: #22c55e;
            --color-warning: #f59e0b;
            --color-danger: #ef4444;
            --color-background: #f8fafc;
            --color-surface: #ffffff;
            --color-text: #1e293b;
            --color-text-muted: #64748b;
            --color-border: #e2e8f0;
            --sidebar-bg: #0f172a;
            --sidebar-surface: #172033;
            --sidebar-text: #cbd5e1;
            --sidebar-muted: #7f8da3;
            --spacing-xs: 0.25rem;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --radius-sm: 0.375rem;
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--color-background);
            color: var(--color-text);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .admin-sidebar {
            width: 272px;
            background: linear-gradient(180deg, var(--sidebar-bg), #111827);
            border-right: 1px solid rgb(255 255 255 / 0.06);
            position: fixed;
            inset: 0 auto 0 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
            z-index: 40;
            scrollbar-width: thin;
            scrollbar-color: #334155 transparent;
        }

        .sidebar-header {
            min-height: 76px;
            padding: 0.875rem 1.25rem;
            border-bottom: 1px solid rgb(255 255 255 / 0.08);
            display: flex;
            align-items: center;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
        }
        .sidebar-brand:hover { color: white; text-decoration: none; }
        .sidebar-brand img { width: 42px; height: 42px; object-fit: contain; }
        .sidebar-brand small { display: block; color: #94a3b8; font-size: 0.7rem; font-weight: 500; margin-top: 0.1rem; }

        .sidebar-nav {
            padding: var(--spacing-md) 0;
            flex: 1;
        }

        .nav-section {
            margin-bottom: var(--spacing-lg);
        }

        .nav-section-title {
            padding: 0.625rem 1.25rem;
            font-size: 0.68rem;
            font-weight: 700;
            color: var(--sidebar-muted);
            text-transform: uppercase;
            letter-spacing: 0.11em;
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.65rem;
            margin: 0.125rem 0.75rem;
            padding: 0.62rem 0.75rem;
            border-radius: 0.5rem;
            color: var(--sidebar-text);
            font-size: 0.875rem;
            text-decoration: none;
            transition: background-color 0.18s, color 0.18s, transform 0.18s;
        }

        .nav-link:hover {
            background: rgb(255 255 255 / 0.07);
            color: white;
            text-decoration: none;
            transform: translateX(2px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, #175cd3, #2563eb);
            color: white;
            box-shadow: 0 8px 20px rgb(23 92 211 / 0.25);
        }
        .nav-badge { margin-left: auto; min-width: 1.35rem; padding: 0.1rem 0.4rem; border-radius: 999px; background: #ef4444; color: white; font-size: 0.65rem; text-align: center; }

        .nav-link-icon {
            width: 19px;
            height: 19px;
            flex: 0 0 auto;
        }

        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 272px;
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation */
        .admin-topnav {
            background-color: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            padding: var(--spacing-md) var(--spacing-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topnav-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .topnav-context small { display: block; color: var(--color-text-muted); font-size: 0.72rem; }
        .topnav-context strong { font-size: 0.95rem; }
        .topnav-action { display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.5rem 0.7rem; border-radius: 0.5rem; color: #475569; text-decoration: none; font-size: 0.82rem; }
        .topnav-action:hover { color: var(--color-primary); background: #eff6ff; text-decoration: none; }
        .admin-popover { position: absolute; right: 0; top: calc(100% + 0.55rem); width: 240px; padding: 0.5rem; border: 1px solid var(--color-border); border-radius: 0.75rem; background: white; box-shadow: var(--shadow-lg); z-index: 60; }
        .admin-popover a, .admin-popover button { display: flex; width: 100%; align-items: center; padding: 0.65rem 0.75rem; border-radius: 0.45rem; color: #334155; font-size: 0.85rem; text-decoration: none; }
        .admin-popover a:hover, .admin-popover button:hover { background: #f1f5f9; color: var(--color-primary); }
        .admin-backdrop { position: fixed; inset: 0; background: rgb(15 23 42 / 0.55); z-index: 35; }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: var(--spacing-sm);
        }

        .topnav-right {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--color-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        /* Content Area */
        .admin-content {
            padding: var(--spacing-lg);
            flex: 1;
        }

        .page-header {
            margin-bottom: var(--spacing-lg);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0 0 var(--spacing-sm) 0;
        }

        .page-subtitle {
            color: var(--color-text-muted);
            margin: 0;
        }

        /* Card Component */
        .card {
            background-color: var(--color-surface);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-border);
            margin-bottom: var(--spacing-lg);
        }

        .card-header {
            padding: var(--spacing-md) var(--spacing-lg);
            border-bottom: 1px solid var(--color-border);
            font-weight: 600;
        }

        .card-body {
            padding: var(--spacing-lg);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .stat-card {
            background-color: var(--color-surface);
            padding: var(--spacing-lg);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-border);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--color-text-muted);
            margin-bottom: var(--spacing-xs);
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--color-text);
        }

        .stat-change {
            font-size: 0.875rem;
            margin-top: var(--spacing-xs);
        }

        .stat-change.positive {
            color: var(--color-success);
        }

        .stat-change.negative {
            color: var(--color-danger);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-sm) var(--spacing-md);
            border-radius: var(--radius-sm);
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            font-size: 0.875rem;
        }

        .btn-primary {
            background-color: var(--color-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-hover);
        }

        .btn-secondary {
            background-color: var(--color-secondary);
            color: white;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--color-border);
            color: var(--color-text);
        }

        .btn-outline:hover {
            background-color: var(--color-background);
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: var(--spacing-sm) var(--spacing-md);
            text-align: left;
            border-bottom: 1px solid var(--color-border);
        }

        .table th {
            font-weight: 600;
            color: var(--color-text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .table tr:hover {
            background-color: var(--color-background);
        }

        /* Badge */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                z-index: 100;
            }

            .admin-sidebar.open {
                transform: translateX(0);
            }

            .admin-main {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false, quickOpen: false, userOpen: false }" @keydown.escape.window="sidebarOpen=false;quickOpen=false;userOpen=false">
    <div class="admin-layout">
        <div class="admin-backdrop md:hidden" x-show="sidebarOpen" x-cloak @click="sidebarOpen=false" x-transition.opacity></div>
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar" :class="{ 'open': sidebarOpen }">
            <div class="sidebar-header">
                <a href="{{ Auth::user()?->hasRole('admin') ? route('admin.dashboard') : route('admin.cms.posts.index') }}" class="sidebar-brand">
                    <img src="{{ asset('images/logo-dark.svg') }}" alt="" width="42" height="42">
                    <span>KangGui RCM<small>Content & Operations</small></span>
                </a>
            </div>

            <nav class="sidebar-nav" @click="if($event.target.closest('a')) sidebarOpen=false">
                @if(Auth::user()?->hasRole('admin'))
                <div class="nav-section">
                    <div class="nav-section-title">Main</div>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.marketing-leads.index') }}" class="nav-link {{ request()->routeIs('admin.marketing-leads.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a5 5 0 00-10 0v2m10 0H7m8-11a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Marketing Leads
                        @if(($adminUiCounts['new_leads'] ?? 0) > 0)<span class="nav-badge">{{ min(99, $adminUiCounts['new_leads']) }}</span>@endif
                    </a>
                    <a href="{{ route('admin.analytics.index') }}" class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg>
                        Visitor Analytics
                    </a>
                    <a href="{{ route('admin.notifications.index') }}" class="nav-link {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">Notification Deliveries @if(($adminUiCounts['failed_notifications'] ?? 0)>0)<span class="nav-badge">{{ min(99,$adminUiCounts['failed_notifications']) }}</span>@endif</a>
                </div>
                @endif

                @if(Auth::user()?->hasPermission('posts.view') || Auth::user()?->hasPermission('pages.view') || Auth::user()?->hasPermission('forms.manage') || Auth::user()?->hasPermission('media.upload'))
                <div class="nav-section">
                    <div class="nav-section-title">CMS</div>
                    @if(Auth::user()?->hasPermission('posts.view'))
                    <a href="{{ route('admin.cms.posts.index') }}" class="nav-link {{ request()->routeIs('admin.cms.posts.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Posts
                    </a>
                    @endif
                    @if(Auth::user()?->hasPermission('pages.view'))
                    <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Pages
                    </a>
                    @endif
                    @if(Auth::user()?->hasPermission('posts.view'))
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
                    @endif
                    @if(Auth::user()?->hasPermission('pages.view'))
                    <a href="{{ route('admin.content.index') }}" class="nav-link {{ request()->routeIs('admin.content.*') ? 'active' : '' }}">Structured RCM Content</a>
                    @endif
                    @if(Auth::user()?->hasPermission('forms.manage'))
                    <a href="{{ route('admin.forms.index') }}" class="nav-link {{ request()->routeIs('admin.forms.*') || request()->routeIs('admin.form-*') ? 'active' : '' }}">Forms & Submissions</a>
                    @endif
                    @if(Auth::user()?->hasPermission('media.upload'))
                    <a href="{{ route('admin.media.index') }}" class="nav-link {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4 4 4 4-6 4 6M4 4h16v16H4z"/></svg>
                        Media Library
                    </a>
                    @endif
                </div>
                @endif

                @if(Auth::user()?->hasPermission('lists.manage') || Auth::user()?->hasPermission('templates.manage') || Auth::user()?->hasPermission('campaigns.view'))
                <div class="nav-section">
                    <div class="nav-section-title">Email Marketing</div>
                    @if(Auth::user()?->hasPermission('lists.manage'))
                    <a href="{{ route('admin.email.lists.index') }}" class="nav-link {{ request()->routeIs('admin.email.lists.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Lists
                    </a>
                    @endif
                    @if(Auth::user()?->hasPermission('templates.manage'))
                    <a href="{{ route('admin.email.templates.index') }}" class="nav-link {{ request()->routeIs('admin.email.templates.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Templates
                    </a>
                    @endif
                    @if(Auth::user()?->hasPermission('campaigns.view'))
                    <a href="{{ route('admin.email.campaigns.index') }}" class="nav-link {{ request()->routeIs('admin.email.campaigns.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Campaigns
                    </a>
                    @endif
                </div>
                @endif

                @if(Auth::user()?->hasPermission('employees.view') || Auth::user()?->hasPermission('payroll.view'))
                <div class="nav-section">
                    <div class="nav-section-title">HRM</div>
                    @if(Auth::user()?->hasPermission('employees.view'))
                    <a href="{{ route('admin.hrm.employees.index') }}" class="nav-link {{ request()->routeIs('admin.hrm.employees.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Employees
                    </a>
                    @endif
                    @if(Auth::user()?->hasPermission('payroll.view'))
                    <a href="{{ route('admin.hrm.payrolls.index') }}" class="nav-link {{ request()->routeIs('admin.hrm.payrolls.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Payroll
                    </a>
                    @endif
                </div>
                @endif

                @if(Auth::user()?->hasRole('admin'))
                <div class="nav-section">
                    <div class="nav-section-title">Settings</div>
                    <a href="{{ route('admin.settings.site.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Site Settings</a>
                    <a href="{{ route('admin.menus.index') }}" class="nav-link {{ request()->routeIs('admin.menus.*') || request()->routeIs('admin.menu-items.*') ? 'active' : '' }}">Menus</a>
                    <a href="{{ route('admin.widgets.index') }}" class="nav-link {{ request()->routeIs('admin.widgets.*') ? 'active' : '' }}">Widget Modules</a>
                    <a href="{{ route('admin.legal.index') }}" class="nav-link {{ request()->routeIs('admin.legal.*') ? 'active' : '' }}">Legal & Privacy</a>
                    <a href="{{ route('admin.redirects.index') }}" class="nav-link {{ request()->routeIs('admin.redirects.*') ? 'active' : '' }}">Redirects</a>
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.657-1.79 3-4 3s-4-1.343-4-3 1.79-3 4-3 4 1.343 4 3zm8-3a3 3 0 11-6 0 3 3 0 016 0zM2 20a6 6 0 0112 0m1-5a5 5 0 015 5"/></svg>
                        Roles & Permissions
                    </a>
                    <a href="{{ route('admin.theme.index') }}" class="nav-link {{ request()->routeIs('admin.theme.*') ? 'active' : '' }}">
                        <svg class="nav-link-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3a9 9 0 100 18h1.5a1.5 1.5 0 000-3H12a1.5 1.5 0 010-3h2a7 7 0 007-7c0-2.761-4.03-5-9-5zM7.5 9h.01M10 6.5h.01M15 7h.01"/></svg>
                        Appearance
                    </a>
                </div>
                @endif
            </nav>
            <div class="m-3 mt-auto rounded-lg border border-white/10 bg-white/5 p-3 text-xs text-slate-400">
                <div class="flex items-center justify-between"><span>System</span><span class="inline-flex items-center gap-1 text-emerald-400"><i class="h-2 w-2 rounded-full bg-emerald-400"></i> Online</span></div>
                <div class="mt-2">v{{ config('app.version', '1.0.0') }}</div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Top Navigation -->
            <header class="admin-topnav">
                <div class="topnav-left">
                    <button class="menu-toggle" @click="sidebarOpen=!sidebarOpen" aria-label="Toggle sidebar">
                        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="topnav-context"><small>Dashboard / {{ str(request()->route()?->getName() ?? 'admin')->replace('admin.','')->replace('.',' / ')->headline() }}</small><strong>@yield('title','Dashboard')</strong></div>
                </div>

                <div class="topnav-right">
                    <a class="topnav-action hidden sm:inline-flex" href="{{ route('home') }}" target="_blank" rel="noopener">View site ↗</a>
                    <div class="relative">
                        <button class="topnav-action" @click="quickOpen=!quickOpen;userOpen=false" aria-haspopup="menu" :aria-expanded="quickOpen">＋ Quick create</button>
                        <div class="admin-popover" x-show="quickOpen" x-cloak @click.outside="quickOpen=false" x-transition role="menu">
                            @if(Auth::user()?->hasPermission('pages.create'))<a href="{{ route('admin.pages.create') }}">New page</a>@endif
                            @if(Auth::user()?->hasPermission('posts.create'))<a href="{{ route('admin.cms.posts.create') }}">New post</a>@endif
                            @if(Auth::user()?->hasPermission('forms.manage'))<a href="{{ route('admin.forms.index') }}">New form</a>@endif
                            @if(Auth::user()?->hasRole('admin'))<a href="{{ route('admin.menus.index') }}">Edit menus</a>@endif
                        </div>
                    </div>
                    <div class="relative">
                        <button class="user-menu rounded-lg p-1.5 hover:bg-slate-100" @click="userOpen=!userOpen;quickOpen=false" aria-haspopup="menu" :aria-expanded="userOpen">
                            <div class="user-avatar">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</div>
                            <span class="hidden sm:block text-left"><strong class="block text-sm">{{ Auth::user()->name ?? 'User' }}</strong><small class="block text-xs text-slate-500">{{ Auth::user()?->role?->display_name ?? 'User' }}</small></span>
                        </button>
                        <div class="admin-popover" x-show="userOpen" x-cloak @click.outside="userOpen=false" x-transition role="menu">
                            <div class="border-b px-3 py-2 mb-1"><strong class="block text-sm">{{ Auth::user()->email }}</strong><small class="text-slate-500">Signed in securely</small></div>
                            <a href="{{ route('home') }}" target="_blank" rel="noopener">View public site</a>
                            @if(Auth::user()?->hasRole('admin'))<a href="{{ route('admin.settings.site.edit') }}">Site settings</a>@endif
                            <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-red-600">Sign out</button></form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="admin-content">
                @if(isset($pageTitle))
                <div class="page-header">
                    <h1 class="page-title">{{ $pageTitle }}</h1>
                    @if(isset($pageSubtitle))
                    <p class="page-subtitle">{{ $pageSubtitle }}</p>
                    @endif
                </div>
                @endif

                @yield('content')
            </div>
            <footer class="mt-auto border-t bg-white px-6 py-4 text-xs text-slate-500"><div class="flex flex-wrap items-center justify-between gap-3"><span>© {{ date('Y') }} {{ $site['name'] ?? config('app.name') }} · Admin Console</span><span class="flex gap-4"><a href="{{ route('home') }}" target="_blank" rel="noopener">Public site</a>@if(Auth::user()?->hasRole('admin'))<a href="{{ route('admin.settings.site.edit') }}">Settings</a>@endif</span></div></footer>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
