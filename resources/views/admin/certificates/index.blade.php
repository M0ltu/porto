@php
    $certificates = collect($portfolio['certificates']);
    $types = $certificates->pluck('type')->unique()->prepend('Semua')->values();

    $meta = $certificates->map(fn (array $certificate) => [
        'type' => $certificate['type'],
        'search' => mb_strtolower(implode(' ', [$certificate['title'], $certificate['issuer'], $certificate['type'], $certificate['year']])),
    ])->values();
@endphp

<x-layouts.admin title="Sertifikat" subtitle="Kelola sertifikat, piagam, dan dokumentasi prestasi.">
    <x-slot name="actions">
        <x-ui.button :href="route('admin.certificates.create')" size="sm">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Tambah sertifikat
        </x-ui.button>
    </x-slot>

    <div
        x-data="{
            query: '',
            type: 'Semua',
            meta: @js($meta),
            matches(item) {
                const query = this.query.trim().toLowerCase();
                const typeOk = this.type === 'Semua' || item.type === this.type;
                return typeOk && (query === '' || item.search.includes(query));
            },
            visible(index) { return this.matches(this.meta[index]) },
            count() { return this.meta.filter(item => this.matches(item)).length },
        }"
    >
        <div class="flex flex-col gap-3 rounded-2xl border border-line bg-surface-raised p-4 shadow-soft sm:flex-row sm:items-center sm:justify-between">
            <div class="relative w-full sm:max-w-xs">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-ink-faint">
                    <x-ui.icon name="search" class="h-4 w-4" />
                </span>
                <label for="admin-certificate-search" class="sr-only">Cari sertifikat</label>
                <input
                    id="admin-certificate-search"
                    type="search"
                    x-model.debounce.150ms="query"
                    placeholder="Cari sertifikat…"
                    class="w-full rounded-xl border border-line bg-surface py-2.5 pl-9 pr-3 text-sm text-ink placeholder:text-ink-faint focus:border-accent focus:ring-0"
                >
            </div>

            <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter jenis">
                @foreach ($types as $type)
                    <button
                        type="button"
                        @click="type = @js($type)"
                        :aria-pressed="(type === @js($type)).toString()"
                        class="rounded-full border px-3 py-1.5 text-xs transition-colors duration-200"
                        :class="type === @js($type) ? 'border-transparent bg-accent-soft font-medium text-accent' : 'border-line bg-surface text-ink-muted hover:text-ink'"
                    >
                        {{ $type }}
                    </button>
                @endforeach
            </div>
        </div>

        <p class="mt-3 font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">
            <span x-text="count()">{{ $certificates->count() }}</span> dari {{ $certificates->count() }} dokumen
        </p>

        <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($certificates as $certificate)
                <div x-data x-show="visible({{ $loop->index }})" class="flex flex-col overflow-hidden rounded-2xl border border-line bg-surface-raised shadow-soft">
                    <div class="relative aspect-[16/10] bg-surface">
                        <img src="{{ $certificate['image'] }}" alt="Dokumentasi {{ $certificate['title'] }}" class="h-full w-full object-cover" loading="lazy" width="600" height="375">
                        <span class="absolute left-3 top-3">
                            <x-ui.badge :variant="$certificate['type'] === 'Prestasi' ? 'accent' : 'neutral'">{{ $certificate['type'] }}</x-ui.badge>
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col p-4">
                        <p class="font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">{{ $certificate['year'] }}</p>
                        <h3 class="mt-1 text-sm font-semibold leading-5 text-ink">{{ $certificate['title'] }}</h3>
                        <p class="mt-1 text-xs text-ink-muted">{{ $certificate['issuer'] }}</p>

                        <div class="mt-4 flex items-center justify-between gap-2 border-t border-line pt-3">
                            <a href="{{ $certificate['image'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-xs text-ink-muted transition-colors hover:text-accent">
                                <x-ui.icon name="eye" class="h-3.5 w-3.5" />
                                Pratinjau
                            </a>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.certificates.edit', $certificate['id']) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-surface hover:text-ink" aria-label="Edit {{ $certificate['title'] }}">
                                    <x-ui.icon name="pencil" class="h-3.5 w-3.5" />
                                </a>
                                <button
                                    type="button"
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-danger-soft hover:text-danger"
                                    aria-label="Hapus {{ $certificate['title'] }}"
                                    @click="askConfirm({ title: 'Hapus sertifikat?', message: @js('Sertifikat '.$certificate['title'].' akan dihapus dari daftar.'), confirmLabel: 'Hapus', onConfirm: () => $refs.destroy.submit() })"
                                >
                                    <x-ui.icon name="trash" class="h-3.5 w-3.5" />
                                </button>
                                <form method="POST" action="{{ route('admin.certificates.destroy', $certificate['id']) }}" x-ref="destroy" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div x-show="count() === 0" x-cloak class="mt-4">
            <x-ui.empty-state icon="award" title="Tidak ada dokumen" message="Ubah kata kunci atau reset filter jenis.">
                <x-slot name="action">
                    <x-ui.button variant="secondary" type="button" @click="query = ''; type = 'Semua'">Reset filter</x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        </div>
    </div>
</x-layouts.admin>
