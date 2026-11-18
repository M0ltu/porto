<x-guest-layout title="Konfirmasi" heading="Konfirmasi kata sandi" subheading="Area ini dilindungi. Masukkan kata sandi untuk melanjutkan.">
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="password" value="Kata sandi" />
            <x-text-input id="password" class="mt-1.5 block" type="password" name="password" required autofocus autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Konfirmasi
        </x-primary-button>
    </form>
</x-guest-layout>
