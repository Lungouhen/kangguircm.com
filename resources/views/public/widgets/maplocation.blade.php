@props(['api_key' => '', 'address' => '', 'height' => 400])
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="rounded-lg overflow-hidden shadow-lg" style="height: {{ $height }}px;">
            @if($api_key && $address)
                <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                    src="https://www.google.com/maps/embed/v1/place?key={{ $api_key }}&q={{ urlencode($address) }}" title="Map showing {{ $address }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
            @else
                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                    Map Configuration Missing (API Key or Address)
                </div>
            @endif
        </div>
    </div>
</section>