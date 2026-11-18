<x-guest-layout title="Lupa kata sandi" heading="Atur ulang kata sandi" subheading="Masukkan email akun, kami akan mengirim tautan untuk membuat kata sandi baru.">
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="mt-1.5 block" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Kirim tautan reset
        </x-primary-button>
    </form>

    <p class="mt-5 border-t border-line pt-4 text-center text-sm text-ink-muted">
        Ingat kata sandi?
        <a href="{{ route('login') }}" class="font-medium text-accent hover:underline">Kembali masuk</a>
    </p>
</x-guest-layout>
