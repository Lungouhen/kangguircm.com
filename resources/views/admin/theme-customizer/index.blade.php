@extends('layouts.admin')
@section('title', 'Theme Customizer')
@section('content')

    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.theme.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Colors -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Brand Colors</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Primary Color</label>
                        <input type="color" name="primary_color" value="{{ \App\Models\ThemeSetting::get('colors.primary', '#2563EB') }}" class="mt-1 h-10 w-full cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Secondary Color</label>
                        <input type="color" name="secondary_color" value="{{ \App\Models\ThemeSetting::get('colors.secondary', '#7C3AED') }}" class="mt-1 h-10 w-full cursor-pointer">
                    </div>
                </div>
            </div>

            <!-- Typography -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Typography</h3>
                <select name="font_family" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="Plus Jakarta Sans" {{ \App\Models\ThemeSetting::get('typography.font_family') == 'Plus Jakarta Sans' ? 'selected' : '' }}>Plus Jakarta Sans (Modern)</option>
                    <option value="Inter" {{ \App\Models\ThemeSetting::get('typography.font_family') == 'Inter' ? 'selected' : '' }}>Inter (Clean)</option>
                    <option value="Poppins" {{ \App\Models\ThemeSetting::get('typography.font_family') == 'Poppins' ? 'selected' : '' }}>Poppins (Rounded)</option>
                    <option value="Roboto" {{ \App\Models\ThemeSetting::get('typography.font_family') == 'Roboto' ? 'selected' : '' }}>Roboto (Standard)</option>
                </select>
            </div>

            <!-- Layout -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Layout & Header</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium">Container Width</label>
                        <select name="layout_width" class="mt-1 block w-full rounded-md">
                            <option value="boxed" {{ \App\Models\ThemeSetting::get('layout.width') == 'boxed' ? 'selected' : '' }}>Boxed (Max 1280px)</option>
                            <option value="full" {{ \App\Models\ThemeSetting::get('layout.width') == 'full' ? 'selected' : '' }}>Full Width</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Header Style</label>
                        <select name="header_style" class="mt-1 block w-full rounded-md">
                            <option value="glass" {{ \App\Models\ThemeSetting::get('layout.header_style') == 'glass' ? 'selected' : '' }}>Glass (Blurred)</option>
                            <option value="solid" {{ \App\Models\ThemeSetting::get('layout.header_style') == 'solid' ? 'selected' : '' }}>Solid Color</option>
                            <option value="transparent" {{ \App\Models\ThemeSetting::get('layout.header_style') == 'transparent' ? 'selected' : '' }}>Transparent</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Animations -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium mb-4">Animations</h3>
                <div>
                    <label class="block text-sm font-medium">Animation Speed (seconds)</label>
                    <input type="range" name="animation_speed" min="0" max="2" step="0.1" value="{{ \App\Models\ThemeSetting::get('animations.speed', 0.6) }}" class="mt-1 w-full">
                    <span class="text-xs text-gray-500">Current: {{ \App\Models\ThemeSetting::get('animations.speed', 0.6) }}s</span>
                </div>
            </div>

            <div class="flex justify-between">
                <button type="submit" form="reset-theme" class="text-red-600 hover:text-red-800 text-sm">Reset to Defaults</button>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Save Changes</button>
            </div>
        </form>
    </div>
<form id="reset-theme" method="POST" action="{{ route('admin.theme.reset') }}" class="hidden">@csrf</form>
@endsection
