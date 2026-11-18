<section>
    <div class="flex items-start justify-between gap-3">
        <div>
            <h2 class="text-sm font-semibold text-ink">Keamanan kata sandi</h2>
            <p class="mt-1 text-xs leading-5 text-ink-faint">Gunakan kata sandi yang panjang dan acak agar akun tetap aman.</p>
        </div>
        <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-accent-soft text-accent">
            <x-ui.icon name="settings" class="h-4 w-4" />
        </span>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('put')

        <x-ui.field label="Kata sandi baru" for="update_password_password" :error="$errors->updatePassword->first('password')" required>
            <x-ui.input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                required
                @if ($errors->updatePassword->has('password')) aria-invalid="true" aria-describedby="update_password_password-error" @endif
            />
        </x-ui.field>

        <x-ui.field label="Konfirmasi kata sandi baru" for="update_password_password_confirmation" required>
            <x-ui.input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                required
            />
        </x-ui.field>

        <div class="flex flex-wrap items-center gap-3 border-t border-line pt-4">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" />
                Perbarui kata sandi
            </x-ui.button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-medium text-success"
                >Kata sandi diperbarui.</p>
            @endif
        </div>
    </form>
</section>
