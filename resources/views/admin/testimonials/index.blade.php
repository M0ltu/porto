@php
    $all = collect($portfolio['messages']);
    $approved = $all->where('approved', true)->sortByDesc('id')->values();
    $pending = $all->where('approved', false)->sortByDesc('id')->values();
    $relations = ['Semua', 'Teman', 'Guru'];
@endphp

<x-layouts.admin title="Testimoni" subtitle="Testimoni berasal dari kiriman halaman Kesan & Pesan dan hanya tampil di publik setelah disetujui.">
    <x-slot name="actions">
        <x-ui.button :href="route('messages.create')" variant="secondary" size="sm">
            <x-ui.icon name="external-link" class="h-4 w-4" />
            Lihat form publik
        </x-ui.button>
    </x-slot>

    <div
        x-data="{
            relation: 'Semua',
            visible(value) { return this.relation === 'Semua' || value === this.relation.toLowerCase() },
        }"
    >
        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border border-line bg-surface-raised p-4">
                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">Disetujui</p>
                <p class="mt-1 text-lg font-semibold text-ink">{{ $approved->count() }}</p>
                <p class="mt-0.5 text-xs text-ink-muted">Tampil di beranda dan halaman kesan &amp; pesan</p>
            </div>
            <div class="rounded-xl border border-line bg-surface-raised p-4">
                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">Menunggu tinjauan</p>
                <p class="mt-1 text-lg font-semibold text-ink">{{ $pending->count() }}</p>
                <p class="mt-0.5 text-xs text-ink-muted">Belum muncul di halaman publik</p>
            </div>
            <div class="rounded-xl border border-line bg-surface-raised p-4">
                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">Total kiriman</p>
                <p class="mt-1 text-lg font-semibold text-ink">{{ $all->count() }}</p>
                <p class="mt-0.5 text-xs text-ink-muted">{{ $all->where('relation', 'teman')->count() }} teman · {{ $all->where('relation', 'guru')->count() }} guru</p>
            </div>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-1.5 rounded-2xl border border-line bg-surface-raised p-4 shadow-soft" role="group" aria-label="Filter peran testimoni">
            @foreach ($relations as $option)
                <button
                    type="button"
                    @click="relation = @js($option)"
                    :aria-pressed="(relation === @js($option)).toString()"
                    class="rounded-full border px-3 py-1.5 text-xs transition-colors duration-200"
                    :class="relation === @js($option) ? 'border-transparent bg-accent-soft font-medium text-accent' : 'border-line bg-surface text-ink-muted hover:text-ink'"
                >
                    {{ $option }}
                </button>
            @endforeach
        </div>

        <h2 class="mt-6 text-sm font-semibold text-ink">Disetujui</h2>

        <div class="mt-3 grid gap-4 lg:grid-cols-2">
            @forelse ($approved as $testimonial)
                <div x-data x-show="visible(@js($testimonial['relation']))" class="flex flex-col rounded-2xl border border-line bg-surface-raised p-5 shadow-soft">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <x-ui.avatar :name="$testimonial['name']" size="sm" />
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $testimonial['name'] }}</p>
                                <p class="text-xs text-ink-muted">{{ $testimonial['role'] }} · {{ $testimonial['date'] }}</p>
                            </div>
                        </div>
                        <div class="flex flex-none items-center gap-1.5">
                            @if (! empty($testimonial['ratings']))
                                <x-ui.rating-stars :value="collect($testimonial['ratings'])->avg()" show-value />
                            @endif
                            <x-ui.badge variant="neutral">{{ $testimonial['relation'] === 'guru' ? 'Guru' : 'Teman' }}</x-ui.badge>
                        </div>
                    </div>

                    <blockquote class="mt-4 flex-1 rounded-xl bg-surface p-4 text-sm leading-6 text-ink-muted">
                        “{{ $testimonial['body'] }}”
                    </blockquote>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-line pt-3">
                        <x-ui.badge variant="success">Tampil di publik</x-ui.badge>

                        <div class="flex items-center gap-1">
                            <button type="button" class="inline-flex items-center gap-1.5 rounded-lg px-2 py-1.5 text-xs text-ink-muted transition-colors hover:bg-surface hover:text-ink" @click="askConfirm({ title: 'Sembunyikan testimoni?', message: @js('Testimoni dari '.$testimonial['name'].' disembunyikan dari halaman publik.'), confirmLabel: 'Sembunyikan', onConfirm: () => $refs.toggle.submit() })">
                                <x-ui.icon name="eye" class="h-3.5 w-3.5" />
                                Sembunyikan
                            </button>
                            <button
                                type="button"
                                class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-danger-soft hover:text-danger"
                                aria-label="Hapus testimoni {{ $testimonial['name'] }}"
                                @click="askConfirm({ title: 'Hapus testimoni?', message: @js('Testimoni dari '.$testimonial['name'].' akan dihapus permanen.'), confirmLabel: 'Hapus', onConfirm: () => $refs.destroy.submit() })"
                            >
                                <x-ui.icon name="trash" class="h-3.5 w-3.5" />
                            </button>
                            <form method="POST" action="{{ route('admin.messages.approve', $testimonial['id']) }}" x-ref="toggle" class="hidden">
                                @csrf
                            </form>
                            <form method="POST" action="{{ route('admin.messages.destroy', $testimonial['id']) }}" x-ref="destroy" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-line bg-surface-raised px-4 py-8 text-center text-sm text-ink-muted lg:col-span-2">
                    Belum ada testimoni disetujui.
                </p>
            @endforelse
        </div>

        <h2 class="mt-8 text-sm font-semibold text-ink">Menunggu tinjauan</h2>

        <div class="mt-3 grid gap-4 lg:grid-cols-2">
            @forelse ($pending as $testimonial)
                <div x-data x-show="visible(@js($testimonial['relation']))" class="flex flex-col rounded-2xl border border-dashed border-line bg-surface px-5 py-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <x-ui.avatar :name="$testimonial['name']" size="sm" />
                            <div>
                                <p class="text-sm font-semibold text-ink">{{ $testimonial['name'] }}</p>
                                <p class="text-xs text-ink-muted">{{ $testimonial['role'] }} · {{ $testimonial['date'] }}</p>
                            </div>
                        </div>
                        @if (! empty($testimonial['ratings']))
                            <x-ui.rating-stars :value="collect($testimonial['ratings'])->avg()" show-value />
                        @endif
                    </div>

                    <p class="mt-3 line-clamp-3 text-sm leading-6 text-ink-muted">“{{ $testimonial['body'] }}”</p>

                    <div class="mt-4 flex items-center gap-2 border-t border-line pt-3">
                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg bg-accent px-2.5 py-1.5 text-xs font-medium text-white transition-opacity hover:opacity-90" @click="askConfirm({ title: 'Setujui testimoni?', message: @js('Kiriman dari '.$testimonial['name'].' akan tampil di halaman publik.'), confirmLabel: 'Setujui', onConfirm: () => $refs.approve.submit() })">
                            <x-ui.icon name="check" class="h-3.5 w-3.5" />
                            Setujui
                        </button>
                        <button type="button" class="inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1.5 text-xs text-ink-faint transition-colors hover:bg-danger-soft hover:text-danger" @click="askConfirm({ title: 'Tolak & hapus?', message: @js('Kiriman dari '.$testimonial['name'].' akan dihapus permanen.'), confirmLabel: 'Tolak', onConfirm: () => $refs.destroy.submit() })">
                            <x-ui.icon name="x" class="h-3.5 w-3.5" />
                            Tolak
                        </button>
                        <form method="POST" action="{{ route('admin.messages.approve', $testimonial['id']) }}" x-ref="approve" class="hidden">
                            @csrf
                        </form>
                        <form method="POST" action="{{ route('admin.messages.destroy', $testimonial['id']) }}" x-ref="destroy" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                        <span class="ml-auto font-mono text-[9px] uppercase tracking-[0.1em] text-ink-faint">{{ $testimonial['relation'] === 'guru' ? 'Guru' : 'Teman' }}</span>
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-line bg-surface-raised px-4 py-8 text-center text-sm text-ink-muted lg:col-span-2">
                    Tidak ada kiriman yang menunggu tinjauan.
                </p>
            @endforelse
        </div>

        <p class="mt-6 text-xs leading-5 text-ink-faint">
            Kiriman baru masuk lewat halaman “Kesan &amp; Pesan”. Setujui hanya setelah membaca isi dan ratingnya.
        </p>
    </div>
</x-layouts.admin>
