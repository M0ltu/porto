@props(['compact' => false])

<button
    type="button"
    onclick="toggleTheme()"
    class="inline-flex items-center justify-center rounded-xl border border-line bg-surface-raised text-ink-muted transition-colors duration-[160ms] hover:border-ink-faint hover:text-ink {{ $compact ? 'h-10 w-10' : 'h-10 gap-2 px-3 text-sm' }}"
    aria-label="Ganti mode gelap atau terang"
    title="Ganti tema"
>
    <span class="theme-icon-swap dark:hidden">
        <x-ui.icon name="moon" class="theme-icon-swap-target h-4 w-4 motion-reduce:transform-none" />
    </span>
    <span class="theme-icon-swap hidden dark:inline-flex">
        <x-ui.icon name="sun" class="theme-icon-swap-target h-4 w-4 motion-reduce:transform-none" />
    </span>
    @unless ($compact)
        <span class="hidden sm:inline dark:hidden">Gelap</span>
        <span class="hidden sm:dark:inline">Terang</span>
    @endunless
</button>
