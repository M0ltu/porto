@props(['items'])

@php
    $items = collect($items);
    $count = $items->count();
    $duration = max(30, min(100, $count * 7));
    $marqueeId = uniqid('testimonial-marquee-');
@endphp

@if ($count)
    <div data-marquee data-reveal>
        <div class="mb-2 flex justify-end">
            <button
                type="button"
                data-marquee-toggle
                aria-controls="{{ $marqueeId }}"
                aria-pressed="false"
                class="inline-flex items-center gap-1.5 rounded-lg border border-line bg-surface-raised px-2.5 py-1.5 text-xs text-ink-muted transition-colors duration-200 hover:text-ink"
            >
                <x-ui.icon name="pause" data-marquee-icon="pause" class="h-3.5 w-3.5" />
                <x-ui.icon name="play" data-marquee-icon="play" class="hidden h-3.5 w-3.5" />
                <span data-marquee-label>Jeda guliran</span>
            </button>
        </div>

        <div
            id="{{ $marqueeId }}"
            class="marquee rounded-2xl"
            data-marquee-viewport
            tabindex="0"
            role="region"
            aria-label="Testimoni pengunjung, bergulir otomatis dari kanan ke kiri. Gunakan tombol Jeda atau fokuskan area ini untuk menghentikan guliran."
            style="--marquee-duration: {{ $duration }}s"
        >
            <div class="marquee-track py-2">
                @foreach ([0, 1] as $group)
                    <div
                        class="flex gap-5 pr-5 {{ $group === 1 ? 'marquee-clone' : '' }}"
                        @if ($group === 1) aria-hidden="true" @endif
                    >
                        @foreach ($items as $testimonial)
                            <div class="w-[19rem] shrink-0 sm:w-[22rem]">
                                <x-ui.testimonial-card :testimonial="$testimonial" />
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
