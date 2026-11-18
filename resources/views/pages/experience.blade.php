@php
    $profile = $portfolio['profile'];
    $experiences = collect($portfolio['experiences']);
    $current = $experiences->where('current', true);
    $organizations = $experiences->pluck('organization')->unique()->count();
@endphp

<x-layouts.public title="Pengalaman" description="Pengalaman organisasi, lomba, dan praktik kerja lapangan {{ $profile['name'] }}.">
    <section class="section">
        <div class="container-page">
            <x-ui.page-header
                eyebrow="Pengalaman"
                title="Belajar lewat pengalaman nyata"
                subtitle="Praktik kerja lapangan, organisasi sekolah, lomba, dan kegiatan yang membentuk cara saya bekerja dalam tim."
            >
                <x-slot name="actions">
                    <x-ui.button :href="route('certificates.index')" variant="secondary">
                        Lihat sertifikat
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                </x-slot>
            </x-ui.page-header>

            <div class="mt-8 grid gap-4 sm:grid-cols-3" data-reveal-group>
                <x-ui.stat label="Total pengalaman" :value="$experiences->count()" hint="Sejak 2024" />
                <x-ui.stat label="Sedang berjalan" :value="$current->count()" hint="Aktif saat ini" />
                <x-ui.stat label="Organisasi & mitra" :value="$organizations" hint="Tempat belajar" />
            </div>

            <div class="mt-10 grid gap-10 lg:grid-cols-[1.2fr_0.8fr] lg:gap-16">
                <div>
                    <div class="flex items-end justify-between gap-4 border-b border-line pb-4">
                        <div>
                            <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Linimasa</p>
                            <h2 class="mt-2 text-xl font-semibold tracking-tight text-ink">Perjalanan</h2>
                        </div>
                        <x-ui.badge variant="neutral">{{ $experiences->count() }} catatan</x-ui.badge>
                    </div>

                    <ol class="mt-8 space-y-8" data-reveal-group data-reveal-stagger="60">
                        @foreach ($experiences as $experience)
                            <x-ui.timeline-item
                                :period="$experience['period']"
                                :title="$experience['title']"
                                :organization="$experience['organization']"
                                :description="$experience['description']"
                                :type="$experience['type']"
                                :current="$experience['current']"
                                :tags="$experience['tags']"
                            />
                        @endforeach
                    </ol>
                </div>

                <aside class="space-y-5 lg:sticky lg:top-24 lg:self-start" data-reveal-group>
                    <x-ui.card>
                        <x-ui.icon name="graduation-cap" class="h-5 w-5 text-accent" />
                        <h2 class="mt-3 text-sm font-semibold text-ink">Pendidikan</h2>
                        <ul class="mt-4 space-y-4">
                            @foreach ($profile['education'] as $item)
                                <li class="border-l-2 border-line pl-3">
                                    <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">{{ $item['period'] }}</p>
                                    <p class="mt-1 text-sm font-medium text-ink">{{ $item['title'] }}</p>
                                    <p class="text-xs text-ink-muted">{{ $item['organization'] }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </x-ui.card>

                    <x-ui.card>
                        <x-ui.icon name="sparkles" class="h-5 w-5 text-accent" />
                        <h2 class="mt-3 text-sm font-semibold text-ink">Keahlian yang terlatih</h2>
                        <p class="mt-2 text-sm leading-6 text-ink-muted">
                            Dari pengalaman di atas, kemampuan yang paling berkembang adalah kerja tim, dokumentasi, dan kemampuan beradaptasi dengan alur kerja baru.
                        </p>
                        <x-ui.button :href="route('skills.index')" variant="secondary" size="sm" class="mt-4 w-full">
                            Lihat keahlian
                            <x-ui.icon name="arrow-right" class="h-4 w-4" />
                        </x-ui.button>
                    </x-ui.card>

                    <x-ui.card>
                        <h2 class="text-sm font-semibold text-ink">Ingin mengajak kolaborasi?</h2>
                        <p class="mt-2 text-sm leading-6 text-ink-muted">Saya terbuka untuk proyek sekolah, kolaborasi, atau kegiatan komunitas.</p>
                        <x-ui.button :href="route('contact')" class="mt-4 w-full">Hubungi saya</x-ui.button>
                    </x-ui.card>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
