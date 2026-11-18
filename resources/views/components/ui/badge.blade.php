@props(['variant' => 'neutral'])

@php
    $variants = [
        'neutral' => 'bg-surface text-ink-muted border-line',
        'accent' => 'bg-accent-soft text-accent border-transparent',
        'success' => 'bg-success-soft text-success border-transparent',
        'danger' => 'bg-danger-soft text-danger border-transparent',
        'outline' => 'bg-transparent text-ink-muted border-line',
    ];

    $classes = 'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 font-mono text-[11px] uppercase tracking-[0.12em] '
        .($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
