@props(['testimonial'])

@php
    $ratings = collect($testimonial['ratings'] ?? []);
    $average = $ratings->isNotEmpty() ? $ratings->avg() : null;
    $relation = $testimonial['relation'] ?? null;
    $relationLabel = match ($relation) {
        'guru' => 'Guru',
        'teman' => 'Teman',
        default => null,
    };
@endphp

<figure class="flex h-full flex-col rounded-2xl border border-line bg-surface-raised p-6 shadow-soft">
    <div class="flex items-start justify-between gap-3">
        <span class="pointer-events-none select-none font-mono text-5xl leading-none text-accent/15" aria-hidden="true">&ldquo;</span>

        @if ($average !== null)
            <x-ui.rating-stars :value="$average" show-value />
        @endif
    </div>

    <blockquote class="mt-4 flex-1 text-pretty text-sm leading-7 text-ink-muted">
        “{{ $testimonial['body'] ?? $testimonial['message'] }}”
    </blockquote>

    <figcaption class="mt-5 flex items-center gap-3 border-t border-line pt-4">
        <x-ui.avatar :name="$testimonial['name']" size="sm" />
        <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-semibold text-ink">{{ $testimonial['name'] }}</p>
            <p class="truncate text-xs text-ink-muted">{{ $testimonial['role'] }}</p>
        </div>

        @if ($relationLabel)
            <x-ui.badge :variant="$relation === 'guru' ? 'accent' : 'neutral'">{{ $relationLabel }}</x-ui.badge>
        @endif
    </figcaption>
</figure>
