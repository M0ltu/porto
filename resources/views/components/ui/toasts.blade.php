<div
    x-data
    class="pointer-events-none fixed inset-x-0 top-4 z-[70] flex flex-col items-center gap-2 px-4 sm:items-end sm:px-6"
    aria-live="polite"
    aria-atomic="false"
>
    <template x-for="toast in $store.toasts.items" :key="toast.id">
        <div
            class="modal-enter pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-xl border bg-surface-raised p-4 shadow-lift"
            :class="{
                'border-success': toast.type === 'success',
                'border-danger': toast.type === 'error',
                'border-line': toast.type !== 'success' && toast.type !== 'error',
            }"
            role="status"
        >
            <span
                class="mt-0.5 flex h-7 w-7 flex-none items-center justify-center rounded-full"
                :class="{
                    'bg-success-soft text-success': toast.type === 'success',
                    'bg-danger-soft text-danger': toast.type === 'error',
                    'bg-accent-soft text-accent': toast.type !== 'success' && toast.type !== 'error',
                }"
            >
                <svg x-show="toast.type === 'success'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5" /></svg>
                <svg x-show="toast.type === 'error'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10" /><path d="m15 9-6 6" /><path d="m9 9 6 6" /></svg>
                <svg x-show="toast.type !== 'success' && toast.type !== 'error'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10" /><path d="M12 16v-4" /><path d="M12 8h.01" /></svg>
            </span>

            <p class="flex-1 text-sm leading-6 text-ink" x-text="toast.message"></p>

            <button
                type="button"
                class="flex-none rounded-lg p-1 text-ink-faint transition-colors hover:text-ink"
                @click="$store.toasts.dismiss(toast.id)"
                aria-label="Tutup notifikasi"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18" /><path d="m6 6 12 12" /></svg>
            </button>
        </div>
    </template>
</div>
