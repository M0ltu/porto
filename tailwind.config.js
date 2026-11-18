import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                surface: 'var(--color-surface)',
                'surface-raised': 'var(--color-surface-raised)',
                ink: 'var(--color-ink)',
                'ink-muted': 'var(--color-ink-muted)',
                'ink-faint': 'var(--color-ink-faint)',
                line: 'var(--color-line)',
                accent: 'var(--color-accent)',
                'accent-ink': 'var(--color-accent-ink)',
                ring: 'var(--color-ring)',
                'accent-soft': 'var(--color-accent-soft)',
                success: 'var(--color-success)',
                'success-soft': 'var(--color-success-soft)',
                danger: 'var(--color-danger)',
                'danger-soft': 'var(--color-danger-soft)',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            borderRadius: {
                xl: '12px',
                '2xl': '16px',
            },
            boxShadow: {
                soft: '0 1px 2px 0 rgb(0 0 0 / 0.04), 0 8px 24px -12px rgb(0 0 0 / 0.18)',
                lift: '0 2px 4px 0 rgb(0 0 0 / 0.05), 0 16px 40px -16px rgb(0 0 0 / 0.25)',
            },
            maxWidth: {
                page: '76rem',
                prose: '68ch',
            },
            transitionTimingFunction: {
                'out-soft': 'cubic-bezier(0.22, 1, 0.36, 1)',
            },
        },
    },

    plugins: [forms],
};
