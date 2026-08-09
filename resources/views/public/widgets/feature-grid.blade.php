<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <div class="w-24 h-1 bg-indigo-600 mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($features as $feature)
                <div class="bg-white p-8 rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300" data-aos="fade-up">
                    @if(isset($feature['icon']))
                        <div class="w-14 h-14 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                            <span class="text-2xl">{{ $feature['icon'] }}</span>
                        </div>
                    @endif
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $feature['title'] ?? 'Feature' }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $feature['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
