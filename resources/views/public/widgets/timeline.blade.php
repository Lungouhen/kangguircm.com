@props(['events' => []])
<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-3xl">
        <div class="border-l-4 border-blue-600 ml-4 space-y-8">
            @foreach($events as $event)
                <div class="relative pl-8">
                    <div class="absolute -left-3 top-0 w-6 h-6 bg-blue-600 rounded-full border-4 border-white"></div>
                    <span class="text-sm font-bold text-blue-600 uppercase">{{ $event['year'] ?? 'Year' }}</span>
                    <h3 class="text-xl font-bold text-gray-900">{{ $event['title'] ?? 'Title' }}</h3>
                    <p class="text-gray-600 mt-2">{{ $event['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>