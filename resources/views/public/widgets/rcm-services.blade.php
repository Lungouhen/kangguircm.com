@php
$items = $services ?? [
 ['icon' => '🧾', 'title' => 'Medical Billing', 'description' => 'Accurate charge entry, claim submission, and payment posting managed by experienced billing teams.'],
 ['icon' => '✅', 'title' => 'Eligibility & Benefits', 'description' => 'Upfront insurance verification that helps reduce avoidable denials and patient surprises.'],
 ['icon' => '🔎', 'title' => 'Denial Management', 'description' => 'Root-cause analysis, timely appeals, and prevention strategies built around your payer mix.'],
 ['icon' => '📊', 'title' => 'A/R Management', 'description' => 'Focused follow-up across aging buckets to improve cash flow and reduce days in A/R.'],
 ['icon' => '🩺', 'title' => 'Medical Coding', 'description' => 'Specialty-aware coding support designed for accuracy, compliance, and complete reimbursement.'],
 ['icon' => '💳', 'title' => 'Patient Billing', 'description' => 'Clear statements and compassionate support that make patient payments easier to manage.'],
];
@endphp
<section class="py-20 bg-slate-50">
 <div class="max-w-7xl mx-auto px-6">
  <div class="max-w-3xl mb-12"><p class="text-sm font-bold tracking-widest uppercase text-blue-600">{{ $eyebrow ?? 'End-to-end revenue cycle management' }}</p><h2 class="mt-3 text-4xl font-bold text-slate-900">{{ $title ?? 'Revenue cycle services built around your practice' }}</h2>@if(!empty($description))<p class="mt-4 text-lg text-slate-600">{{ $description }}</p>@endif</div>
  <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">@foreach($items as $service)<article class="group bg-white border border-slate-200 rounded-2xl p-7 hover:-translate-y-1 hover:shadow-xl transition"><span class="inline-flex w-12 h-12 items-center justify-center rounded-xl bg-blue-50 text-2xl">{{ $service['icon'] ?? '•' }}</span><h3 class="mt-5 text-xl font-bold text-slate-900">{{ $service['title'] ?? '' }}</h3><p class="mt-3 text-slate-600 leading-relaxed">{{ $service['description'] ?? '' }}</p>@if(!empty($service['link']))<a class="inline-flex mt-5 font-semibold text-blue-600" href="{{ $service['link'] }}">Learn more about {{ $service['title'] ?? 'this service' }} <span aria-hidden="true">→</span></a>@endif</article>@endforeach</div>
 </div>
</section>
