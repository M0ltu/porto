@props(['project'])

<article class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-surface-raised shadow-soft transition-all duration-200 ease-out-soft hover:-translate-y-0.5 hover:border-ink-faint hover:shadow-lift">
    <a href="{{ route('projects.show', $project['slug']) }}" class="absolute inset-0 z-10" aria-label="Lihat detail proyek {{ $project['title'] }}"></a>

    <div class="relative aspect-[16/10] overflow-hidden bg-surface">
        <img
            src="{{ $project['cover'] }}"
            alt="Tangkapan layar proyek {{ $project['title'] }}"
            class="h-full w-full object-cover transition-transform duration-300 ease-out-soft group-hover:scale-[1.02] motion-reduce:transform-none"
            loading="lazy"
            width="800"
            height="500"
        >
        <span class="absolute left-3 top-3 rounded-full bg-surface-raised px-2.5 py-1 font-mono text-[10px] uppercase tracking-[0.14em] text-ink-muted">{{ $project['category'] }}</span>
        @if (! empty($project['featured']))
            <span class="absolute right-3 top-3 inline-flex items-center gap-1 rounded-full bg-accent px-2.5 py-1 font-mono text-[10px] uppercase tracking-[0.14em] text-accent-ink">
                <x-ui.icon name="star" class="h-3 w-3" />
                Unggulan
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <h3 class="text-balance text-base font-semibold text-ink transition-colors duration-200 group-hover:text-accent">{{ $project['title'] }}</h3>
        <p class="mt-1.5 line-clamp-2 text-sm leading-6 text-ink-muted">{{ $project['summary'] }}</p>

        <ul class="mt-4 flex flex-wrap gap-1.5">
            @foreach (array_slice($project['tech'] ?? [], 0, 3) as $tech)
                <li class="rounded-full bg-surface px-2.5 py-1 font-mono text-[11px] text-ink-muted">{{ $tech }}</li>
            @endforeach
            @if (count($project['tech'] ?? []) > 3)
                <li class="rounded-full bg-surface px-2.5 py-1 font-mono text-[11px] text-ink-faint">+{{ count($project['tech']) - 3 }}</li>
            @endif
        </ul>

        <div class="mt-5 flex items-center justify-between border-t border-line pt-4">
            <span class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-faint">{{ $project['year'] ?? '2026' }}</span>
            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-accent">
                Detail
                <x-ui.icon name="arrow-right" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5 motion-reduce:transform-none" />
            </span>
        </div>
    </div>
</article>
