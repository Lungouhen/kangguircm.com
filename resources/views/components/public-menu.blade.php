@props(['items', 'mobile' => false])
@foreach($items as $item)
    @if($item->is_active && ($item->type !== 'page' || ($item->page?->is_published && $item->page->published_at?->isPast())))
        <div class="{{ $mobile ? '' : 'relative group' }}">
            <a href="{{ $item->resolvedUrl() }}" target="{{ $item->target }}" @if($item->target==='_blank') rel="noopener" @endif class="{{ $mobile ? 'block rounded px-3 py-2 hover:bg-slate-50' : 'hover:text-[var(--primary-color)] transition' }}">{{ $item->label }}</a>
            @if($item->children->isNotEmpty())
                <div class="{{ $mobile ? 'pl-4' : 'md:absolute md:hidden md:group-hover:block md:min-w-52 md:bg-white md:shadow-xl md:rounded-lg md:p-3 md:top-full md:left-0' }}">
                    <x-public-menu :items="$item->children" :mobile="$mobile" />
                </div>
            @endif
        </div>
    @endif
@endforeach
