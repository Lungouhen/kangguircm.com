@php
    $mapUrl = filter_var($map_embed ?? '', FILTER_VALIDATE_URL) && in_array(parse_url($map_embed, PHP_URL_SCHEME), ['https'], true)
        ? $map_embed
        : null;
@endphp
<section class="py-12" aria-labelledby="contact-heading">
    <div class="max-w-3xl mx-auto px-6">
        <h2 id="contact-heading" class="text-3xl font-bold">{{ $title ?? 'Contact us' }}</h2>
        <form method="POST" action="{{ route('marketing-leads.store') }}" class="mt-6 grid gap-4">
            @csrf
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
            <input type="hidden" name="source" value="contact-widget">
            <label><span class="block text-sm font-semibold">Name</span><input class="mt-1 w-full border p-3" name="name" autocomplete="name" required></label>
            <label><span class="block text-sm font-semibold">Work email</span><input class="mt-1 w-full border p-3" type="email" name="email" autocomplete="email" required></label>
            <label><span class="block text-sm font-semibold">How can we help?</span><textarea class="mt-1 w-full border p-3" name="message" maxlength="3000"></textarea></label>
            <label class="flex gap-2 text-sm"><input type="checkbox" name="consent" value="1" required><span>I agree to be contacted. Do not include patient or protected health information.</span></label>
            <x-captcha-widget /><button class="bg-blue-600 text-white p-3" type="submit">Send request</button>
        </form>
        @if(!empty($show_map) && $mapUrl)<iframe class="mt-8 w-full h-80" src="{{ $mapUrl }}" title="Office location map" loading="lazy" referrerpolicy="no-referrer"></iframe>@endif
    </div>
</section>
