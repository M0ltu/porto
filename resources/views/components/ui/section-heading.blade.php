@props(['eyebrow' => null, 'title', 'description' => null, 'align' => 'left', 'number' => null])

<div {{ $attributes->merge(['class' => $align === 'center' ? 'text-center' : '']) }}>
    @if ($eyebrow)
        <p class="mb-3 flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint {{ $align === 'center' ? 'justify-center' : '' }}">
            <span class="eyebrow-tick" aria-hidden="true"></span>
            {{ $eyebrow }}
            @if ($number)
                <span class="ml-1 text-ink-faint/70" aria-hidden="true">· {{ $number }}</span>
            @endif
        </p>
    @endif

    <h2 class="text-balance text-2xl font-semibold tracking-tight text-ink sm:text-3xl">{{ $title }}</h2>

    @if ($description)
        <p class="mt-3 max-w-2xl text-pretty text-sm leading-6 text-ink-muted {{ $align === 'center' ? 'mx-auto' : '' }}">
            {{ $description }}
        </p>
    @endif
</div>
