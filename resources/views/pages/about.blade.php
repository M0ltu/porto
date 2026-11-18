@php
    $profile = $portfolio['profile'];
@endphp

<x-layouts.public title="Tentang" description="Profil, pendidikan, nilai kerja, dan tujuan {{ $profile['name'] }}.">
    <section class="section">
        <div class="container-page">
            <x-ui.page-header
                eyebrow="Tentang"
                title="Belajar sambil membangun hal yang berguna"
                subtitle="{{ $profile['bio'] }}"
            />

            <div class="mt-10 grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
                <div class="lg:sticky lg:top-24 lg:self-start" data-reveal>
                    <div class="rounded-2xl border border-line bg-surface-raised p-6 shadow-soft">
                        <x-ui.avatar :name="$profile['name']" :src="$profile['avatar']" size="xl" />

                        <h2 class="mt-5 text-lg font-semibold text-ink">{{ $profile['name'] }}</h2>
                        <p class="mt-1 text-sm text-ink-muted">{{ $profile['school_class'] }} · {{ $profile['major'] }}</p>

                        <dl class="mt-6 space-y-3 border-t border-line pt-5 text-sm">
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-ink-faint">Domisili</dt>
                                <dd class="text-right text-ink">{{ $profile['location'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-ink-faint">Status</dt>
                                <dd class="text-right text-ink">Siswa aktif</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <dt class="text-ink-faint">Ketersediaan</dt>
                                <dd class="max-w-[60%] text-right text-ink">{{ $profile['availability'] }}</dd>
                            </div>
                        </dl>

                        <div class="mt-6 flex gap-2">
                            <x-ui.button :href="route('contact')" class="flex-1">
                                Hubungi
                                <x-ui.icon name="arrow-right" class="h-4 w-4" />
                            </x-ui.button>
                            <x-ui.button :href="route('cv')" variant="secondary">
                                CV
                            </x-ui.button>
                        </div>

                        <x-ui.social-links :items="$portfolio['socials']" class="mt-4" />
                    </div>
                </div>

                <div class="space-y-14">
                    <div data-reveal>
                        <h2 class="text-lg font-semibold text-ink">Cerita singkat</h2>
                        <div class="prose-portfolio mt-4">
                            <p>
                                Ketertarikan saya pada teknologi dimulai dari rasa penasaran: bagaimana sebuah tombol bisa
                                memindahkan orang ke halaman lain, dan bagaimana data sederhana bisa berubah menjadi
                                informasi yang membantu.
                            </p>
                            <p>
                                Sekarang, saya banyak belajar melalui proyek sekolah. Mulai dari jurnal digital, papan
                                tugas kelas, sampai game kecil untuk melatih hitung cepat. Setiap proyek saya usahakan
                                selesai dalam bentuk yang bisa dipakai, bukan hanya prototipe.
                            </p>
                            <p>
                                Di luar koding, saya suka merapikan dokumentasi, mencoba alur baru di Figma, dan
                                memperhatikan hal-hal kecil yang membuat antarmuka terasa nyaman — terutama untuk
                                pengguna keyboard dan pembaca layar.
                            </p>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-ink">Pendidikan</h2>
                        <ol class="mt-5 space-y-6" data-reveal-group>
                            @foreach ($profile['education'] as $index => $item)
                                <x-ui.timeline-item
                                    :period="$item['period']"
                                    :title="$item['title']"
                                    :organization="$item['organization']"
                                    :description="$item['description']"
                                    :current="$index === 0"
                                />
                            @endforeach
                        </ol>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-ink">Nilai yang saya pegang</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2" data-reveal-group>
                            @foreach ($profile['values'] as $value)
                                <x-ui.card class="h-full">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-accent-soft text-accent">
                                        <x-ui.icon name="check-circle" class="h-4 w-4" />
                                    </span>
                                    <h3 class="mt-3 text-sm font-semibold text-ink">{{ $value['title'] }}</h3>
                                    <p class="mt-1.5 text-sm leading-6 text-ink-muted">{{ $value['description'] }}</p>
                                </x-ui.card>
                            @endforeach
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2" data-reveal-group>
                        <x-ui.card>
                            <x-ui.icon name="heart" class="h-5 w-5 text-accent" />
                            <h3 class="mt-3 text-sm font-semibold text-ink">Minat</h3>
                            <ul class="mt-3 flex flex-wrap gap-1.5">
                                @foreach ($profile['interests'] as $interest)
                                    <li class="rounded-full bg-surface px-2.5 py-1 text-xs text-ink-muted">{{ $interest }}</li>
                                @endforeach
                            </ul>
                        </x-ui.card>

                        <x-ui.card>
                            <x-ui.icon name="target" class="h-5 w-5 text-accent" />
                            <h3 class="mt-3 text-sm font-semibold text-ink">Tujuan</h3>
                            <p class="mt-2 text-sm leading-6 text-ink-muted">{{ $profile['goals'] }}</p>
                        </x-ui.card>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section border-t border-line bg-surface-raised">
        <div class="container-page">
            <div class="flex flex-col items-start justify-between gap-6 rounded-2xl border border-line bg-surface p-8 sm:flex-row sm:items-center" data-reveal>
                <div>
                    <h2 class="text-xl font-semibold tracking-tight text-ink">Ingin melihat hasil kerjanya?</h2>
                    <p class="mt-1.5 text-sm text-ink-muted">Lanjutkan ke halaman proyek atau unduh CV digital.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <x-ui.button :href="route('projects.index')">
                        Lihat proyek
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>
                    <x-ui.button :href="route('cv')" variant="secondary">CV digital</x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
