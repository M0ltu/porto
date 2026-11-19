@php
    $profile = $portfolio['profile'];
@endphp

<x-layouts.public :title="$project['title']" :description="$project['summary']">
    <section class="section">
        <div class="container-page">
            <x-ui.breadcrumbs :items="[
                ['label' => 'Beranda', 'href' => route('home')],
                ['label' => 'Proyek', 'href' => route('projects.index')],
                ['label' => $project['title']],
            ]" />

            <x-ui.page-header
                :eyebrow="$project['category'].' · '.$project['year']"
                :title="$project['title']"
                :subtitle="$project['summary']"
                :back="route('projects.index')"
                back-label="Kembali ke semua proyek"
            >
                <x-slot name="actions">
                    @if (! empty($project['demo_url']))
                        <x-ui.button :href="$project['demo_url']" target="_blank" rel="noopener noreferrer">
                            Lihat demo
                            <x-ui.icon name="external-link" class="h-4 w-4" />
                        </x-ui.button>
                    @endif
                    @if (! empty($project['repo_url']))
                        <x-ui.button :href="$project['repo_url']" variant="secondary" target="_blank" rel="noopener noreferrer">
                            <x-ui.icon name="github" class="h-4 w-4" />
                            Repositori
                        </x-ui.button>
                    @endif
                </x-slot>
            </x-ui.page-header>

            <div class="mt-8 grid gap-8 lg:grid-cols-[1.35fr_0.65fr] lg:items-start">
                <div class="space-y-10">
                    <button
                        type="button"
                        class="group relative block w-full overflow-hidden rounded-2xl border border-line bg-surface-raised shadow-soft"
                        data-lightbox-trigger
                        data-src="{{ $project['cover'] }}"
                        data-alt="Tangkapan layar proyek {{ $project['title'] }}"
                        data-caption="{{ $project['title'] }} — {{ $project['category'] }}"
                        aria-label="Perbesar tangkapan layar {{ $project['title'] }}"
                    >
                        <img
                            src="{{ $project['cover'] }}"
                            alt="Tangkapan layar proyek {{ $project['title'] }}"
                            class="aspect-[16/10] w-full object-cover transition-transform duration-300 group-hover:scale-[1.01] motion-reduce:transform-none"
                            width="1200"
                            height="750"
                        >
                        <span class="absolute bottom-4 right-4 inline-flex items-center gap-2 rounded-full bg-black/60 px-3 py-1.5 text-xs font-medium text-white opacity-0 transition-opacity duration-200 group-hover:opacity-100">
                            <x-ui.icon name="eye" class="h-4 w-4" />
                            Perbesar
                        </span>
                    </button>

                    <div data-reveal>
                        <h2 class="text-lg font-semibold text-ink">Masalah</h2>
                        <p class="mt-2 max-w-prose text-pretty text-sm leading-7 text-ink-muted">{{ $project['problem'] }}</p>
                    </div>

                    <div data-reveal>
                        <h2 class="text-lg font-semibold text-ink">Tujuan</h2>
                        <p class="mt-2 max-w-prose text-pretty text-sm leading-7 text-ink-muted">{{ $project['goal'] }}</p>
                    </div>

                    <div data-reveal>
                        <h2 class="text-lg font-semibold text-ink">Fitur utama</h2>
                        <ul class="mt-4 grid gap-3 sm:grid-cols-2">
                            @foreach ($project['features'] as $feature)
                                <li class="flex items-start gap-3 rounded-xl border border-line bg-surface-raised p-4">
                                    <span class="mt-0.5 flex h-5 w-5 flex-none items-center justify-center rounded-full bg-accent-soft text-accent">
                                        <x-ui.icon name="check" class="h-3.5 w-3.5" />
                                    </span>
                                    <span class="text-sm leading-6 text-ink-muted">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div data-reveal>
                        <h2 class="text-lg font-semibold text-ink">Proses</h2>
                        <p class="mt-2 max-w-prose text-pretty text-sm leading-7 text-ink-muted">{{ $project['process'] }}</p>
                    </div>

                    <div class="rounded-2xl border border-line bg-ink p-6 text-surface" data-reveal>
                        <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-surface/60">Umpan balik</p>
                        <p class="mt-3 text-pretty text-sm leading-7 text-surface/90">{{ $project['feedback'] }}</p>
                    </div>

                    @if (count($project['gallery']) > 1)
                        <div data-reveal>
                            <h2 class="text-lg font-semibold text-ink">Galeri</h2>
                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                @foreach ($project['gallery'] as $index => $image)
                                    <button
                                        type="button"
                                        class="group relative overflow-hidden rounded-xl border border-line bg-surface-raised"
                                        data-lightbox-trigger
                                        data-src="{{ $image }}"
                                        data-alt="Dokumentasi {{ $project['title'] }} bagian {{ $index + 1 }}"
                                        data-caption="{{ $project['title'] }} — dokumentasi {{ $index + 1 }}"
                                        aria-label="Perbesar dokumentasi {{ $index + 1 }} dari {{ $project['title'] }}"
                                    >
                                        <img
                                            src="{{ $image }}"
                                            alt="Dokumentasi {{ $project['title'] }} bagian {{ $index + 1 }}"
                                            class="aspect-[16/10] w-full object-cover transition-transform duration-300 group-hover:scale-[1.02] motion-reduce:transform-none"
                                            loading="lazy"
                                            width="800"
                                            height="500"
                                        >
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <aside class="space-y-5 lg:sticky lg:top-24" data-reveal>
                    <div class="rounded-2xl border border-line bg-surface-raised p-5 shadow-soft">
                        <h2 class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint">Ringkasan</h2>

                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-ink-faint">Peran</dt>
                                <dd class="max-w-[62%] text-right text-ink">{{ $project['role'] }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-ink-faint">Kategori</dt>
                                <dd class="text-right text-ink">{{ $project['category'] }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="text-ink-faint">Tahun</dt>
                                <dd class="text-right text-ink">{{ $project['year'] }}</dd>
                            </div>
                        </dl>

                        <div class="mt-5 border-t border-line pt-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint">Teknologi</p>
                            <ul class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($project['tech'] as $tech)
                                    <li class="rounded-full bg-surface px-2.5 py-1 font-mono text-[11px] text-ink-muted">{{ $tech }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    @if (! empty($project['metrics']))
                        <div class="rounded-2xl border border-line bg-surface-raised p-5 shadow-soft">
                            <h2 class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint">Hasil</h2>
                            <dl class="mt-3 space-y-3">
                                @foreach ($project['metrics'] as $metric)
                                    <div class="flex items-baseline justify-between gap-3 border-b border-line pb-2 last:border-0 last:pb-0">
                                        <dt class="text-xs text-ink-faint">{{ $metric['label'] }}</dt>
                                        <dd class="text-sm font-medium text-ink">{{ $metric['value'] }}</dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif

                    <div class="rounded-2xl border border-line bg-surface-raised p-5 shadow-soft">
                        <p class="text-sm leading-6 text-ink-muted">Tertarik dengan proyek serupa atau ingin bertanya soal prosesnya?</p>
                        <x-ui.button :href="route('contact')" class="mt-4 w-full">
                            Hubungi saya
                            <x-ui.icon name="arrow-right" class="h-4 w-4" />
                        </x-ui.button>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section border-t border-line bg-surface-raised">
            <div class="container-page">
                <x-ui.section-heading
                    eyebrow="Proyek lain"
                    title="Yang mungkin juga menarik"
                    description="Proyek dengan kategori atau pendekatan yang berdekatan."
                />

                <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-reveal>
                    @foreach ($related as $item)
                        <x-ui.project-card :project="$item" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.public>
