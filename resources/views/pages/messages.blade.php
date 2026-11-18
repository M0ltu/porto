@php
    $profile = $portfolio['profile'];
    $approved = collect($portfolio['messages'])->where('approved', true)->sortByDesc('id')->take(3)->values();
    $scrollDuration = max(18, min(60, $approved->count() * 8));
@endphp

<x-layouts.public title="Kesan & Pesan" description="Kirim kesan setelah mencoba website portfolio {{ $profile['name'] }}.">
    <section class="section">
        <div class="container-page">
            <x-ui.page-header
                eyebrow="Kesan &amp; pesan"
                title="Bagaimana pengalaman Anda memakai situs ini?"
                subtitle="Tulis kesan atau saran. Setiap kiriman ditinjau admin sebelum tampil sebagai testimoni."
            />

            <div class="mt-10 grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:gap-12">
                <x-ui.card data-reveal>
                    @if (session('success'))
                        <div class="mb-5 rounded-xl border border-success bg-success-soft px-4 py-3 text-sm text-success" role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form class="space-y-5" action="{{ route('messages.store') }}" method="POST" novalidate>
                        @csrf

                        <input type="text" name="website" value="" class="hidden" tabindex="-1" autocomplete="off" aria-hidden="true">

                        <x-ui.field label="Nama" for="message-name" required>
                            <x-ui.input id="message-name" name="name" type="text" required placeholder="Nama atau inisial" autocomplete="name" :value="old('name')" />
                            <x-input-error :messages="$errors->get('name')" />
                        </x-ui.field>

                        <fieldset>
                            <legend class="block text-sm font-medium text-ink">
                                Peran
                                <span class="ml-0.5 text-danger" aria-hidden="true">*</span>
                                <span class="sr-only">(wajib diisi)</span>
                            </legend>

                            <div class="mt-1.5 grid grid-cols-2 gap-2">
                                <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-line bg-surface px-3.5 py-2.5 text-sm text-ink-muted transition-colors duration-200 has-[:checked]:border-accent has-[:checked]:bg-accent-soft has-[:checked]:font-medium has-[:checked]:text-accent">
                                    <input type="radio" name="relation" value="teman" class="sr-only" required @checked(old('relation') === 'teman')>
                                    <x-ui.icon name="users" class="h-4 w-4" />
                                    Teman
                                </label>

                                <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-line bg-surface px-3.5 py-2.5 text-sm text-ink-muted transition-colors duration-200 has-[:checked]:border-accent has-[:checked]:bg-accent-soft has-[:checked]:font-medium has-[:checked]:text-accent">
                                    <input type="radio" name="relation" value="guru" class="sr-only" required @checked(old('relation') === 'guru')>
                                    <x-ui.icon name="graduation-cap" class="h-4 w-4" />
                                    Guru
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('relation')" />
                        </fieldset>

                        <fieldset class="space-y-4">
                            <legend class="block text-sm font-medium text-ink">
                                Penilaian per aspek
                                <span class="ml-0.5 text-danger" aria-hidden="true">*</span>
                                <span class="sr-only">(wajib diisi)</span>
                            </legend>

                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach ($portfolio['feedback_aspects'] as $key => $label)
                                    <div>
                                        <x-ui.rating-input :name="'ratings['.$key.']'" :label="$label" />
                                        <x-input-error :messages="$errors->get('ratings.'.$key)" />
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('ratings')" />
                        </fieldset>

                        <x-ui.field label="Kesan dan pesan" for="message-body" hint="Maksimal 500 karakter. Ceritakan pengalaman saat memakai situs ini." required>
                            <x-ui.textarea id="message-body" name="body" rows="6" maxlength="500" required placeholder="Apa yang paling membantu, dan bagian mana yang bisa diperbaiki?">{{ old('body') }}</x-ui.textarea>
                            <x-input-error :messages="$errors->get('body')" />
                        </x-ui.field>

                        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-line pt-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">Kesan ditinjau admin sebelum tampil publik</p>
                            <x-ui.button type="submit">
                                Kirim kesan
                                <x-ui.icon name="send" class="h-4 w-4" />
                            </x-ui.button>
                        </div>
                    </form>
                </x-ui.card>

                <aside>
                    <div data-reveal data-marquee>
                        <h2 class="text-sm font-semibold text-ink">Testimoni terbaru</h2>
                        <p class="mt-1 text-xs text-ink-faint">Bergulir otomatis dari bawah ke atas. Gunakan tombol Jeda atau arahkan kursor untuk berhenti.</p>

                        @if ($approved->isNotEmpty())
                            <div class="mt-3 flex justify-end">
                                <button
                                    type="button"
                                    data-marquee-toggle
                                    aria-controls="testimonial-marquee-vertical"
                                    aria-pressed="false"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-surface-raised px-2.5 py-1.5 text-xs text-ink-muted transition-colors duration-200 hover:text-ink"
                                >
                                    <x-ui.icon name="pause" data-marquee-icon="pause" class="h-3.5 w-3.5" />
                                    <x-ui.icon name="play" data-marquee-icon="play" class="hidden h-3.5 w-3.5" />
                                    <span data-marquee-label>Jeda guliran</span>
                                </button>
                            </div>

                            <div
                                id="testimonial-marquee-vertical"
                                class="marquee-y mt-2 h-[32rem] rounded-2xl"
                                data-marquee-viewport
                                tabindex="0"
                                role="region"
                                aria-label="Testimoni terbaru, bergulir otomatis dari bawah ke atas. Gunakan tombol Jeda atau fokuskan area ini untuk menghentikan guliran."
                                style="--marquee-duration: {{ $scrollDuration }}s"
                            >
                                <div class="marquee-y-track">
                                    @foreach ([0, 1] as $group)
                                        <div
                                            class="flex flex-col gap-3 pb-3 {{ $group === 1 ? 'marquee-y-clone' : '' }}"
                                            @if ($group === 1) aria-hidden="true" @endif
                                        >
                                            @foreach ($approved as $testimonial)
                                                <x-ui.testimonial-card :testimonial="$testimonial" />
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <p class="mt-3 rounded-xl border border-dashed border-line bg-surface-raised px-4 py-6 text-center text-sm text-ink-muted">
                                Belum ada testimoni yang disetujui.
                            </p>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
