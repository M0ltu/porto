@php
    $profile = $portfolio['profile'];
    $socials = collect($portfolio['socials']);
    $hasCv = ! empty($profile['cv_path']);
@endphp

<x-layouts.admin title="Pengaturan" subtitle="Perbarui identitas publik, tautan sosial, dan dokumen CV.">
    <x-slot name="actions">
        <x-ui.button :href="route('profile.edit')" variant="secondary" size="sm">
            <x-ui.icon name="user" class="h-4 w-4" />
            Akun &amp; keamanan
        </x-ui.button>
    </x-slot>

    <form
        method="POST"
        action="{{ route('admin.settings.update') }}"
        enctype="multipart/form-data"
        class="grid gap-6 xl:grid-cols-[1.4fr_0.6fr]"
    >
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <x-ui.card>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-ink">Identitas publik</h2>
                        <p class="mt-1 text-xs text-ink-faint">Tampil di beranda, footer, dan halaman kontak.</p>
                    </div>
                    <x-ui.badge variant="accent">Publik</x-ui.badge>
                </div>

                <div class="mt-5 flex flex-col gap-4 border-b border-line pb-5 sm:flex-row sm:items-center">
                    <x-ui.avatar :name="$profile['name']" :src="$profile['avatar']" size="lg" />
                    <div>
                        <label for="avatar" class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-line bg-surface px-3 py-2 text-xs text-ink-muted transition-colors hover:text-ink">
                            <x-ui.icon name="upload" class="h-4 w-4" />
                            Ganti foto
                            <input id="avatar" name="avatar" type="file" accept="image/*" class="sr-only">
                        </label>
                        <x-input-error :messages="$errors->get('avatar')" class="mt-1.5" />
                        <p class="mt-1.5 font-mono text-[10px] uppercase tracking-[0.1em] text-ink-faint">JPG / PNG · maks 2 MB</p>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.field label="Nama lengkap" for="name" required>
                            <x-ui.input id="name" name="name" type="text" :value="old('name', $profile['name'])" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                        </x-ui.field>

                        <x-ui.field label="Nama panggilan" for="nickname">
                            <x-ui.input id="nickname" name="nickname" type="text" :value="old('nickname', $profile['nickname'])" />
                        </x-ui.field>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.field label="Email publik" for="email" required>
                            <x-ui.input id="email" name="email" type="email" :value="old('email', $profile['email'])" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                        </x-ui.field>

                        <x-ui.field label="Lokasi" for="location">
                            <x-ui.input id="location" name="location" type="text" :value="old('location', $profile['location'])" />
                        </x-ui.field>
                    </div>

                    <x-ui.field label="Headline" for="headline" hint="Satu kalimat yang muncul di bawah nama." required>
                        <x-ui.input id="headline" name="headline" type="text" :value="old('headline', $profile['headline'])" required />
                        <x-input-error :messages="$errors->get('headline')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Bio singkat" for="bio" hint="2–3 kalimat, fokus pada minat dan cara kerja." required>
                        <x-ui.textarea id="bio" name="bio" rows="4" required>{{ old('bio', $profile['bio']) }}</x-ui.textarea>
                        <x-input-error :messages="$errors->get('bio')" class="mt-1.5" />
                    </x-ui.field>

                    <x-ui.field label="Status ketersediaan" for="availability" hint="Contoh: Terbuka untuk kolaborasi.">
                        <x-ui.input id="availability" name="availability" type="text" :value="old('availability', $profile['availability'])" />
                    </x-ui.field>
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h2 class="text-sm font-semibold text-ink">Tautan sosial</h2>
                        <p class="mt-1 text-xs text-ink-faint">Ikon muncul di footer dan halaman kontak.</p>
                    </div>
                    <span class="font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">{{ $socials->count() }} tautan</span>
                </div>

                <div class="mt-5 space-y-4">
                    @foreach ($socials as $social)
                        <div class="grid items-end gap-3 sm:grid-cols-[auto_1fr]">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-accent-soft text-accent" title="{{ $social['label'] }}">
                                <x-ui.icon :name="$social['icon']" class="h-4 w-4" />
                            </span>
                            <x-ui.field :label="$social['label']" :for="'social-'.$social['id']">
                                <x-ui.input
                                    :id="'social-'.$social['id']"
                                    :name="'socials['.$social['id'].']'"
                                    type="text"
                                    :value="old('socials.'.$social['id'], $social['href'])"
                                />
                            </x-ui.field>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card>
                <div class="flex items-start justify-between gap-3">
                    <h2 class="text-sm font-semibold text-ink">Dokumen CV</h2>
                    @if ($hasCv)
                        <x-ui.badge variant="success">Tersedia</x-ui.badge>
                    @else
                        <x-ui.badge variant="danger">Belum ada</x-ui.badge>
                    @endif
                </div>

                <div class="mt-4 rounded-xl border border-dashed border-line bg-surface px-4 py-6 text-center">
                    <x-ui.icon name="file-text" class="mx-auto h-6 w-6 text-ink-faint" />
                    <p class="mt-2 text-xs leading-5 text-ink-muted">
                        @if ($hasCv)
                            CV aktif: <span class="font-mono text-[10px]">{{ basename($profile['cv_path']) }}</span>
                        @else
                            Belum ada PDF yang diunggah. Tombol unduh di situs dinonaktifkan.
                        @endif
                    </p>
                </div>

                <label for="cv" class="mt-3 flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-line bg-surface px-3 py-2.5 text-xs text-ink-muted transition-colors hover:text-ink">
                    <x-ui.icon name="upload" class="h-4 w-4" />
                    Unggah CV PDF
                    <input id="cv" name="cv" type="file" accept="application/pdf" class="sr-only">
                </label>
                <x-input-error :messages="$errors->get('cv')" class="mt-1.5" />
                <p class="mt-2 text-center font-mono text-[10px] uppercase tracking-[0.1em] text-ink-faint">PDF · maks 2 MB</p>

                @if ($hasCv)
                    <label class="mt-4 flex items-start gap-2.5 border-t border-line pt-4 text-sm">
                        <input type="checkbox" name="remove_cv" value="1" class="mt-0.5 rounded border-line text-accent focus:ring-0">
                        <span class="text-xs text-ink-muted">Hapus CV saat menyimpan</span>
                    </label>
                @endif

                <div class="mt-4 flex items-center justify-between gap-2 border-t border-line pt-4">
                    <a href="{{ route('cv') }}" class="inline-flex items-center gap-1.5 text-xs text-ink-muted transition-colors hover:text-accent">
                        <x-ui.icon name="eye" class="h-3.5 w-3.5" />
                        Lihat halaman CV
                    </a>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Tampilan</h2>
                <p class="mt-1 text-xs leading-5 text-ink-faint">Tema situs mengikuti pilihan pengunjung dan tersimpan di perangkat mereka.</p>

                <div class="mt-4 flex items-center justify-between gap-3 rounded-xl border border-line bg-surface px-3.5 py-3">
                    <div>
                        <p class="text-sm text-ink">Tema terang / gelap</p>
                        <p class="mt-0.5 text-xs text-ink-faint">Tombol ada di header situs publik.</p>
                    </div>
                    <x-ui.theme-toggle />
                </div>
            </x-ui.card>

            <x-ui.card>
                <div class="flex flex-col gap-2">
                    <x-ui.button type="submit" class="w-full">
                        <x-ui.icon name="check" class="h-4 w-4" />
                        Simpan pengaturan
                    </x-ui.button>
                    <x-ui.button :href="route('admin.dashboard')" variant="secondary" class="w-full">Batal</x-ui.button>
                </div>
            </x-ui.card>
        </div>
    </form>
</x-layouts.admin>
