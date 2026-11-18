@props(['as' => 'div', 'padding' => 'p-6', 'hover' => false])

@php
    $classes = 'rounded-2xl border border-line bg-surface-raised shadow-soft '.$padding;

    if ($hover) {
        $classes .= ' transition-all duration-200 ease-out-soft hover:-translate-y-0.5 hover:border-ink-faint hover:shadow-lift';
    }
@endphp

<{{ $as }} {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</{{ $as }}>
