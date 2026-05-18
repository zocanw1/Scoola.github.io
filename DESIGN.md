---
version: 1.0
name: Sistem Desain Scoola (Runway Editorial)
description: Antarmuka bergaya "Bento Grid" premium dengan kontras tinggi yang dirancang khusus untuk Scoola. Mengusung estetika editorial dan terstruktur yang menonjolkan elemen hitam-putih tegas di atas latar (canvas) abu-abu netral. Desain ini mengutamakan tipografi (Inter), presisi geometris, serta ruang padding yang lega tanpa mengandalkan bayangan (shadow) atau gradasi.

---

## 🎨 Palet Warna

Scoola menggunakan palet warna yang selektif dan berkontras tinggi untuk mempertahankan kesan premium dan elegan.

### Permukaan & Latar (Surface & Canvas)
- **Canvas (`--color-canvas`):** `#C8C8C8` — Latar abu-abu netral yang solid. Warna ini memberikan kontras yang tepat agar kotak (card) putih terlihat menonjol tanpa memerlukan efek bayangan (drop shadow).
- **Surface (`#ffffff`):** Putih bersih. Digunakan untuk semua kotak (card) utama, panel, dan formulir.
- **Canvas Warm (`--color-canvas-warm`):** `#fefefe` — Digunakan untuk sorotan halus, seperti latar badge waktu pada agenda di tampilan seluler.

### Tinta & Teks (Ink & Text)
- **Ink (`--color-ink`):** `#030303` — Warna teks utama untuk keterbacaan maksimal.
- **Ink Soft (`--color-ink-soft`):** `#1a1a1a` — Digunakan untuk tautan navigasi dan judul yang sedikit diperhalus.
- **Graphite (`--color-graphite`):** `#404040` — Teks paragraf (body) standar.
- **Slate (`--color-slate`):** `#676f7b` — Teks metadata sekunder.
- **Stone (`--color-stone`):** `#939393` — Digunakan untuk teks berukuran kecil (micro-caps), eyebrow, dan label tersier.

### Aksen & Garis Batas (Accents & Borders)
- **Primary (`--color-primary`):** `#000000` — Hitam pekat untuk tombol utama dan status aktif.
- **On-Primary (`--color-on-primary`):** `#ffffff` — Teks putih di atas tombol hitam utama.
- **Hairline (`--color-hairline`):** `#e7eaf0` — Warna garis batas (border) 1px standar yang digunakan untuk memperjelas struktur komponen (kotak, tabel, pembatas).

---

## 🔤 Tipografi

Scoola secara eksklusif menggunakan **Inter** (`--font-family-base`) untuk memberikan kesan geometris, bersih, dan modernis.

### Hierarki
- **Display Title (`.display-title`):** 48px, Bobot 400, Spasi huruf rapat (-1.2px). Digunakan untuk judul utama halaman (contoh: "Data Kelas").
- **Heading SM (`.text-heading-sm`):** 14px, Bobot 700, Spasi huruf 0.1em, Huruf Kapital. Digunakan untuk judul kotak (card).
- **Eyebrow (`.eyebrow` / `.text-micro-caps`):** 11px, Bobot 700 (atau 500), Spasi huruf 0.35px, Huruf Kapital. Digunakan di atas judul utama atau sebagai label struktur kecil.
- **Body (`.text-body`):** 16px, Bobot 400, Tinggi baris 1.5. Digunakan untuk deskripsi editorial.
- **Meta (`.text-meta`):** 13px, Bobot 400. Digunakan untuk breadcrumb dan detail pendukung.

---

## 📐 Sistem Tata Letak & Grid (Bento Style)

Tata letak Scoola mengandalkan blok-blok terstruktur dengan garis batas yang jelas (Bento Grid) serta padding internal yang lega.

### 1. Responsive Card Grid (`.responsive-card-grid`)
- **Desktop:** Ditampilkan sebagai grid 2 kolom (`50% / 50%`) dengan jarak (gap) `24px` (`var(--spacing-lg)`).
- **Asimetri Dinamis (Aturan Jumlah Ganjil):** Jika daftar berisi jumlah item ganjil (contoh: 3 item), **item pertama akan otomatis melebar penuh (100%)** sebagai elemen utama (hero), sedangkan 2 item berikutnya akan sejajar berdampingan (50% / 50%).
- **Seluler / Mobile (`max-width: 768px`):** Otomatis berubah menjadi 1 kolom vertikal (`1fr`) dengan lebar `100%`, guna mencegah elemen terdorong keluar layar.

### 2. Stats Grid (`.stats-grid`)
- **Desktop:** Ditampilkan sebagai grid `auto-fit` dengan lebar minimal `340px` per kotak.
- **Seluler / Mobile:** Otomatis berubah menjadi 1 kolom vertikal.

### 3. Sistem Spasi
- **Padding Kotak (Card):** Padding yang luas dan lega adalah ciri khas desain ini. Umumnya `32px` hingga `48px` pada desktop, dan disesuaikan menjadi `24px` pada perangkat seluler.
- **Jarak (Gap):** Jarak standar antar grid adalah `var(--spacing-md)` (16px) atau `var(--spacing-lg)` (24px).
- **Margin Seksi:** Jarak vertikal antar bagian utama adalah `var(--spacing-section)` (64px).

---

## 🧩 Komponen Antarmuka (UI Components)

### 1. Kotak (Cards)
Kotak (card) adalah fondasi utama pembentuk antarmuka.
- **Gaya:** Latar `#ffffff`, Garis batas `1px solid var(--color-hairline)`, Sudut melengkung `12px` (atau `16px` untuk panel besar).
- **Kedalaman:** **Tanpa bayangan (zero drop shadows).** Dimensi dan kedalaman murni dicapai melalui kontras antara kotak putih dan latar belakang abu-abu `#C8C8C8`.

### 2. Tombol (Buttons)
- **Tombol Utama (`.btn-primary`):** Hitam pekat (`#000000`), teks putih, melengkung penuh (`var(--rounded-full)` / bentuk pil), huruf kapital, tebal. Efek hover membalikkan warna secara dramatis (`filter: invert(1)`).
- **Tombol Transparan (`.btn-ghost`):** Latar transparan, garis batas hitam (`#030303`), teks hitam. Bentuk pil melengkung penuh.
- **FAB (Floating Action Button):** Posisi tetap di kanan bawah. Lingkaran hitam yang akan memanjang menampilkan teks label saat di-hover.

### 3. Tabel (`.data-table` / `.responsive-table`)
- **Desktop:** Tata letak tabel standar dengan garis batas bawah tipis.
- **Seluler / Mobile (Max 768px):** Berubah total menjadi format "Daftar Kotak" (Card List). Bagian `<thead>` disembunyikan. Setiap baris `<tr>` menjadi kotak tersendiri, dan setiap kolom `<td>` menjadi baris fleksibel (`display: flex; justify-content: space-between`). Judul kolom disisipkan secara dinamis melalui atribut `data-label` pada setiap `<td>` menggunakan elemen pseudo CSS (`::before`).

### 4. Item Agenda (`.agenda-list`)
- **Desktop:** Tata letak horizontal. Waktu di kiri, subjek/detail di tengah (`flex-1`), dan status/pengajar di kanan.
- **Seluler / Mobile (Max 768px):** Ditumpuk secara vertikal. Elemen waktu berubah menjadi badge ringkas berlatar abu-abu hangat (`--color-canvas-warm`).

---

## 📱 Responsivitas Seluler (Aturan 768px)

Semua penyesuaian untuk tampilan seluler diatur secara global di dalam file `runway.css` pada blok `@media (max-width: 768px)`. Hal ini menjamin konsistensi di seluruh halaman tanpa perlu menulis kelas responsif secara manual di HTML.

1.  **Penyesuaian Padding:** Padding konten halaman berkurang dari `64px` menjadi `32px 16px`. Padding pada kotak (card) disesuaikan menjadi `24px`.
2.  **Penyederhanaan Grid:** Semua elemen yang menggunakan `.stats-grid`, `.responsive-card-grid`, atau `display: grid` dipaksa menjadi 1 kolom vertikal (`grid-template-columns: 1fr !important`).
3.  **Pembungkusan Flex (Flex Wrap):** Semua kontainer `display: flex` horizontal dipaksa untuk membungkus (`flex-wrap: wrap`) agar elemen tidak melampaui batas lebar layar.
4.  **Penyesuaian Tipografi:** Judul besar `.display-title` disesuaikan ukurannya menjadi `28px` agar pas di layar ponsel yang sempit.
