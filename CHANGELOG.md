# Changelog / Catatan Update Scoola

Dokumen ini mencatat pembaruan dan fitur yang telah diimplementasikan pada aplikasi Scoola.

---

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
