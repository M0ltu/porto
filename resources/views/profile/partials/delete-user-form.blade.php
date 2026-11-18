<section>
    <div class="flex items-start justify-between gap-3">
        <div>
            <h2 class="text-sm font-semibold text-danger">Hapus akun</h2>
            <p class="mt-1 text-xs leading-5 text-ink-muted">Setelah dihapus, seluruh data akun tidak bisa dikembalikan. Pastikan Anda sudah menyimpan informasi penting sebelum melanjutkan.</p>
        </div>
        <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-danger-soft text-danger">
            <x-ui.icon name="trash" class="h-4 w-4" />
        </span>
    </div>

    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-line pt-4">
        <p class="text-xs text-ink-faint">
            Akun aktif: <span class="font-mono text-[11px] text-ink-muted">{{ $user->email }}</span>
        </p>

        <x-ui.button
            variant="danger-ghost"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >
            <x-ui.icon name="trash" class="h-4 w-4" />
            Hapus akun
        </x-ui.button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-start gap-3">
                <span class="flex h-10 w-10 flex-none items-center justify-center rounded-full bg-danger-soft text-danger">
                    <x-ui.icon name="alert-circle" class="h-5 w-5" />
                </span>
                <div class="min-w-0">
                    <h2 id="delete-account-title" class="text-base font-semibold text-ink">Yakin ingin menghapus akun?</h2>
                    <p class="mt-1.5 text-sm leading-6 text-ink-muted">Tindakan ini permanen. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun.</p>
                </div>
            </div>

            <div class="mt-5">
                <x-ui.field label="Kata sandi" for="password" :error="$errors->userDeletion->first('password')" required>
                    <x-ui.input
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Kata sandi"
                        autocomplete="current-password"
                        required
                        @if ($errors->userDeletion->has('password')) aria-invalid="true" aria-describedby="password-error" @endif
                    />
                </x-ui.field>
            </div>

            <div class="mt-6 flex flex-wrap items-center justify-end gap-2">
                <x-ui.button variant="secondary" x-on:click="$dispatch('close')">Batal</x-ui.button>
                <x-ui.button type="submit" variant="danger">Hapus akun</x-ui.button>
            </div>
        </form>
    </x-modal>
</section>
