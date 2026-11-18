@php
    $isEdit = isset($skill);
@endphp

<x-layouts.admin
    :title="$isEdit ? 'Edit Keahlian' : 'Keahlian Baru'"
    :subtitle="$isEdit ? 'Perbarui keahlian yang sudah ada.' : 'Tambahkan keahlian teknis atau interpersonal.'"
>
    <x-slot name="actions">
        <x-ui.button :href="route('admin.skills.index')" variant="secondary" size="sm">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </x-ui.button>
    </x-slot>

    <form method="POST" action="{{ $isEdit ? route('admin.skills.update', $skill['id']) : route('admin.skills.store') }}" class="mx-auto grid w-full max-w-2xl gap-6">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <x-ui.card>
            <h2 class="text-sm font-semibold text-ink">Informasi keahlian</h2>
            <p class="mt-1 text-xs text-ink-faint">Kolom bertanda bintang wajib diisi.</p>

            <div class="mt-5 space-y-4">
                <x-ui.field label="Nama keahlian" for="name" required>
                    <x-ui.input id="name" name="name" type="text" :value="old('name', $skill['name'] ?? '')" required placeholder="Contoh: Laravel" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </x-ui.field>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.field label="Kategori" for="type" required>
                        <x-ui.select id="type" name="type" required>
                            <option value="hard" @selected(old('type', $skill['type'] ?? 'hard') === 'hard')>Hard skill</option>
                            <option value="soft" @selected(old('type', $skill['type'] ?? 'hard') === 'soft')>Soft skill</option>
                        </x-ui.select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Penguasaan (%)" for="value" hint="Perkiraan jujur, 1–100." required>
                        <x-ui.input id="value" name="value" type="number" min="1" max="100" :value="old('value', $skill['value'] ?? 70)" required />
                        <x-input-error :messages="$errors->get('value')" class="mt-1.5" />
                    </x-ui.field>
                </div>

                <x-ui.field label="Catatan" for="note" hint="Contoh: terbiasa dipakai di proyek kelas.">
                    <x-ui.input id="note" name="note" type="text" :value="old('note', $skill['note'] ?? '')" placeholder="Keterangan singkat" />
                    <x-input-error :messages="$errors->get('note')" class="mt-1.5" />
                </x-ui.field>
            </div>
        </x-ui.card>

        <div class="flex items-center gap-2">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" />
                {{ $isEdit ? 'Simpan perubahan' : 'Tambah keahlian' }}
            </x-ui.button>
            <x-ui.button :href="route('admin.skills.index')" variant="secondary">Batal</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
