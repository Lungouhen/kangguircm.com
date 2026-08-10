@php
$items = $metrics ?? [
 ['value' => '98%+', 'label' => 'Clean claim rate', 'context' => 'After workflow stabilization'],
 ['value' => '< 35', 'label' => 'Days in A/R', 'context' => 'Across supported practices'],
 ['value' => '96%+', 'label' => 'Collection rate', 'context' => 'Net collection performance'],
 ['value' => '24–48h', 'label' => 'Charge lag', 'context' => 'Target submission window'],
];
@endphp
<section class="py-20 bg-blue-700 text-white"><div class="max-w-7xl mx-auto px-6"><div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6"><div><p class="font-bold tracking-widest uppercase text-blue-200 text-sm">{{ $eyebrow ?? 'Measurable performance' }}</p><h2 class="mt-3 text-4xl font-bold">{{ $title ?? 'Results your practice can see' }}</h2></div>@if(!empty($disclaimer))<p class="max-w-xl text-sm text-blue-100">{{ $disclaimer }}</p>@endif</div><div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">@foreach($items as $metric)<div class="rounded-2xl bg-white/10 border border-white/20 p-6"><strong class="text-4xl font-bold">{{ $metric['value'] ?? '' }}</strong><div class="mt-3 font-semibold">{{ $metric['label'] ?? '' }}</div>@if(!empty($metric['context']))<div class="mt-1 text-sm text-blue-100">{{ $metric['context'] }}</div>@endif</div>@endforeach</div></div></section>
