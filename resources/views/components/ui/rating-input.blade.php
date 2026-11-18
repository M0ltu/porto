@props(['name', 'label', 'value' => 0, 'required' => true, 'hint' => null])

@php
    $dotName = trim(str_replace(['][', '[', ']'], ['.', '.', ''], $name), '.');
    $current = (int) old($dotName, $value);
@endphp

<div class="rounded-xl border border-line bg-surface p-4">
    <p id="{{ $name }}-label" class="block text-sm font-medium text-ink">
        {{ $label }}
        @if ($required)
            <span class="ml-0.5 text-danger" aria-hidden="true">*</span>
            <span class="sr-only">(wajib diisi)</span>
        @endif
    </p>

    <div
        x-data="{ rating: {{ $current }}, hover: 0, popped: 0 }"
        class="mt-2 flex flex-wrap items-center gap-2"
        role="radiogroup"
        aria-labelledby="{{ $name }}-label"
    >
        <input type="hidden" name="{{ $name }}" :value="rating || ''">

        <div class="inline-flex items-center gap-0.5">
            <template x-for="star in 5" :key="star">
                <button
                    type="button"
                    role="radio"
                    :aria-checked="(rating === star).toString()"
                    :aria-label="star + ' dari 5 bintang'"
                    :tabindex="rating === star || (rating === 0 && star === 1) ? 0 : -1"
                    class="rounded-lg p-0.5 transition-colors duration-150 hover:text-accent focus:outline-none"
                    :class="[star <= (hover || rating) ? 'text-accent' : 'text-ink-faint', popped === star ? 'star-pop' : '']"
                    @click="rating = star; popped = star; setTimeout(() => { if (popped === star) popped = 0 }, 220)"
                    @mouseenter="hover = star"
                    @mouseleave="hover = 0"
                    @focus="hover = star"
                    @blur="hover = 0"
                    @keydown.arrow-right.prevent="rating = Math.min(5, rating + 1)"
                    @keydown.arrow-left.prevent="rating = Math.max(1, rating - 1)"
                    @keydown.home.prevent="rating = 1"
                    @keydown.end.prevent="rating = 5"
                >
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 2 15 9l7 .9-5 4.8 1.2 7L12 18.5 5.8 21.7 7 14.7 2 9.9 9 9Z" />
                    </svg>
                </button>
            </template>
        </div>

        <span class="font-mono text-[10px] uppercase tracking-[0.1em] text-ink-faint" aria-hidden="true">
            <template x-for="r in [rating]" :key="r">
                <span
                    x-transition:enter="transition-opacity ease-out duration-[120ms]"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-text="['Belum dinilai', 'Sangat kurang', 'Kurang', 'Cukup', 'Baik', 'Sangat baik'][r]"
                ></span>
            </template>
        </span>
    </div>

    @if ($hint)
        <p class="mt-2 text-xs text-ink-faint">{{ $hint }}</p>
    @endif
</div>
