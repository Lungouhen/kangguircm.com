@props(['logos' => []])
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center opacity-70">
            @foreach($logos as $logo)
                <div class="flex justify-center">
                    <a href="{{ $logo['link'] ?? '#' }}">
                        <img src="{{ $logo['image'] ?? 'https://via.placeholder.com/150x50?text=Logo' }}" alt="Client Logo" class="h-12 object-contain grayscale hover:grayscale-0 transition-all">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>