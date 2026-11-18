@php
    $profile = $portfolio['profile'];
    $featured = collect($portfolio['projects'])->where('featured', true)->take(3);
    $hardSkills = collect($portfolio['skills']['hard'])->take(4);
    $featuredCertificates = collect($portfolio['certificates'])->take(3);
    $feedback = collect($portfolio['messages'])->where('approved', true)->sortByDesc('id')->values();
    $averageRating = $feedback->flatMap(fn (array $message) => array_values($message['ratings'] ?? []))->avg();
    $hasCv = ! empty($profile['cv_path']);
@endphp

<x-layouts.public description="{{ $profile['headline'] }}">
    <section class="section relative overflow-hidden pt-10 sm:pt-14">
        <div class="noise-overlay pointer-events-none absolute inset-0" aria-hidden="true" data-ambient></div>
        <div class="pointer-events-none absolute -right-24 top-8 h-72 w-72 rounded-full bg-accent-soft/70 blur-3xl" aria-hidden="true" data-ambient></div>
        <div class="pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-accent-soft/40 blur-3xl" aria-hidden="true" data-ambient></div>
        <svg class="pointer-events-none absolute right-[18%] top-16 hidden h-5 w-5 text-accent/30 md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true" data-ambient>
            <path d="M12 5v14M5 12h14" />
        </svg>
        <svg class="pointer-events-none absolute bottom-24 left-[6%] hidden h-4 w-4 text-accent/25 md:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true" data-ambient>
            <path d="M12 5v14M5 12h14" />
        </svg>

        <div class="container-page relative">
            <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                <div class="relative" data-reveal>
                    <span class="page-watermark page-watermark--hero" aria-hidden="true">
                        <span class="page-watermark-index">01</span>
                        <span class="page-watermark-label">Beranda</span>
                    </span>

                    <span class="inline-flex items-center gap-2 rounded-full border border-line bg-surface-raised px-3 py-1.5 font-mono text-[11px] uppercase tracking-[0.14em] text-ink-muted">
                        <span class="relative flex h-2 w-2">
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-success"></span>
                        </span>
                        {{ $profile['school_class'] }} · {{ $profile['major'] }}
                    </span>

                    <h1 class="mt-6 text-balance text-4xl font-semibold tracking-tight text-ink sm:text-5xl lg:text-[3.4rem] lg:leading-[1.05]">
                        Halo, saya {{ $profile['nickname'] }}.
                        <span class="text-gradient">Merancang web &amp; game</span> yang rapi dan berguna.
                    </h1>

                    <p class="mt-5 max-w-xl text-pretty text-base leading-7 text-ink-muted">
                        {{ $profile['headline'] }} Saat ini belajar di {{ $profile['school_class'] }} dengan fokus {{ $profile['major'] }}.
                    </p>

                    <div class="mt-8 flex flex-wrap items-center gap-3">
                        <x-ui.button :href="route('projects.index')" size="lg" class="glow-accent">
                            Lihat proyek
                            <x-ui.icon name="arrow-right" class="h-4 w-4" />
                        </x-ui.button>

                        @if ($hasCv)
                            <x-ui.button :href="route('cv')" variant="secondary" size="lg">
                                <x-ui.icon name="download" class="h-4 w-4" />
                                Unduh CV
                            </x-ui.button>
                        @else
                            <x-ui.button :href="route('cv')" variant="secondary" size="lg">
                                <x-ui.icon name="file-text" class="h-4 w-4" />
                                Lihat CV digital
                            </x-ui.button>
                        @endif
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-4 border-t border-line pt-6">
                        <x-ui.social-links :items="$portfolio['socials']" />
                        <span class="text-xs text-ink-faint">Terbuka untuk &amp; kolaborasi</span>
                    </div>
                </div>

                <div class="relative" data-reveal>
                    <div class="absolute -inset-4 -z-10 rounded-[2rem] bg-accent-soft/60 blur-2xl" aria-hidden="true"></div>

                    <div class="rounded-2xl border border-line bg-surface-raised p-5 shadow-lift sm:p-6">
                        <div class="flex items-start gap-4">
                            <x-ui.avatar :name="$profile['name']" :src="$profile['avatar']" size="xl" ring />

                            <div class="min-w-0 pt-1">
                                <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">Portfolio / 2026</p>
                                <p class="mt-1 text-lg font-semibold leading-tight text-ink">{{ $profile['name'] }}</p>
                                <p class="mt-1 text-sm text-ink-muted">{{ $profile['major'] }}</p>
                            </div>
                        </div>

                        <dl class="mt-6 grid grid-cols-2 gap-3">
                            @foreach ($portfolio['stats'] as $stat)
                                <div class="rounded-xl border border-line bg-surface p-3">
                                    <dt class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">{{ $stat['label'] }}</dt>
                                    <dd class="mt-1 text-lg font-semibold tracking-tight text-ink">{{ $stat['value'] }}</dd>
                                </div>
                            @endforeach
                        </dl>

                        <div class="mt-5 rounded-xl bg-surface p-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">Fokus saat ini</p>
                            <ul class="mt-3 flex flex-wrap gap-1.5">
                                @foreach (array_slice($profile['interests'], 0, 4) as $interest)
                                    <li class="rounded-full border border-line bg-surface-raised px-2.5 py-1 text-xs text-ink-muted">{{ $interest }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-3 flex items-center gap-2 text-xs text-ink-faint">
                                <x-ui.icon name="map-pin" class="h-3.5 w-3.5" />
                                {{ $profile['location'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="scroll-cue mt-12 flex justify-center" data-reveal aria-hidden="true">
                <span class="scroll-cue-target inline-flex h-9 w-9 items-center justify-center rounded-full border border-line bg-surface-raised text-ink-faint motion-reduce:!animate-none">
                    <x-ui.icon name="chevron-down" class="h-4 w-4" />
                </span>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <x-ui.section-heading
                    eyebrow="Proyek pilihan"
                    title="Beberapa hal yang saya bangun"
                    description="Proyek sekolah, eksperimen pribadi, dan alat kecil yang lahir dari masalah nyata di sekitar."
                    number="01"
                />

                <x-ui.button :href="route('projects.index')" variant="secondary" class="flex-none">
                    Semua proyek
                    <x-ui.icon name="arrow-right" class="h-4 w-4" />
                </x-ui.button>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach ($featured as $project)
                    <x-ui.project-card :project="$project" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="section border-y border-line bg-surface-raised">
        <div class="container-page grid gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
            <div data-reveal>
                <x-ui.section-heading
                    eyebrow="Keahlian"
                    title="Yang sedang saya dalami"
                    description="Bukan daftar panjang tanpa bukti. Ini area yang paling sering saya pakai untuk membangun proyek nyata."
                    number="02"
                />

                <div class="mt-6 flex flex-wrap gap-1.5">
                    @foreach ($portfolio['profile']['interests'] as $interest)
                        <span class="rounded-full border border-line bg-surface px-3 py-1.5 text-xs text-ink-muted">{{ $interest }}</span>
                    @endforeach
                </div>

                <x-ui.button :href="route('skills.index')" variant="secondary" class="mt-8">
                    Detail keahlian
                    <x-ui.icon name="arrow-right" class="h-4 w-4" />
                </x-ui.button>
            </div>

            <div class="space-y-5" data-reveal-group>
                @foreach ($hardSkills as $skill)
                    <x-ui.progress :label="$skill['name']" :value="$skill['value']" :note="$skill['note']" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
                <div data-reveal>
                    <x-ui.section-heading
                        eyebrow="Tentang"
                        title="Siswa yang suka merapikan hal kecil"
                        description="{{ $profile['bio'] }}"
                        number="03"
                    />

                    <x-ui.button :href="route('about')" variant="secondary" class="mt-6">
                        Kenali saya lebih jauh
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                </div>

                <div class="grid gap-4 sm:grid-cols-3" data-reveal-group>
                    @foreach ($profile['values'] as $value)
                        <x-ui.card class="h-full">
                            <x-ui.icon name="sparkles" class="h-5 w-5 text-accent" />
                            <h3 class="mt-4 text-sm font-semibold text-ink">{{ $value['title'] }}</h3>
                            <p class="mt-2 text-sm leading-6 text-ink-muted">{{ $value['description'] }}</p>
                        </x-ui.card>
                    @endforeach

                    <div class="rounded-2xl border border-line bg-ink p-6 text-surface sm:col-span-3">
                        <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-surface/60">Tujuan</p>
                        <p class="mt-3 text-pretty text-sm leading-7 text-surface/90">{{ $profile['goals'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section border-y border-line bg-surface-raised">
        <div class="container-page">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <x-ui.section-heading
                    eyebrow="Sertifikat &amp; prestasi"
                    title="Bukti belajar dan berkarya"
                    description="Sebagian kecil dari sertifikat, lomba, dan kegiatan yang membentuk cara saya bekerja."
                    number="04"
                />

                <x-ui.button :href="route('certificates.index')" variant="secondary" class="flex-none">
                    Semua sertifikat
                    <x-ui.icon name="arrow-right" class="h-4 w-4" />
                </x-ui.button>
            </div>

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
                @foreach ($featuredCertificates as $certificate)
                    <x-ui.certificate-card :certificate="$certificate" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <x-ui.section-heading
                    eyebrow="Kata mereka"
                    title="Testimoni pengunjung"
                    description="Kesan dan rating dari teman serta guru setelah mencoba website portfolio ini. Hanya testimoni yang disetujui admin yang ditampilkan."
                    number="05"
                />

                <x-ui.button :href="route('messages.create')" variant="secondary" class="flex-none">
                    <x-ui.icon name="message-square" class="h-4 w-4" />
                    Isi kesan &amp; pesan
                </x-ui.button>
            </div>

            <dl class="mt-8 grid gap-3 sm:grid-cols-2" data-reveal-group>
                <div class="rounded-xl border border-line bg-surface-raised p-4">
                    <dt class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">Total testimoni</dt>
                    <dd class="mt-1.5 text-lg font-semibold tracking-tight text-ink">{{ $feedback->count() }}</dd>
                    <p class="mt-2 text-xs text-ink-muted">Disetujui dan tampil di publik</p>
                </div>

                <div class="rounded-xl border border-line bg-surface-raised p-4">
                    <dt class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">Rata-rata rating</dt>
                    <dd class="mt-1.5 flex items-center gap-2">
                        <span class="text-lg font-semibold tracking-tight text-ink">{{ $averageRating ? number_format($averageRating, 1) : '�?"' }}</span>
                        <span class="text-xs text-ink-faint">/ 5</span>
                    </dd>
                    @if ($averageRating)
                        <x-ui.rating-stars :value="$averageRating" size="md" class="mt-1" />
                    @endif
                    <p class="mt-2 text-xs text-ink-muted">Dari semua testimoni disetujui</p>
                </div>
            </dl>

            <div class="mt-8">
                @if ($feedback->isNotEmpty())
                    <x-ui.testimonial-marquee :items="$feedback" />
                @else
                    <x-ui.empty-state icon="message-square" title="Belum ada testimoni" message="Testimoni akan tampil di sini setelah disetujui admin." />
                @endif
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <div class="corner-brackets relative" data-reveal>
                <div class="relative overflow-hidden rounded-2xl border border-line bg-ink px-6 py-12 text-surface sm:px-12">
                    <div class="ambient-grid ambient-grid--faint pointer-events-none absolute inset-0" aria-hidden="true" data-ambient></div>
                    <div class="noise-overlay pointer-events-none absolute inset-0" aria-hidden="true" data-ambient></div>
                    <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-accent/30 blur-3xl" aria-hidden="true" data-ambient></div>

                <div class="relative max-w-2xl">
                    <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-surface/60">Mari berkolaborasi</p>
                    <h2 class="mt-3 text-balance text-2xl font-semibold tracking-tight sm:text-3xl">
                        Punya proyek kecil atau ingin berbagi ide?
                    </h2>
                    <p class="mt-3 text-pretty text-sm leading-7 text-surface/80">
                        Saya senang belajar lewat proyek nyata. Kirim pesan singkat atau sapa lewat email — saya akan balas secepatnya.
                    </p>

                    <div class="mt-7 flex flex-wrap items-center gap-3">
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 rounded-xl bg-surface px-5 py-3 text-sm font-medium text-ink transition-colors duration-200 hover:bg-surface/90">
                            Hubungi saya
                            <x-ui.icon name="arrow-right" class="h-4 w-4" />
                        </a>
                        <a href="{{ route('messages.create') }}" class="inline-flex items-center gap-2 rounded-xl border border-surface/25 px-5 py-3 text-sm font-medium text-surface transition-colors duration-200 hover:border-surface/50">
                            <x-ui.icon name="message-square" class="h-4 w-4" />
                            Kesan &amp; pesan
                        </a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
