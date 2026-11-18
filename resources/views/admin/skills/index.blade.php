@php
    $hard = collect($portfolio['skills']['hard']);
    $soft = collect($portfolio['skills']['soft']);
@endphp

<x-layouts.admin title="Keahlian" subtitle="Atur daftar keahlian teknis dan interpersonal beserta tingkat penguasaannya.">
    <x-slot name="actions">
        <x-ui.button :href="route('admin.skills.create')" size="sm">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Tambah keahlian
        </x-ui.button>
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-ui.stat label="Hard skill" :value="$hard->count()" hint="Area teknis" />
        <x-ui.stat label="Soft skill" :value="$soft->count()" hint="Kebiasaan kerja" />
        <x-ui.stat label="Rata-rata" :value="round(($hard->avg('value') + $soft->avg('value')) / 2).'%'" hint="Gabungan semua area" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        @foreach ([
            ['title' => 'Keahlian teknis', 'description' => 'Kemampuan yang langsung terlihat di proyek.', 'items' => $hard, 'icon' => 'code'],
            ['title' => 'Keahlian interpersonal', 'description' => 'Cara bekerja dalam tim dan mengelola tugas.', 'items' => $soft, 'icon' => 'users'],
        ] as $group)
            <x-ui.card padding="p-0">
                <div class="flex items-start justify-between gap-3 border-b border-line px-5 py-4">
                    <div class="flex items-start gap-3">
                        <span class="flex h-9 w-9 flex-none items-center justify-center rounded-xl bg-accent-soft text-accent">
                            <x-ui.icon :name="$group['icon']" class="h-4 w-4" />
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-ink">{{ $group['title'] }}</h2>
                            <p class="mt-0.5 text-xs text-ink-faint">{{ $group['description'] }}</p>
                        </div>
                    </div>
                    <x-ui.badge variant="neutral">{{ $group['items']->count() }}</x-ui.badge>
                </div>

                <ul class="divide-y divide-line">
                    @foreach ($group['items'] as $skill)
                        <li x-data class="px-5 py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-ink">{{ $skill['name'] }}</p>
                                    <p class="mt-0.5 text-xs text-ink-muted">{{ $skill['note'] }}</p>
                                </div>

                                <div class="flex flex-none items-center gap-1">
                                    <span class="font-mono text-xs text-ink-muted">{{ $skill['value'] }}%</span>
                                    <a href="{{ route('admin.skills.edit', $skill['id']) }}" class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-surface hover:text-ink" aria-label="Edit {{ $skill['name'] }}">
                                        <x-ui.icon name="pencil" class="h-3.5 w-3.5" />
                                    </a>
                                    <button
                                        type="button"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-danger-soft hover:text-danger"
                                        aria-label="Hapus {{ $skill['name'] }}"
                                        @click="askConfirm({ title: 'Hapus keahlian?', message: @js('Keahlian '.$skill['name'].' akan dihapus.'), confirmLabel: 'Hapus', onConfirm: () => $refs.destroy.submit() })"
                                    >
                                        <x-ui.icon name="trash" class="h-3.5 w-3.5" />
                                    </button>
                                    <form method="POST" action="{{ route('admin.skills.destroy', $skill['id']) }}" x-ref="destroy" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            </div>

                            <div class="mt-2.5 h-1.5 w-full overflow-hidden rounded-full bg-line">
                                <div class="h-full rounded-full bg-accent" style="width: {{ $skill['value'] }}%"></div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </x-ui.card>
        @endforeach
    </div>

    <div class="mt-6 rounded-2xl border border-line bg-accent-soft/40 p-5 text-sm leading-6 text-ink-muted">
        <span class="inline-flex items-center gap-2 font-medium text-ink">
            <x-ui.icon name="info" class="h-4 w-4 text-accent" />
            Tentang persentase
        </span>
        <p class="mt-1.5">Nilai ini adalah perkiraan jujur untuk portfolio, bukan sertifikasi. Sebaiknya perbarui setelah menyelesaikan proyek yang relevan.</p>
    </div>
</x-layouts.admin>
