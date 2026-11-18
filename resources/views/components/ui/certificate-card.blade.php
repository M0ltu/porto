@props(['certificate'])

<article class="flex h-full flex-col overflow-hidden rounded-2xl border border-line bg-surface-raised shadow-soft">
    <button
        type="button"
        class="group relative block aspect-[4/3] overflow-hidden bg-surface"
        data-lightbox-trigger
        data-src="{{ $certificate['image'] }}"
        data-alt="Dokumentasi {{ $certificate['title'] }}"
        data-caption="{{ $certificate['title'] }} — {{ $certificate['issuer'] }}"
        aria-label="Perbesar dokumentasi {{ $certificate['title'] }}"
    >
        <img
            src="{{ $certificate['image'] }}"
            alt="Dokumentasi {{ $certificate['title'] }}"
            class="cert-cover h-full w-full object-cover transition-transform duration-300 ease-out-soft group-hover:scale-[1.02] motion-reduce:transform-none"
            loading="lazy"
            width="600"
            height="450"
        >
        <span class="absolute inset-0 flex items-center justify-center bg-black/0 text-white opacity-0 transition-all duration-200 group-hover:bg-black/40 group-hover:opacity-100">
            <span class="inline-flex items-center gap-2 rounded-full bg-black/60 px-3 py-1.5 text-xs font-medium">
                <x-ui.icon name="eye" class="h-4 w-4" />
                Perbesar
            </span>
        </span>
    </button>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center gap-2">
            <x-ui.badge :variant="$certificate['type'] === 'Prestasi' ? 'accent' : 'neutral'">{{ $certificate['type'] }}</x-ui.badge>
            <span class="font-mono text-[11px] text-ink-faint">{{ $certificate['year'] }}</span>
        </div>

        <h3 class="mt-2 text-base font-semibold text-ink">{{ $certificate['title'] }}</h3>
        <p class="mt-1 text-sm text-ink-muted">{{ $certificate['issuer'] }}</p>

        @if (! empty($certificate['description']))
            <p class="mt-2 text-sm leading-6 text-ink-muted">{{ $certificate['description'] }}</p>
        @endif
    </div>
</article>
