@props(['value' => 0, 'showValue' => false, 'size' => 'sm'])

@php
    $value = max(0, min(5, (float) $value));
    $percent = ($value / 5) * 100;
    $starClass = match ($size) {
        'lg' => 'h-5 w-5',
        'md' => 'h-4 w-4',
        default => 'h-3.5 w-3.5',
    };
    $formatted = number_format($value, 1);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5']) }} role="img" aria-label="Rating {{ $formatted }} dari 5">
    <span class="relative inline-flex" aria-hidden="true">
        <span class="flex gap-0.5 text-line">
            @for ($i = 0; $i < 5; $i++)
                <svg class="{{ $starClass }}" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2 15 9l7 .9-5 4.8 1.2 7L12 18.5 5.8 21.7 7 14.7 2 9.9 9 9Z" />
                </svg>
            @endfor
        </span>

        <span class="absolute inset-y-0 left-0 overflow-hidden" style="width: {{ $percent }}%">
            <span class="flex gap-0.5 text-accent" style="width: max-content">
                @for ($i = 0; $i < 5; $i++)
                    <svg class="{{ $starClass }} flex-none" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2 15 9l7 .9-5 4.8 1.2 7L12 18.5 5.8 21.7 7 14.7 2 9.9 9 9Z" />
                    </svg>
                @endfor
            </span>
        </span>
    </span>

    @if ($showValue)
        <span class="font-mono text-[11px] text-ink-muted">{{ $formatted }}</span>
    @endif
</span>
