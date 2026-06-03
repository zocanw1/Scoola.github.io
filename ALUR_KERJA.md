# 🚀 Alur Kerja & Arsitektur Sistem Scoola

**Scoola** adalah aplikasi presensi sekolah modern berbasis web yang mengusung tema estetika visual unik **Manga-Pop & Neobrutalism UI** (sesuai panduan di `DESIGN.md`). Aplikasi ini dirancang untuk mempermudah manajemen kehadiran siswa secara mandiri berbasis lokasi (GPS) dan kode dinamis demi meminimalkan kecurangan.

Dokumen ini menjelaskan arsitektur data, alur kerja (workflow) antarpengguna, mekanisme teknis validasi presensi, serta fitur keamanan sistem.

---

## 🏛️ 1. Arsitektur & Teknologi Utama

Sistem Scoola dibangun dengan teknologi modern berikut:
* **Framework Backend & Frontend**: Laravel (PHP) dengan Template Engine **Blade**
* **Gaya Visual (UI)**: Custom Vanilla CSS Neobrutalism UI (Garis tepi tebal 3px/4px, Bayangan keras tanpa blur, Warna solid kontras tinggi seperti `--sakura`, `--cyber`, `--cosmo`, `--gold`, dan latar pola polkadot manga).
* **Penyimpanan Data**: Database Relasional (MySQL / PostgreSQL) dengan optimasi indeks pada kolom pencarian dashboard dan autentikasi.
* **Fitur Utama**: Validasi GPS berbasis formula matematis, Dynamic OTP Code, dan Real-Time Snapshot.

---

## 💾 2. Skema & Model Data (Database Schema)

Sistem menggunakan database relasional dengan relasi antar-entitas berikut:

```
                  +--------------------+
                  |       users        |
                  +--------------------+
                  | - id (PK)          |
                  | - name, email      |
                  | - password, role   |
                  +--------------------+
                            |
           +----------------+----------------+
           | 1                               | 1
    +---------------+                 +--------------+
    |     siswa     |                 |     guru     |
    +---------------+                 +--------------+
    | - NIS (PK)    |                 | - NIP (PK)   |
    | - user_id (FK)|                 | - user_id(FK)|
    | - nama_siswa  |                 | - nama_guru  |
    | - kelas       |                 +--------------+
    +---------------+                        |
           |                                 | 1
           | 1                               |
           |                                 +--------+
           |                                 |        |
           |                                 |        | 1..*
           |                                 v        v
           |                        +------------------+     +---------------+
           |                        | jadwal_pelajaran |     |  sesi_presensi|
           |                        +------------------+     +--------------------+
           |                        | - kd_jp (PK)     |     | - id (PK)          |
           |                        | - NIP (FK)       |     | - guru_id (FK)     |
           |                        | - kd_mapel (FK)  |     | - kelas            |
           |                        | - kelas, hari    |     | - kd_jp (FK)       |
           |                        | - jam_mulai/sel. |     | - kode_presensi    |
           |                        +------------------+     | - waktu_berlaku    |
           |                                 |               | - status           |
           |                                 | 1             +--------------------+
           |                                 |                        |
           |                                 | 1                      | 1
           | *                               v                        v *
    +-----------------------------------------------------------------------------+
    |                                   presensi                                  |
    +-----------------------------------------------------------------------------+
    | - id (PK - UUID)                                                            |
    | - sesi_id (FK)      - NIS (FK)              - kd_presensi                   |
    | - tanggal           - jam_masuk             - status (Hadir, Izin, Sakit,   |
    | - kd_jp (FK)        - latitude, longitude     Alpa, Ditolak, Belum Hadir)   |
    | - is_dalam_radius (boolean)                                                 |
    +-----------------------------------------------------------------------------+
```

### Penjelasan Model Utama:
1. **`User`**: Menyimpan data login pengguna dengan hak akses (`role`): `admin`, `kakonsli`, `guru`, `siswa`.
2. **`Siswa`**: Berisi detail data murid yang terhubung satu-ke-satu (`hasOne`) dengan `User` melalui `user_id`. Kunci utama menggunakan **`NIS`**.
3. **`Guru`**: Berisi detail data pengajar yang terhubung satu-ke-satu dengan `User`. Kunci utama menggunakan **`NIP`**.
4. **`Kelas`**: Menyimpan daftar kelas unik beserta NIP guru yang bertugas sebagai **Wali Kelas**.
5. **`Mapel`**: Menyimpan daftar mata pelajaran beserta kodenya (`kd_mapel`).
6. **`JadwalPelajaran`**: Menghubungkan Guru, Mata Pelajaran, Kelas, Hari, dan Jam KBM. Kunci utama adalah **`kd_jp`**.
7. **`SesiPresensi`**: Sesi absensi aktif yang dibuka oleh Guru pada kelas tertentu dengan kode verifikasi OTP 6 digit.
8. **`Presensi`**: Catatan kehadiran per siswa untuk sesi tertentu, menyimpan koordinat GPS siswa dan status validitas lokasi.
9. **`ActivityLog`**: Log aktivitas administrasi untuk keperluan audit sistem.
10. **`TeamMember`**: Data profil anggota tim pengembang untuk halaman portfolio.

---

## 👥 3. Alur Kerja Pengguna Berdasarkan Peran (User Workflow)

### A. Alur Administrator
1. **Initial Setup (`/scoola-setup`)**: Registrasi admin pertama kali pada sistem baru. Jika admin sudah ada, halaman ini terkunci secara otomatis kecuali jika menyertakan `SETUP_SECRET` khusus di berkas konfigurasi `.env`.
2. **Manajemen Master Data**: Admin mengelola akun (Admin, Guru, Siswa, Kakonsli), data kelas, wali kelas, mata pelajaran, dan jadwal pelajaran. Mendukung fitur impor massal (bulk import) data siswa dan guru.
3. **Monitoring Log**: Admin memantau perubahan penting melalui log aktivitas admin (`/admin/logs`).
4. **Rekap & Ekspor**: Admin dapat memantau kehadiran siswa secara keseluruhan dan mengekspor rekap kehadiran dalam bentuk berkas Excel.

### B. Alur Kakonsli (Kepala Konseling)
1. **Monitoring Siswa**: Hak akses khusus untuk memantau data seluruh siswa di sekolah.
2. **Rekap Presensi**: Mengamati status kehadiran harian siswa untuk mengidentifikasi pola absensi buruk guna tindakan bimbingan konseling.

### C. Alur Guru (Pengajar)
1. **Dashboard Harian**: Guru masuk ke dashboard untuk melihat jadwal mengajar mereka hari ini secara otomatis berdasarkan hari aktif.
2. **Buka Sesi Presensi (`bukaKelas`)**: 
   * Guru memilih kelas dari jadwal hari ini.
   * Sistem menutup semua sesi aktif lain milik guru tersebut secara otomatis (memastikan 1 guru hanya mengajar 1 kelas secara aktif).
   * Sistem membuat token kode unik 6 digit (misal: `ABCXYZ`) yang berlaku selama 2 jam.
3. **Membagikan Kode (`tampilKode`)**: Guru membuka tampilan proyektor bertema manga besar berisi kode 6 digit agar siswa di kelas dapat melihat dan menyalinnya.
4. **Monitoring Real-Time (Ruang Kelas)**:
   * Guru diarahkan ke halaman **Ruang Kelas** yang memantau secara real-time status absensi siswa.
   * Halaman ini memvalidasi integritas data melalui mekanisme **Snapshot Versioning** (`statusSnapshot`) untuk mendeteksi perubahan status kehadiran siswa tanpa perlu memuat ulang seluruh halaman.
   * **Deteksi Tabrakan Sesi (Anti-Collision)**: Sistem memperingatkan jika ada guru lain yang tidak sengaja membuka sesi aktif pada kelas yang sama.
5. **Intervensi Manual (Manual Override)**: Guru dapat mengubah status kehadiran siswa secara langsung di halaman Ruang Kelas (misal: jika ada siswa sakit, izin, atau lokasinya ditolak karena kendala GPS/HP).
6. **Akhiri Presensi**: Guru dapat menutup gerbang presensi (kode dinonaktifkan/dihapus) tetapi kelas tetap berlangsung.
7. **Akhiri Kelas**: Guru menutup sesi pelajaran secara permanen. Status sesi diubah menjadi `selesai`, dan seluruh rekap kehadiran siswa di sesi tersebut dikunci untuk rekapitulasi permanen admin.

### D. Alur Siswa (Peserta Didik)
1. **Dashboard Siswa**: Murid melihat ringkasan profil, status kehadiran hari ini, dan riwayat 5 presensi terakhir mereka.
2. **Pengisian Presensi Mandiri (`absenMandiri`)**:
   * Siswa memasukkan kode presensi 6 digit yang ditampilkan oleh guru di depan kelas.
   * Browser secara otomatis meminta akses lokasi (GPS) siswa.
   * Siswa mengirimkan data presensi berupa kode OTP, koordinat `latitude`, dan `longitude`.
3. **Validasi Multi-Faktor**: Sistem memproses inputan siswa dengan alur validasi berlapis (detail pada Bab 4).
4. **Pencatatan Status**:
   * Jika siswa berada dalam radius sekolah, status disimpan sebagai **"Hadir"**.
   * Jika di luar radius, status disimpan sebagai **"Ditolak"** dan siswa mendapatkan pesan peringatan jarak mereka dari koordinat sekolah.

---

## 🛠️ 4. Proses Teknis & Fitur Keamanan Presensi

### A. Penghitungan Jarak GPS (Formula Haversine)
Untuk mencegah kecurangan absensi dari rumah (titip absen), sistem mencocokkan koordinat siswa dengan koordinat resmi sekolah menggunakan **Formula Haversine** di sisi backend.

* **Titik Koordinat Sekolah (SMKN 4 Malang)**:
  * Latitude: `-7.974867815619122`
  * Longitude: `112.67166658058967`
* **Radius Toleransi Maksimum**: `200 meter`

Mekanisme perhitungan dalam PHP (`hitungJarak`):
```php
private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $earthRadius = 6371000; // Radius bumi dalam meter

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c; // Jarak dalam satuan meter
}
```

### B. Rate Limiting (Anti-Spam & Brute Force)
Demi mencegah serangan otomatisasi tebakan kode presensi, sistem menerapkan pembatasan ketat (*Rate Limiting*):
* **Batas Akses Presensi**: Maksimal **10 kali percobaan per menit** untuk setiap akun siswa.
* **Batas Akses Login**: Maksimal **5 kali kegagalan login per menit** sebelum akun terkunci sementara.

### C. Sequence Diagram Alur Validasi Absensi Siswa

Berikut adalah gambaran langkah demi langkah yang terjadi saat siswa menekan tombol "Kirim Kehadiran":

```
[ Siswa ]             [ Browser ]             [ Server Scoola ]             [ Database ]
    |                      |                          |                           |
    |--- Masukkan Kode ---->                          |                           |
    |--- Tekan Absen ------>                          |                           |
    |                      |--- Minta GPS Lokasi ---->|                           |
    |                      |<-- Koordinat Lat, Lng ---|                           |
    |                      |                          |                           |
    |                      |------ POST request ----->|                           |
    |                      |  (kode, lat, lng)        |                           |
    |                      |                          |--- Rate Limit Check? ---->|
    |                      |                          |    (Maks 10x per mnt)     |
    |                      |                          |                           |
    |                      |                          |--- Cocokkan Kode Aktif? ->|
    |                      |                          |<-- Sesi Presensi Aktif ---|
    |                      |                          |                           |
    |                      |                          |--- Cek Kadaluarsa Waktu?->|
    |                      |                          |                           |
    |                      |                          |--- Cek Kelas Murid? ----->|
    |                      |                          |    (Siswa.kelas = Sesi.kls)
    |                      |                          |                           |
    |                      |                          |--- Hitung Haversine GPS ->|
    |                      |                          |    (Jarak <= 200m?)       |
    |                      |                          |                           |
    |                      |                          |--- Simpan / Update ------->|
    |                      |                          |    (Hadir / Ditolak)      |
    |                      |                          |<-- Status Tersimpan ------|
    |                      |                          |                           |
    |<-- Tampilkan Status -|<---- Respons Berhasil ---|                           |
    |    (Sukses/Ditolak)  |                          |                           |
```

---

## 🔒 5. Mekanisme Integritas Data & Anti-Manipulasi

1. **Hybrid UUID pada Presensi**: Tabel presensi menggunakan `UUID` untuk primary key untuk mencegah eksploitasi serangan tebakan ID sekuensial (Insecure Direct Object References - IDOR).
2. **Backend Enforcement**: Penentuan status `'Hadir'` atau `'Ditolak'` diputuskan **100% di sisi server** berdasarkan perhitungan matematis GPS. Mengirimkan koordinat palsu dari konsol inspeksi akan terfilter karena server membandingkannya secara absolut.
3. **Session Cleansing**: Guru hanya diperbolehkan memiliki satu sesi kelas yang aktif pada satu waktu guna menghindari penumpukan token absensi aktif yang dapat dieksploitasi siswa kelas lain.

---

## 📈 6. Ringkasan Status Kehadiran Siswa

Setiap catatan kehadiran memiliki status tegas di database:
* **`Hadir`**: Berhasil absensi mandiri, kode cocok, waktu sesuai, kelas tepat, dan berada di dalam radius sekolah.
* **`Ditolak`**: Mengisi kode dengan benar tetapi di luar radius GPS sekolah (siswa mencoba mengisi dari luar lingkungan sekolah).
* **`Izin`**: Diperbarui secara manual oleh guru karena siswa mengirim surat izin resmi.
* **`Sakit`**: Diperbarui secara manual oleh guru karena siswa sakit dengan bukti surat dokter.
* **`Alpa`**: Murid tidak hadir tanpa alasan (diperbarui guru/admin).
* **`Belum Hadir`**: Status awal siswa saat kelas dibuka sebelum melakukan absensi mandiri.
