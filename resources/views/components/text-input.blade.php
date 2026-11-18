@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-line bg-surface px-3.5 py-2.5 text-sm text-ink placeholder:text-ink-faint shadow-none transition-colors duration-200 focus:border-accent focus:ring-0 disabled:cursor-not-allowed disabled:opacity-60']) }}>
