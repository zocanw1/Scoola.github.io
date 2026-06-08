<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

use App\Models\ActivityLog;

/**
 * Mengelola CRUD siswa admin, termasuk filter daftar dan import massal.
 */
class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa beserta statistik ringkas untuk header halaman admin.
     */
    public function index(Request $request)
    {
        $query = Siswa::with('user')->orderBy('nama_siswa');

        if ($request->filled('q')) {
            $keyword = Str::lower(trim($request->q));
            $query->where(function ($builder) use ($keyword) {
                $likeKeyword = '%' . $keyword . '%';

                $builder->whereRaw($this->lowerLikeColumn('nama_siswa'), [$likeKeyword])
                    ->orWhereRaw($this->lowerLikeColumn('NIS'), [$likeKeyword])
                    ->orWhereRaw($this->lowerLikeColumn('kelas'), [$likeKeyword])
                    ->orWhereHas('user', function ($userBuilder) use ($likeKeyword) {
                        $userBuilder->whereRaw('LOWER(email) LIKE ?', [$likeKeyword]);
                    });
            });
        }

        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }

        $siswa = $query->paginate(25)->withQueryString();

        $totalSiswa = Siswa::count();
        $totalKelasAktif = Siswa::distinct('kelas')->count('kelas');
        $kelasOptions = Siswa::query()->distinct()->orderBy('kelas')->pluck('kelas');

        return view('admin.siswa.siswa-index', compact('siswa', 'totalSiswa', 'totalKelasAktif', 'kelasOptions'));
    }

    private function lowerLikeColumn(string $column): string
    {
        $wrapped = DB::connection()->getQueryGrammar()->wrap($column);

        return "LOWER({$wrapped}) LIKE ?";
    }

    /**
     * Menampilkan form pembuatan siswa baru.
     */
    public function create()
    {
        return view('admin.siswa.siswa-create');
    }

    /**
     * Menyimpan akun user dan profil siswa di dalam satu transaksi.
     */
    public function store(Request $request)
    {
        $hasGenderColumn = $this->hasGenderColumn();

        $request->validate([
            'nis'      => 'required|string|max:50|unique:siswa,NIS',
            'nama'     => 'required|string|max:255',
            'kelas'    => 'required|in:XI-SIJA 1,XI-SIJA 2',
            'jenis_kelamin' => $hasGenderColumn ? 'required|in:L,P' : 'nullable|in:L,P',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Simpan user dan entitas siswa sekaligus agar data tidak pernah setengah jadi.
        DB::transaction(function () use ($request, $hasGenderColumn) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'siswa',
                'ref_id'   => $request->nis,
            ]);

            $siswaPayload = [
                'NIS'        => $request->nis,
                'user_id'    => $user->id,
                'nama_siswa' => $request->nama,
                'kelas'      => $request->kelas,
            ];

            if ($hasGenderColumn) {
                $siswaPayload['jenis_kelamin'] = $request->jenis_kelamin;
            }

            Siswa::create($siswaPayload);
        });

        ActivityLog::log("Menambahkan data siswa baru: {$request->nama} (NIS: {$request->nis})");

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
     * Mengimpor banyak siswa dari payload JSON yang sudah diparsing di sisi client.
     */
    public function import(Request $request)
    {
        $request->validate([
            'rows' => 'required|string',
        ]);

        $rows = json_decode($request->input('rows'), true);
        if (! is_array($rows)) {
            return redirect()->route('siswa.index')
                ->with('error', 'File import tidak valid.');
        }

        $created = 0;
        $skipped = 0;
        $seenNis = [];

        $hasGenderColumn = $this->hasGenderColumn();

        // Setiap baris diperiksa satu per satu supaya data ganda bisa dilewati tanpa membatalkan batch lain.
        DB::transaction(function () use ($rows, &$created, &$skipped, &$seenNis, $hasGenderColumn): void {
            foreach ($rows as $row) {
                $nis = trim((string) ($row['nis'] ?? ''));
                $nama = trim((string) ($row['nama'] ?? ''));
                $kelas = $this->normalizeImportedKelas((string) ($row['kelas'] ?? ''));

                if ($nis === '' || $nama === '' || $kelas === null || isset($seenNis[$nis])) {
                    $skipped++;
                    continue;
                }

                $seenNis[$nis] = true;

                if (Siswa::whereKey($nis)->exists()) {
                    $skipped++;
                    continue;
                }

                $token = $this->buildSiswaImportToken($nis);
                $user = User::create([
                    'name' => $nama,
                    'email' => $this->buildImportEmail($token),
                    'password' => Hash::make($token),
                    'role' => 'siswa',
                ]);

                $siswaPayload = [
                    'NIS' => $nis,
                    'user_id' => $user->id,
                    'nama_siswa' => $nama,
                    'kelas' => $kelas,
                ];

                if ($hasGenderColumn) {
                    $siswaPayload['jenis_kelamin'] = 'L';
                }

                Siswa::create($siswaPayload);

                $created++;
            }
        });

        if ($created > 0) {
            ActivityLog::log("Import data siswa: {$created} ditambahkan, {$skipped} dilewati");

            return redirect()->route('siswa.index')->with(
                'success',
                "Import siswa selesai. {$created} data ditambahkan, {$skipped} data dilewati. Email akun memakai pola siswa-NIS@gmail.com dan password default NIS tanpa simbol."
            );
        }

        return redirect()->route('siswa.index')
            ->with('error', "Import siswa gagal. Tidak ada data baru yang bisa ditambahkan. {$skipped} data dilewati.");
    }

    /**
     * Menampilkan form edit siswa dan akun login yang terhubung.
     */
    public function edit($nis)
    {
        $siswa = Siswa::with('user')->findOrFail($nis);
        return view('admin.siswa.siswa-edit', compact('siswa'));
    }

    /**
     * Memperbarui profil siswa serta email akun user dalam satu transaksi.
     */
    public function update(Request $request, $nis)
    {
        $siswa = Siswa::with('user')->findOrFail($nis);
        $hasGenderColumn = $this->hasGenderColumn();

        $request->validate([
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|in:XI-SIJA 1,XI-SIJA 2',
            'jenis_kelamin' => $hasGenderColumn ? 'required|in:L,P' : 'nullable|in:L,P',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
        ]);

        DB::transaction(function () use ($request, $siswa, $hasGenderColumn) {
            $siswaPayload = [
                'nama_siswa' => $request->nama,
                'kelas'      => $request->kelas,
            ];

            if ($hasGenderColumn) {
                $siswaPayload['jenis_kelamin'] = $request->jenis_kelamin;
            }

            $siswa->update($siswaPayload);

            $siswa->user->update([
                'name'  => $request->nama,
                'email' => $request->email,
            ]);
        });

        ActivityLog::log("Memperbarui data siswa: {$request->nama} (NIS: {$nis})");

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Menghapus akun user dan data siswa agar referensi tetap bersih.
     */
    public function destroy($nis)
    {
        $siswa = Siswa::with('user')->findOrFail($nis);
        $nama = $siswa->nama_siswa;

        DB::transaction(function () use ($siswa) {
            $siswa->user->delete();
            $siswa->delete();
        });

        ActivityLog::log("Menghapus data siswa: {$nama} (NIS: {$nis})");

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil dihapus');
    }

    /**
     * Menyamakan berbagai variasi penulisan kelas hasil import ke format baku aplikasi.
     */
    private function normalizeImportedKelas(string $kelas): ?string
    {
        $normalized = Str::upper(trim($kelas));
        $normalized = preg_replace('/\s+/', ' ', $normalized);

        return match ($normalized) {
            'XI SIJA 1', 'XI-SIJA 1' => 'XI-SIJA 1',
            'XI SIJA 2', 'XI-SIJA 2' => 'XI-SIJA 2',
            default => null,
        };
    }

    /**
     * Token import memakai NIS yang dibersihkan agar aman dijadikan password/email default.
     */
    private function buildSiswaImportToken(string $nis): string
    {
        return preg_replace('/[^A-Za-z0-9]/', '', $nis) ?: $nis;
    }

    /**
     * Email import dijaga unik walau NIS serupa sudah pernah dipakai.
     */
    private function buildImportEmail(string $token): string
    {
        $base = 'siswa-' . Str::lower($token) . '@gmail.com';
        $email = $base;
        $suffix = 2;

        while (User::where('email', $email)->exists()) {
            $email = 'siswa-' . Str::lower($token) . '-' . $suffix . '@gmail.com';
            $suffix++;
        }

        return $email;
    }

    /**
     * Cek kolom gender menjaga controller tetap kompatibel saat migrasi belum dijalankan.
     */
    private function hasGenderColumn(): bool
    {
        return Schema::hasColumn('siswa', 'jenis_kelamin');
    }
}
