@php
    $profile = $portfolio['profile'];
@endphp

<x-layouts.public title="Kontak" description="Hubungi {{ $profile['name'] }} untuk kolaborasi, atau pertanyaan seputar proyek.">
    <section class="section">
        <div class="container-page">
            <x-ui.page-header
                eyebrow="Kontak"
                title="Mari bicara soal ide atau proyek"
                subtitle="Sapa langsung lewat email atau media sosial. Untuk masukan soal website ini, gunakan halaman kesan dan pesan."
            />

            <div class="mt-10 grid gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:gap-12">
                <div class="space-y-5">
                    <x-ui.card data-reveal>
                        <h2 class="text-sm font-semibold text-ink">Informasi kontak</h2>
                        <ul class="mt-4 space-y-4 text-sm">
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-accent-soft text-accent">
                                    <x-ui.icon name="mail" class="h-4 w-4" />
                                </span>
                                <div class="min-w-0">
                                    <p class="text-xs text-ink-faint">Email</p>
                                    <a href="mailto:{{ $profile['email'] }}" class="break-all text-ink transition-colors hover:text-accent">{{ $profile['email'] }}</a>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-accent-soft text-accent">
                                    <x-ui.icon name="map-pin" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="text-xs text-ink-faint">Lokasi</p>
                                    <p class="text-ink">{{ $profile['location'] }}</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-8 w-8 flex-none items-center justify-center rounded-lg bg-accent-soft text-accent">
                                    <x-ui.icon name="clock" class="h-4 w-4" />
                                </span>
                                <div>
                                    <p class="text-xs text-ink-faint">Ketersediaan</p>
                                    <p class="text-ink">{{ $profile['availability'] }}</p>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-5 border-t border-line pt-4">
                            <p class="text-xs text-ink-faint">Media sosial</p>
                            <x-ui.social-links :items="$portfolio['socials']" show-label class="mt-2.5" />
                        </div>
                    </x-ui.card>

                    <x-ui.card data-reveal>
                        <h2 class="text-sm font-semibold text-ink">Waktu balasan</h2>
                        <p class="mt-2 text-sm leading-6 text-ink-muted">
                            Biasanya saya membalas dalam 1–2 hari sekolah. Instagram adalah jalur tercepat untuk urusan mendesak.
                        </p>
                        <div class="mt-4 flex items-center gap-2 rounded-xl bg-surface p-3 text-xs text-ink-muted">
                            <span class="relative flex h-2 w-2">
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-success"></span>
                            </span>
                            Terbuka untuk kolaborasi baru
                        </div>
                    </x-ui.card>
                </div>

                <div class="space-y-5">
                    <x-ui.card class="h-fit" data-reveal>
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-accent-soft text-accent">
                            <x-ui.icon name="message-square" class="h-5 w-5" />
                        </span>
                        <h2 class="mt-4 text-lg font-semibold tracking-tight text-ink">Sudah mencoba website ini?</h2>
                        <p class="mt-2 text-sm leading-7 text-ink-muted">
                            Bagikan pengalaman Anda lewat halaman kesan dan pesan. Beri rating untuk desain, navigasi, kejelasan isi, dan kenyamanan, lalu tulis saran yang bisa saya tindak lanjuti.
                        </p>

                        <ul class="mt-4 space-y-2.5 text-sm text-ink-muted">
                            @foreach ([
                                'Rating 1–5 untuk empat aspek utama.',
                                'Bisa dikirim sebagai teman atau guru.',
                            ] as $point)
                                <li class="flex items-start gap-2.5">
                                    <x-ui.icon name="check" class="mt-0.5 h-4 w-4 flex-none text-success" />
                                    <span class="leading-6">{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <x-ui.button :href="route('messages.create')" class="mt-6 w-full justify-center">
                            Isi kesan &amp; pesan
                            <x-ui.icon name="arrow-right" class="h-4 w-4" />
                        </x-ui.button>
                    </x-ui.card>

                    <x-ui.card data-reveal>
                        <h2 class="text-sm font-semibold text-ink">Yang bisa didiskusikan</h2>
                        <ul class="mt-3 divide-y divide-line text-sm">
                            @foreach ([
                                ['icon' => 'code', 'label' => 'Kolaborasi proyek sekolah'],
                                ['icon' => 'message-square', 'label' => 'Masukan desain dan aksesibilitas'],
                                ['icon' => 'mail', 'label' => 'Pertanyaan seputar proyek di portfolio'],
                            ] as $item)
                                <li class="flex items-center gap-3 py-2.5">
                                    <x-ui.icon :name="$item['icon']" class="h-4 w-4 flex-none text-accent" />
                                    <span class="text-ink-muted">{{ $item['label'] }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <p class="mt-4 text-xs leading-6 text-ink-faint">
                            Ada pertanyaan lain? Kirim email ke
                            <a href="mailto:{{ $profile['email'] }}" class="font-medium text-accent">{{ $profile['email'] }}</a>.
                        </p>
                    </x-ui.card>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>
