@props(['type' => 'Organization', 'data' => []])

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "{{ $type }}",
    @if($type === 'Organization')
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.svg') }}"
    @elseif($type === 'WebSite')
    "name": "{{ config('app.name') }}",
    "url": "{{ url('/') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ url('/search?q={search_term_string}') }}",
        "query-input": "required name=search_term_string"
    }
    @elseif($type === 'Article')
    "headline": "{{ $data['title'] ?? '' }}",
    "image": "{{ $data['image'] ?? asset('images/og-default.jpg') }}",
    "author": {
        "@type": "Person",
        "name": "{{ $data['author'] ?? config('app.name') }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "{{ config('app.name') }}",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('images/logo.svg') }}"
        }
    },
    "datePublished": "{{ $data['date'] ?? now()->toIso8601String() }}",
    "description": "{{ $data['description'] ?? '' }}"
    @endif
}
</script>
