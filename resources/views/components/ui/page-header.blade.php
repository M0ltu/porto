@props([
    'title',
    'subtitle' => null,
    'back' => null,
    'backLabel' => 'Kembali',
    'eyebrow' => null,
])

@php
    $marks = [
        'about' => ['02', 'Tentang'],
        'skills.index' => ['03', 'Keahlian'],
        'projects.index' => ['04', 'Proyek'],
        'projects.show' => ['04', 'Proyek'],
        'experience.index' => ['05', 'Pengalaman'],
        'contact' => ['06', 'Kontak'],
        'certificates.index' => ['07', 'Sertifikat'],
        'messages.create' => ['08', 'Kesan'],
        'cv' => ['09', 'CV'],
    ];

    $mark = $marks[request()->route()?->getName()] ?? null;
@endphp

<div {{ $attributes->merge(['class' => 'page-header relative pb-8']) }}>
    @if ($mark)
        <span class="page-watermark" aria-hidden="true">
            <span class="page-watermark-index">{{ $mark[0] }}</span>
            <span class="page-watermark-label">{{ $mark[1] }}</span>
        </span>
    @endif

    @if ($back)
        <a href="{{ $back }}" class="mb-5 inline-flex items-center gap-2 text-sm text-ink-muted transition-colors duration-200 hover:text-ink">
            <x-ui.icon name="arrow-left" class="h-4 w-4" />
            {{ $backLabel }}
        </a>
    @endif

    <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div class="min-w-0">
            @if ($eyebrow)
                <p class="mb-2 font-mono text-[11px] uppercase tracking-[0.2em] text-ink-faint">{{ $eyebrow }}</p>
            @endif

            <h1 class="text-balance text-3xl font-semibold tracking-tight text-ink sm:text-4xl">{{ $title }}</h1>

            @if ($subtitle)
                <p class="mt-3 max-w-2xl text-pretty text-sm leading-6 text-ink-muted">{{ $subtitle }}</p>
            @endif
        </div>

        @isset($actions)
            <div class="flex flex-wrap items-center gap-2">
                {{ $actions }}
            </div>
        @endisset
    </div>
</div>
