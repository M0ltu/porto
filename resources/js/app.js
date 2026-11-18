import Alpine from 'alpinejs';

window.Alpine = Alpine;

const themeKey = 'pongo-theme';

window.currentTheme = () => (document.documentElement.classList.contains('dark') ? 'dark' : 'light');

window.applyTheme = (theme) => {
    document.documentElement.classList.toggle('dark', theme === 'dark');
    window.localStorage.setItem(themeKey, theme);
    window.dispatchEvent(new CustomEvent('theme-changed', { detail: theme }));
};

window.toggleTheme = () => {
    window.applyTheme(window.currentTheme() === 'dark' ? 'light' : 'dark');
};

Alpine.store('toasts', {
    items: [],
    counter: 0,
    push(type, message, duration = 4500) {
        const id = ++this.counter;
        this.items.push({ id, type, message });

        if (duration) {
            window.setTimeout(() => this.dismiss(id), duration);
        }
    },
    dismiss(id) {
        this.items = this.items.filter((item) => item.id !== id);
    },
});

Alpine.store('confirmDialog', {
    open: false,
    title: '',
    message: '',
    confirmLabel: 'Hapus',
    onConfirm: null,
    ask(options = {}) {
        this.title = options.title || 'Konfirmasi';
        this.message = options.message || 'Tindakan ini tidak bisa dibatalkan.';
        this.confirmLabel = options.confirmLabel || 'Hapus';
        this.onConfirm = typeof options.onConfirm === 'function' ? options.onConfirm : null;
        this.open = true;
    },
    confirm() {
        if (this.onConfirm) {
            this.onConfirm();
        }
        this.open = false;
    },
    cancel() {
        this.open = false;
        this.onConfirm = null;
    },
});

window.askConfirm = (options) => {
    if (window.Alpine && Alpine.store('confirmDialog')) {
        Alpine.store('confirmDialog').ask(options);
    }
};

window.notify = (type, message, duration) => {
    if (window.Alpine && Alpine.store('toasts')) {
        Alpine.store('toasts').push(type, message, duration);
    }
};

Alpine.start();

const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const isInViewport = (el) => {
    const rect = el.getBoundingClientRect();
    const height = window.innerHeight || document.documentElement.clientHeight;

    return rect.bottom > 0 && rect.top < height * 1.08;
};

const whenVisible = (elements, callback, options = {}) => {
    const items = Array.from(elements);

    if (!items.length) {
        return;
    }

    if (prefersReducedMotion() || !('IntersectionObserver' in window)) {
        items.forEach((el) => callback(el));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                callback(entry.target);
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.1, ...options }
    );

    items.forEach((el) => {
        if (isInViewport(el)) {
            callback(el);

            return;
        }

        observer.observe(el);
    });
};

const setupReveal = () => {
    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
        const children = Array.from(group.children);

        if (!children.length) {
            return;
        }

        const step = Math.max(0, Number(group.dataset.revealStagger) || 50);

        whenVisible([group], () => {
            children.forEach((child, index) => {
                child.style.setProperty('--reveal-delay', `${Math.min(index * step, 240)}ms`);
                child.classList.add('is-visible');
            });
        });
    });

    const singles = Array.from(document.querySelectorAll('[data-reveal]')).filter(
        (el) => !el.closest('[data-reveal-group]')
    );

    singles.forEach((el) => {
        whenVisible([el], (target) => target.classList.add('is-visible'));
    });
};

const setupProgressBars = () => {
    document.querySelectorAll('[data-progress]').forEach((root) => {
        const value = Math.max(0, Math.min(100, Number(root.dataset.progress) || 0));
        const fills = root.querySelectorAll('[data-progress-fill]');

        fills.forEach((fill) => fill.style.setProperty('--progress', String(value / 100)));

        whenVisible([root], () => {
            fills.forEach((fill) => fill.classList.add('is-filled'));
        });
    });
};

const setupMarquees = () => {
    document.querySelectorAll('[data-marquee]').forEach((root) => {
        const viewport = root.querySelector('[data-marquee-viewport]');
        const toggle = root.querySelector('[data-marquee-toggle]');

        if (!viewport) {
            return;
        }

        let userPaused = false;
        let onscreen = true;

        const sync = () => {
            viewport.classList.toggle('is-paused', userPaused);
            viewport.classList.toggle('is-offscreen', !onscreen || document.hidden);
        };

        const updateToggle = () => {
            if (!toggle) {
                return;
            }

            toggle.setAttribute('aria-pressed', userPaused.toString());

            const label = toggle.querySelector('[data-marquee-label]');
            if (label) {
                label.textContent = userPaused ? 'Lanjutkan guliran' : 'Jeda guliran';
            }

            toggle.querySelector('[data-marquee-icon="pause"]')?.classList.toggle('hidden', userPaused);
            toggle.querySelector('[data-marquee-icon="play"]')?.classList.toggle('hidden', !userPaused);
        };

        toggle?.addEventListener('click', () => {
            userPaused = !userPaused;
            sync();
            updateToggle();
        });

        if ('IntersectionObserver' in window) {
            new IntersectionObserver(
                ([entry]) => {
                    onscreen = entry.isIntersecting;
                    sync();
                },
                { threshold: 0.05 }
            ).observe(viewport);
        }

        document.addEventListener('visibilitychange', sync);

        if (prefersReducedMotion()) {
            toggle?.classList.add('hidden');
        }

        sync();
        updateToggle();
    });
};

const setupHeader = () => {
    const header = document.querySelector('[data-site-header]');

    if (!header) {
        return;
    }

    const update = () => {
        header.dataset.scrolled = window.scrollY > 24 ? 'true' : 'false';
    };

    update();
    window.addEventListener('scroll', update, { passive: true });
};

const setupBackToTop = () => {
    const button = document.querySelector('[data-back-to-top]');

    if (!button) {
        return;
    }

    const update = () => {
        button.classList.toggle('pointer-events-none', window.scrollY < 480);
        button.classList.toggle('opacity-0', window.scrollY < 480);
        button.classList.toggle('translate-y-3', window.scrollY < 480);
    };

    update();
    window.addEventListener('scroll', update, { passive: true });

    button.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
};

const setupLightbox = () => {
    const lightbox = document.querySelector('[data-lightbox]');

    if (!lightbox) {
        return;
    }

    const image = lightbox.querySelector('[data-lightbox-image]');
    const caption = lightbox.querySelector('[data-lightbox-caption]');

    document.querySelectorAll('[data-lightbox-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            if (!(image instanceof HTMLImageElement)) {
                return;
            }

            image.src = trigger.dataset.src || '';
            image.alt = trigger.dataset.alt || '';
            if (caption) {
                caption.textContent = trigger.dataset.caption || '';
            }
            lightbox.removeAttribute('hidden');
            lightbox.setAttribute('aria-hidden', 'false');
            lightbox.querySelector('[data-lightbox-close]')?.focus();
        });
    });

    const close = () => {
        lightbox.setAttribute('hidden', '');
        lightbox.setAttribute('aria-hidden', 'true');
    };

    lightbox.querySelectorAll('[data-lightbox-close], [data-lightbox-backdrop]').forEach((el) => {
        el.addEventListener('click', close);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !lightbox.hasAttribute('hidden')) {
            close();
        }
    });
};

const setupFastNavigation = () => {
    if (navigator.connection?.saveData) {
        return;
    }

    const seen = new Set();

    const prefetch = (href) => {
        if (!href || seen.has(href)) {
            return;
        }

        let url;

        try {
            url = new URL(href, window.location.href);
        } catch {
            return;
        }

        if (url.origin !== window.location.origin || url.href === window.location.href) {
            return;
        }

        seen.add(url.href);

        const link = document.createElement('link');
        link.rel = 'prefetch';
        link.as = 'document';
        link.href = url.href;
        document.head.appendChild(link);
    };

    const eligible = (anchor) => {
        if (!(anchor instanceof HTMLAnchorElement)) {
            return false;
        }

        if (!anchor.href || anchor.hasAttribute('download') || anchor.dataset.noPrefetch !== undefined) {
            return false;
        }

        if (anchor.target && anchor.target !== '_self') {
            return false;
        }

        const href = anchor.getAttribute('href') || '';

        if (!href || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) {
            return false;
        }

        return anchor.origin === window.location.origin;
    };

    const onIntent = (event) => {
        const anchor = event.target.closest?.('a');

        if (eligible(anchor)) {
            prefetch(anchor.href);
        }
    };

    document.addEventListener('mouseover', onIntent, { passive: true });
    document.addEventListener('touchstart', onIntent, { passive: true });
    document.addEventListener('mousedown', onIntent, { passive: true });
    document.addEventListener('focusin', onIntent);
};

const boot = () => {
    document.documentElement.classList.add('reveal-ready');
    setupReveal();
    setupProgressBars();
    setupMarquees();
    setupHeader();
    setupBackToTop();
    setupLightbox();
    setupFastNavigation();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}
