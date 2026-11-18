@props([
    'title' => 'Dashboard',
    'subtitle' => null,
])

@php
    $profile = $portfolio['profile'];

    $groups = [
        'Utama' => [
            ['label' => 'Ringkasan', 'route' => 'admin.dashboard', 'icon' => 'layout-dashboard', 'active' => 'admin.dashboard'],
        ],
        'Konten' => [
            ['label' => 'Proyek', 'route' => 'admin.projects.index', 'icon' => 'folder', 'active' => 'admin.projects.*'],
            ['label' => 'Sertifikat', 'route' => 'admin.certificates.index', 'icon' => 'award', 'active' => 'admin.certificates.*'],
            ['label' => 'Keahlian', 'route' => 'admin.skills.index', 'icon' => 'code', 'active' => 'admin.skills.*'],
            ['label' => 'Pengalaman', 'route' => 'admin.experiences.index', 'icon' => 'briefcase', 'active' => 'admin.experiences.*'],
            ['label' => 'Testimoni', 'route' => 'admin.testimonials.index', 'icon' => 'quote', 'active' => 'admin.testimonials.*'],
        ],
        'Pengaturan' => [
            ['label' => 'Profil & CV', 'route' => 'admin.settings', 'icon' => 'settings', 'active' => 'admin.settings'],
            ['label' => 'Akun', 'route' => 'profile.edit', 'icon' => 'user', 'active' => 'profile.*'],
        ],
    ];

    $pendingTestimonials = collect($portfolio['messages'])->where('approved', false)->count();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }} — Admin {{ $profile['name'] }}</title>

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
    <a href="#admin-main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[90] focus:rounded-xl focus:bg-accent focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-accent-ink">
        Lewati ke konten utama
    </a>

    <div x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="flex min-h-screen">
        <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black/50 lg:hidden" aria-hidden="true" @click="sidebarOpen = false"></div>

        <aside
            id="admin-sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-line bg-surface-raised transition-transform duration-200 lg:sticky lg:top-0 lg:z-30 lg:h-screen lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            aria-label="Navigasi admin"
        >
            <div class="flex h-16 flex-none items-center justify-between border-b border-line px-4">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-ink font-mono text-[11px] font-semibold text-surface">MD</span>
                    <span>
                        <span class="block text-sm font-semibold leading-tight text-ink">Admin Panel</span>
                        <span class="block font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">Portfolio</span>
                    </span>
                </a>

                <button
                    type="button"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg text-ink-muted hover:bg-surface lg:hidden"
                    @click="sidebarOpen = false"
                    aria-label="Tutup navigasi admin"
                >
                    <x-ui.icon name="x" class="h-4 w-4" />
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4">
                @foreach ($groups as $group => $items)
                    <p class="px-3 pb-2 pt-3 font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint first:pt-0">{{ $group }}</p>
                    <ul class="space-y-0.5">
                        @foreach ($items as $item)
                            @php $isActive = request()->routeIs($item['active']); @endphp
                            <li>
                                <a
                                    href="{{ route($item['route']) }}"
                                    class="relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm transition-colors duration-200 {{ $isActive ? 'bg-accent-soft font-medium text-accent' : 'text-ink-muted hover:bg-surface hover:text-ink' }}"
                                    @if ($isActive) aria-current="page" @endif
                                >
                                    <x-ui.icon :name="$item['icon']" class="h-4 w-4 flex-none" />
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    @if ($item['route'] === 'admin.testimonials.index' && $pendingTestimonials)
                                        <span class="rounded-full bg-danger px-1.5 py-0.5 font-mono text-[10px] font-medium text-white">{{ $pendingTestimonials }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endforeach
            </nav>

            <div class="flex-none border-t border-line p-3">
                <div class="flex items-center gap-2.5 rounded-xl px-2 py-2">
                    <x-ui.avatar :name="auth()->user()?->name ?? 'Admin'" size="sm" />
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-ink">{{ auth()->user()?->name ?? 'Administrator' }}</p>
                        <p class="truncate text-xs text-ink-faint">{{ auth()->user()?->email ?? 'admin@a.com' }}</p>
                    </div>
                </div>
                <div class="mt-2 flex items-center gap-2">
                    <a href="{{ route('home') }}" class="inline-flex h-9 flex-1 items-center justify-center gap-1.5 rounded-lg border border-line text-xs text-ink-muted transition-colors hover:text-ink">
                        <x-ui.icon name="external-link" class="h-3.5 w-3.5" />
                        Lihat situs
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line text-ink-muted transition-colors hover:text-danger" aria-label="Keluar dari akun">
                            <x-ui.icon name="log-out" class="h-4 w-4" />
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex h-16 flex-none items-center justify-between gap-3 border-b border-line bg-surface/95 px-4 backdrop-blur sm:px-6">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-line text-ink-muted hover:text-ink lg:hidden"
                        @click="sidebarOpen = true"
                        aria-label="Buka navigasi admin"
                        aria-controls="admin-sidebar"
                    >
                        <x-ui.icon name="menu" class="h-4 w-4" />
                    </button>
                    <div class="min-w-0">
                        <p class="font-mono text-[10px] uppercase tracking-[0.16em] text-ink-faint">Dashboard Admin</p>
                        <p class="truncate text-sm font-semibold text-ink">{{ $title }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-ui.theme-toggle compact />
                    <a href="{{ route('home') }}" class="hidden items-center gap-1.5 rounded-xl border border-line bg-surface-raised px-3 py-2 text-xs text-ink-muted transition-colors hover:text-ink sm:inline-flex">
                        <x-ui.icon name="external-link" class="h-3.5 w-3.5" />
                        Situs publik
                    </a>
                </div>
            </header>

            <div class="border-b border-line bg-surface-raised">
                <div class="flex flex-col gap-4 px-4 py-6 sm:flex-row sm:items-end sm:justify-between sm:px-6 lg:px-8">
                    <div class="min-w-0">
                        <h1 class="text-balance text-2xl font-semibold tracking-tight text-ink">{{ $title }}</h1>
                        @if ($subtitle)
                            <p class="mt-1.5 max-w-2xl text-sm leading-6 text-ink-muted">{{ $subtitle }}</p>
                        @endif
                    </div>

                    @isset($actions)
                        <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
                    @endisset
                </div>
            </div>

            <main id="admin-main" class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                @if (session('success'))
                    <div class="mb-5 flex items-start gap-2.5 rounded-xl border border-success bg-success-soft px-4 py-3 text-sm font-medium text-success" role="status">
                        <x-ui.icon name="check" class="mt-0.5 h-4 w-4 flex-none" />
                        {{ session('success') }}
                    </div>
                @endif

                {{ $slot }}
            </main>

            <footer class="border-t border-line px-4 py-4 text-xs text-ink-faint sm:px-6 lg:px-8">
                {{ config('app.name', 'Portfolio') }} — panel admin.
            </footer>
        </div>
    </div>

    <x-ui.toasts />
    <x-ui.confirm-dialog />

    <script>
        (function () {
            try {
                var key = 'admin-scroll:' + window.location.pathname + window.location.search;

                var restore = function () {
                    var raw = sessionStorage.getItem(key);

                    if (!raw) {
                        return;
                    }

                    var value = parseInt(raw, 10);

                    if (!isNaN(value)) {
                        window.scrollTo(0, value);
                    }
                };

                if (document.readyState === 'complete') {
                    restore();
                } else {
                    window.addEventListener('load', restore, { once: true });
                }

                var save = function () {
                    sessionStorage.setItem(key, String(window.scrollY || window.pageYOffset || 0));
                };

                var ticking = false;

                window.addEventListener(
                    'scroll',
                    function () {
                        if (ticking) {
                            return;
                        }

                        ticking = true;

                        window.requestAnimationFrame(function () {
                            save();
                            ticking = false;
                        });
                    },
                    { passive: true }
                );

                document.addEventListener(
                    'submit',
                    function () {
                        save();
                    },
                    true
                );

                window.addEventListener('pagehide', save);

                window.adminSubmitAction = function (action, method) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = action;

                    var csrf = document.querySelector('meta[name="csrf-token"]');
                    var token = document.createElement('input');
                    token.type = 'hidden';
                    token.name = '_token';
                    token.value = csrf ? csrf.getAttribute('content') : '';
                    form.appendChild(token);

                    if (method && method.toUpperCase() !== 'POST') {
                        var spoof = document.createElement('input');
                        spoof.type = 'hidden';
                        spoof.name = '_method';
                        spoof.value = method.toUpperCase();
                        form.appendChild(spoof);
                    }

                    document.body.appendChild(form);
                    save();
                    form.submit();
                };
            } catch (event) {}
        })();
    </script>
</body>
</html>
