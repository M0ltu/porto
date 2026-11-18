@php
    $profile = $portfolio['profile'];
    $certificates = collect($portfolio['certificates']);
    $types = $certificates->pluck('type')->unique()->prepend('Semua')->values();

    $meta = $certificates->map(fn (array $certificate) => [
        'type' => $certificate['type'],
        'year' => $certificate['year'],
        'search' => mb_strtolower(implode(' ', [
            $certificate['title'],
            $certificate['issuer'],
            $certificate['type'],
            $certificate['description'] ?? '',
            $certificate['year'],
        ])),
    ])->values();

    $achievements = $certificates->where('type', 'Prestasi')->count();
    $years = $certificates->pluck('year')->unique()->sortDesc()->values();
@endphp

<x-layouts.public title="Sertifikat & Prestasi" description="Sertifikat, prestasi, dan dokumentasi kegiatan {{ $profile['name'] }}.">
    <section class="section">
        <div class="container-page">
            <x-ui.page-header
                eyebrow="Sertifikat &amp; prestasi"
                title="Catatan belajar yang terdokumentasi"
                subtitle="Kumpulan sertifikat, piagam, dan dokumentasi kegiatan. Klik gambar untuk melihat versi lebih besar."
            />

            <div class="mt-8 grid gap-4 sm:grid-cols-3" data-reveal-group>
                <x-ui.stat label="Total dokumen" :value="$certificates->count()" hint="Sertifikat & prestasi" />
                <x-ui.stat label="Prestasi" :value="$achievements" hint="Juara & finalis" />
                <x-ui.stat label="Rentang tahun" :value="$years->last().'–'.$years->first()" hint="Belajar berkelanjutan" />
            </div>

            <div
                class="mt-8"
                x-data="{
                    query: '',
                    type: 'Semua',
                    counting: false,
                    firstCount: true,
                    meta: @js($meta),
                    matches(item) {
                        const query = this.query.trim().toLowerCase();
                        const typeOk = this.type === 'Semua' || item.type === this.type;
                        const queryOk = query === '' || item.search.includes(query);
                        return typeOk && queryOk;
                    },
                    visible(index) { return this.matches(this.meta[index]) },
                    count() { return this.meta.filter(item => this.matches(item)).length },
                    pulseCount() {
                        this.counting = true;
                        setTimeout(() => { this.counting = false }, 220);
                    },
                }"
                x-effect="count(); if (firstCount) { firstCount = false } else { pulseCount() }"
            >
                <div class="flex flex-col gap-4 rounded-2xl border border-line bg-surface-raised p-4 shadow-soft sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative w-full sm:max-w-xs">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint">
                            <x-ui.icon name="search" class="h-4 w-4" />
                        </span>
                        <label for="certificate-search" class="sr-only">Cari sertifikat</label>
                        <input
                            id="certificate-search"
                            type="search"
                            x-model.debounce.150ms="query"
                            placeholder="Cari sertifikat atau penerbit…"
                            class="w-full rounded-xl border border-line bg-surface py-2.5 pl-9 pr-3 text-sm text-ink placeholder:text-ink-faint focus:border-accent focus:ring-0"
                        >
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter jenis dokumen">
                        @foreach ($types as $type)
                            <button
                                type="button"
                                @click="type = @js($type)"
                                :aria-pressed="(type === @js($type)).toString()"
                                class="rounded-full border px-3 py-1.5 text-xs transition-colors duration-200"
                                :class="type === @js($type) ? 'border-transparent bg-accent-soft font-medium text-accent' : 'border-line bg-surface text-ink-muted hover:text-ink'"
                            >
                                {{ $type }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <p class="mt-4 font-mono text-[11px] uppercase tracking-[0.14em] text-ink-faint">
                    Menampilkan <span class="inline-block" :class="counting ? 'count-pulse' : ''" x-text="count()">{{ $certificates->count() }}</span> dari {{ $certificates->count() }} dokumen
                </p>

                <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                    @foreach ($certificates as $certificate)
                        <div
                            x-show="visible({{ $loop->index }})"
                            x-transition:enter="transition ease-out duration-[180ms]"
                            x-transition:enter-start="opacity-0 translate-y-1.5"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-[180ms]"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1.5"
                        >
                            <x-ui.certificate-card :certificate="$certificate" />
                        </div>
                    @endforeach
                </div>

                <div
                    x-show="count() === 0"
                    x-cloak
                    x-transition:enter="transition-opacity ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mt-6"
                >
                    <x-ui.empty-state
                        icon="award"
                        title="Dokumen tidak ditemukan"
                        message="Coba kata kunci lain atau tampilkan semua jenis dokumen."
                    >
                        <x-slot name="action">
                            <x-ui.button variant="secondary" type="button" @click="query = ''; type = 'Semua'">
                                Reset filter
                            </x-ui.button>
                        </x-slot>
                    </x-ui.empty-state>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
