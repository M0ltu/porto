@php
    $projects = collect($portfolio['projects']);
    $categories = $projects->pluck('category')->unique()->prepend('Semua')->values();

    $meta = $projects->map(fn (array $project) => [
        'category' => $project['category'],
        'search' => mb_strtolower(implode(' ', [$project['title'], $project['slug'], $project['category'], implode(' ', $project['tech'])])),
    ])->values();
@endphp

<x-layouts.admin title="Proyek" subtitle="Kelola daftar proyek yang tampil di portfolio publik.">
    <x-slot name="actions">
        <x-ui.button :href="route('admin.projects.create')" size="sm">
            <x-ui.icon name="plus" class="h-4 w-4" />
            Tambah proyek
        </x-ui.button>
    </x-slot>

    <div
        x-data="{
            query: '',
            category: 'Semua',
            meta: @js($meta),
            matches(item) {
                const query = this.query.trim().toLowerCase();
                const categoryOk = this.category === 'Semua' || item.category === this.category;
                const queryOk = query === '' || item.search.includes(query);
                return categoryOk && queryOk;
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
                <label for="admin-project-search" class="sr-only">Cari proyek</label>
                <input
                    id="admin-project-search"
                    type="search"
                    x-model.debounce.150ms="query"
                    placeholder="Cari proyek…"
                    class="w-full rounded-xl border border-line bg-surface py-2.5 pl-9 pr-3 text-sm text-ink placeholder:text-ink-faint focus:border-accent focus:ring-0"
                >
            </div>

            <div class="flex flex-wrap items-center gap-1.5" role="group" aria-label="Filter kategori proyek">
                @foreach ($categories as $category)
                    <button
                        type="button"
                        @click="category = @js($category)"
                        :aria-pressed="(category === @js($category)).toString()"
                        class="rounded-full border px-3 py-1.5 text-xs transition-colors duration-200"
                        :class="category === @js($category) ? 'border-transparent bg-accent-soft font-medium text-accent' : 'border-line bg-surface text-ink-muted hover:text-ink'"
                    >
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        </div>

        <p class="mt-3 font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">
            <span x-text="count()">{{ $projects->count() }}</span> dari {{ $projects->count() }} proyek
        </p>

        <div class="mt-4 overflow-hidden rounded-2xl border border-line bg-surface-raised shadow-soft">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-line bg-surface font-mono text-[10px] uppercase tracking-[0.12em] text-ink-faint">
                            <th scope="col" class="px-5 py-3 font-medium">Proyek</th>
                            <th scope="col" class="px-3 py-3 font-medium">Kategori</th>
                            <th scope="col" class="px-3 py-3 font-medium">Teknologi</th>
                            <th scope="col" class="px-3 py-3 font-medium">Tahun</th>
                            <th scope="col" class="px-3 py-3 font-medium">Status</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($projects as $project)
                            <tr x-data x-show="visible({{ $loop->index }})" class="transition-colors hover:bg-surface">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $project['cover'] }}" alt="" class="h-10 w-14 rounded-lg border border-line object-cover" loading="lazy" width="112" height="80">
                                        <div class="min-w-0">
                                            <a href="{{ route('projects.show', $project['slug']) }}" class="font-medium text-ink hover:text-accent">{{ $project['title'] }}</a>
                                            <p class="font-mono text-[10px] text-ink-faint">{{ $project['slug'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-ink-muted">{{ $project['category'] }}</td>
                                <td class="px-3 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach (array_slice($project['tech'], 0, 2) as $tech)
                                            <span class="rounded-full bg-surface px-2 py-0.5 font-mono text-[10px] text-ink-muted">{{ $tech }}</span>
                                        @endforeach
                                        @if (count($project['tech']) > 2)
                                            <span class="rounded-full bg-surface px-2 py-0.5 font-mono text-[10px] text-ink-faint">+{{ count($project['tech']) - 2 }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-3 font-mono text-xs text-ink-muted">{{ $project['year'] }}</td>
                                <td class="px-3 py-3">
                                    <x-ui.badge :variant="$project['featured'] ? 'accent' : 'neutral'">{{ $project['featured'] ? 'Unggulan' : 'Reguler' }}</x-ui.badge>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('projects.show', $project['slug']) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-surface hover:text-ink" aria-label="Lihat {{ $project['title'] }} di situs">
                                            <x-ui.icon name="eye" class="h-4 w-4" />
                                        </a>
                                        <a href="{{ route('admin.projects.edit', $project['slug']) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-surface hover:text-ink" aria-label="Edit {{ $project['title'] }}">
                                            <x-ui.icon name="pencil" class="h-4 w-4" />
                                        </a>
                                        <button
                                            type="button"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-ink-faint transition-colors hover:bg-danger-soft hover:text-danger"
                                            aria-label="Hapus {{ $project['title'] }}"
                                            @click="askConfirm({ title: 'Hapus proyek?', message: @js('Proyek '.$project['title'].' beserta galerinya akan dihapus dari daftar.'), confirmLabel: 'Hapus', onConfirm: () => $refs.destroy.submit() })"
                                        >
                                            <x-ui.icon name="trash" class="h-4 w-4" />
                                        </button>
                                        <form method="POST" action="{{ route('admin.projects.destroy', $project['slug']) }}" x-ref="destroy" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="count() === 0" x-cloak class="mt-4">
            <x-ui.empty-state icon="search" title="Proyek tidak ditemukan" message="Ubah kata kunci atau reset filter kategori.">
                <x-slot name="action">
                    <x-ui.button variant="secondary" type="button" @click="query = ''; category = 'Semua'">Reset filter</x-ui.button>
                </x-slot>
            </x-ui.empty-state>
        </div>
    </div>
</x-layouts.admin>
