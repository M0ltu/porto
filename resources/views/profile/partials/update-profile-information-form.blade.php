<section>
    <div class="flex items-start justify-between gap-3">
        <div>
            <h2 class="text-sm font-semibold text-ink">Informasi profil</h2>
            <p class="mt-1 text-xs leading-5 text-ink-faint">Perbarui nama dan alamat email yang dipakai untuk masuk ke dashboard.</p>
        </div>
        <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-accent-soft text-accent">
            <x-ui.icon name="user" class="h-4 w-4" />
        </span>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('patch')

        <x-ui.field label="Nama" for="name" :error="$errors->first('name')" required>
            <x-ui.input name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
        </x-ui.field>

        <x-ui.field label="Email" for="email" :error="$errors->first('email')" required>
            <x-ui.input name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
        </x-ui.field>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-xl border border-line bg-surface px-3.5 py-3">
                <p class="text-xs leading-5 text-ink-muted">Alamat email belum terverifikasi.</p>

                <x-ui.button type="submit" form="send-verification" variant="secondary" size="sm" class="mt-2.5">
                    <x-ui.icon name="mail" class="h-3.5 w-3.5" />
                    Kirim ulang email verifikasi
                </x-ui.button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-medium text-success">Tautan verifikasi baru sudah dikirim ke email Anda.</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap items-center gap-3 border-t border-line pt-4">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" />
                Simpan
            </x-ui.button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-medium text-success"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
