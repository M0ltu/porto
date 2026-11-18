<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-xl bg-accent px-4 py-2.5 text-sm font-medium text-accent-ink transition-all duration-200 hover:brightness-110 focus:outline-none disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
