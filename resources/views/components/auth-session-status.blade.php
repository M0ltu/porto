@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-success bg-success-soft px-3.5 py-2.5 text-sm font-medium text-success']) }} role="status">
        {{ $status }}
    </div>
@endif
