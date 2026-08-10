@props(['logos' => []])
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 items-center opacity-70">
            @foreach($logos as $logo)
                <div class="flex justify-center">
                    @if(!empty($logo['link']))<a href="{{ $logo['link'] }}" aria-label="Visit {{ $logo['name'] ?? 'client website' }}">@endif
                        <img src="{{ $logo['image'] ?? asset('images/logo.svg') }}" alt="{{ $logo['name'] ?? 'Client logo' }}" width="150" height="50" loading="lazy" decoding="async" class="h-12 object-contain grayscale hover:grayscale-0 transition-all">
                    @if(!empty($logo['link']))</a>@endif
                </div>
            @endforeach
        </div>
    </div>
</section>