# Admin Clean Modern Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Redesign the Scoola admin area to match the clean modern white-blue design guide while preserving every admin route, form, table, and hosted behavior.

**Architecture:** Keep the redesign presentation-only. Update the shared admin layout and shared admin component stylesheet so existing Blade pages inherit the new visual system without changing controllers, route names, form fields, request methods, or database-facing code.

**Tech Stack:** Laravel Blade, Bootstrap Icons, Vite/Tailwind build pipeline, PHPUnit feature tests.

---

### Task 1: Shared Admin Shell

**Files:**
- Modify: `resources/views/layouts/admin.blade.php`

- [ ] Replace the old Manga-Pop shell tokens with clean modern tokens from `Panduan Desain Web.zip`: primary blue `#2563eb`, dark heading `#101828`, muted text `#667085`, soft blue `#eff6ff`, white background, thin borders, soft shadows, and rounded cards.
- [ ] Keep all existing sidebar links, `route(...)` calls, role guards, logout form, breadcrumb include, theme toggle, mobile menu button, and `@yield('content')` untouched.
- [ ] Add late admin-only CSS overrides for legacy page-local classes such as `.neo-card`, `.manga-card`, `.fab-button`, `.btn-fab`, `.neo-btn-primary`, and `.manga-hover-effect` so older inline-heavy admin pages visually follow the same clean guide.

### Task 2: Shared Admin Components

**Files:**
- Modify: `resources/views/layouts/partials/admin-manga-components.blade.php`

- [ ] Restyle `.mp-*` shared components from hard-shadow neobrutalism to clean modern cards, badges, tables, forms, tabs, empty states, alerts, floating action buttons, and mobile cards.
- [ ] Preserve every class name so all existing admin Blade files keep working without template rewrites.
- [ ] Keep responsive behavior: mobile-only/desktop-only helpers, horizontal table scroll, stacked forms, sticky mobile actions, and touch-device hover suppression.

### Task 3: Verification

**Files:**
- Test: `tests/Feature/AdminDashboardNavigationTest.php`
- Test: `tests/Feature/AdminRegressionTest.php`
- Test: existing Blade compilation through `php artisan view:cache`

- [ ] Run `php artisan test --filter=AdminDashboardNavigationTest`.
- [ ] Run `php artisan test --filter=AdminRegressionTest`.
- [ ] Run `php artisan view:cache`.
- [ ] Run `npm run build`.
- [ ] Check `git diff --stat` and `git status --short` to confirm only scoped presentation files and this plan were added by this task.
