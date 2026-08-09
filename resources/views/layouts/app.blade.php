<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ config('settings.site_description', 'Premium CMS Solution') }}">
    <title>{{ config('settings.site_title', 'Kangguircm') }} - @yield('title', 'Home')</title>
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* Critical CSS for above-fold content */
        .hero-gradient { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); }
    </style>
</head>
<body class="bg-slate-50 overflow-x-hidden">
    
    <!-- Navigation -->
    <nav class="glass-nav transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-3">
                    @if(config('settings.site_logo'))
                        <img class="h-10 w-auto" src="{{ asset('storage/' . config('settings.site_logo')) }}" alt="Logo">
                    @else
                        <div class="text-2xl font-display font-bold text-gradient">Kangguircm</div>
                    @endif
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    @foreach($menuItems as $item)
                        <a href="{{ $item->url }}" class="text-slate-600 hover:text-brand-600 font-medium transition-colors duration-200">
                            {{ $item->label }}
                        </a>
                    @endforeach
                    <a href="/contact" class="px-5 py-2.5 rounded-full bg-brand-600 text-white font-semibold shadow-lg shadow-brand-500/30 hover:bg-brand-700 hover:shadow-brand-500/40 transition-all duration-300 transform hover:-translate-y-0.5">
                        Get Started
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-slate-500 hover:text-slate-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden md:hidden glass-panel absolute w-full border-t-0 rounded-b-xl mx-4 top-20">
            <div class="px-4 pt-2 pb-6 space-y-2">
                @foreach($menuItems as $item)
                    <a href="{{ $item->url }}" class="block px-3 py-3 rounded-md text-base font-medium text-slate-700 hover:text-brand-600 hover:bg-brand-50">
                        {{ $item->label }}
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-display font-bold mb-4">Kangguircm</h3>
                    <p class="text-slate-400 max-w-sm">{{ config('settings.site_description', 'Empowering businesses with next-gen CRM and CMS solutions.') }}</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="/" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="/about" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="/blog" class="hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li>{{ config('settings.contact_email', 'hello@kangguircm.com') }}</li>
                        <li>{{ config('settings.contact_phone', '+1 (555) 123-4567') }}</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-slate-500 text-sm">
                &copy; {{ date('Y') }} {{ config('settings.site_title', 'Kangguircm') }}. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize Animations
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });

        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-lg');
            } else {
                nav.classList.remove('shadow-lg');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
