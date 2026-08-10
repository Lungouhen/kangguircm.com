<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-indigo-500 to-purple-700 bg-cover bg-center" @if($bgImage) style="background-image: url('{{ $bgImage }}')" @endif>
        <div class="absolute inset-0" style="background-color: {{ $overlay }}; opacity: 0.7;"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6 leading-tight animate-fade-in-up">
                {{ $title }}
            </h1>
            
            @if($subtitle)
                <p class="text-xl md:text-2xl text-gray-200 mb-10 animate-fade-in-up" style="animation-delay: 0.2s;">
                    {{ $subtitle }}
                </p>
            @endif
            
            @if($btnText)
                <div class="animate-fade-in-up" style="animation-delay: 0.4s;">
                    <a href="{{ $btnUrl }}" 
                       class="inline-block px-8 py-4 bg-white text-indigo-900 font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        {{ $btnText }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
