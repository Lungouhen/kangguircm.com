@props(['counters' => []])
<section class="py-12 bg-blue-600 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($counters as $counter)
                <div class="text-center p-6" x-data="{ count: 0 }" x-intersect.once="$animate.countUp($el, '{{ $counter['number'] ?? 0 }}')">
                    <div class="text-5xl font-bold mb-2 counter-target">{{ $counter['number'] ?? 0 }}</div>
                    <div class="text-lg opacity-90">{{ $counter['label'] ?? 'Label' }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.directive('intersect.once', (el, { expression }) => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const target = el.querySelector('.counter-target');
                    const final = parseInt(target.innerText);
                    let current = 0;
                    const increment = final / 50;
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= final) {
                            target.innerText = final;
                            clearInterval(timer);
                        } else {
                            target.innerText = Math.floor(current);
                        }
                    }, 30);
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.5 });
        observer.observe(el);
    });
});
</script>