@props(['video_url' => '', 'autoplay' => false])
<section class="py-12 bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="aspect-w-16 aspect-h-9 max-w-4xl mx-auto">
            @if(Str::contains($video_url, 'youtube.com') || Str::contains($video_url, 'youtu.be'))
                @php
                    $videoId = Str::after($video_url, 'v=');
                    if(Str::contains($video_url, 'youtu.be')) $videoId = Str::after($video_url, 'youtu.be/');
                    $videoId = Str::before($videoId, '&');
                @endphp
                <iframe class="w-full h-96 rounded-lg shadow-lg" 
                        src="https://www.youtube.com/embed/{{ $videoId }}?{{ $autoplay ? 'autoplay=1' : '' }}" 
                        frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @elseif(Str::contains($video_url, 'vimeo.com'))
                @php $videoId = Str::afterLast($video_url, '/'); @endphp
                <iframe class="w-full h-96 rounded-lg shadow-lg" 
                        src="https://player.vimeo.com/video/{{ $videoId }}?{{ $autoplay ? 'autoplay=1' : '' }}" 
                        frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
            @else
                <div class="text-white text-center">Invalid Video URL</div>
            @endif
        </div>
    </div>
</section>