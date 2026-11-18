@props(['items' => []])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'mb-6']) }}>
    <ol class="flex flex-wrap items-center gap-2 font-mono text-[11px] uppercase tracking-[0.14em] text-ink-faint">
        @foreach ($items as $index => $item)
            <li class="flex items-center gap-2">
                @if ($index > 0)
                    <span aria-hidden="true">/</span>
                @endif

                @if (! empty($item['href']) && $index < count($items) - 1)
                    <a href="{{ $item['href'] }}" class="transition-colors duration-200 hover:text-ink">{{ $item['label'] }}</a>
                @else
                    <span class="text-ink" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
