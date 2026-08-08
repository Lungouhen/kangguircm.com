@extends('layouts.admin')

@section('title', 'Campaigns')
@section('page-title', 'Email Campaigns')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <input type="text" placeholder="Search campaigns..." 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"/>
            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <a href="{{ route('email.campaigns.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Campaign
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-500">Total Campaigns</div>
            <div class="text-2xl font-bold text-gray-900">{{ $campaigns->total() }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-500">Sent</div>
            <div class="text-2xl font-bold text-green-600">{{ $campaigns->where('status', 'sent')->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-500">Drafts</div>
            <div class="text-2xl font-bold text-yellow-600">{{ $campaigns->where('status', 'draft')->count() }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="text-sm text-gray-500">Avg Open Rate</div>
            <div class="text-2xl font-bold text-blue-600">
                @php
                    $sent = \App\Models\Campaign::where('status', 'sent')->sum('total_recipients');
                    $opened = \App\Models\Campaign::where('status', 'sent')->sum('open_count');
                @endphp
                {{ $sent > 0 ? round(($opened / $sent) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    {{-- Campaigns Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campaign Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lists</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Recipients</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sent Date</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($campaigns as $campaign)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $campaign->name }}</div>
                            <div class="text-sm text-gray-500">From: {{ $campaign->from_name }} &lt;{{ $campaign->from_email }}&gt;</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $campaign->subject }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $campaign->lists->count() }} list(s)</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ number_format($campaign->total_recipients ?? 0) }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-yellow-100 text-yellow-800',
                                    'sent' => 'bg-green-100 text-green-800',
                                    'scheduled' => 'bg-blue-100 text-blue-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$campaign->status] ?? 'bg-gray-100 text-gray-800' }} capitalize">
                                {{ ucfirst($campaign->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $campaign->sent_at ? $campaign->sent_at->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium space-x-2">
                            <a href="{{ route('email.campaigns.show', $campaign) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            @if($campaign->status === 'draft')
                                <form action="{{ route('email.campaigns.send', $campaign) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">Send</button>
                                </form>
                            @endif
                            <a href="{{ route('email.campaigns.edit', $campaign) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No campaigns found. <a href="{{ route('email.campaigns.create') }}" class="text-blue-600 hover:underline">Create your first campaign</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($campaigns->hasPages())
        <div class="bg-white px-4 py-3 rounded-lg shadow">
            {{ $campaigns->links() }}
        </div>
    @endif
</div>
@endsection
