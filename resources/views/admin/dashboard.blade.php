@php
    $projects = collect($portfolio['projects']);
    $certificates = collect($portfolio['certificates']);
    $experiences = collect($portfolio['experiences']);
    $messages = collect($portfolio['messages']);
    $approved = $messages->where('approved', true)->sortByDesc('id')->values();
    $pending = $messages->where('approved', false)->sortByDesc('id')->values();
    $averageRating = $approved->flatMap(fn (array $message) => array_values($message['ratings'] ?? []))->avg();

    $profileReady = ! empty($portfolio['profile']['name']) && ! empty($portfolio['profile']['headline']) && ! empty($portfolio['profile']['bio']);

    $stats = [
        ['label' => 'Proyek', 'value' => $projects->count(), 'hint' => $projects->where('featured', true)->count().' unggulan', 'icon' => 'folder', 'route' => 'admin.projects.index'],
        ['label' => 'Sertifikat', 'value' => $certificates->count(), 'hint' => $certificates->where('type', 'Prestasi')->count().' prestasi', 'icon' => 'award', 'route' => 'admin.certificates.index'],
        ['label' => 'Testimoni disetujui', 'value' => $approved->count(), 'hint' => $pending->count().' menunggu tinjauan', 'icon' => 'quote', 'route' => 'admin.testimonials.index'],
        ['label' => 'Menunggu tinjauan', 'value' => $pending->count(), 'hint' => $messages->count().' total kesan masuk', 'icon' => 'message-square', 'route' => 'admin.testimonials.index'],
    ];

    $readiness = [
        ['label' => 'Proyek dengan repo/demo', 'value' => $projects->filter(fn ($p) => ! empty($p['repo_url']) || ! empty($p['demo_url']))->count(), 'total' => $projects->count()],
        ['label' => 'Sertifikat terdokumentasi', 'value' => $certificates->filter(fn ($c) => ! empty($c['image']))->count(), 'total' => $certificates->count()],
        ['label' => 'Testimoni disetujui', 'value' => $approved->count(), 'total' => $messages->count()],
        ['label' => 'Profil & CV', 'value' => (int) $profileReady + (int) ! empty($portfolio['profile']['cv_path']), 'total' => 2],
    ];
@endphp

<x-layouts.admin title="Ringkasan" subtitle="Kondisi konten portfolio hari ini. Semua angka berasal dari database.">
    <x-slot name="actions">
        <x-ui.button :href="route('home')" variant="secondary" size="sm">
            <x-ui.icon name="external-link" class="h-4 w-4" />
            Buka situs
        </x-ui.button>
        <x-ui.button :href="route('admin.projects.create')" size="sm">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Proyek baru
        </x-ui.button>
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($stats as $stat)
            <a href="{{ route($stat['route']) }}" class="group rounded-2xl border border-line bg-surface-raised p-5 shadow-soft transition-all duration-200 hover:-translate-y-0.5 hover:border-ink-faint hover:shadow-lift">
                <div class="flex items-start justify-between gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-accent-soft text-accent">
                        <x-ui.icon :name="$stat['icon']" class="h-4 w-4" />
                    </span>
                    <x-ui.icon name="chevron-right" class="h-4 w-4 text-ink-faint transition-transform duration-200 group-hover:translate-x-0.5 motion-reduce:transform-none" />
                </div>
                <p class="mt-4 text-2xl font-semibold tracking-tight text-ink">{{ $stat['value'] }}</p>
                <p class="mt-0.5 text-sm text-ink-muted">{{ $stat['label'] }}</p>
                <p class="mt-1 font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">{{ $stat['hint'] }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_0.65fr]">
        <div class="space-y-6">
            <x-ui.card padding="p-0">
                <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-4">
                    <div>
                        <h2 class="text-sm font-semibold text-ink">Testimoni menunggu tinjauan</h2>
                        <p class="mt-0.5 text-xs text-ink-faint">{{ $pending->count() }} menunggu · {{ $approved->count() }} disetujui · rata-rata {{ $averageRating ? number_format($averageRating, 1) : '—' }}/5</p>
                    </div>
                    <a href="{{ route('admin.testimonials.index') }}" class="text-xs font-medium text-accent hover:underline">Kelola testimoni</a>
                </div>

                <ul class="divide-y divide-line">
                    @forelse ($pending->take(4) as $message)
                        <li class="flex items-start gap-3 px-5 py-4">
                            <x-ui.avatar :name="$message['name']" size="sm" />
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-sm font-medium text-ink">{{ $message['name'] }}</p>
                                    <span class="rounded-full bg-accent-soft px-1.5 py-0.5 font-mono text-[9px] uppercase tracking-[0.1em] text-accent">Menunggu</span>
                                </div>
                                <p class="text-xs text-ink-faint">{{ ($message['relation'] ?? '') === 'guru' ? 'Guru' : 'Teman' }} · {{ $message['date'] }}</p>
                                <p class="mt-0.5 line-clamp-2 text-xs text-ink-faint">{{ $message['body'] }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="px-5 py-8 text-center text-sm text-ink-muted">Tidak ada testimoni yang menunggu tinjauan.</li>
                    @endforelse
                </ul>
            </x-ui.card>

            <x-ui.card padding="p-0">
                <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-4">
                    <div>
                        <h2 class="text-sm font-semibold text-ink">Proyek terbaru</h2>
                        <p class="mt-0.5 text-xs text-ink-faint">Diurutkan dari tahun terbaru</p>
                    </div>
                    <a href="{{ route('admin.projects.index') }}" class="text-xs font-medium text-accent hover:underline">Kelola proyek</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-line font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">
                                <th scope="col" class="px-5 py-2.5 font-medium">Proyek</th>
                                <th scope="col" class="px-3 py-2.5 font-medium">Kategori</th>
                                <th scope="col" class="px-3 py-2.5 font-medium">Tahun</th>
                                <th scope="col" class="px-5 py-2.5 text-right font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line">
                            @foreach ($projects->sortByDesc('year')->take(5) as $project)
                                <tr class="transition-colors hover:bg-surface">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('projects.show', $project['slug']) }}" class="font-medium text-ink hover:text-accent">{{ $project['title'] }}</a>
                                        <p class="font-mono text-[10px] text-ink-faint">{{ $project['slug'] }}</p>
                                    </td>
                                    <td class="px-3 py-3 text-ink-muted">{{ $project['category'] }}</td>
                                    <td class="px-3 py-3 font-mono text-xs text-ink-muted">{{ $project['year'] }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <x-ui.badge :variant="$project['featured'] ? 'accent' : 'neutral'">{{ $project['featured'] ? 'Unggulan' : 'Reguler' }}</x-ui.badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <div class="space-y-6">
            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Kelengkapan konten</h2>
                <p class="mt-1 text-xs leading-5 text-ink-faint">Perkiraan kesiapan konten publik.</p>

                <div class="mt-5 space-y-4">
                    @foreach ($readiness as $item)
                        @php $percent = $item['total'] > 0 ? round(($item['value'] / $item['total']) * 100) : 0; @endphp
                        <div>
                            <div class="flex items-baseline justify-between gap-3">
                                <span class="text-xs text-ink-muted">{{ $item['label'] }}</span>
                                <span class="font-mono text-[10px] text-ink-faint">{{ $item['value'] }}/{{ $item['total'] }}</span>
                            </div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-line">
                                <div class="h-full rounded-full bg-accent" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (! $profileReady || empty($portfolio['profile']['cv_path']))
                    <div class="mt-5 rounded-xl border border-line bg-surface p-3.5 text-xs leading-5 text-ink-muted">
                        <span class="inline-flex items-center gap-1.5 font-medium text-ink">
                            <x-ui.icon name="info" class="h-3.5 w-3.5 text-accent" />
                            Profil / CV perlu dilengkapi
                        </span>
                        <p class="mt-1.5">Lengkapi profil dan unggah CV PDF agar halaman publik siap dibagikan.</p>
                        <a href="{{ route('admin.settings') }}" class="mt-2 inline-flex items-center gap-1 font-medium text-accent hover:underline">
                            Buka pengaturan
                            <x-ui.icon name="arrow-right" class="h-3 w-3" />
                        </a>
                    </div>
                @endif
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Aksi cepat</h2>
                <div class="mt-4 space-y-2">
                    <a href="{{ route('admin.projects.create') }}" class="flex items-center gap-3 rounded-xl border border-line bg-surface px-3 py-2.5 text-sm text-ink-muted transition-colors hover:text-ink">
                        <x-ui.icon name="plus" class="h-4 w-4 text-accent" />
                        Tambah proyek
                    </a>
                    <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 rounded-xl border border-line bg-surface px-3 py-2.5 text-sm text-ink-muted transition-colors hover:text-ink">
                        <x-ui.icon name="quote" class="h-4 w-4 text-accent" />
                        Tinjau testimoni
                        @if ($pending->count())
                            <span class="ml-auto rounded-full bg-danger px-1.5 py-0.5 font-mono text-[10px] text-white">{{ $pending->count() }}</span>
                        @endif
                    </a>
                    <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 rounded-xl border border-line bg-surface px-3 py-2.5 text-sm text-ink-muted transition-colors hover:text-ink">
                        <x-ui.icon name="settings" class="h-4 w-4 text-accent" />
                        Perbarui profil
                    </a>
                </div>
            </x-ui.card>

            <x-ui.card>
                <h2 class="text-sm font-semibold text-ink">Testimoni terbaru</h2>
                <ol class="mt-4 space-y-3">
                    @forelse ($approved->take(4) as $message)
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1.5 h-1.5 w-1.5 flex-none rounded-full bg-accent"></span>
                            <div class="min-w-0">
                                <p class="text-xs leading-5 text-ink-muted line-clamp-2">{{ $message['body'] }}</p>
                                <p class="font-mono text-[10px] text-ink-faint">{{ $message['name'] }} · {{ $message['date'] }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-xs leading-5 text-ink-faint">Belum ada testimoni disetujui.</li>
                    @endforelse
                </ol>
            </x-ui.card>
        </div>
    </div>
</x-layouts.admin>
