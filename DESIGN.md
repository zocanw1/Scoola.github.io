---
version: 1.0
name: Scoola Design System (Runway Editorial)
description: A premium, high-contrast "Bento-style" interface designed for Scoola. It relies on a structural, editorial aesthetic featuring stark black-and-white elements against a neutral gray canvas. The design prioritizes typography (Inter), geometric precision, and spacious padding over shadows or gradients. 

---

## 🎨 Color Palette

Scoola uses a restrained, high-contrast color palette to maintain a premium feel.

### Surface & Canvas
- **Canvas (`--color-canvas`):** `#C8C8C8` — A solid, neutral gray floor. This provides the contrast needed for white cards to pop without relying on drop shadows.
- **Surface (`#ffffff`):** Pure white. Used for all primary cards, panels, and forms.
- **Canvas Warm (`--color-canvas-warm`):** `#fefefe` — Used for subtle highlights, like mobile agenda time badges.

### Ink & Text
- **Ink (`--color-ink`):** `#030303` — The primary text color for maximum readability.
- **Ink Soft (`--color-ink-soft`):** `#1a1a1a` — Used for navigation links and slightly softened headers.
- **Graphite (`--color-graphite`):** `#404040` — Standard body text.
- **Slate (`--color-slate`):** `#676f7b` — Secondary metadata.
- **Stone (`--color-stone`):** `#939393` — Used for micro-caps, eyebrows, and tertiary labels.

### Accents & Borders
- **Primary (`--color-primary`):** `#000000` — Pure black for primary buttons and active states.
- **On-Primary (`--color-on-primary`):** `#ffffff` — White text over primary black buttons.
- **Hairline (`--color-hairline`):** `#e7eaf0` — The standard 1px border color used to define all structural boundaries (cards, tables, dividers).

---

## 🔤 Typography

Scoola exclusively uses **Inter** (`--font-family-base`) for a clean, modernist geometric feel.

### Hierarchy
- **Display Title (`.display-title`):** 48px, Weight 400, Tracking tight (-1.2px). Used for main page headers (e.g., "Data Kelas").
- **Heading SM (`.text-heading-sm`):** 14px, Weight 700, Tracking 0.1em, Uppercase. Used for card titles.
- **Eyebrow (`.eyebrow` / `.text-micro-caps`):** 11px, Weight 700 (or 500), Tracking 0.35px, Uppercase. Used above main titles or as small structural labels.
- **Body (`.text-body`):** 16px, Weight 400, Line-height 1.5. Used for editorial descriptions.
- **Meta (`.text-meta`):** 13px, Weight 400. Used for breadcrumbs and minor details.

---

## 📐 Layout & Grid System (Bento Style)

Scoola's layout relies on distinct, bordered structural blocks (Bento Grid) with generous internal padding.

### 1. Responsive Card Grid (`.responsive-card-grid`)
- **Desktop:** Displays as a 2-column grid (`50% / 50%`) with a gap of `24px` (`var(--spacing-lg)`).
- **Dynamic Asymmetry (Odd Count Rule):** If a list contains an odd number of items (e.g., 3 items), the **first item automatically spans full-width (100%)** as a hero element, while the subsequent 2 items sit side-by-side (50% / 50%).
- **Mobile (`max-width: 768px`):** Automatically collapses to a single vertical column (`1fr`) with `100%` width, preventing horizontal overflow.

### 2. Stats Grid (`.stats-grid`)
- **Desktop:** Displays as an `auto-fit` grid with a minimum width of `340px` per card.
- **Mobile:** Collapses to a single vertical column.

### 3. Spacing System
- **Card Padding:** Generous padding is a signature of this design. Typically `32px` to `48px` on desktop, scaling down to `24px` on mobile.
- **Gaps:** Standard grid gap is `var(--spacing-md)` (16px) or `var(--spacing-lg)` (24px).
- **Section Margin:** Distance between major horizontal sections is `var(--spacing-section)` (64px).

---

## 🧩 UI Components

### 1. Cards
Cards are the fundamental building blocks of the UI.
- **Style:** Background `#ffffff`, Border `1px solid var(--color-hairline)`, Border Radius `12px` (or `16px` for larger structural panels).
- **Depth:** **Zero drop shadows.** Depth is purely achieved through the contrast between the white card and the `#C8C8C8` canvas.

### 2. Buttons
- **Primary Button (`.btn-primary`):** Pure black (`#000000`), white text, fully rounded (`var(--rounded-full)` / pill shape), uppercase, bold. Hover effect uses a dramatic color inversion (`filter: invert(1)`).
- **Ghost Button (`.btn-ghost`):** Transparent background, black border (`#030303`), black text. Fully rounded pill shape.
- **FAB (Floating Action Button):** Fixed at bottom right. Black circle, expands to show text label on hover.

### 3. Tables (`.data-table` / `.responsive-table`)
- **Desktop:** Standard tabular layout with hairline bottom borders.
- **Mobile (Max 768px):** Transforms entirely into a "Card List" format. The `<thead>` is hidden. Every `<tr>` becomes a bordered block, and every `<td>` becomes a flex row (`display: flex; justify-content: space-between`). The table headers are injected dynamically via the `data-label` attribute on each `<td>` using CSS pseudo-elements (`::before`).

### 4. Agenda / List Items (`.agenda-list`)
- **Desktop:** Horizontal flex layout. Time on the left, subject/details in the center (flex-1), and status/teacher on the right.
- **Mobile (Max 768px):** Stacks vertically. The time element turns into a compact highlighted badge with a warm gray background (`--color-canvas-warm`).

---

## 📱 Mobile Responsiveness (The 768px Rule)

All mobile responsive behaviors are driven globally in `runway.css` under the `@media (max-width: 768px)` block to guarantee consistency without writing inline responsive classes in HTML.

1.  **Padding Shrinkage:** Global page content padding reduces from `64px` to `32px 16px`. Card padding reduces to `24px`.
2.  **Grid Flattening:** Any element using `.stats-grid`, `.responsive-card-grid`, or inline `display: grid` is forced to `grid-template-columns: 1fr !important`.
3.  **Flex Wrapping:** Any horizontal `display: flex` container is forced to `flex-wrap: wrap` to prevent elements from bleeding off the screen edges.
4.  **Typography Adjustments:** Huge `.display-title` headers shrink to `28px` to fit narrow mobile viewports.
