@extends('layouts.admin')
@section('title', 'Campaigns')
@section('content')
<div class="md:flex md:items-center md:justify-between mb-6">
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Email Campaigns</h1>
    <a href="{{ route('campaigns.create') }}" class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">New Campaign</a>
</div>
<div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Subject</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Sent</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Opened</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($campaigns as $campaign)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $campaign->subject }}</td>
                <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $campaign->status === 'sent' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ ucfirst($campaign->status) }}</span></td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $campaign->sent_count ?? 0 }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $campaign->opened_count ?? 0 }}</td>
                <td class="px-6 py-4 text-right"><a href="{{ route('campaigns.stats', $campaign) }}" class="text-blue-600 hover:text-blue-900">View Stats</a></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No campaigns found</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
