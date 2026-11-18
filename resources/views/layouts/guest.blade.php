@props([
    'title' => 'Masuk',
    'heading' => 'Masuk ke Dashboard',
    'subheading' => 'Area khusus administrator untuk mengelola konten portfolio.',
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — {{ $portfolio['profile']['name'] ?? config('app.name') }}</title>

    <script>
        (function () {
            try {
                var stored = localStorage.getItem('pongo-theme');
                var theme = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
            } catch (event) {}
            document.documentElement.classList.add('js');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-surface font-sans text-ink antialiased">
    <div class="flex min-h-screen flex-col">
        <header class="border-b border-line">
            <div class="container-page flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-ink font-mono text-[13px] font-semibold text-surface">MD</span>
                    <span class="text-sm font-semibold text-ink">Kembali ke portfolio</span>
                </a>
                <x-ui.theme-toggle compact />
            </div>
        </header>

        <main class="flex flex-1 items-center justify-center px-5 py-12">
            <div class="w-full max-w-md">
                <div class="mb-6 text-center">
                    <h1 class="text-balance text-2xl font-semibold tracking-tight text-ink">{{ $heading }}</h1>
                    <p class="mt-2 text-sm text-ink-muted">{{ $subheading }}</p>
                </div>

                <div class="rounded-2xl border border-line bg-surface-raised p-6 shadow-soft sm:p-8">
                    {{ $slot }}
                </div>

                <p class="mt-6 text-center font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">
                    Demo UI — autentikasi memakai Laravel Breeze
                </p>
            </div>
        </main>
    </div>

    <x-ui.toasts />
</body>
</html>
