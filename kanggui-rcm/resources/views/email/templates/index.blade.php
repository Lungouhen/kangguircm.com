@extends('layouts.admin')

@section('title', 'Email Templates')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Email Templates</h1>
    <a href="{{ route('email.templates.create') }}" class="btn-primary">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create Template
    </a>
</div>

@if(session('success'))
    <div class="alert-success mb-6">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($templates as $template)
    <div class="card hover:shadow-lg transition-shadow">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $template->name }}</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ Str::limit($template->subject, 60) }}</p>
        </div>
        
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between items-center">
            <span class="text-xs text-gray-500">{{ $template->updated_at->diffForHumans() }}</span>
            <div class="space-x-2">
                <a href="{{ route('email.templates.show', $template) }}" class="text-blue-600 hover:text-blue-800 text-sm">View</a>
                <a href="{{ route('email.templates.edit', $template) }}" class="text-green-600 hover:text-green-800 text-sm">Edit</a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-12">
        <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
        </svg>
        <p class="text-gray-500">No email templates created yet.</p>
        <a href="{{ route('email.templates.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">Create your first template</a>
    </div>
    @endforelse
</div>

@if($templates->hasPages())
<div class="mt-8">
    {{ $templates->links() }}
</div>
@endif
@endsection
