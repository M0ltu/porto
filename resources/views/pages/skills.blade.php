@php
    $profile = $portfolio['profile'];
    $hard = $portfolio['skills']['hard'];
    $soft = $portfolio['skills']['soft'];
    $averageHard = collect($hard)->avg('value');
    $averageSoft = collect($soft)->avg('value');
@endphp

<x-layouts.public title="Keahlian" description="Keahlian teknis dan interpersonal {{ $profile['name'] }} beserta tingkat penguasaannya.">
    <section class="section">
        <div class="container-page">
            <x-ui.page-header
                eyebrow="Keahlian"
                title="Kemampuan teknis dan cara bekerja"
                subtitle="Penilaian ini gambaran jujur dari proyek yang sudah saya kerjakan, bukan klaim penguasaan penuh. Setiap area terus saya latih lewat proyek nyata."
            >
                <x-slot name="actions">
                    <x-ui.button :href="route('projects.index')" variant="secondary">
                        Lihat buktinya di proyek
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                </x-slot>
            </x-ui.page-header>

            <div class="mt-8 grid gap-4 sm:grid-cols-3" data-reveal-group>
                <x-ui.stat label="Rata-rata teknis" :value="round($averageHard).'%'" hint="Dari 6 area utama" />
                <x-ui.stat label="Rata-rata interpersonal" :value="round($averageSoft).'%'" hint="Dari 6 kebiasaan kerja" />
                <x-ui.stat label="Fokus utama" value="Web dan Game" hint="Laravel, JavaScript" />
            </div>

            <div class="mt-12 grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">
                <div data-reveal>
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Hard skill</p>
                            <h2 class="mt-2 text-xl font-semibold tracking-tight text-ink">Teknis</h2>
                        </div>
                        <x-ui.badge variant="accent">{{ count($hard) }} area</x-ui.badge>
                    </div>

                    <div class="mt-6 space-y-6 rounded-2xl border border-line bg-surface-raised p-6 shadow-soft">
                        @foreach ($hard as $skill)
                            <x-ui.progress :label="$skill['name']" :value="$skill['value']" :note="$skill['note']" />
                        @endforeach
                    </div>
                </div>

                <div data-reveal>
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">Soft skill</p>
                            <h2 class="mt-2 text-xl font-semibold tracking-tight text-ink">Cara bekerja</h2>
                        </div>
                        <x-ui.badge variant="neutral">{{ count($soft) }} kebiasaan</x-ui.badge>
                    </div>

                    <div class="mt-6 space-y-6 rounded-2xl border border-line bg-surface-raised p-6 shadow-soft">
                        @foreach ($soft as $skill)
                            <x-ui.progress :label="$skill['name']" :value="$skill['value']" :note="$skill['note']" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section border-y border-line bg-surface-raised">
        <div class="container-page">
            <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16">
                <div data-reveal>
                    <x-ui.section-heading
                        eyebrow="Alat &amp; teknologi"
                        title="Yang sering saya pakai"
                        description="Bukan daftar lengkap, hanya alat yang benar-benar muncul di proyek dan catatan belajar saya."
                    />

                    <ul class="mt-6 flex flex-wrap gap-2">
                        @foreach (['Laravel', 'PHP', 'Blade', 'Tailwind CSS', 'Alpine.js', 'JavaScript', 'SQLite', 'MySQL', 'Git', 'Figma', 'Canvas API'] as $tool)
                            <li class="rounded-xl border border-line bg-surface px-3 py-2 font-mono text-xs text-ink-muted">{{ $tool }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="grid gap-4 sm:grid-cols-3" data-reveal-group>
                    @foreach ([
                        ['icon' => 'book-open', 'title' => 'Baca dokumentasi', 'text' => 'Mulai dari sumber resmi, lalu catat bagian yang benar-benar dipakai.'],
                        ['icon' => 'wrench', 'title' => 'Bangun proyek kecil', 'text' => 'Setiap konsep baru saya coba dalam satu halaman atau satu fitur utuh.'],
                        ['icon' => 'users', 'title' => 'Minta masukan', 'text' => 'Uji ke teman atau guru, lalu perbaiki bagian yang paling membingungkan.'],
                    ] as $step)
                        <x-ui.card class="h-full">
                            <x-ui.icon :name="$step['icon']" class="h-5 w-5 text-accent" />
                            <h3 class="mt-4 text-sm font-semibold text-ink">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-sm leading-6 text-ink-muted">{{ $step['text'] }}</p>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container-page">
            <div class="flex flex-col items-start justify-between gap-6 rounded-2xl border border-line bg-surface-raised p-8 shadow-soft sm:flex-row sm:items-center" data-reveal>
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-ink">Butuh orang yang bisa belajar cepat?</h2>
                    <p class="mt-1.5 max-w-xl text-sm leading-6 text-ink-muted">
                        Saya terbuka untuk kolaborasi, atau pekerjaan kecil yang bisa dikerjakan sambil belajar.
                    </p>
                </div>
                <x-ui.button :href="route('contact')" class="flex-none">
                    Hubungi saya
                    <x-ui.icon name="arrow-right" class="h-4 w-4" />
                </x-ui.button>
            </div>
        </div>
    </section>
</x-layouts.public>
