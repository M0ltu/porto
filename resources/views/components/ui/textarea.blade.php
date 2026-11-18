@props(['name' => null, 'rows' => 5])

@php
    $hasError = $name && isset($errors) && $errors->has($name);
@endphp

<textarea
    @if ($name) id="{{ $name }}" name="{{ $name }}" @endif
    rows="{{ $rows }}"
    @if ($hasError)
        aria-invalid="true"
        aria-describedby="{{ $name }}-error"
    @endif
    {{ $attributes->merge([
        'class' => 'w-full resize-y rounded-xl border bg-surface px-3.5 py-2.5 text-sm leading-6 text-ink placeholder:text-ink-faint transition-colors duration-200 focus:border-accent focus:ring-0 '
            .($hasError ? 'border-danger' : 'border-line'),
    ]) }}
>{{ $slot }}</textarea>
