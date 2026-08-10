<section class="py-12" aria-labelledby="gallery-{{ md5($title ?? 'gallery') }}">
    <h2 id="gallery-{{ md5($title ?? 'gallery') }}" class="text-3xl font-bold text-center mb-6">{{ $title ?? 'Image gallery' }}</h2>
    <div class="grid gap-4 grid-cols-{{ $columns ?? 3 }}">
        @foreach(($images ?? []) as $image)
            @php $item = is_array($image) ? $image : ['url' => $image]; @endphp
            <x-responsive-image
                :src="$item['url'] ?? asset('images/content-placeholder.jpg')"
                :webp="$item['webp'] ?? null"
                :avif="$item['avif'] ?? null"
                :srcset="$item['srcset'] ?? null"
                :alt="$item['alt'] ?? 'Gallery image'"
                width="800"
                height="600"
                sizes="(min-width: 1024px) 33vw, (min-width: 768px) 50vw, 100vw"
                class="w-full object-cover"
            />
        @endforeach
    </div>
</section>
