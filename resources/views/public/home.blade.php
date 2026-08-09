@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="relative pt-20 pb-32 overflow-hidden hero-gradient">
    <!-- Animated Background Blobs -->
    <div class="blob-bg blob-delay-1 bg-brand-300 w-96 h-96 rounded-full top-0 left-0 -translate-x-1/2 -translate-y-1/2"></div>
    <div class="blob-bg blob-delay-2 bg-indigo-300 w-80 h-80 rounded-full bottom-0 right-0 translate-x-1/3 translate-y-1/3"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-display font-extrabold mb-8 leading-tight" data-aos="fade-up">
                Next-Gen <span class="text-gradient">CMS & CRM</span><br>for Modern Business
            </h1>
            <p class="text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                {{ config('settings.site_description', 'Empower your team with an all-in-one platform that combines powerful content management with intelligent customer relationship tools.') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
                <a href="/contact" class="px-8 py-4 rounded-full bg-brand-600 text-white font-bold text-lg shadow-xl shadow-brand-500/30 hover:bg-brand-700 hover:shadow-brand-500/40 transition-all duration-300 transform hover:-translate-y-1">
                    Get Started Free
                </a>
                <a href="#features" class="px-8 py-4 rounded-full glass-panel text-slate-700 font-bold text-lg hover:bg-white transition-all duration-300">
                    View Features
                </a>
            </div>
        </div>
        
        <!-- Hero Image/Dashboard Preview -->
        <div class="mt-16 relative" data-aos="fade-up" data-aos-delay="300">
            <div class="glass-panel rounded-2xl p-2 shadow-2xl">
                <img src="{{ asset('images/placeholders/hero-bg.jpg') }}" alt="Dashboard Preview" class="rounded-xl w-full object-cover h-[400px] md:h-[500px]">
            </div>
        </div>
    </div>
</section>

<!-- Stats Section with Animated Counters -->
<section class="py-20 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @php
                $stats = [
                    ['label' => 'Active Users', 'value' => '10,000+', 'icon' => 'users'],
                    ['label' => 'Countries', 'value' => '50+', 'icon' => 'globe'],
                    ['label' => 'Uptime', 'value' => '99.9%', 'icon' => 'clock'],
                    ['label' => 'Support', 'value' => '24/7', 'icon' => 'headset']
                ];
            @endphp
            
            @foreach($stats as $index => $stat)
                <div class="p-6" data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                    <div class="text-4xl md:text-5xl font-display font-bold text-brand-600 mb-2 counter-value" data-target="{{ str_replace(['+', ',', '%'], '', $stat['value']) }}">
                        0
                    </div>
                    <div class="text-slate-500 font-medium">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-3xl md:text-4xl font-display font-bold mb-4" data-aos="fade-up">Everything You Need to <span class="text-gradient">Scale</span></h2>
            <p class="text-slate-600 text-lg" data-aos="fade-up" data-aos-delay="100">Powerful features built for modern teams who demand excellence.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            @php
                $features = [
                    ['title' => 'Dynamic CMS', 'desc' => 'Build pages visually with our drag-and-drop block builder.', 'icon' => 'layout'],
                    ['title' => 'Smart CRM', 'desc' => 'Track deals, manage contacts, and close more sales.', 'icon' => 'users'],
                    ['title' => 'Email Marketing', 'desc' => 'Create campaigns that convert with advanced analytics.', 'icon' => 'mail'],
                    ['title' => 'HR Management', 'desc' => 'Streamline attendance, leaves, and employee data.', 'icon' => 'briefcase'],
                    ['title' => 'Audit Logs', 'desc' => 'Track every action with comprehensive security logging.', 'icon' => 'shield'],
                    ['title' => 'SEO Optimized', 'desc' => 'Built-in tools to help you rank higher on search engines.', 'icon' => 'search']
                ];
            @endphp
            
            @foreach($features as $index => $feature)
                <div class="glass-panel p-8 rounded-2xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="w-14 h-14 bg-brand-100 rounded-xl flex items-center justify-center text-brand-600 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900">{{ $feature['title'] }}</h3>
                    <p class="text-slate-600 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-brand-900"></div>
    <div class="blob-bg bg-brand-600 w-96 h-96 rounded-full top-0 right-0 translate-x-1/3 -translate-y-1/3 opacity-50"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h2 class="text-4xl md:text-5xl font-display font-bold text-white mb-6" data-aos="fade-up">Ready to Transform Your Business?</h2>
        <p class="text-brand-100 text-xl mb-10" data-aos="fade-up" data-aos-delay="100">Join thousands of companies using Kangguircm to drive growth.</p>
        <a href="/contact" class="inline-block px-10 py-5 rounded-full bg-white text-brand-900 font-bold text-lg shadow-xl hover:bg-brand-50 transition-all duration-300 transform hover:-translate-y-1" data-aos="fade-up" data-aos-delay="200">
            Start Your Free Trial
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Animated Counter Logic
    const counters = document.querySelectorAll('.counter-value');
    const speed = 200; // The lower the slower

    const animateCounters = () => {
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target').replace(/,/g, '');
                const count = +counter.innerText.replace(/,/g, '').replace(/\+/g, '').replace(/%/g, '');
                
                const inc = target / speed;

                if (count < target) {
                    let nextVal = Math.ceil(count + inc);
                    // Format back with commas/percentages if needed
                    if(counter.getAttribute('data-target').includes('%')) nextVal += '%';
                    else if(counter.getAttribute('data-target').includes('+')) nextVal += '+';
                    else if(target > 1000) nextVal = nextVal.toLocaleString();
                    
                    counter.innerText = nextVal;
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = counter.getAttribute('data-target');
                }
            };
            updateCount();
        });
    };

    // Trigger animation when stats section is in view
    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.counter-value').forEach(el => observer.observe(el));
</script>
@endpush
