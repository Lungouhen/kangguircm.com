@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center justify-center overflow-hidden bg-gradient-to-br from-blue-50 via-white to-cyan-50">
        <!-- Animated Background Shapes -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-cyan-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-float" style="animation-delay: 4s;"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold text-gray-900 mb-6 leading-tight" data-aos="fade-up">
                Transform Your Business with <br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-cyan-500">Intelligent Solutions</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 mb-10 max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                Streamline operations, engage customers, and scale efficiently with our all-in-one CRM and CMS platform.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="400">
                <a href="/contact" class="px-8 py-4 rounded-full bg-blue-600 text-white font-bold text-lg shadow-xl shadow-blue-600/30 hover:bg-blue-700 hover:shadow-blue-700/40 transition-all duration-300 transform hover:-translate-y-1">
                    Get Started Free
                </a>
                <a href="/about" class="px-8 py-4 rounded-full bg-white text-gray-900 font-bold text-lg border-2 border-gray-200 hover:border-blue-600 hover:text-blue-600 transition-all duration-300">
                    Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section with Animated Counters -->
    <section class="py-20 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Stat Item 1 -->
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white shadow-lg hover:shadow-xl transition-shadow duration-300" data-aos="zoom-in" data-aos-delay="0">
                    <div class="text-4xl md:text-5xl font-bold text-blue-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let start = 0; const end = 500; const duration = 2000; const step = end / (duration / 16); const timer = setInterval(() => { start += step; if (start >= end) { count = end; clearInterval(timer); } else { count = Math.floor(start); } }, 16); }, 500)" x-text="count + '+'"></div>
                    <div class="text-gray-600 font-medium">Happy Clients</div>
                </div>
                <!-- Stat Item 2 -->
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white shadow-lg hover:shadow-xl transition-shadow duration-300" data-aos="zoom-in" data-aos-delay="100">
                    <div class="text-4xl md:text-5xl font-bold text-cyan-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let start = 0; const end = 98; const duration = 2000; const step = end / (duration / 16); const timer = setInterval(() => { start += step; if (start >= end) { count = end; clearInterval(timer); } else { count = Math.floor(start); } }, 16); }, 500)" x-text="count + '%'"></div>
                    <div class="text-gray-600 font-medium">Retention Rate</div>
                </div>
                <!-- Stat Item 3 -->
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white shadow-lg hover:shadow-xl transition-shadow duration-300" data-aos="zoom-in" data-aos-delay="200">
                    <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let start = 0; const end = 24; const duration = 2000; const step = end / (duration / 16); const timer = setInterval(() => { start += step; if (start >= end) { count = end; clearInterval(timer); } else { count = Math.floor(start); } }, 16); }, 500)" x-text="count + '/7'"></div>
                    <div class="text-gray-600 font-medium">Support</div>
                </div>
                <!-- Stat Item 4 -->
                <div class="text-center p-8 rounded-2xl bg-gradient-to-br from-gray-50 to-white shadow-lg hover:shadow-xl transition-shadow duration-300" data-aos="zoom-in" data-aos-delay="300">
                    <div class="text-4xl md:text-5xl font-bold text-green-600 mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let start = 0; const end = 150; const duration = 2000; const step = end / (duration / 16); const timer = setInterval(() => { start += step; if (start >= end) { count = end; clearInterval(timer); } else { count = Math.floor(start); } }, 16); }, 500)" x-text="count + '+'"></div>
                    <div class="text-gray-600 font-medium">Projects Delivered</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4" data-aos="fade-up">Our Premium Services</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">Comprehensive solutions tailored to elevate your business operations.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="group p-8 rounded-2xl bg-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 rounded-xl bg-blue-100 flex items-center justify-center mb-6 group-hover:bg-blue-600 transition-colors duration-300">
                        <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">CRM Solutions</h3>
                    <p class="text-gray-600 leading-relaxed">Manage customer relationships, track deals, and automate pipelines with our intelligent CRM system.</p>
                </div>

                <!-- Service 2 -->
                <div class="group p-8 rounded-2xl bg-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 rounded-xl bg-cyan-100 flex items-center justify-center mb-6 group-hover:bg-cyan-600 transition-colors duration-300">
                        <svg class="w-8 h-8 text-cyan-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">CMS Platform</h3>
                    <p class="text-gray-600 leading-relaxed">Build and manage stunning websites with our drag-and-drop page builder and dynamic content blocks.</p>
                </div>

                <!-- Service 3 -->
                <div class="group p-8 rounded-2xl bg-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 rounded-xl bg-purple-100 flex items-center justify-center mb-6 group-hover:bg-purple-600 transition-colors duration-300">
                        <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">HR Management</h3>
                    <p class="text-gray-600 leading-relaxed">Streamline HR processes, track attendance, manage leave requests, and monitor employee performance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvc3ZnPg==')] opacity-30"></div>
        
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6" data-aos="fade-up">Ready to Transform Your Business?</h2>
            <p class="text-xl text-blue-100 mb-10" data-aos="fade-up" data-aos-delay="100">Join hundreds of companies already using our platform to drive growth and efficiency.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
                <a href="/contact" class="px-8 py-4 rounded-full bg-white text-blue-600 font-bold text-lg shadow-xl hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-1">
                    Start Free Trial
                </a>
                <a href="/demo" class="px-8 py-4 rounded-full bg-transparent text-white font-bold text-lg border-2 border-white hover:bg-white/10 transition-all duration-300">
                    Watch Demo
                </a>
            </div>
        </div>
    </section>
@endsection
