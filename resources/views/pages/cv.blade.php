@php
    $profile = $portfolio['profile'];
    $hasCv = ! empty($profile['cv_path']);

    $topProjects = collect($portfolio['projects'])->where('featured', true)->take(3);
    $prestasi = collect($portfolio['certificates'])->where('type', 'Prestasi');
    $hardSkills = collect($portfolio['skills']['hard'])->sortByDesc('value');
@endphp

<x-layouts.public title="CV Digital" description="CV digital {{ $profile['name'] }}, {{ $profile['major'] }}, {{ $profile['school_class'] }}.">
    <section class="section">
        <div class="container-page">
            <div class="no-print">
                <x-ui.page-header
                    eyebrow="CV Digital"
                    title="Curriculum Vitae"
                    :subtitle="$hasCv ? 'Pratinjau CV PDF yang diunggah melalui admin.' : 'Ringkasan profil, pendidikan, keahlian, dan pengalaman dalam satu halaman yang siap dicetak.'"
                >
                    <x-slot name="actions">
                        @if ($hasCv)
                            <x-ui.button :href="$profile['cv_path']" target="_blank" rel="noopener noreferrer">
                                <x-ui.icon name="external-link" class="h-4 w-4" />
                                Buka PDF
                            </x-ui.button>
                            <x-ui.button :href="$profile['cv_path']" variant="secondary" download>
                                <x-ui.icon name="download" class="h-4 w-4" />
                                Unduh
                            </x-ui.button>
                        @else
                            <x-ui.button variant="secondary" disabled title="File PDF belum diunggah">
                                <x-ui.icon name="download" class="h-4 w-4" />
                                PDF belum tersedia
                            </x-ui.button>
                            <x-ui.button variant="secondary" type="button" onclick="window.print()">
                                <x-ui.icon name="printer" class="h-4 w-4" />
                                Cetak halaman
                            </x-ui.button>
                        @endif
                    </x-slot>
                </x-ui.page-header>

                @unless ($hasCv)
                    <div class="mt-6 flex items-start gap-3 rounded-xl border border-line bg-accent-soft/50 p-4 text-sm text-ink-muted">
                        <x-ui.icon name="info" class="mt-0.5 h-4 w-4 flex-none text-accent" />
                        <p>File PDF CV belum diunggah. Sementara ini, gunakan tombol <strong class="font-medium text-ink">Cetak halaman</strong> lalu pilih �?oSimpan sebagai PDF�??.</p>
                    </div>
                @endunless
            </div>

            @if ($hasCv)
                <div class="mt-8 overflow-hidden rounded-2xl border border-line bg-surface-raised shadow-soft" data-reveal>
                    <object
                        data="{{ $profile['cv_path'] }}"
                        type="application/pdf"
                        class="block w-full"
                        style="height: min(85vh, 1100px);"
                        aria-label="CV {{ $profile['name'] }}"
                    >
                        <div class="flex flex-col items-center justify-center gap-3 p-10 text-center text-sm text-ink-muted">
                            <p>Pratinjau PDF tidak didukung di peramban ini.</p>
                            <x-ui.button :href="$profile['cv_path']" target="_blank" rel="noopener noreferrer">
                                Buka atau unduh PDF
                            </x-ui.button>
                        </div>
                    </object>
                </div>
            @else
                <article class="mt-8 rounded-2xl border border-line bg-surface-raised p-6 shadow-soft sm:p-10 print-plain" data-reveal>
                <header class="flex flex-col gap-6 border-b border-line pb-8 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-4">
                        <x-ui.avatar :name="$profile['name']" :src="$profile['avatar']" size="lg" />
                        <div>
                            <h1 class="text-2xl font-semibold tracking-tight text-ink">{{ $profile['name'] }}</h1>
                            <p class="mt-1 text-sm font-medium text-accent">{{ $profile['major'] }}</p>
                            <p class="mt-1 text-sm text-ink-muted">{{ $profile['school_class'] }} · {{ $profile['location'] }}</p>
                        </div>
                    </div>

                    <ul class="space-y-1.5 text-sm text-ink-muted sm:text-right">
                        <li>
                            <a href="mailto:{{ $profile['email'] }}" class="break-all transition-colors hover:text-ink">{{ $profile['email'] }}</a>
                        </li>
                        <li>{{ $profile['availability'] }}</li>
                        <li class="font-mono text-xs text-ink-faint">Diperbarui {{ now()->translatedFormat('F Y') }}</li>
                    </ul>
                </header>

                <div class="mt-8 space-y-8">
                    <section>
                        <h2 class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Ringkasan</h2>
                        <p class="mt-3 max-w-prose text-pretty text-sm leading-7 text-ink-muted">{{ $profile['bio'] }}</p>
                        <p class="mt-2 max-w-prose text-pretty text-sm leading-7 text-ink-muted">{{ $profile['goals'] }}</p>
                    </section>

                    <section>
                        <h2 class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Pendidikan</h2>
                        <ul class="mt-3 space-y-4">
                            @foreach ($profile['education'] as $item)
                                <li class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-6">
                                    <div>
                                        <p class="text-sm font-semibold text-ink">{{ $item['title'] }}</p>
                                        <p class="text-sm text-ink-muted">{{ $item['organization'] }}</p>
                                        <p class="mt-1 max-w-xl text-xs leading-5 text-ink-muted">{{ $item['description'] }}</p>
                                    </div>
                                    <p class="flex-none font-mono text-[11px] text-ink-faint">{{ $item['period'] }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section>
                        <h2 class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Keahlian teknis</h2>
                        <ul class="mt-3 grid gap-x-8 gap-y-3 sm:grid-cols-2">
                            @foreach ($hardSkills as $skill)
                                <li>
                                    <div class="flex items-baseline justify-between gap-3">
                                        <span class="text-sm text-ink">{{ $skill['name'] }}</span>
                                        <span class="font-mono text-[11px] text-ink-faint">{{ $skill['value'] }}%</span>
                                    </div>
                                    <div class="mt-1.5 h-1 w-full overflow-hidden rounded-full bg-line">
                                        <div class="h-full rounded-full bg-accent" style="width: {{ $skill['value'] }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section>
                        <h2 class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Pengalaman</h2>
                        <ul class="mt-3 space-y-4">
                            @foreach (array_slice($portfolio['experiences'], 0, 4) as $experience)
                                <li>
                                    <div class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-6">
                                        <p class="text-sm font-semibold text-ink">{{ $experience['title'] }}</p>
                                        <p class="flex-none font-mono text-[11px] text-ink-faint">{{ $experience['period'] }}</p>
                                    </div>
                                    <p class="text-sm text-ink-muted">{{ $experience['organization'] }} · {{ $experience['type'] }}</p>
                                    <p class="mt-1 max-w-2xl text-xs leading-5 text-ink-muted">{{ $experience['description'] }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    <section>
                        <h2 class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Proyek pilihan</h2>
                        <ul class="mt-3 space-y-3">
                            @foreach ($topProjects as $project)
                                <li class="rounded-xl border border-line bg-surface p-4">
                                    <div class="flex flex-wrap items-baseline justify-between gap-2">
                                        <p class="text-sm font-semibold text-ink">{{ $project['title'] }}</p>
                                        <p class="font-mono text-[11px] text-ink-faint">{{ $project['category'] }} · {{ $project['year'] }}</p>
                                    </div>
                                    <p class="mt-1 text-xs leading-5 text-ink-muted">{{ $project['summary'] }}</p>
                                    <p class="mt-1.5 font-mono text-[10px] text-ink-faint">{{ implode(' · ', $project['tech']) }}</p>
                                </li>
                            @endforeach
                        </ul>
                    </section>

                    @if ($prestasi->isNotEmpty())
                        <section>
                            <h2 class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Prestasi</h2>
                            <ul class="mt-3 space-y-2">
                                @foreach ($prestasi as $item)
                                    <li class="flex flex-col gap-1 sm:flex-row sm:justify-between sm:gap-6">
                                        <p class="text-sm text-ink">{{ $item['title'] }} — <span class="text-ink-muted">{{ $item['issuer'] }}</span></p>
                                        <p class="flex-none font-mono text-[11px] text-ink-faint">{{ $item['year'] }}</p>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endif

                    <section>
                        <h2 class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Minat</h2>
                        <ul class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($profile['interests'] as $interest)
                                <li class="rounded-full border border-line bg-surface px-2.5 py-1 text-xs text-ink-muted">{{ $interest }}</li>
                            @endforeach
                        </ul>
                    </section>
                </div>

                <footer class="mt-10 flex flex-col gap-2 border-t border-line pt-6 text-xs text-ink-faint sm:flex-row sm:items-center sm:justify-between">
                    <p>{{ $profile['name'] }} A� {{ $profile['email'] }}</p>
                    <p class="font-mono">{{ $profile['major'] }} A� {{ $profile['school_class'] }}</p>
                </footer>
                </article>
            @endif
        </div>
    </section>
</x-layouts.public>
