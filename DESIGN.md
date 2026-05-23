🎨 Panduan Sistem Desain "Manga-Pop" (Scoola Anime Style)

Selamat datang di dokumentasi resmi Sistem Desain Manga-Pop. Panduan ini menggabungkan estetika budaya anime yang penuh energi (colorful), ceria, dan kawaii dengan tren tata letak Neobrutalism modern (garis tepi tebal, kontras tinggi, dan bayangan solid tanpa blur).

Gunakan panduan ini sebagai acuan (blueprint) saat merancang halaman baru di platform Scoola agar seluruh visual tetap konsisten.

🚀 Filosofi Desain

Gaya desain ini memiliki misi untuk mengubah antarmuka sekolah yang biasanya kaku menjadi ruang digital yang interaktif dan menyenangkan. Tiga pilar utamanya adalah:

Komik Strip & Manga Vibe: Menggunakan tinta pembatas (outline) hitam tebal yang memberi kesan gambar tangan 2D panel manga.

Tabrakan Warna Berani: Tidak takut memadukan warna pastel neon yang kontras tinggi secara berdampingan.

Ekspresif & Interaktif: Menggunakan kaomoji (emotikon teks Jepang) dan efek melayang (hover) mekanis yang memuaskan saat disentuh atau diklik.

🎨 Palet Warna (The Manga Palette)

Warna-warna ini wajib didefinisikan di dalam variabel CSS atau Tailwind config Anda untuk kemudahan penggunaan.

Nama Warna

Kode Hex

Variabel CSS

Karakter & Kegunaan

Midnight Ink

#1E1B29

--midnight

Warna garis luar (border), teks utama, dan bayangan solid.

Sakura Burst

#FF7675

--sakura

Warna aksen merah muda cerah. Sangat baik untuk tombol aksi utama (CTA).

Cyber Oasis

#00CEC9

--cyber

Warna cyan futuristik untuk lencana, info penting, atau status aktif.

Cosmo Violet

#6C5CE7

--cosmo

Warna latar belakang panel hero, memberikan kesan magis dan modern.

Chibi Gold

#FDCB6E

--gold

Warna kuning emas penarik perhatian, dekorasi bintang, dan lencana sekunder.

Mochi Cream

#FAF9FF

--mochi

Warna latar belakang halaman utama. Lembut di mata dan bersih.

Pure White

#FFFFFF

--white

Warna kartu kontainer utama (surface).

🔤 Sistem Tipografi

Kami menggunakan kombinasi font sans-serif geometris yang bulat agar ramah pengguna dan memiliki keterbacaan yang tinggi.

Font Judul Utama / Heading (<h1>, <h2>): Fredoka One (Tebal, bulat, dan sangat bergaya anime).

Font Konten / Antarmuka / Paragraf (<body>, <p>, input): Nunito atau Poppins (Sangat bersih dan mudah dibaca di berbagai resolusi layar).

Aturan Skala Tipografi:

Hero Title: Fredoka One, 48px - 64px dengan -webkit-text-stroke: 2px var(--midnight) dan bayangan teks solid.

Section Heading: Fredoka One, 32px - 38px.

Body Text: Nunito, 15px - 17px, ketebalan 600 (Semi-bold) atau 700 (Bold) untuk memperkuat kontras teks di atas elemen berwarna.

⚡ 4 Aturan Utama Visual (The Golden Rules)

Agar halaman baru Anda benar-benar terasa seperti satu kesatuan dengan desain login Scoola, ikuti aturan wajib berikut:

1. Aturan Garis Tepi Tebal (The 3px/4px Rule)

Setiap elemen penampung (card), kolom input, tombol, dan lencana wajib memiliki garis tepi solid berwarna gelap:

border: 3px solid var(--midnight); /* 4px untuk elemen yang lebih besar */


2. Bayangan Solid Tanpa Blur (Hard Shadow)

Jangan pernah menggunakan bayangan pudar (soft-blurry shadow). Gunakan bayangan tajam bersudut dengan pergeseran arah searah jarum jam (kanan-bawah):

box-shadow: 5px 5px 0px 0px var(--midnight);


3. Efek Hover Mekanis yang Memuaskan (Hover Interaction)

Saat pengguna mengarahkan kursor pada tombol atau kartu interaktif, buat elemen tersebut seolah terdorong ke belakang dengan memindahkan bayangannya secara simultan:

/* Efek Hover */
.element:hover {
    transform: translate(-2px, -2px);
    box-shadow: 7px 7px 0px 0px var(--midnight);
}

/* Efek Klik Aktif */
.element:active {
    transform: translate(2px, 2px);
    box-shadow: 2px 2px 0px 0px var(--midnight);
}


4. Ekspresi Kaomoji & Stiker (Playful Elements)

Tambahkan teks kaomoji khas anime di atas sudut container atau di sela-sela form untuk mencairkan suasana:

( ≧◡≦ ) — Untuk sambutan hangat.

(✿◡‿◡) — Untuk footer atau catatan kaki penutup.

Lencana Miring (transform: rotate(-2deg)) — Memberikan kesan dinamis seperti tempelan stiker fisik.

🛠️ Blueprint Kode Komponen (Tailwind CSS)

Berikut adalah contekan cepat (cheat sheet) kelas Tailwind CSS untuk membuat elemen baru yang serasi:

A. Tombol Utama (Primary Action)

<button class="px-6 py-4 font-['Fredoka_One'] text-lg uppercase tracking-wider text-white bg-[#FF7675] border-[3px] border-[#1E1B29] rounded-xl shadow-[5px_5px_0px_rgba(30,27,41,1)] transition-all hover:-translate-x-0.5 hover:-translate-y-0.5 hover:shadow-[7px_7px_0px_rgba(30,27,41,1)] active:translate-x-0.5 active:translate-y-0.5 active:shadow-[2px_2px_0px_rgba(30,27,41,1)]">
    Klik Aku! ✨
</button>


B. Kotak Kartu Info (Card Container)

<div class="relative bg-white border-[4px] border-[#1E1B29] p-8 rounded-2xl shadow-[8px_8px_0px_rgba(30,27,41,1)]">
    <!-- Hiasan Stiker Mini di pojok -->
    <span class="absolute -top-4 -right-2 bg-[#FDCB6E] text-[#1E1B29] border-[3px] border-[#1E1B29] font-bold text-xs px-3 py-1 rounded-lg rotate-6 shadow-[3px_3px_0px_rgba(30,27,41,1)]">
        NEW ✨
    </span>
    <h3 class="font-['Fredoka_One'] text-2xl mb-2">Statistik Kehadiran</h3>
    <p class="font-['Nunito'] text-gray-700">Data presensi harian Anda otomatis tercatat aman.</p>
</div>


C. Kolom Input Form

<div class="flex flex-col gap-2">
    <label class="font-bold text-xs uppercase tracking-wider text-[#1E1B29]">Username</label>
    <input type="text" placeholder="Masukkan username..." class="w-full p-4 font-semibold border-[3px] border-[#1E1B29] rounded-xl bg-[#FAF9FF] shadow-[4px_4px_0px_rgba(30,27,41,1)] outline-none focus:bg-white focus:-translate-x-0.5 focus:-translate-y-0.5 focus:shadow-[6px_6px_0px_rgba(30,27,41,1)] transition-all" />
</div>


(✿◡‿◡) "Desain yang konsisten membuat petualangan belajar di sekolah menjadi jauh lebih menyenangkan!"

© 2026 Scoola Design Team.