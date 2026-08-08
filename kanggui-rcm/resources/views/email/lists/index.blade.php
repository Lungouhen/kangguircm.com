@extends('layouts.admin')

@section('title', 'Subscriber Lists')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Subscriber Lists</h1>
    <a href="{{ route('email.lists.create') }}" class="btn-primary">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Create List
    </a>
</div>

@if(session('success'))
    <div class="alert-success mb-6">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Name</th>
                    <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Description</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Subscribers</th>
                    <th class="text-center py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Created</th>
                    <th class="text-right py-3 px-4 font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lists as $list)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900">
                    <td class="py-3 px-4 text-gray-900 dark:text-white">{{ $list->name }}</td>
                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ Str::limit($list->description, 50) }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="badge-primary">{{ $list->subscribers_count }}</span>
                    </td>
                    <td class="py-3 px-4 text-center text-gray-600 dark:text-gray-400">
                        {{ $list->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="{{ route('email.lists.edit', $list) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form action="{{ route('email.lists.destroy', $list) }}" method="POST" class="inline" onsubmit="return confirm('Delete this list?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-500">No subscriber lists found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($lists->hasPages())
    <div class="mt-4">
        {{ $lists->links() }}
    </div>
    @endif
</div>
@endsection
