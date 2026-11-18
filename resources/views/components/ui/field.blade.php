@props(['label', 'for' => null, 'hint' => null, 'error' => null, 'required' => false])

<div {{ $attributes->merge(['class' => 'space-y-1.5']) }}>
    <label for="{{ $for }}" class="block text-sm font-medium text-ink">
        {{ $label }}
        @if ($required)
            <span class="ml-0.5 text-danger" aria-hidden="true">*</span>
            <span class="sr-only">(wajib diisi)</span>
        @endif
    </label>

    {{ $slot }}

    @if ($hint && ! $error)
        <p id="{{ $for }}-hint" class="text-xs text-ink-faint">{{ $hint }}</p>
    @endif

    @if ($error)
        <p id="{{ $for }}-error" class="flex items-center gap-1.5 text-xs text-danger" role="alert">
            <x-ui.icon name="alert-circle" class="h-3.5 w-3.5" />
            {{ $error }}
        </p>
    @endif
</div>
