@props(['members' => []])
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($members as $member)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <x-responsive-image :src="$member['image'] ?? asset('images/team-placeholder.jpg')" :webp="empty($member['image']) ? asset('images/team-placeholder.webp') : null" :alt="'Portrait of '.($member['name'] ?? 'team member')" width="800" height="800" sizes="(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw" class="w-full h-64 object-cover" />
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">{{ $member['name'] ?? 'Name' }}</h3>
                        <p class="text-blue-600 mb-4">{{ $member['role'] ?? 'Role' }}</p>
                        @if(!empty($member['bio']))<p class="text-sm text-slate-600">{{ $member['bio'] }}</p>@endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>