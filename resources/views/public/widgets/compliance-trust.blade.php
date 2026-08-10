@php
$items = $standards ?? [
 ['title' => 'HIPAA-aligned operations', 'description' => 'Administrative, technical, and physical safeguards support responsible PHI handling.'],
 ['title' => 'Role-based access', 'description' => 'Access controls and least-privilege practices help protect sensitive information.'],
 ['title' => 'Secure workflows', 'description' => 'Documented processes support secure data exchange and operational consistency.'],
 ['title' => 'Ongoing training', 'description' => 'Teams receive recurring privacy, security, and compliance education.'],
];
@endphp
<section class="py-20 bg-white"><div class="max-w-7xl mx-auto px-6"><div class="grid lg:grid-cols-12 gap-12 items-start"><div class="lg:col-span-5"><span class="inline-flex w-14 h-14 items-center justify-center rounded-2xl bg-emerald-100 text-3xl">🛡️</span><h2 class="mt-6 text-4xl font-bold text-slate-900">{{ $title ?? 'Security and compliance at every touchpoint' }}</h2><p class="mt-4 text-lg text-slate-600">{{ $description ?? 'Revenue cycle performance should never come at the expense of patient trust.' }}</p>@if(!empty($note))<p class="mt-5 text-xs text-slate-500">{{ $note }}</p>@endif</div><div class="lg:col-span-7 grid sm:grid-cols-2 gap-5">@foreach($items as $standard)<article class="rounded-2xl border border-slate-200 p-6"><span class="text-emerald-600 font-bold">✓</span><h3 class="mt-3 font-bold text-lg text-slate-900">{{ $standard['title'] ?? '' }}</h3><p class="mt-2 text-slate-600">{{ $standard['description'] ?? '' }}</p></article>@endforeach</div></div></div></section>
