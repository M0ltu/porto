<div
    data-lightbox
    hidden
    aria-hidden="true"
    class="fixed inset-0 z-[75] flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    aria-label="Pratinjau gambar"
>
    <div data-lightbox-backdrop class="absolute inset-0 cursor-zoom-out bg-black/80" aria-hidden="true"></div>

    <div class="relative w-full max-w-4xl">
        <button
            type="button"
            data-lightbox-close
            class="absolute -top-11 right-0 inline-flex h-9 w-9 items-center justify-center rounded-full bg-surface-raised text-ink shadow-lift"
            aria-label="Tutup pratinjau"
        >
            <x-ui.icon name="x" class="h-4 w-4" />
        </button>

        <img
            data-lightbox-image
            src=""
            alt=""
            class="max-h-[80vh] w-full rounded-2xl border border-line object-contain"
        >

        <p data-lightbox-caption class="mt-3 text-center text-sm text-white/80"></p>
    </div>
</div>
