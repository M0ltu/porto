@props(['name' => null, 'type' => 'text'])

@php
    $hasError = $name && isset($errors) && $errors->has($name);
    $fieldId = $attributes->get('id') ?? $name;
@endphp

<input
    @if ($fieldId) id="{{ $fieldId }}" @endif
    @if ($name) name="{{ $name }}" @endif
    type="{{ $type }}"
    @if ($hasError)
        aria-invalid="true"
        aria-describedby="{{ $fieldId }}-error"
    @endif
    {{ $attributes->except('id')->merge([
        'class' => 'w-full rounded-xl border bg-surface px-3.5 py-2.5 text-sm text-ink placeholder:text-ink-faint transition-colors duration-200 focus:border-accent focus:ring-0 '
            .($hasError ? 'border-danger' : 'border-line'),
    ]) }}
>
