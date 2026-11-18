@props(['label', 'value', 'hint' => null])

<div {{ $attributes->merge(['class' => 'stat-accent rounded-xl border border-line bg-surface-raised p-4']) }}>
    <dt class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">{{ $label }}</dt>
    <dd class="mt-1.5 text-lg font-semibold tracking-tight text-ink">{{ $value }}</dd>
    @if ($hint)
        <p class="mt-1 text-xs text-ink-muted">{{ $hint }}</p>
    @endif
</div>
