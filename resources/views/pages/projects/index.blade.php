@php
    $profile = $portfolio['profile'];
    $projects = collect($portfolio['projects']);
    $categories = $projects->pluck('category')->unique()->prepend('Semua')->values();

    $meta = $projects->map(fn (array $project) => [
        'category' => $project['category'],
        'search' => mb_strtolower(implode(' ', [
            $project['title'],
            $project['summary'],
            $project['category'],
            implode(' ', $project['tech'] ?? []),
        ])),
    ])->values();
@endphp

<x-layouts.public title="Proyek" description="Kumpulan proyek web, game, dan tools yang dikerjakan {{ $profile['name'] }}.">
    <section class="section">
        <div class="container-page">
            <x-ui.page-header
                eyebrow="Proyek"
                title="Karya yang bisa dilihat dan dicoba"
                subtitle="Setiap proyek di bawah berawal dari masalah kecil di sekolah atau sekitar. Saya usahakan selalu ada versi yang benar-benar bisa dipakai."
            >
                <x-slot name="actions">
                    <x-ui.button :href="route('contact')" variant="secondary">
                        Diskusikan proyek
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                </x-slot>
            </x-ui.page-header>

            <div
                class="mt-8"
                x-data="{
                    query: '',
                    category: 'Semua',
                    counting: false,
                    firstCount: true,
                    meta: @js($meta),
                    matches(item) {
                        const query = this.query.trim().toLowerCase();
                        const categoryOk = this.category === 'Semua' || item.category === this.category;
                        const queryOk = query === '' || item.search.includes(query);
                        return categoryOk && queryOk;
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
                        <label for="project-search" class="sr-only">Cari proyek</label>
                        <input
                            id="project-search"
                            type="search"
                            x-model.debounce.150ms="query"
                            placeholder="Cari judul, teknologi, atau kata kunci…"
                            class="w-full rounded-xl border border-line bg-surface py-2.5 pl-9 pr-3 text-sm text-ink placeholder:text-ink-faint focus:border-accent focus:ring-0"
                        >
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter kategori proyek">
                        @foreach ($categories as $category)
                            <button
                                type="button"
                                @click="category = @js($category)"
                                :aria-pressed="(category === @js($category)).toString()"
                                class="rounded-full border px-3 py-1.5 text-xs transition-colors duration-200"
                                :class="category === @js($category) ? 'border-transparent bg-accent-soft font-medium text-accent' : 'border-line bg-surface text-ink-muted hover:text-ink'"
                            >
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between gap-4">
                    <p class="font-mono text-[11px] uppercase tracking-[0.14em] text-ink-faint">
                        Menampilkan <span class="inline-block" :class="counting ? 'count-pulse' : ''" x-text="count()">{{ $projects->count() }}</span> dari {{ $projects->count() }} proyek
                    </p>
                    <p class="hidden text-xs text-ink-faint sm:block">Klik kartu untuk membaca proses lengkapnya.</p>
                </div>

                <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                    @foreach ($projects as $project)
                        <div
                            x-show="visible({{ $loop->index }})"
                            x-transition:enter="transition ease-out duration-[180ms]"
                            x-transition:enter-start="opacity-0 translate-y-1.5"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-[180ms]"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1.5"
                        >
                            <x-ui.project-card :project="$project" />
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
                        icon="search"
                        title="Tidak ada proyek yang cocok"
                        message="Coba kata kunci lain atau kembali ke kategori Semua."
                    >
                        <x-slot name="action">
                            <x-ui.button variant="secondary" type="button" @click="query = ''; category = 'Semua'">
                                Reset filter
                            </x-ui.button>
                        </x-slot>
                    </x-ui.empty-state>
                </div>
            </div>
        </div>
    </section>

    <section class="section border-t border-line bg-surface-raised">
        <div class="container-page">
            <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center" data-reveal>
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-ink">Ingin tahu cara saya bekerja?</h2>
                    <p class="mt-1.5 text-sm text-ink-muted">Lihat pengalaman, sertifikat, atau ajak saya berkolaborasi.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <x-ui.button :href="route('experience.index')" variant="secondary">Pengalaman</x-ui.button>
                    <x-ui.button :href="route('contact')">
                        Hubungi saya
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
