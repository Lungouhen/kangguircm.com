@extends('layouts.admin')

@section('title', 'Create Subscriber List')

@section('content')
<div class="mb-6">
    <a href="{{ route('email.lists.index') }}" class="text-blue-600 hover:underline">&larr; Back to Lists</a>
</div>

<div class="card max-w-2xl">
    <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">Create New Subscriber List</h2>
    
    <form action="{{ route('email.lists.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">List Name</label>
            <input type="text" name="name" id="name" 
                   class="input-field @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" required autofocus>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
            <textarea name="description" id="description" rows="4" 
                      class="input-field @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Create List</button>
            <a href="{{ route('email.lists.index') }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
