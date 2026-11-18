@php
    $isEdit = isset($experience);
@endphp

<x-layouts.admin
    :title="$isEdit ? 'Edit Pengalaman' : 'Pengalaman Baru'"
    :subtitle="$isEdit ? 'Perbarui catatan pengalaman yang sudah ada.' : 'Tambahkan pengalaman organisasi, lomba, atau praktik kerja.'"
>
    <x-slot name="actions">
        <x-ui.button :href="route('admin.experiences.index')" variant="secondary" size="sm">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </x-ui.button>
    </x-slot>

    <form method="POST" action="{{ $isEdit ? route('admin.experiences.update', $experience['id']) : route('admin.experiences.store') }}" class="mx-auto grid w-full max-w-2xl gap-6">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <x-ui.card>
            <h2 class="text-sm font-semibold text-ink">Informasi pengalaman</h2>
            <p class="mt-1 text-xs text-ink-faint">Kolom bertanda bintang wajib diisi.</p>

            <div class="mt-5 space-y-4">
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.field label="Periode" for="period" hint="Contoh: 2024 — Sekarang." required>
                        <x-ui.input id="period" name="period" type="text" :value="old('period', $experience['period'] ?? '')" required placeholder="2024 — Sekarang" />
                        <x-input-error :messages="$errors->get('period')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Jenis" for="type" required>
                        <x-ui.select id="type" name="type" required>
                            @foreach (['Organisasi', 'Lomba', 'Praktik Kerja', 'Proyek'] as $option)
                                <option value="{{ $option }}" @selected(old('type', $experience['type'] ?? '') === $option)>{{ $option }}</option>
                            @endforeach
                        </x-ui.select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1.5" />
                    </x-ui.field>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <x-ui.field label="Peran / gelar" for="title" required>
                        <x-ui.input id="title" name="title" type="text" :value="old('title', $experience['title'] ?? '')" required placeholder="Contoh: Ketua Divisi IT" />
                        <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Organisasi / tempat" for="organization" required>
                        <x-ui.input id="organization" name="organization" type="text" :value="old('organization', $experience['organization'] ?? '')" required placeholder="Contoh: OSIS SMKN 1" />
                        <x-input-error :messages="$errors->get('organization')" class="mt-1.5" />
                    </x-ui.field>
                </div>

                <x-ui.field label="Deskripsi" for="description" required>
                    <x-ui.textarea id="description" name="description" rows="4" required placeholder="Apa yang dikerjakan dan hasilnya…">{{ old('description', $experience['description'] ?? '') }}</x-ui.textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-1.5" />
                </x-ui.field>

                <x-ui.field label="Tag" for="tags" hint="Pisahkan dengan koma.">
                    <x-ui.input id="tags" name="tags" type="text" :value="$isEdit ? implode(', ', $experience['tags'] ?? []) : old('tags')" placeholder="Kepemimpinan, Event" />
                    <x-input-error :messages="$errors->get('tags')" class="mt-1.5" />
                </x-ui.field>

                <label class="flex items-start gap-2.5">
                    <input type="checkbox" name="current" value="1" class="mt-0.5 rounded border-line text-accent focus:ring-0" @checked($isEdit ? $experience['current'] : old('current'))>
                    <span>
                        <span class="block font-medium text-ink">Sedang berjalan</span>
                        <span class="text-xs text-ink-faint">Tandai bila masih aktif saat ini.</span>
                    </span>
                </label>
            </div>
        </x-ui.card>

        <div class="flex items-center gap-2">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" />
                {{ $isEdit ? 'Simpan perubahan' : 'Tambah pengalaman' }}
            </x-ui.button>
            <x-ui.button :href="route('admin.experiences.index')" variant="secondary">Batal</x-ui.button>
        </div>
    </form>
</x-layouts.admin>
