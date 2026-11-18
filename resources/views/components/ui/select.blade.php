@props(['name' => null])

@php
    $hasError = $name && isset($errors) && $errors->has($name);
@endphp

<select
    @if ($name) id="{{ $name }}" name="{{ $name }}" @endif
    @if ($hasError) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
    {{ $attributes->merge([
        'class' => 'w-full rounded-xl border bg-surface px-3.5 py-2.5 text-sm text-ink transition-colors duration-200 focus:border-accent focus:ring-0 '
            .($hasError ? 'border-danger' : 'border-line'),
    ]) }}
>
    {{ $slot }}
</select>
