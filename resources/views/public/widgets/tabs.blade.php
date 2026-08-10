@props(['tabs' => []])
<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-4xl" x-data="{ tab: 0 }">
        <div class="flex border-b mb-6">
            @foreach($tabs as $index => $tab)
                <button @click="tab = {{ $index }}" 
                        :class="tab === {{ $index }} ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500'"
                        class="px-6 py-2 border-b-2 font-medium transition-colors">
                    {{ $tab['label'] ?? 'Tab' }}
                </button>
            @endforeach
        </div>
        @foreach($tabs as $index => $tab)
            <div x-show="tab === {{ $index }}" x-transition class="p-6 bg-gray-50 rounded-lg">
                {!! nl2br(e($tab['content'] ?? '')) !!}
            </div>
        @endforeach
    </div>
</section>