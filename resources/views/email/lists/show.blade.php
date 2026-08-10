@extends('layouts.admin')
@section('content')<div class="p-6"><h1 class="text-2xl font-bold">{{ $list->name }}</h1><p>{{ $list->description }}</p><h2 class="font-bold mt-6">Subscribers</h2>@forelse($list->subscribers as $subscriber)<div class="border-b py-2">{{ $subscriber->email }}</div>@empty<p>No subscribers.</p>@endforelse</div>@endsection
