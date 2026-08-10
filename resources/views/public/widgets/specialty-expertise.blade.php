@php
$items = $specialties ?? [
 ['icon' => '❤️', 'name' => 'Cardiology', 'description' => 'Complex coding, diagnostic testing, and procedure billing.'],
 ['icon' => '🦴', 'name' => 'Orthopedics', 'description' => 'Surgical, therapy, DME, and injection billing workflows.'],
 ['icon' => '🧠', 'name' => 'Behavioral Health', 'description' => 'Therapy, psychiatry, and telehealth reimbursement support.'],
 ['icon' => '👩‍⚕️', 'name' => 'Primary Care', 'description' => 'Preventive, chronic care, and value-based billing support.'],
 ['icon' => '🔬', 'name' => 'Pathology & Labs', 'description' => 'High-volume claims, medical necessity, and modifier expertise.'],
 ['icon' => '🚑', 'name' => 'Urgent Care', 'description' => 'Fast-paced coding and billing for walk-in care models.'],
];
@endphp
<section class="py-20 bg-slate-900 text-white"><div class="max-w-7xl mx-auto px-6"><div class="max-w-3xl"><h2 class="text-4xl font-bold">{{ $title ?? 'RCM expertise for every specialty' }}</h2>@if(!empty($description))<p class="mt-4 text-lg text-slate-300">{{ $description }}</p>@endif</div><div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-px bg-slate-700 border border-slate-700 rounded-2xl overflow-hidden">@foreach($items as $specialty)<article class="bg-slate-900 p-7 hover:bg-slate-800 transition"><span class="text-3xl">{{ $specialty['icon'] ?? '✚' }}</span><h3 class="mt-4 text-xl font-bold">{{ $specialty['name'] ?? '' }}</h3><p class="mt-2 text-slate-300">{{ $specialty['description'] ?? '' }}</p></article>@endforeach</div></div></section>
