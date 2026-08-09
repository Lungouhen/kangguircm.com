@props(['members' => []])
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($members as $member)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                    <img src="{{ $member['image'] ?? 'https://via.placeholder.com/400x400' }}" alt="{{ $member['name'] }}" class="w-full h-64 object-cover">
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900">{{ $member['name'] ?? 'Name' }}</h3>
                        <p class="text-blue-600 mb-4">{{ $member['role'] ?? 'Role' }}</p>
                        <div class="flex justify-center space-x-3">
                            <!-- Social Links Placeholder -->
                            <a href="#" class="text-gray-400 hover:text-blue-500">FB</a>
                            <a href="#" class="text-gray-400 hover:text-blue-400">TW</a>
                            <a href="#" class="text-gray-400 hover:text-pink-500">IN</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>