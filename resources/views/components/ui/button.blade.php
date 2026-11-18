@props([
    'href' => null,
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
])

@php
    $sizes = [
        'sm' => 'px-3 py-2 text-[13px]',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-[15px]',
    ];

    $variants = [
        'primary' => 'bg-accent text-accent-ink hover:brightness-110 active:brightness-95',
        'secondary' => 'border border-line bg-surface-raised text-ink hover:border-ink-faint',
        'ghost' => 'text-ink-muted hover:bg-surface-raised hover:text-ink',
        'danger' => 'bg-danger text-white hover:brightness-110',
        'danger-ghost' => 'border border-line text-danger hover:border-danger',
        'soft' => 'bg-accent-soft text-accent hover:brightness-[0.98]',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-xl font-medium transition-all duration-200 ease-out-soft active:scale-[0.98] motion-reduce:active:scale-100 disabled:cursor-not-allowed disabled:opacity-60 '
        .($sizes[$size] ?? $sizes['md']).' '
        .($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
