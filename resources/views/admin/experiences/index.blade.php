@php
    $experiences = collect($portfolio['experiences']);
@endphp

<x-layouts.admin title="Pengalaman" subtitle="Kelola pengalaman organisasi, lomba, dan praktik kerja lapangan.">
    <x-slot name="actions">
        <x-ui.button :href="route('admin.experiences.create')" size="sm">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Tambah pengalaman
        </x-ui.button>
    </x-slot>

    <div class="grid gap-4 sm:grid-cols-3">
        <x-ui.stat label="Total" :value="$experiences->count()" hint="Semua catatan" />
        <x-ui.stat label="Berjalan" :value="$experiences->where('current', true)->count()" hint="Ditandai sekarang" />
        <x-ui.stat label="Organisasi" :value="$experiences->pluck('organization')->unique()->count()" hint="Tempat berbeda" />
    </div>

    <div class="mt-6 overflow-hidden rounded-2xl border border-line bg-surface-raised shadow-soft">
        <div class="flex items-center justify-between gap-3 border-b border-line px-5 py-4">
            <h2 class="text-sm font-semibold text-ink">Daftar pengalaman</h2>
            <x-ui.badge variant="neutral">{{ $experiences->count() }} catatan</x-ui.badge>
        </div>

        <ul class="divide-y divide-line">
            @foreach ($experiences as $experience)
                <li x-data class="flex flex-col gap-3 px-5 py-4 transition-colors hover:bg-surface sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">{{ $experience['period'] }}</span>
                            <x-ui.badge variant="accent">{{ $experience['type'] }}</x-ui.badge>
                            @if ($experience['current'])
                                <x-ui.badge variant="success">Sekarang</x-ui.badge>
                            @endif
                        </div>
                        <p class="mt-1.5 text-sm font-semibold text-ink">{{ $experience['title'] }}</p>
                        <p class="text-xs text-ink-muted">{{ $experience['organization'] }}</p>
                        <p class="mt-1.5 line-clamp-2 max-w-2xl text-xs leading-5 text-ink-muted">{{ $experience['description'] }}</p>
                        <ul class="mt-2 flex flex-wrap gap-1">
                            @foreach ($experience['tags'] ?? [] as $tag)
                                <li class="rounded-full bg-surface px-2 py-0.5 font-mono text-[10px] text-ink-muted">{{ $tag }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="flex flex-none items-center gap-1">
                        <a href="{{ route('admin.experiences.edit', $experience['id']) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-surface hover:text-ink" aria-label="Edit {{ $experience['title'] }}">
                            <x-ui.icon name="pencil" class="h-4 w-4" />
                        </a>
                        <button
                            type="button"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-danger-soft hover:text-danger"
                            aria-label="Hapus {{ $experience['title'] }}"
                            @click="askConfirm({ title: 'Hapus pengalaman?', message: @js('Catatan '.$experience['title'].' akan dihapus.'), confirmLabel: 'Hapus', onConfirm: () => $refs.destroy.submit() })"
                        >
                            <x-ui.icon name="trash" class="h-4 w-4" />
                        </button>
                        <form method="POST" action="{{ route('admin.experiences.destroy', $experience['id']) }}" x-ref="destroy" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-layouts.admin>
