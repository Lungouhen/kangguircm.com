@props([
    'src',
    'alt',
    'width',
    'height',
    'webp' => null,
    'avif' => null,
    'srcset' => null,
    'webpSrcset' => null,
    'avifSrcset' => null,
    'sizes' => '100vw',
    'priority' => false,
])
<picture>
    @if($avif || $avifSrcset)<source type="image/avif" srcset="{{ $avifSrcset ?: $avif }}" sizes="{{ $sizes }}">@endif
    @if($webp || $webpSrcset)<source type="image/webp" srcset="{{ $webpSrcset ?: $webp }}" sizes="{{ $sizes }}">@endif
    <img
        src="{{ $src }}"
        @if($srcset) srcset="{{ $srcset }}" sizes="{{ $sizes }}" @endif
        alt="{{ $alt }}"
        width="{{ $width }}"
        height="{{ $height }}"
        {{ $attributes->class(['max-w-full h-auto']) }}
        @if($priority) fetchpriority="high" loading="eager" @else loading="lazy" @endif
        decoding="async"
    >
</picture>
