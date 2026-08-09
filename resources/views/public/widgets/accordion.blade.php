@props(['title' => '', 'items' => []])
<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-8">{{ $title }}</h2>
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ active: null }">
            @foreach($items as $index => $item)
                <div class="border rounded-lg overflow-hidden">
                    <button @click="active === {{ $index }} ? active = null : active = {{ $index }}" 
                            class="w-full text-left p-4 bg-gray-50 hover:bg-gray-100 flex justify-between items-center">
                        <span class="font-semibold">{{ $item['heading'] ?? 'Item' }}</span>
                        <span x-text="active === {{ $index }} ? '-' : '+'" class="text-xl"></span>
                    </button>
                    <div x-show="active === {{ $index }}" x-collapse class="p-4 bg-white">
                        <p class="text-gray-600">{{ $item['content'] ?? '' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>