# Admin Dashboard Pie Chart And Gender Field Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a second real-data student chart to the admin dashboard and persist `L/P` values for siswa and guru records with `L` as the default for existing data.

**Architecture:** Extend the existing admin dashboard controller to aggregate student counts by class and render them in a new dashboard card without replacing the 7-day trend chart. Add `jenis_kelamin` columns to `siswa` and `guru`, wire the field through models/controllers/forms, and cover the behavior with focused feature tests.

**Tech Stack:** Laravel, Blade, PHPUnit feature tests, MySQL/SQLite migrations

---

### Task 1: Lock the new behavior with failing tests

**Files:**
- Modify: `tests/Feature/DashboardMetricsTest.php`
- Modify: `tests/Feature/SiswaTest.php`
- Modify: `tests/Feature/GuruTest.php`

- [ ] Add a failing dashboard assertion for real student composition data.
- [ ] Add failing siswa create/update assertions for `jenis_kelamin`.
- [ ] Add failing guru create/update assertions for `jenis_kelamin`.
- [ ] Run the focused tests and confirm they fail for the missing feature.

### Task 2: Persist `jenis_kelamin` in siswa and guru flows

**Files:**
- Create: `database/migrations/2026_06_01_000001_add_jenis_kelamin_to_siswa_and_guru.php`
- Modify: `app/Models/Siswa.php`
- Modify: `app/Models/Guru.php`
- Modify: `app/Http/Controllers/SiswaController.php`
- Modify: `app/Http/Controllers/GuruController.php`
- Modify: `resources/views/admin/siswa/siswa-create.blade.php`
- Modify: `resources/views/admin/siswa/siswa-edit.blade.php`
- Modify: `resources/views/admin/guru/guru-create.blade.php`
- Modify: `resources/views/admin/guru/guru-edit.blade.php`

- [ ] Add migration columns with `L` default and backfill-safe defaults.
- [ ] Pass `jenis_kelamin` through model fillables and controller validation/store/update/import flows.
- [ ] Add `L/P` inputs to create/edit forms for siswa and guru.

### Task 3: Add the new student pie chart card to admin dashboard

**Files:**
- Modify: `app/Http/Controllers/AdminDashboardController.php`
- Modify: `resources/views/admin/dashboard.blade.php`

- [ ] Compute `XI-SIJA 1` and `XI-SIJA 2` totals from real siswa data in the controller.
- [ ] Render a new dashboard card for student composition while keeping the existing 7-day chart untouched.
- [ ] Show counts, percentages, and a visible empty state if there is no student data.

### Task 4: Verify the implementation

**Files:**
- Modify: `tests/Feature/DashboardMetricsTest.php`
- Modify: `tests/Feature/SiswaTest.php`
- Modify: `tests/Feature/GuruTest.php`

- [ ] Run the focused feature tests for dashboard, siswa, and guru flows.
- [ ] Run `php artisan view:cache` to verify the updated Blade templates compile.
