<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 rounded-xl border border-line bg-surface-raised px-4 py-2.5 text-sm font-medium text-ink transition-colors duration-200 hover:border-ink-faint focus:outline-none disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
