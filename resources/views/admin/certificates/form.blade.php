@php
    $isEdit = isset($certificate);
@endphp

<x-layouts.admin
    :title="$isEdit ? 'Edit Sertifikat' : 'Sertifikat Baru'"
    :subtitle="$isEdit ? 'Perbarui informasi sertifikat yang sudah ada.' : 'Tambahkan sertifikat, piagam, atau dokumentasi prestasi.'"
>
    <x-slot name="actions">
        <x-ui.button :href="route('admin.certificates.index')" variant="secondary" size="sm">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </x-ui.button>
    </x-slot>

    <form
        method="POST"
        action="{{ $isEdit ? route('admin.certificates.update', $certificate['id']) : route('admin.certificates.store') }}"
        enctype="multipart/form-data"
        class="grid gap-6 xl:grid-cols-[1.4fr_0.6fr]"
    >
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Informasi sertifikat</h2>
                <p class="mt-1 text-xs text-ink-faint">Kolom bertanda bintang wajib diisi.</p>

                <div class="mt-5 space-y-4">
                    <x-ui.field label="Judul" for="title" required>
                        <x-ui.input id="title" name="title" type="text" :value="old('title', $certificate['title'] ?? '')" required placeholder="Contoh: Juara 1 Lomba Web Design" />
                        <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
                    </x-ui.field>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.field label="Penerbit" for="issuer" required>
                            <x-ui.input id="issuer" name="issuer" type="text" :value="old('issuer', $certificate['issuer'] ?? '')" required placeholder="Contoh: Kemendikbudristek" />
                            <x-input-error :messages="$errors->get('issuer')" class="mt-1.5" />
                        </x-ui.field>

                        <x-ui.field label="Tahun" for="year" required>
                            <x-ui.input id="year" name="year" type="number" min="2020" max="2030" :value="old('year', $certificate['year'] ?? date('Y'))" required />
                            <x-input-error :messages="$errors->get('year')" class="mt-1.5" />
                        </x-ui.field>
                    </div>

                    <x-ui.field label="Jenis" for="type" required>
                        <x-ui.select id="type" name="type" required>
                            @foreach (['Prestasi', 'Pelatihan', 'Kompetensi', 'Organisasi'] as $option)
                                <option value="{{ $option }}" @selected(old('type', $certificate['type'] ?? '') === $option)>{{ $option }}</option>
                            @endforeach
                        </x-ui.select>
                        <x-input-error :messages="$errors->get('type')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Deskripsi" for="description" hint="Ringkas satu hingga dua kalimat.">
                        <x-ui.textarea id="description" name="description" rows="3" placeholder="Deskripsi singkat capaian…">{{ old('description', $certificate['description'] ?? '') }}</x-ui.textarea>
                    </x-ui.field>

                    <x-ui.field label="URL kredensial" for="credential_url" hint="Tautan verifikasi bila ada.">
                        <x-ui.input id="credential_url" name="credential_url" type="url" :value="old('credential_url', $certificate['credential_url'] ?? '')" placeholder="https://…" />
                        <x-input-error :messages="$errors->get('credential_url')" class="mt-1.5" />
                    </x-ui.field>
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Dokumentasi</h2>

                <div class="mt-4">
                    @if ($isEdit && $certificate['image'])
                        <img src="{{ $certificate['image'] }}" alt="Dokumentasi {{ $certificate['title'] }}" class="aspect-[16/10] w-full rounded-xl border border-line object-cover">
                    @else
                        <div class="flex aspect-[16/10] w-full flex-col items-center justify-center rounded-xl border border-dashed border-line bg-surface text-center">
                            <x-ui.icon name="image" class="h-6 w-6 text-ink-faint" />
                            <p class="mt-2 text-xs text-ink-faint">Belum ada gambar</p>
                        </div>
                    @endif

                    <label for="image" class="mt-3 flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-line bg-surface px-3 py-2.5 text-xs text-ink-muted transition-colors hover:text-ink">
                        <x-ui.icon name="upload" class="h-4 w-4" />
                        Unggah dokumentasi
                        <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                    </label>
                    <x-input-error :messages="$errors->get('image')" class="mt-1.5" />
                    <p class="mt-2 text-center font-mono text-[10px] uppercase tracking-[0.1em] text-ink-faint">JPG / PNG · maks 2 MB</p>
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" class="w-full">
                        <x-ui.icon name="check" class="h-4 w-4" />
                        {{ $isEdit ? 'Simpan perubahan' : 'Tambah sertifikat' }}
                    </x-ui.button>
                    <x-ui.button :href="route('admin.certificates.index')" variant="secondary" class="w-full">Batal</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </form>
</x-layouts.admin>
