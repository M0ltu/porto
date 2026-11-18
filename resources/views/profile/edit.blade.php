<x-layouts.admin title="Pengaturan Akun" subtitle="Kelola informasi akun administrator yang dipakai untuk masuk ke dashboard.">
    <x-slot name="actions">
        <x-ui.button :href="route('admin.settings')" variant="secondary" size="sm">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            Kembali ke Pengaturan
        </x-ui.button>
    </x-slot>

    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-ui.card>
            @include('profile.partials.update-profile-information-form')
        </x-ui.card>

        <x-ui.card>
            @include('profile.partials.update-password-form')
        </x-ui.card>

        <x-ui.card>
            @include('profile.partials.delete-user-form')
        </x-ui.card>
    </div>
</x-layouts.admin>
