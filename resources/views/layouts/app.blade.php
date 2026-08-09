<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <x-seo-meta />
    
    <!-- Dynamic Fonts based on Admin Selection -->
    @php $font = $theme['font_family'] ?? 'Plus Jakarta Sans'; @endphp
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $font) }}:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        :root {
            --primary-color: {{ $theme['primary_color'] ?? '#2563EB' }};
            --secondary-color: {{ $theme['secondary_color'] ?? '#7C3AED' }};
            --font-family: '{{ $font }}', sans-serif;
            --animation-speed: {{ $theme['animation_speed'] ?? 0.6 }}s;
        }
        body { font-family: var(--font-family); }
        .header-glass { {{ $theme['header_style'] == 'glass' ? 'backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.8);' : '' }} {{ $theme['header_style'] == 'solid' ? 'background: white;' : '' }} {{ $theme['header_style'] == 'transparent' ? 'background: transparent;' : '' }} }
        .container-custom { {{ $theme['layout_width'] == 'boxed' ? 'max-width: 1280px; margin-left: auto; margin-right: auto;' : 'max-width: 100%;' }} }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <header class="fixed w-full top-0 z-50 transition-all duration-300 header-glass shadow-sm">
        <nav class="container-custom mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold" style="color: var(--primary-color);">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <div class="hidden md:flex space-x-8">
                    <a href="/" class="hover:text-indigo-600 transition">Home</a>
                    <a href="/about" class="hover:text-indigo-600 transition">About</a>
                    <a href="/blog" class="hover:text-indigo-600 transition">Blog</a>
                    <a href="/contact" class="hover:text-indigo-600 transition">Contact</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="pt-20">
        {{ $slot }}
    </main>

    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="container-custom mx-auto px-6 text-center">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: var(--animation-speed) * 1000 || 600, once: true });
    </script>
</body>
</html>
