@props(['label', 'value', 'note' => null])

<div {{ $attributes->merge(['data-progress' => $value]) }}>
    <div class="flex items-baseline justify-between gap-3">
        <span class="text-sm font-medium text-ink">{{ $label }}</span>
        <span class="font-mono text-xs text-ink-muted">{{ $value }}%</span>
    </div>

    <div
        class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-line"
        role="progressbar"
        aria-valuenow="{{ $value }}"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-label="Tingkat penguasaan {{ $label }}"
    >
        <div class="progress-fill h-full rounded-full bg-accent" data-progress-fill style="--progress: {{ $value / 100 }}"></div>
    </div>

    @if ($note)
        <p class="mt-1.5 text-xs text-ink-faint">{{ $note }}</p>
    @endif
</div>
