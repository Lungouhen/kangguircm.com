<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic SEO Meta -->
    @if(isset($seo))
        <title>{{ $seo['title'] }} | {{ config('app.name', 'KangguiRCM') }}</title>
        <meta name="description" content="{{ $seo['description'] }}">
        <meta name="keywords" content="{{ $seo['keywords'] ?? '' }}">
        <link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
        
        <!-- Open Graph -->
        <meta property="og:title" content="{{ $seo['title'] }}">
        <meta property="og:description" content="{{ $seo['description'] }}">
        <meta property="og:image" content="{{ $seo['image'] ?? asset('images/og-default.jpg') }}">
        <meta property="og:url" content="{{ $seo['canonical'] ?? url()->current() }}">
        <meta property="og:type" content="website">
        
        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $seo['title'] }}">
        <meta name="twitter:description" content="{{ $seo['description'] }}">
    @else
        <title>{{ config('app.name', 'KangguiRCM') }}</title>
    @endif

    <!-- Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/premium.css') }}">

    @stack('styles')
</head>
<body class="font-sans antialiased text-gray-900 bg-white selection:bg-blue-600 selection:text-white overflow-x-hidden">

    <!-- Navigation -->
    <header class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-md bg-white/80 border-b border-white/20" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500">
                        {{ config('app.name', 'KangguiRCM') }}
                    </a>
                </div>

                <!-- Desktop Menu -->
                <nav class="hidden md:flex space-x-8">
                    @if(isset($menuItems))
                        @foreach($menuItems as $item)
                            <a href="{{ $item->url }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors duration-200">
                                {{ $item->label }}
                            </a>
                        @endforeach
                    @else
                        <a href="/" class="text-gray-600 hover:text-blue-600 font-medium">Home</a>
                        <a href="/about" class="text-gray-600 hover:text-blue-600 font-medium">About</a>
                        <a href="/services" class="text-gray-600 hover:text-blue-600 font-medium">Services</a>
                        <a href="/contact" class="text-gray-600 hover:text-blue-600 font-medium">Contact</a>
                    @endif
                </nav>

                <!-- CTA Button -->
                <div class="hidden md:flex">
                    <a href="/contact" class="px-6 py-2.5 rounded-full bg-blue-600 text-white font-semibold shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:shadow-blue-700/40 transition-all duration-300 transform hover:-translate-y-0.5">
                        Get Started
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="text-gray-600 hover:text-blue-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobile-menu" class="hidden md:hidden bg-white/95 backdrop-blur-xl border-t border-gray-100 absolute w-full">
            <div class="px-4 pt-2 pb-6 space-y-2 shadow-xl">
                @if(isset($menuItems))
                    @foreach($menuItems as $item)
                        <a href="{{ $item->url }}" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50 transition-colors">
                            {{ $item->label }}
                        </a>
                    @endforeach
                @else
                    <a href="/" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Home</a>
                    <a href="/about" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">About</a>
                    <a href="/services" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Services</a>
                    <a href="/contact" class="block px-3 py-3 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-blue-50">Contact</a>
                @endif
            </div>
        </div>
    </header>

    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <h3 class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-cyan-300 mb-4">
                        {{ config('app.name', 'KangguiRCM') }}
                    </h3>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Empowering businesses with innovative CRM and CMS solutions. Built for the future of digital engagement.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/" class="hover:text-blue-400 transition-colors">Home</a></li>
                        <li><a href="/about" class="hover:text-blue-400 transition-colors">About Us</a></li>
                        <li><a href="/services" class="hover:text-blue-400 transition-colors">Services</a></li>
                        <li><a href="/contact" class="hover:text-blue-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white">Legal</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/privacy" class="hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="/terms" class="hover:text-blue-400 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white">Connect</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-blue-400 transition-colors">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} {{ config('app.name', 'KangguiRCM') }}. All rights reserved.
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
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');

        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md');
                navbar.classList.replace('bg-white/80', 'bg-white/95');
            } else {
                navbar.classList.remove('shadow-md');
                navbar.classList.replace('bg-white/95', 'bg-white/80');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
