@extends('layouts.admin')

@section('title', 'Subscriber Lists')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Subscriber Lists</h1>
        <a href="{{ route('admin.email.lists.create') }}" 
           class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create List
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <!-- Lists Grid -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($lists as $list)
            <div class="card p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $list->name }}</h3>
                    <span class="badge-primary">{{ $list->subscribers_count }} subscribers</span>
                </div>
                
                @if($list->description)
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($list->description, 100) }}</p>
                @endif

                <div class="flex space-x-2">
                    <a href="{{ route('admin.email.lists.show', $list) }}" 
                       class="btn-secondary text-sm">View</a>
                    <a href="{{ route('admin.email.lists.edit', $list) }}" 
                       class="btn-secondary text-sm">Edit</a>
                    <form action="{{ route('admin.email.lists.destroy', $list) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger text-sm">Delete</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    @if($lists->isEmpty())
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No lists yet</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new subscriber list.</p>
        </div>
    @endif
</div>
@endsection
