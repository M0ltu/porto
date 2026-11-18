<x-layouts.public title="Halaman tidak ditemukan">
    <section class="section">
        <div class="container-page">
            <div class="mx-auto max-w-xl py-10 text-center">
                <p class="font-mono text-[11px] uppercase tracking-[0.22em] text-ink-faint">Error 404</p>
                <h1 class="mt-4 text-balance text-4xl font-semibold tracking-tight text-ink">Halaman ini tidak ada</h1>
                <p class="mt-4 text-pretty text-sm leading-7 text-ink-muted">
                    Mungkin tautannya salah ketik, sudah dipindahkan, atau memang belum pernah dibuat. Coba kembali ke beranda atau jelajahi halaman lain.
                </p>

                <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
                    <x-ui.button :href="route('home')">
                        Ke beranda
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                    <x-ui.button :href="route('projects.index')" variant="secondary">Lihat proyek</x-ui.button>
                    <x-ui.button :href="route('contact')" variant="ghost">Hubungi saya</x-ui.button>
                </div>

                <div class="mx-auto mt-10 max-w-md overflow-hidden rounded-2xl border border-line bg-surface-raised text-left shadow-soft" aria-hidden="true">
                    <div class="flex items-center gap-1.5 border-b border-line bg-surface px-4 py-2.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-danger/70"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-ink-faint/50"></span>
                        <span class="h-2.5 w-2.5 rounded-full bg-success/70"></span>
                        <span class="ml-2 font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">terminal</span>
                    </div>
                    <div class="space-y-1 px-4 py-3 font-mono text-[12px] leading-6">
                        <p class="text-ink-muted"><span class="text-success">$</span> git checkout <span class="text-ink">halaman-ini</span></p>
                        <p class="text-danger">fatal: path 'halaman-ini' tidak ditemukan di server ini</p>
                        <p class="text-ink-muted"><span class="text-success">$</span> <span class="inline-block h-3.5 w-2 translate-y-0.5 bg-ink-faint/60"></span></p>
                    </div>
                </div>

                <div class="mt-10 rounded-2xl border border-line bg-surface-raised p-5 text-left shadow-soft">
                    <p class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint">Halaman yang sering dibuka</p>
                    <ul class="mt-3 grid gap-2 sm:grid-cols-2">
                        @foreach ([
                            ['label' => 'Tentang saya', 'route' => 'about'],
                            ['label' => 'Keahlian', 'route' => 'skills.index'],
                            ['label' => 'Sertifikat', 'route' => 'certificates.index'],
                            ['label' => 'Pengalaman', 'route' => 'experience.index'],
                            ['label' => 'Kesan & pesan', 'route' => 'messages.create'],
                            ['label' => 'CV digital', 'route' => 'cv'],
                        ] as $item)
                            <li>
                                <a href="{{ route($item['route']) }}" class="flex items-center justify-between gap-2 rounded-xl border border-line bg-surface px-3 py-2.5 text-sm text-ink-muted transition-colors hover:text-ink">
                                    {{ $item['label'] }}
                                    <x-ui.icon name="chevron-right" class="h-3.5 w-3.5 opacity-60" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
