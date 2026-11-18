<div
    x-data
    x-show="$store.confirmDialog.open"
    x-cloak
    class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    @keydown.escape.window="$store.confirmDialog.cancel()"
>
    <div class="absolute inset-0 bg-black/60" aria-hidden="true" @click="$store.confirmDialog.cancel()"></div>

    <div
        class="modal-enter relative w-full max-w-sm rounded-2xl border border-line bg-surface-raised p-6 shadow-lift"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="confirm-dialog-title"
        aria-describedby="confirm-dialog-message"
    >
        <div class="flex items-start gap-3">
            <span class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-danger-soft text-danger">
                <x-ui.icon name="alert-circle" class="h-5 w-5" />
            </span>
            <div class="min-w-0">
                <h2 id="confirm-dialog-title" class="text-base font-semibold text-ink" x-text="$store.confirmDialog.title"></h2>
                <p id="confirm-dialog-message" class="mt-1.5 text-sm leading-6 text-ink-muted" x-text="$store.confirmDialog.message"></p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-2">
            <x-ui.button variant="ghost" type="button" @click="$store.confirmDialog.cancel()">Batal</x-ui.button>
            <x-ui.button variant="danger" type="button" @click="$store.confirmDialog.confirm()">
                <span x-text="$store.confirmDialog.confirmLabel">Hapus</span>
            </x-ui.button>
        </div>
    </div>
</div>
