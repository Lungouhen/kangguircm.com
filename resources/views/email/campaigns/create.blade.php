@extends('layouts.admin')
@section('title', 'Create Campaign')
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.email.campaigns.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to Campaigns</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">Create New Campaign</h1>
    </div>
    <form action="{{ route('admin.email.campaigns.store') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Campaign Name</label>
            <input type="text" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror" value="{{ old('name') }}">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Subject Line</label>
            <input type="text" name="subject" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('subject') border-red-500 @enderror" value="{{ old('subject') }}">
            @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Select Subscriber Lists</label>
            <select name="lists[]" multiple required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @foreach($lists as $list)
                <option value="{{ $list->id }}">{{ $list->name }} ({{ $list->subscribers_count ?? 0 }} subscribers)</option>
                @endforeach
            </select>
            @error('lists') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email Content (HTML)</label>
            <textarea name="content" rows="10" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
            @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-end gap-3">
            <button type="submit" name="status" value="draft" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Save as Draft</button>
            <button type="submit" name="status" value="scheduled" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Schedule Campaign</button>
        </div>
    </form>
</div>
@endsection
