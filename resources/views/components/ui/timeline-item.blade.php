@props(['period', 'title', 'organization' => null, 'description' => null, 'type' => null, 'current' => false, 'tags' => []])

<li class="timeline-item relative pl-10">
    <span class="timeline-dot absolute left-0 top-1.5 flex h-5 w-5 items-center justify-center rounded-full border-2 {{ $current ? 'timeline-dot--current border-accent bg-accent' : 'border-line bg-surface-raised' }}">
        <span class="h-1.5 w-1.5 rounded-full {{ $current ? 'bg-accent-ink' : 'bg-ink-faint' }}"></span>
    </span>

    <div class="flex flex-wrap items-center gap-2">
        <span class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-faint">{{ $period }}</span>
        @if ($type)
            <x-ui.badge variant="accent">{{ $type }}</x-ui.badge>
        @endif
        @if ($current)
            <x-ui.badge variant="success">Sekarang</x-ui.badge>
        @endif
    </div>

    <h3 class="mt-1.5 text-base font-semibold text-ink">{{ $title }}</h3>

    @if ($organization)
        <p class="text-sm text-ink-muted">{{ $organization }}</p>
    @endif

    @if ($description)
        <p class="mt-2 max-w-2xl text-sm leading-6 text-ink-muted">{{ $description }}</p>
    @endif

    @if (count($tags))
        <ul class="mt-3 flex flex-wrap gap-1.5">
            @foreach ($tags as $tag)
                <li class="rounded-full bg-surface px-2.5 py-1 font-mono text-[11px] text-ink-muted">{{ $tag }}</li>
            @endforeach
        </ul>
    @endif
</li>
