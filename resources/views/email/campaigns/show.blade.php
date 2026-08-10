@extends('layouts.admin')
@section('title', 'Campaign Details')
@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.email.campaigns.index') }}" class="text-blue-600 hover:text-blue-800">&larr; Back to Campaigns</a>
        @if($campaign->status === 'draft')
        <form action="{{ route('admin.email.campaigns.send', $campaign) }}" method="POST">
            @csrf
            <button type="submit" class="btn-primary bg-green-600 hover:bg-green-700">Send Campaign</button>
        </form>
        @endif
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900">{{ $campaign->name }}</h1>
        <p class="text-gray-600 mt-2">{{ $campaign->subject }}</p>
        <span class="mt-2 inline-flex px-3 py-1 text-sm rounded-full {{ $campaign->status === 'sent' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($campaign->status) }}</span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 shadow rounded-lg text-center">
            <div class="text-3xl font-bold text-blue-600">{{ number_format($campaign->sent_count ?? 0) }}</div>
            <div class="text-sm text-gray-500">Sent</div>
        </div>
        <div class="bg-white p-5 shadow rounded-lg text-center">
            <div class="text-3xl font-bold text-green-600">{{ number_format($campaign->opened_count ?? 0) }}</div>
            <div class="text-sm text-gray-500">Opened</div>
        </div>
        <div class="bg-white p-5 shadow rounded-lg text-center">
            <div class="text-3xl font-bold text-purple-600">{{ number_format($campaign->clicked_count ?? 0) }}</div>
            <div class="text-sm text-gray-500">Clicked</div>
        </div>
        <div class="bg-white p-5 shadow rounded-lg text-center">
            <div class="text-3xl font-bold text-red-600">{{ number_format($campaign->bounced_count ?? 0) }}</div>
            <div class="text-sm text-gray-500">Bounced</div>
        </div>
    </div>
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Email Content Preview</h3>
        <pre class="border rounded p-4 bg-gray-50 whitespace-pre-wrap overflow-auto">{{ $campaign->html_content }}</pre>
    </div>
</div>
@endsection
