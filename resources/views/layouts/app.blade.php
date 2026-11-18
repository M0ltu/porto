@props(['header' => null])

<x-layouts.admin title="Pengaturan Akun" subtitle="Kelola informasi akun administrator yang dipakai untuk masuk ke dashboard.">
    @isset($header)
        <div class="mb-6">
            {{ $header }}
        </div>
    @endisset

    {{ $slot }}
</x-layouts.admin>
