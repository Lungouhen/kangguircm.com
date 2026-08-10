@extends('layouts.admin')
@section('content')<div class="p-6"><h1 class="text-2xl font-bold">{{ $role->display_name }}</h1><p>{{ $role->description }}</p><a href="{{ route('admin.roles.edit', $role) }}">Edit</a></div>@endsection
