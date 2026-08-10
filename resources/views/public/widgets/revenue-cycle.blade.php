@php
$items = $steps ?? [
 ['title' => 'Patient access', 'description' => 'Registration, eligibility, authorization, and clean demographic data.'],
 ['title' => 'Clinical documentation', 'description' => 'Documentation support that connects care delivery to accurate coding.'],
 ['title' => 'Coding & charge capture', 'description' => 'Timely, specialty-aware coding and complete charge reconciliation.'],
 ['title' => 'Claims management', 'description' => 'Clean claim submission, clearinghouse monitoring, and rapid corrections.'],
 ['title' => 'Payment & follow-up', 'description' => 'Payment posting, denial resolution, appeals, and focused A/R follow-up.'],
];
@endphp
<section class="py-20 bg-white"><div class="max-w-7xl mx-auto px-6"><div class="text-center max-w-3xl mx-auto"><h2 class="text-4xl font-bold text-slate-900">{{ $title ?? 'A healthier revenue cycle, step by step' }}</h2>@if(!empty($description))<p class="mt-4 text-lg text-slate-600">{{ $description }}</p>@endif</div><ol class="mt-14 grid md:grid-cols-5 gap-6">@foreach($items as $step)<li class="relative"><div class="text-sm font-bold text-blue-600">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</div><div class="mt-3 h-1 bg-blue-600 rounded"></div><h3 class="mt-5 font-bold text-lg text-slate-900">{{ $step['title'] ?? '' }}</h3><p class="mt-2 text-sm leading-6 text-slate-600">{{ $step['description'] ?? '' }}</p></li>@endforeach</ol></div></section>
