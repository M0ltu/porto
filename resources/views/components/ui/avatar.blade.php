@props(['name', 'src' => null, 'size' => 'md', 'ring' => false])

@php
    $sizes = [
        'sm' => 'h-9 w-9 text-xs',
        'md' => 'h-11 w-11 text-sm',
        'lg' => 'h-16 w-16 text-lg',
        'xl' => 'h-24 w-24 text-2xl sm:h-28 sm:w-28 sm:text-3xl',
    ];

    $initials = \Illuminate\Support\Str::of($name)
        ->explode(' ')
        ->filter()
        ->take(2)
        ->map(fn ($part) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))
        ->implode('');

    $classes = 'flex flex-none items-center justify-center overflow-hidden rounded-full bg-accent-soft font-mono font-semibold uppercase text-accent '
        .($sizes[$size] ?? $sizes['md']).' '.($ring ? 'ring-4 ring-surface' : '');
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if ($src)
        <img src="{{ $src }}" alt="Foto {{ $name }}" class="h-full w-full object-cover" loading="lazy">
    @else
        <span aria-hidden="true">{{ $initials }}</span>
        <span class="sr-only">{{ $name }}</span>
    @endif
</span>
