@props([
    'title' => null,
    'description' => null,
])

@php
    $profile = $portfolio['profile'];
    $socials = $portfolio['socials'];
    $pageTitle = $title ? $title.' — '.$profile['name'] : $profile['name'].' — '.$profile['major'];

    $navItems = [
        ['label' => 'Tentang', 'route' => 'about'],
        ['label' => 'Keahlian', 'route' => 'skills.index'],
        ['label' => 'Proyek', 'route' => 'projects.index', 'active' => 'projects.*'],
        ['label' => 'Pengalaman', 'route' => 'experience.index'],
        ['label' => 'Kontak', 'route' => 'contact'],
    ];

    $moreItems = [
        ['label' => 'Sertifikat & Prestasi', 'route' => 'certificates.index', 'icon' => 'award'],
        ['label' => 'Kesan & Pesan', 'route' => 'messages.create', 'icon' => 'message-square'],
        ['label' => 'CV Digital', 'route' => 'cv', 'icon' => 'file-text'],
    ];
@endphp

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f7f7f8" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0b0b0d" media="(prefers-color-scheme: dark)">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description ?? $profile['headline'] }}">

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
    <div class="page-decor" aria-hidden="true">
        <span class="page-decor-monogram">MD</span>
    </div>

    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[90] focus:rounded-xl focus:bg-accent focus:px-4 focus:py-2 focus:text-sm focus:font-medium focus:text-accent-ink">
        Lewati ke konten utama
    </a>

    <div
        x-data="{
            navOpen: false,
            moreOpen: false,
            morePinned: false,
            moreTimer: null,
            openMore() {
                clearTimeout(this.moreTimer);
                this.moreOpen = true;
            },
            scheduleCloseMore() {
                clearTimeout(this.moreTimer);
                if (this.morePinned) {
                    return;
                }
                this.moreTimer = setTimeout(() => { this.moreOpen = false }, 120);
            },
            toggleMore() {
                clearTimeout(this.moreTimer);
                if (this.moreOpen && this.morePinned) {
                    this.morePinned = false;
                    this.moreOpen = false;
                } else {
                    this.morePinned = true;
                    this.moreOpen = true;
                }
            },
            closeMore() {
                clearTimeout(this.moreTimer);
                this.morePinned = false;
                this.moreOpen = false;
            },
        }"
        @keydown.escape.window="navOpen = false; closeMore()"
    >
        <header
            data-site-header
            class="sticky top-0 z-40 border-b border-line bg-surface/95 backdrop-blur transition-shadow duration-200 data-[scrolled=true]:shadow-soft"
        >
            <div class="container-page flex h-16 items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="group flex items-center gap-2.5" aria-label="Beranda {{ $profile['name'] }}">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-ink font-mono text-[13px] font-semibold text-surface transition-transform duration-200 group-hover:-rotate-3 motion-reduce:transform-none">
                        MD
                    </span>
                    <span class="hidden sm:block">
                        <span class="block text-sm font-semibold leading-tight text-ink">{{ $profile['nickname'] }}.</span>
                        <span class="block font-mono text-[10px] uppercase tracking-[0.14em] text-ink-faint">{{ $profile['major'] }}</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-0.5 lg:flex" aria-label="Navigasi utama">
                    @foreach ($navItems as $item)
                        @php
                            $isActive = request()->routeIs($item['active'] ?? $item['route']);
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            @if ($isActive) aria-current="page" @endif
                            class="rounded-lg px-3 py-2 text-sm transition-colors duration-200 {{ $isActive ? 'bg-accent-soft font-medium text-accent' : 'text-ink-muted hover:bg-surface-raised hover:text-ink' }}"
                        >
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <div
                        class="relative"
                        @mouseenter="openMore()"
                        @mouseleave="scheduleCloseMore()"
                        @focusout="if (! $el.contains($event.relatedTarget)) closeMore()"
                        @click.outside="closeMore()"
                    >
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm text-ink-muted transition-colors duration-200 hover:bg-surface-raised hover:text-ink"
                            @click="toggleMore()"
                            :aria-expanded="moreOpen.toString()"
                            aria-haspopup="true"
                            aria-controls="more-menu"
                        >
                            Lainnya
                            <x-ui.icon name="chevron-down" class="h-3.5 w-3.5 transition-transform duration-200" x-bind:class="moreOpen ? 'rotate-180' : ''" />
                        </button>

                        <div
                            x-show="moreOpen"
                            x-transition.origin.top.right
                            x-cloak
                            class="absolute right-0 top-full w-60 pt-2"
                            @mouseenter="openMore()"
                        >
                            <div id="more-menu" class="rounded-xl border border-line bg-surface-raised p-1.5 shadow-lift">
                                @foreach ($moreItems as $item)
                                    <a
                                        href="{{ route($item['route']) }}"
                                        class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-ink-muted transition-colors duration-200 hover:bg-surface hover:text-ink"
                                        @if (request()->routeIs($item['route'])) aria-current="page" @endif
                                    >
                                        <x-ui.icon :name="$item['icon']" class="h-4 w-4" />
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="flex items-center gap-2">
                    <x-ui.theme-toggle compact />

                    <x-ui.button :href="route('contact')" size="sm" class="hidden sm:inline-flex">
                        Hubungi Saya
                        <x-ui.icon name="arrow-right" class="h-4 w-4" />
                    </x-ui.button>

                    <button
                        type="button"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-line bg-surface-raised text-ink-muted transition-colors duration-200 hover:border-ink-faint hover:text-ink lg:hidden"
                        @click="navOpen = true; $nextTick(() => $refs.drawerClose?.focus())"
                        aria-label="Buka menu navigasi"
                        aria-controls="mobile-drawer"
                        :aria-expanded="navOpen.toString()"
                    >
                        <x-ui.icon name="menu" class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </header>

        <div
            id="mobile-drawer"
            x-show="navOpen"
            x-cloak
            class="fixed inset-0 z-50 lg:hidden"
            role="dialog"
            aria-modal="true"
            aria-label="Menu navigasi"
        >
            <div class="absolute inset-0 bg-black/50" aria-hidden="true" @click="navOpen = false"></div>

            <div
                x-show="navOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="absolute inset-y-0 right-0 flex w-[86%] max-w-sm flex-col overflow-y-auto border-l border-line bg-surface-raised shadow-lift"
            >
                <div class="flex h-16 items-center justify-between border-b border-line px-5">
                    <span class="font-mono text-[11px] uppercase tracking-[0.18em] text-ink-faint">Navigasi</span>
                    <button
                        type="button"
                        x-ref="drawerClose"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-ink-muted transition-colors hover:bg-surface hover:text-ink"
                        @click="navOpen = false"
                        aria-label="Tutup menu navigasi"
                    >
                        <x-ui.icon name="x" class="h-5 w-5" />
                    </button>
                </div>

                <nav class="flex-1 px-3 py-4" aria-label="Navigasi seluler">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium text-ink hover:bg-surface" @click="navOpen = false">
                        <x-ui.icon name="home" class="h-4 w-4 text-ink-faint" />
                        Beranda
                    </a>
                    @foreach ($navItems as $item)
                        @php $isActive = request()->routeIs($item['active'] ?? $item['route']); @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="flex items-center justify-between rounded-xl px-3 py-3 text-sm {{ $isActive ? 'bg-accent-soft font-medium text-accent' : 'text-ink-muted hover:bg-surface hover:text-ink' }}"
                            @click="navOpen = false"
                            @if ($isActive) aria-current="page" @endif
                        >
                            {{ $item['label'] }}
                            <x-ui.icon name="chevron-right" class="h-4 w-4 opacity-50" />
                        </a>
                    @endforeach

                    <p class="px-3 pb-2 pt-5 font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint">Lainnya</p>
                    @foreach ($moreItems as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            class="flex items-center gap-3 rounded-xl px-3 py-3 text-sm text-ink-muted hover:bg-surface hover:text-ink"
                            @click="navOpen = false"
                        >
                            <x-ui.icon :name="$item['icon']" class="h-4 w-4 text-ink-faint" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="border-t border-line p-4">
                    <div class="flex items-center justify-between gap-2">
                        <x-ui.button :href="route('contact')" class="flex-1" @click="navOpen = false">
                            Hubungi Saya
                        </x-ui.button>
                        <x-ui.theme-toggle compact />
                    </div>
                    <x-ui.social-links :items="$socials" class="mt-4 justify-center" />
                </div>
            </div>
        </div>
    </div>

    <main id="main" class="min-h-[60vh]">
        {{ $slot }}
    </main>

    <footer class="border-t border-line bg-surface-raised">
        <div class="container-page py-12">
            <div class="grid gap-10 md:grid-cols-12">
                <div class="md:col-span-5">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-ink font-mono text-[13px] font-semibold text-surface">MD</span>
                        <span class="text-sm font-semibold text-ink">{{ $profile['name'] }}</span>
                    </a>
                    <p class="mt-4 max-w-sm text-sm leading-6 text-ink-muted">{{ $profile['headline'] }}</p>
                    <x-ui.social-links :items="$socials" class="mt-5" />
                </div>

                <nav class="md:col-span-3" aria-label="Navigasi footer">
                    <h2 class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint">Jelajahi</h2>
                    <ul class="mt-4 space-y-2.5">
                        @foreach ($navItems as $item)
                            <li>
                                <a href="{{ route($item['route']) }}" class="link-underline text-sm text-ink-muted transition-colors duration-200 hover:text-ink">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('cv') }}" class="link-underline text-sm text-ink-muted transition-colors duration-200 hover:text-ink">CV Digital</a>
                        </li>
                    </ul>
                </nav>

                <div class="md:col-span-4">
                    <h2 class="font-mono text-[10px] uppercase tracking-[0.18em] text-ink-faint">Kontak</h2>
                    <ul class="mt-4 space-y-2.5 text-sm text-ink-muted">
                        <li class="flex items-center gap-2">
                            <x-ui.icon name="mail" class="h-4 w-4 text-ink-faint" />
                            <a href="mailto:{{ $profile['email'] }}" class="break-all transition-colors hover:text-ink">{{ $profile['email'] }}</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-ui.icon name="map-pin" class="h-4 w-4 text-ink-faint" />
                            {{ $profile['location'] }}
                        </li>
                        <li class="flex items-center gap-2">
                            <x-ui.icon name="clock" class="h-4 w-4 text-ink-faint" />
                            {{ $profile['availability'] }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 flex flex-col gap-2 border-t border-line pt-6 text-xs text-ink-faint sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} {{ $profile['name'] }}. Semua hak dilindungi.</p>
                <p class="font-mono">Dibangun dengan Laravel, Blade, dan Tailwind CSS.</p>
            </div>
        </div>
    </footer>

    <button
        type="button"
        data-back-to-top
        class="fixed bottom-5 right-5 z-30 inline-flex h-11 w-11 translate-y-3 items-center justify-center rounded-full border border-line bg-surface-raised text-ink-muted opacity-0 shadow-lift transition-all duration-200 hover:border-ink-faint hover:text-ink"
        aria-label="Kembali ke atas halaman"
    >
        <x-ui.icon name="arrow-up" class="h-5 w-5" />
    </button>

    <x-ui.toasts />
    <x-ui.confirm-dialog />
    <x-ui.lightbox />
</body>
</html>
