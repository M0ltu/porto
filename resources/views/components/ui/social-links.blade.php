@props(['items' => [], 'showLabel' => false])

<ul {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }}>
    @foreach ($items as $item)
        <li>
            <a
                href="{{ $item['href'] }}"
                class="group inline-flex h-10 items-center gap-2 rounded-xl border border-line bg-surface-raised px-3 text-sm text-ink-muted transition-colors duration-200 hover:border-ink-faint hover:text-ink {{ $showLabel ? '' : 'tooltip' }}"
                @if (! $showLabel) data-tooltip="{{ $item['label'] }}" @endif
                @if (! empty($item['external'])) target="_blank" rel="noopener noreferrer" @endif
            >
                <x-ui.icon :name="$item['icon']" class="h-4 w-4" />
                @if ($showLabel)
                    <span>{{ $item['label'] }}</span>
                @else
                    <span class="sr-only">{{ $item['label'] }}</span>
                @endif
                @if (! empty($item['external']))
                    <span class="sr-only">(membuka tab baru)</span>
                @endif
            </a>
        </li>
    @endforeach
</ul>
