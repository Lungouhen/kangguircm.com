@extends('layouts.app')
@section('seo')
<x-seo-meta
    title="Revenue Cycle Management Services for Medical Practices"
    description="Specialty-focused medical billing, coding, denial management, and accounts receivable services that help practices improve collections and financial visibility."
    :url="url('/')"
/>
<x-structured-data :data="\App\Services\SeoService::organizationSchema()" />
@endsection
@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-blue-800 text-white" aria-labelledby="home-heading">
    <div class="absolute inset-0 opacity-20" aria-hidden="true"><div class="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-cyan-400 blur-3xl"></div></div>
    <div class="relative max-w-7xl mx-auto px-6 py-24 md:py-32 grid lg:grid-cols-2 gap-12 items-center">
        <div>
            <p class="text-sm font-bold tracking-widest uppercase text-cyan-300">Specialty-focused revenue cycle management</p>
            <h1 id="home-heading" class="mt-4 text-5xl md:text-6xl font-bold leading-tight">Turn more earned revenue into collected revenue</h1>
            <p class="mt-6 text-xl text-blue-100 leading-relaxed">Medical billing and RCM support designed to reduce denials, shorten accounts receivable cycles, and give your practice clearer financial visibility.</p>
            <div class="mt-9 flex flex-wrap gap-4"><a href="#rcm-assessment" class="rounded-lg bg-white px-6 py-3 font-bold text-blue-800">Request a free RCM assessment</a><a href="#rcm-services" class="rounded-lg border border-white/40 px-6 py-3 font-bold text-white">Explore RCM services</a></div>
        </div>
        <aside class="rounded-3xl border border-white/20 bg-white/10 p-8 backdrop-blur" aria-label="Revenue cycle priorities"><h2 class="text-2xl font-bold">A healthier revenue cycle starts with the fundamentals</h2><ul class="mt-6 space-y-4 text-blue-50"><li>✓ Accurate eligibility and patient information</li><li>✓ Timely, clean claim submission</li><li>✓ Focused denial prevention and appeals</li><li>✓ Consistent payment posting and A/R follow-up</li><li>✓ Actionable financial reporting</li></ul></aside>
    </div>
</section>
<div id="rcm-services">@include('public.widgets.rcm-services')</div>
@include('public.widgets.revenue-cycle')
@include('public.widgets.specialty-expertise')
@include('public.widgets.rcm-results', ['disclaimer' => 'Illustrative operational targets only. Actual performance varies by organization, payer mix, and engagement scope.'])
@include('public.widgets.compliance-trust', ['note' => 'Security and compliance representations should be confirmed against current company policies and contractual commitments.'])
@include('public.widgets.rcm-assessment')
@endsection
