@extends('layouts.admin')
@section('content')<div class="p-6"><h1 class="text-2xl font-bold">{{ $template->name }}</h1><h2 class="my-3">{{ $template->subject }}</h2><pre class="bg-white p-4 whitespace-pre-wrap overflow-auto">{{ $template->html_content }}</pre></div>@endsection
