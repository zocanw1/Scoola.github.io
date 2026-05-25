# 🎨 Buku Panduan Desain Scoola (Anime Edition v1.0)
**Konsep Utama:** Manga-Pop & Neobrutalism UI

Dokumen ini adalah acuan mutlak (100% akurat) untuk pengembangan antarmuka Scoola. Setiap komponen harus mematuhi fisika desain, palet warna, dan aturan tipografi di bawah ini agar identitas visual tetap konsisten, ceria, dan energik.

---

## 1. 🎨 Palet Warna Utama (CSS Variables)

Semua warna wajib dideklarasikan di dalam `:root` dan menggunakan kontras tinggi. Dilarang keras menggunakan gradien halus (kecuali untuk *pattern* garis/titik), semua warna harus tampil solid.

| Nama Variabel | Kode HEX | Peruntukan Utama & Vibe |
| :--- | :--- | :--- |
| `--sakura` | `#FF7675` | Tombol Primer, Alert Error, Ornamen Stiker. *(Energi, Cinta, Manis)* |
| `--cyber` | `#00CEC9` | Badges, Lencana Info, Tombol Sekunder. *(Masa Depan, Teknologi, Segar)* |
| `--cosmo` | `#6C5CE7` | Background Panel Hero Utama, Aksen Titik. *(Sihir, Modern, Kontras)* |
| `--gold` | `#FDCB6E` | Highlight Teks Brand, Aksesoris Bintang/Lingkaran. *(Bahagia, Sorotan)* |
| `--midnight` | `#1E1B29` | Warna absolut untuk Teks, Border Utama (3px/4px), dan Shadow. |
| `--mochi` | `#FAF9FF` | Latar Belakang (Background) global Body dan Input non-fokus. |
| `--white` | `#FFFFFF` | Latar Belakang Container/Card (seperti Login Card). |

---

## 2. 🔤 Sistem Tipografi

Hanya menggunakan kombinasi dua font dari Google Fonts. Pastikan tautan CDN font disertakan di `<head>`.

* **Font Utama (Heading & Elemen Aksi): `Fredoka One`, cursive**
    * **Penggunaan:** `<h1>`, `<h2>`, Judul Card, Brand Text, Tombol (`.btn-full`), dan Stiker Kaomoji.
    * **Karakter:** Tebal, membulat, ekspresif memberikan kesan judul komik 2D.
* **Font Konten (Body Text): `Nunito`, sans-serif**
    * **Penggunaan:** `<p>`, `<label>`, `<input>`, tabel, dan deskripsi panjang.
    * **Ketebalan (Weight):** `400` (Regular), `500` (Medium placeholder), `600` (Semi-bold isi input/desc), `700` (Bold), `800` (Extra-bold untuk label/footer).
    * *Fallback Font:* `Poppins`, sans-serif.

---

## 3. 📐 Fisika Desain Neobrutalism (Aturan Mutlak)

Konsep dasar antarmuka ini meniru panel komik cetak. Interaksi harus terasa mekanis dan kaku.

### A. Aturan Garis Tepi (The 3px / 4px Rule)
* **Elemen Standar** (Tombol, Input, Badges, Alert): Wajib dibungkus border tebal `border: 3px solid var(--midnight);`
* **Container Besar** (Login Card, Dashboard Panel): Wajib dibungkus border ekstra tebal `border: 4px solid var(--midnight);`

### B. Bayangan Padat (Hard Shadows)
DILARANG menggunakan *blur* pada `box-shadow` (misal: `rgba(0,0,0,0.1)`). Semua bayangan ditarik ke kanan bawah secara solid.
* **Input Field & Badges:** `box-shadow: 4px 4px 0px 0px var(--midnight);`
* **Tombol & Hero Desc:** `box-shadow: 5px 5px 0px 0px var(--midnight);`
* **Card/Container:** `box-shadow: 8px 8px 0px 0px var(--midnight);`

### C. Efek Interaksi (Hover & Active States)
Saat kursor berinteraksi, elemen seolah melayang dan bayangannya memanjang.
* **Hover (Melayang):** Pindah ke kiri-atas, shadow bertambah tebal.
    ```css
    transform: translate(-2px, -2px);
    box-shadow: [nilai_awal + 2px] [nilai_awal + 2px] 0px var(--midnight);
    ```
* **Active (Klik/Ditekan):** Elemen tertekan ke bawah, shadow mengecil drastis.
    ```css
    transform: translate(2px, 2px);
    box-shadow: 2px 2px 0px var(--midnight);
    ```

---

## 4. ✨ Latar Belakang & Elemen Dekoratif

### A. Pola Background (Patterns)
* **Background Utama (`--mochi` Area):** Menggunakan pola *polkadot/screentone* khas manga.
    ```css
    background-image: radial-gradient(var(--cosmo) 1px, transparent 0);
    background-size: 24px 24px; /* Dashboard: 32px 32px */
    ```
* **Background Hero Panel (`--cosmo` Area):** Menggunakan pola garis diagonal silang.
    ```css
    background-image: 
        linear-gradient(45deg, rgba(30, 27, 41, 0.05) 25%, transparent 25%), 
        linear-gradient(-45deg, rgba(30, 27, 41, 0.05) 25%, transparent 25%), 
        linear-gradient(45deg, transparent 75%, rgba(30, 27, 41, 0.05) 75%), 
        linear-gradient(-45deg, transparent 75%, rgba(30, 27, 41, 0.05) 75%);
    background-size: 40px 40px;
    background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
    ```

### B. Stroke & Text Shadow (Gaya Manga)
Teks besar (Judul, Brand) wajib menggunakan ilusi *outline* ganda.
```css
text-shadow: 5px 5px 0px var(--midnight);
-webkit-text-stroke: 2px var(--midnight); /* 3px untuk ikon dekoratif */