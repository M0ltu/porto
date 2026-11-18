@php
    $isEdit = isset($project);
    $value = fn (string $key, $default = '') => old($key, $project[$key] ?? $default);
    $projectModel = $isEdit ? \App\Models\Project::query()->with('images')->where('slug', $project['slug'])->first() : null;
    $galleryImages = $projectModel ? $projectModel->images : collect();
    $gallery = $isEdit ? $project['gallery'] : [];
@endphp

<x-layouts.admin
    :title="$isEdit ? 'Edit Proyek' : 'Proyek Baru'"
    :subtitle="$isEdit ? 'Perbarui informasi proyek yang sudah ada.' : 'Tambahkan proyek baru ke portfolio.'"
>
    <x-slot name="actions">
        <x-ui.button :href="route('admin.projects.index')" variant="secondary" size="sm">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            Kembali
        </x-ui.button>
        @if ($isEdit)
            <x-ui.button :href="route('projects.show', $project['slug'])" variant="secondary" size="sm" target="_blank" rel="noopener noreferrer">
                <x-ui.icon name="external-link" class="h-4 w-4" />
                Lihat di situs
            </x-ui.button>
        @endif
    </x-slot>

    <form
        method="POST"
        action="{{ $isEdit ? route('admin.projects.update', $project['slug']) : route('admin.projects.store') }}"
        enctype="multipart/form-data"
        class="grid gap-6 xl:grid-cols-[1.4fr_0.6fr]"
    >
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Informasi utama</h2>
                <p class="mt-1 text-xs text-ink-faint">Kolom bertanda bintang wajib diisi.</p>

                <div class="mt-5 space-y-4">
                    <x-ui.field label="Judul proyek" for="title" required>
                        <x-ui.input id="title" name="title" type="text" :value="$value('title')" required placeholder="Contoh: Jurnal PLK Digital" />
                        <x-input-error :messages="$errors->get('title')" class="mt-1.5" />
                    </x-ui.field>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.field label="Slug URL" for="slug" hint="Kosongkan untuk dibuat otomatis dari judul.">
                            <x-ui.input id="slug" name="slug" type="text" :value="$value('slug')" placeholder="jurnal-plk-digital" />
                            <x-input-error :messages="$errors->get('slug')" class="mt-1.5" />
                        </x-ui.field>

                        <x-ui.field label="Kategori" for="category" required>
                            <x-ui.select id="category" name="category" required>
                                @foreach (['Web App', 'Game', 'Dashboard', 'Tools', 'Experiment'] as $option)
                                    <option value="{{ $option }}" @selected($value('category') === $option)>{{ $option }}</option>
                                @endforeach
                            </x-ui.select>
                            <x-input-error :messages="$errors->get('category')" class="mt-1.5" />
                        </x-ui.field>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.field label="Tahun" for="year" required>
                            <x-ui.input id="year" name="year" type="number" min="2020" max="2030" :value="$value('year', date('Y'))" required />
                            <x-input-error :messages="$errors->get('year')" class="mt-1.5" />
                        </x-ui.field>

                        <x-ui.field label="Peran" for="role" hint="Contoh: Full-stack developer.">
                            <x-ui.input id="role" name="role" type="text" :value="$value('role')" placeholder="Full-stack developer" />
                        </x-ui.field>
                    </div>

                    <x-ui.field label="Ringkasan" for="summary" hint="Tampil di kartu proyek. Maksimal 2 kalimat." required>
                        <x-ui.textarea id="summary" name="summary" rows="3" required placeholder="Ringkasan singkat proyek…">{{ $value('summary') }}</x-ui.textarea>
                        <x-input-error :messages="$errors->get('summary')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Teknologi" for="tech" hint="Pisahkan dengan koma.">
                        <x-ui.input id="tech" name="tech" type="text" :value="$isEdit ? implode(', ', $project['tech']) : old('tech')" placeholder="Laravel, Blade, Tailwind CSS" />
                        <x-input-error :messages="$errors->get('tech')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Fitur utama" for="features" hint="Satu fitur per baris.">
                        <x-ui.textarea id="features" name="features" rows="4" placeholder="Dashboard statistik&#10;Ekspor laporan PDF">{{ $isEdit ? implode("\n", $project['features'] ?? []) : old('features') }}</x-ui.textarea>
                    </x-ui.field>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Detail studi kasus</h2>

                <div class="mt-5 space-y-4">
                    <x-ui.field label="Masalah" for="problem">
                        <x-ui.textarea id="problem" name="problem" rows="3" placeholder="Masalah yang ingin diselesaikan…">{{ $value('problem') }}</x-ui.textarea>
                    </x-ui.field>

                    <x-ui.field label="Tujuan" for="goal">
                        <x-ui.textarea id="goal" name="goal" rows="3" placeholder="Hasil yang ingin dicapai…">{{ $value('goal') }}</x-ui.textarea>
                    </x-ui.field>

                    <x-ui.field label="Proses" for="process">
                        <x-ui.textarea id="process" name="process" rows="4" placeholder="Langkah pengerjaan, riset, dan pengujian…">{{ $value('process') }}</x-ui.textarea>
                    </x-ui.field>

                    <x-ui.field label="Umpan balik" for="feedback">
                        <x-ui.textarea id="feedback" name="feedback" rows="3" placeholder="Tanggapan pengguna atau pembimbing…">{{ $value('feedback') }}</x-ui.textarea>
                    </x-ui.field>
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Media</h2>

                <div class="mt-4">
                    @if ($isEdit && $project['cover'])
                        <img src="{{ $project['cover'] }}" alt="Cover {{ $project['title'] }}" class="aspect-[16/10] w-full rounded-xl border border-line object-cover">
                    @else
                        <div class="flex aspect-[16/10] w-full flex-col items-center justify-center rounded-xl border border-dashed border-line bg-surface text-center">
                            <x-ui.icon name="image" class="h-6 w-6 text-ink-faint" />
                            <p class="mt-2 text-xs text-ink-faint">Belum ada gambar</p>
                        </div>
                    @endif

                    <label for="cover" class="mt-3 flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-line bg-surface px-3 py-2.5 text-xs text-ink-muted transition-colors hover:text-ink">
                        <x-ui.icon name="upload" class="h-4 w-4" />
                        Unggah cover
                        <input id="cover" name="cover" type="file" accept="image/*" class="sr-only">
                    </label>
                    <x-input-error :messages="$errors->get('cover')" class="mt-1.5" />
                    <p class="mt-2 text-center font-mono text-[10px] uppercase tracking-[0.1em] text-ink-faint">JPG / PNG · maks 2 MB</p>
                </div>

                <div class="mt-5 border-t border-line pt-4">
                    <p class="text-xs font-medium text-ink">Galeri</p>
                    @if ($galleryImages->isNotEmpty())
                        <div class="mt-3 grid grid-cols-3 gap-2">
                            @foreach ($galleryImages as $image)
                                <div class="group relative">
                                    <img src="{{ $image->path }}" alt="" class="aspect-square w-full rounded-lg border border-line object-cover" loading="lazy" width="120" height="120">
                                    <button
                                        type="button"
                                        class="absolute right-1 top-1 inline-flex h-7 w-7 items-center justify-center rounded-lg bg-surface-raised/95 text-ink-faint opacity-0 shadow-soft transition-all hover:bg-danger-soft hover:text-danger group-hover:opacity-100 focus:opacity-100"
                                        aria-label="Hapus foto galeri"
                                        @click="askConfirm({ title: 'Hapus foto galeri?', message: 'Foto ini akan dihapus dari galeri proyek.', confirmLabel: 'Hapus', onConfirm: () => adminSubmitAction(@js(route('admin.projects.images.destroy', [$projectModel, $image])), 'DELETE') })"
                                    >
                                        <x-ui.icon name="trash" class="h-3.5 w-3.5" />
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @elseif (count($gallery) > 0)
                        <div class="mt-3 grid grid-cols-3 gap-2">
                            @foreach ($gallery as $image)
                                <img src="{{ $image }}" alt="" class="aspect-square w-full rounded-lg border border-line object-cover" loading="lazy" width="120" height="120">
                            @endforeach
                        </div>
                    @else
                        <p class="mt-2 text-xs text-ink-faint">Belum ada foto galeri.</p>
                    @endif

                    <label for="gallery" class="mt-3 flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-line bg-surface px-3 py-2.5 text-xs text-ink-muted transition-colors hover:text-ink">
                        <x-ui.icon name="upload" class="h-4 w-4" />
                        Unggah foto galeri
                        <input id="gallery" name="gallery[]" type="file" accept="image/*" multiple class="sr-only">
                    </label>
                    <p class="mt-2 text-center font-mono text-[10px] uppercase tracking-[0.1em] text-ink-faint">Foto baru ditambahkan di akhir galeri</p>
                </div>

                <div class="mt-5 space-y-3 border-t border-line pt-4 text-sm">
                    <label class="flex items-start gap-2.5">
                        <input type="checkbox" name="featured" value="1" class="mt-0.5 rounded border-line text-accent focus:ring-0" @checked($isEdit ? $project['featured'] : old('featured'))>
                        <span>
                            <span class="block font-medium text-ink">Tampilkan sebagai unggulan</span>
                            <span class="text-xs text-ink-faint">Muncul di beranda dan urutan teratas.</span>
                        </span>
                    </label>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Tautan</h2>

                <div class="mt-4 space-y-4">
                    <x-ui.field label="URL demo" for="demo_url">
                        <x-ui.input id="demo_url" name="demo_url" type="url" :value="$value('demo_url')" placeholder="https://…" />
                        <x-input-error :messages="$errors->get('demo_url')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="URL repositori" for="repo_url">
                        <x-ui.input id="repo_url" name="repo_url" type="url" :value="$value('repo_url')" placeholder="https://github.com/…" />
                        <x-input-error :messages="$errors->get('repo_url')" class="mt-1.5" />
                    </x-ui.field>
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" class="w-full">
                        <x-ui.icon name="check" class="h-4 w-4" />
                        {{ $isEdit ? 'Simpan perubahan' : 'Tambah proyek' }}
                    </x-ui.button>
                    <x-ui.button :href="route('admin.projects.index')" variant="secondary" class="w-full">Batal</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </form>
</x-layouts.admin>
