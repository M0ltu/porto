# Design System Master File

> **LOGIC:** When building a specific page, first check `design-system/pages/[page-name].md`.
> If that file exists, its rules **override** this Master file.
> If not, strictly follow the rules below.

---

**Project:** Pongo Portfolio
**Generated:** 2026-09-29 13:35:10
**Category:** Portfolio/Personal
**Design Dials:** Variance 7/10 (Balanced / Modern) | Motion 7/10 (Standard) | Density 3/10 (Spacious)
**Last reconciled with code:** 2026-09-29

---

## Deviations From Generator Output

The generator emits recommendations. Four of them were **rejected** during implementation.
The values in this file describe what is actually in `resources/css/app.css` and the Blade
components — not what the generator originally proposed.

| Item | Generator proposed | Applied | Why |
|------|--------------------|---------|-----|
| Heading/body font | Caveat + Quicksand | JetBrains Mono + IBM Plex Sans | Caveat is a handwriting face; it contradicted both the "developer portfolio" product type and Brutalism's own "default fonts" rule. Swap in the `Developer Mono` pairing from the typography domain. |
| Accent | `#2563EB` | `#ff4d00` | `#2563EB` is Tailwind `blue-600` — the default, and the exact hue the anti-slop rules flag. Vivid orange on a monochrome base survives both themes. |
| Motion library | GSAP | `IntersectionObserver` + CSS | No build-time dependency. The stagger, the reveal, and the reduced-motion fallback are ~40 lines in `resources/js/app.js`. |
| Card/button shape | 8–16px radius, elevation | 0px radius, hairline borders, colour-only hover | Matches Brutalism. The generator's own card spec contradicted the style it selected. |

`--color-accent` is a single variable. Changing it in `app.css` re-themes the whole site.

---

## Global Rules

### Color Palette

Tokens are declared as plain custom properties on `:root` / `.dark`, then mapped into
Tailwind through `@theme inline`. `inline` is what lets one utility resolve per theme.

| Role | Token | Light | Dark |
|------|-------|-------|------|
| Background | `--color-surface` | `#fafafa` | `#0a0a0a` |
| Raised surface | `--color-surface-raised` | `#ffffff` | `#131313` |
| Foreground | `--color-ink` | `#09090b` | `#fafafa` |
| Muted text | `--color-ink-muted` | `#52525b` | `#a1a1aa` |
| Faint text | `--color-ink-faint` | `#71717a` | `#71717a` |
| Border | `--color-line` | `#e4e4e7` | `#262626` |
| Accent | `--color-accent` | `#ff4d00` | `#ff4d00` |
| On accent | `--color-accent-ink` | `#0a0a0a` | `#0a0a0a` |
| Focus ring | `--color-ring` | `#09090b` | `#fafafa` |

**Color Notes:** Monochrome + one accent. The accent is used sparingly — availability dot, active
nav state, project metric, hover borders, focus rings. Never as a large fill.

Contrast, both themes: body text 7.1:1 light / 7.9:1 dark against `--color-surface`. Accent on
accent-ink 7.1:1. Accent as large text on the light background is 3.1:1, which clears the 3:1
bar for large text only — do not use it for body copy.

### Typography

- **Heading + display:** JetBrains Mono, 400/500/700 — self-hosted via Bunny
- **Body:** IBM Plex Sans, 400/500/600 — self-hosted via Bunny
- **Delivery:** `bunny()` in `vite.config.js` → `@fonts` in the layout emits preloads + `@font-face`

Both are wired through `laravel-vite-plugin/fonts`, not a Google Fonts `@import`. The CSS variables
`--font-jetbrains-mono` and `--font-ibm-plex-sans` come from the generated `fonts-manifest.json`,
so `@theme` must reference those exact names.

The mono family carries headings, labels, and body copy in the project cards. Sans is used for
prose paragraphs only. That split is the main thing keeping the page from reading as generic.

### Spacing

Density 3/10 — spacious. Sections are `py-20` on mobile, `py-28` from `sm` up. Max content width
is `max-w-6xl` with `px-5 sm:px-8` gutters. Sibling spacing uses `gap-*`, never margins.

### Radius & Elevation

Zero border radius throughout — no `rounded-*` except the 1.5px availability dot. No box shadows.
Depth comes from hairline `border-line` and a 1px `bg-line` grid behind the hero stats.

---

## Style Guidelines

**Style:** Brutalism

**Keywords:** Raw, high contrast, visible borders, asymmetric, anti-design

**Key Effects:** sharp corners, mono display type, hairline rules, colour-only hovers

### Page Pattern

**Pattern Name:** Scroll-Triggered Storytelling

Scroll reveal is the only scroll-linked effect on the page. There is no parallax and no
scroll-jacking — the ux domain flags both as high-severity nausea risks. DOM reading order is
complete without the animation.

- **Section Order:** Hero > Selected work > Stack > Contact
- **Reveal:** `data-reveal` attribute, flipped to `"visible"` by `IntersectionObserver` in `app.js`

---

## Motion

No animation library. Three behaviours, all in `resources/js/app.js`:

| Behaviour | Mechanism |
|-----------|-----------|
| Scroll reveal | `IntersectionObserver`, `rootMargin: '0px 0px -10% 0px'`, `threshold: 0.1` |
| Stagger | `transitionDelay` set per entry within one callback batch, `index * 60ms` |
| Header shrink | `scroll` listener with a cached 32px threshold, sets `data-scrolled` |

Two rules that must hold:

1. **Reveal styles are scoped under `.js`** in `app.css`. Without scripting the elements stay
   visible instead of stuck at `opacity: 0`. The observer is an enhancement, never the reason
   content is readable.
2. **`prefers-reduced-motion: reduce` renders the end state immediately** — both in the CSS
   media query and in the JS, which skips the observer and marks everything visible.

Hover transitions stay in the 150–300ms range. Hovers change colour or border only, never
layout bounds.

---

## Anti-Patterns (Do NOT Use)

- ❌ Emojis as icons — inline SVG, 1.5px stroke, `aria-hidden="true"`
- ❌ `rounded-*` or box shadows — Brutalism is flat with hairlines
- ❌ Layout-shifting hovers — no scale or lift transforms
- ❌ Inter or a system font stack as the display face
- ❌ The generator's Caveat/Quicksand/blue values, unless the deviation table above is reverted deliberately
- ❌ GSAP or any animation dependency for this page
- ❌ Corporate templates, generic centred-hero layouts
- ❌ Low contrast text, invisible focus states, missing `prefers-reduced-motion`

---

## Pre-Delivery Checklist

- [ ] No emojis used as icons
- [ ] `cursor-pointer` on every clickable element
- [ ] Hover states transition colour in 150–300ms
- [ ] Text contrast 4.5:1 in **both** themes, checked independently
- [ ] Focus states visible for keyboard navigation
- [ ] `prefers-reduced-motion` respected in CSS *and* JS
- [ ] Page still readable with JavaScript disabled
- [ ] Responsive at 375 / 768 / 1024 / 1440
- [ ] No content hidden behind the fixed header (`scroll-mt-24` on anchor targets)
- [ ] No horizontal scroll on mobile
- [ ] Dark toggle applies before first paint — no flash of the wrong theme
