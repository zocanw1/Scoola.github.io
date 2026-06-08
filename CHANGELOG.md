# Changelog / Catatan Update Scoola

Dokumen ini mencatat pembaruan dan fitur yang telah diimplementasikan pada aplikasi Scoola.

---

## [2026-06-08] - Update Nomor Telepon Tim Portfolio

### Portfolio
- Mengganti nomor telepon pada tiga kartu anggota tim di halaman portfolio sesuai nomor terbaru.
- Menambahkan pengecekan nomor telepon pada `tests/Feature/PortfolioTest.php` agar tampilan kontak tim tetap konsisten.

## [2026-06-08] - Penyeragaman Format Nomor Telepon Tim Portfolio

### Portfolio
- Menyeragamkan format nomor telepon kartu tim paling kiri menjadi ber-strip seperti kartu anggota lainnya.
- Memperbarui regresi test portfolio agar mengikuti format nomor terbaru yang tampil di halaman.

## [2026-06-07] - Penambahan Mean Median Modus di Dashboard

### Statistik Dashboard
- Menambahkan helper statistik untuk menghitung `mean`, `median`, dan `modus` dari distribusi kehadiran kelas aktif serta status presensi harian.
- Menampilkan unsur statistik tersebut pada dashboard admin dan dashboard guru di bawah grafik analitik kehadiran.
- Menambahkan regresi test dashboard agar ringkasan statistik tetap ikut tervalidasi saat perhitungan presensi berubah.

## [2026-06-07] - Penambahan Komentar Penjelas di File Inti

### Dokumentasi Kode
- Menambahkan komentar penjelas pada controller inti admin, guru, dan siswa agar alur data lebih mudah dipahami saat membaca kode.
- Menambahkan komentar pada view inti dashboard, rekap, dan ruang kelas aktif untuk menjelaskan fungsi setiap blok tampilan dan script utama.
- Menambahkan komentar pada helper statistik agar tujuan perhitungan `mean`, `median`, dan `modus` lebih jelas saat maintenance.

## [2026-06-07] - Pembersihan Data Lokal Guru dan Siswa

### Operasional Lokal
- Menghapus seluruh akun lokal dengan role `guru` dan `siswa` beserta data turunannya.
- Data admin dan kakonsli tetap dipertahankan.

## [2026-06-05] - Dokumentasi Fitur Scoola Untuk Presentasi Non-IT

### Dokumentasi
- Menambahkan dokumen sumber `docs/scoola-dokumentasi-fitur-presentasi-non-it.md` yang merangkum fitur Scoola dalam bahasa non-teknis.
- Materi difokuskan pada manfaat produk, alur kerja sederhana, peran pengguna, fitur utama, dan susunan slide presentasi.
- Dokumen disiapkan sebagai dasar pembuatan file Word/DOCX untuk kebutuhan presentasi ke audiens umum.

## [2026-06-04] - Perbaikan Link Koreksi Status Presensi

### Bug Fix
- Memperbaiki akses `GET` ke URL koreksi status presensi seperti `/admin/presensi-siswa/{nis}/status` agar tidak masuk ke detail siswa dengan NIS yang salah saat NIS berisi garis miring.
- Menambahkan fallback redirect ke halaman detail presensi siswa yang benar, sehingga refresh atau akses langsung ke URL action form tidak memicu halaman error.
- Menambahkan regresi test untuk NIS berformat slash seperti `17588/122/065`.

## [2026-05-19] - Fitur Rekap Presensi Mingguan & Perapian Dokumen

### Fitur Baru: Sistem Rekap Presensi Mingguan (Format 1 Minggu)
- **Halaman Rekap Mingguan (`/admin/rekap-presensi`)**:
  - Menambahkan menu baru "Rekap Mingguan" di sidebar panel admin.
  - Halaman web interaktif menggunakan tabel biasa yang menampilkan data siswa (`NIS`, `Nama`, `L/P`) dan 12 kolom Jam Pelajaran secara dinamis berdasarkan kelas dan hari yang dipilih.
  - Memetakan mata pelajaran beserta guru pengajar pada kolom jam pelajaran yang bersangkutan secara otomatis.
- **Export ke Excel**:
  - Menambahkan tombol **Export Excel** untuk mengunduh rekap presensi kelas dan hari tertentu.
  - Hasil unduhan berupa file Excel (`.xls`) dengan layout tabel rapi yang siap cetak (printable).
- **Rute Baru**:
  - `/admin/rekap-presensi` (Tampilan tabel web).
  - `/admin/rekap-presensi/export` (Proses export file Excel).

### Perbaikan Dokumen: Perapian Laporan UPK
- Merapikan file **`LAPORAN_UPK_SCOOLA_TERISI.docx`** menjadi dokumen standar laporan akademik Indonesia dengan nama file **`LAPORAN_UPK_SCOOLA_RAPI.docx`** di dalam folder `Ai workspace`.
- **Standar Format yang Diterapkan**:
  - Ukuran Kertas: A4.
  - Margin: 4cm (Kiri), 4cm (Atas), 3cm (Kanan), 3cm (Bawah).
  - Font: Times New Roman 12pt (dengan menjaga format heading).
  - Spasi Baris: 1.5.
  - Alignment: Justify (Rata Kiri Kanan).
