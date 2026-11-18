@props(['title' => 'Belum ada data', 'message' => null, 'icon' => 'folder'])

<div {{ $attributes->merge(['class' => 'icon-pop flex flex-col items-center justify-center rounded-2xl border border-dashed border-line bg-surface-raised px-6 py-14 text-center']) }}>
    <span class="icon-pop-target flex h-12 w-12 items-center justify-center rounded-full bg-surface text-ink-faint motion-reduce:!animate-none">
        <x-ui.icon :name="$icon" class="h-6 w-6" />
    </span>

    <h3 class="mt-4 text-base font-semibold text-ink">{{ $title }}</h3>

    @if ($message)
        <p class="mt-1.5 max-w-sm text-sm text-ink-muted">{{ $message }}</p>
    @endif

    @isset($action)
        <div class="mt-5">
            {{ $action }}
        </div>
    @endisset
</div>
