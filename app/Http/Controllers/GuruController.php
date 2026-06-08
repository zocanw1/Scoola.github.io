<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\ActivityLog;

/**
 * Mengelola data guru, mapel yang diampu, dan akun login terkait.
 */
class GuruController extends Controller
{
    /**
     * Menampilkan daftar guru dengan filter pencarian dan filter mapel.
     */
    public function index(Request $request)
    {
        $query = Guru::with(['user', 'mapels'])->orderBy('nama_guru');

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($builder) use ($keyword) {
                $builder->where('nama_guru', 'like', '%' . $keyword . '%')
                    ->orWhere('NIP', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('mapel')) {
            $query->whereHas('mapels', function ($builder) use ($request) {
                $builder->where('guru_mapel.kd_mapel', $request->mapel);
            });
        }

        $guru = $query->paginate(20)->withQueryString();

        if (($request->filled('q') || $request->filled('mapel'))
            && $guru->isEmpty()
            && $guru->total() > 0
            && $guru->currentPage() > 1) {
            return redirect()->route('guru.index', array_merge($request->except('page'), ['page' => 1]));
        }

        $totalGuru = Guru::count();
        $totalGuruAktif = Guru::whereNotNull('user_id')->count();
        $allMapels = Mapel::orderBy('nama_mapel')->get();

        return view('admin.guru.guru-index', compact('guru', 'totalGuru', 'totalGuruAktif', 'allMapels'));
    }

    /**
     * Menyiapkan form tambah guru beserta daftar mapel yang tersedia.
     */
    public function create()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('admin.guru.guru-create', compact('mapel'));
    }

    /**
     * Menyimpan user guru dan relasi mapel secara atomik.
     */
    public function store(Request $request)
    {
        $hasGenderColumn = $this->hasGenderColumn();

        $request->validate([
            'nip'        => 'required|string|max:50|unique:guru,NIP',
            'nama'       => 'required|string|max:255',
            'jenis_kelamin' => $hasGenderColumn ? 'required|in:L,P' : 'nullable|in:L,P',
            'kd_mapel'   => 'required|array',
            'kd_mapel.*' => 'exists:mapel,kd_mapel',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
        ]);

        // Guru utama, user login, dan relasi mapel wajib sukses bersamaan agar data tetap sinkron.
        DB::transaction(function () use ($request, $hasGenderColumn) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'guru',
                'ref_id'   => $request->nip,
            ]);

            $guruPayload = [
                'NIP'       => $request->nip,
                'user_id'   => $user->id,
                'nama_guru' => $request->nama,
                'kd_mapel'  => $request->kd_mapel[0], // Keep first for backward compatibility
            ];

            if ($hasGenderColumn) {
                $guruPayload['jenis_kelamin'] = $request->jenis_kelamin;
            }

            $guru = Guru::create($guruPayload);

            $guru->mapels()->sync($request->kd_mapel);
        });

        ActivityLog::log("Menambahkan data guru baru: {$request->nama} (NIP: {$request->nip})");

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil ditambahkan');
    }

    /**
     * Mengimpor banyak guru dengan kredensial default berbasis NIP.
     */
    public function import(Request $request)
    {
        $request->validate([
            'rows' => 'required|string',
        ]);

        $rows = json_decode($request->input('rows'), true);
        if (! is_array($rows)) {
            return redirect()->route('guru.index')
                ->with('error', 'File import tidak valid.');
        }

        $created = 0;
        $skipped = 0;
        $seenNips = [];

        $hasGenderColumn = $this->hasGenderColumn();

        // Baris yang rusak atau duplikat dilewati tanpa menggagalkan seluruh proses import.
        DB::transaction(function () use ($rows, &$created, &$skipped, &$seenNips, $hasGenderColumn): void {
            foreach ($rows as $row) {
                $nama = trim((string) ($row['nama'] ?? ''));
                $nip = preg_replace('/\s+/', '', (string) ($row['nip'] ?? ''));

                if ($nama === '' || $nip === '' || strlen($nip) > 20 || isset($seenNips[$nip])) {
                    $skipped++;
                    continue;
                }

                $seenNips[$nip] = true;

                if (Guru::whereKey($nip)->exists()) {
                    $skipped++;
                    continue;
                }

                $user = User::create([
                    'name' => $nama,
                    'email' => $this->buildImportEmail($nip),
                    'password' => Hash::make($nip),
                    'role' => 'guru',
                ]);

                $guruPayload = [
                    'NIP' => $nip,
                    'user_id' => $user->id,
                    'nama_guru' => $nama,
                    'kd_mapel' => null,
                ];

                if ($hasGenderColumn) {
                    $guruPayload['jenis_kelamin'] = 'L';
                }

                Guru::create($guruPayload);

                $created++;
            }
        });

        if ($created > 0) {
            ActivityLog::log("Import data guru: {$created} ditambahkan, {$skipped} dilewati");

            return redirect()->route('guru.index')->with(
                'success',
                "Import guru selesai. {$created} data ditambahkan, {$skipped} data dilewati. Email akun memakai pola guru-NIP@gmail.com dan password default NIP tanpa spasi."
            );
        }

        return redirect()->route('guru.index')
            ->with('error', "Import guru gagal. Tidak ada data baru yang bisa ditambahkan. {$skipped} data dilewati.");
    }

    /**
     * Menampilkan form edit guru dan semua mapel yang bisa dihubungkan.
     */
    public function edit($nip)
    {
        $guru  = Guru::with('user', 'mapels')->findOrFail($nip);
        $mapel = Mapel::orderBy('nama_mapel')->get();

        return view('admin.guru.guru-edit', compact('guru', 'mapel'));
    }

    /**
     * Memperbarui profil guru, relasi mapel, dan opsional kredensial login.
     */
    public function update(Request $request, $nip)
    {
        $guru = Guru::with('user')->findOrFail($nip);
        $hasGenderColumn = $this->hasGenderColumn();

        $request->validate([
            'nama'       => 'required|string|max:255',
            'jenis_kelamin' => $hasGenderColumn ? 'required|in:L,P' : 'nullable|in:L,P',
            'kd_mapel'   => 'required|array',
            'kd_mapel.*' => 'exists:mapel,kd_mapel',
        ]);

        $incomingEmail = trim((string) $request->input('email', $guru->user->email));
        $emailChanged = strcasecmp($incomingEmail, (string) $guru->user->email) !== 0;
        $passwordChanged = $request->filled('password');
        $credentialsChanged = $emailChanged || $passwordChanged;

        // Penggantian email/password sengaja diberi lapisan validasi ekstra karena berdampak ke akun login.
        if ($credentialsChanged) {
            $request->validate([
                'change_login_credentials' => 'accepted',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($guru->user_id)],
                'password' => 'nullable|min:6|confirmed',
                'credential_confirmation' => ['required', function (string $attribute, mixed $value, \Closure $fail) use ($guru): void {
                    if (trim((string) $value) !== $guru->NIP) {
                        $fail('Kode verifikasi NIP tidak cocok.');
                    }
                }],
                'admin_password_confirmation' => 'required|current_password',
            ], [
                'change_login_credentials.accepted' => 'Centang konfirmasi perubahan kredensial terlebih dahulu.',
                'admin_password_confirmation.current_password' => 'Password admin tidak cocok.',
            ]);
        }

        DB::transaction(function () use ($request, $guru, $credentialsChanged, $incomingEmail, $passwordChanged, $hasGenderColumn) {
            $guruPayload = [
                'nama_guru' => $request->nama,
                'kd_mapel'  => $request->kd_mapel[0], // Keep first
            ];

            if ($hasGenderColumn) {
                $guruPayload['jenis_kelamin'] = $request->jenis_kelamin;
            }

            $guru->update($guruPayload);

            $guru->mapels()->sync($request->kd_mapel);

            $userUpdates = [
                'name' => $request->nama,
            ];

            if ($credentialsChanged) {
                $userUpdates['email'] = $incomingEmail;
            }

            if ($passwordChanged) {
                $userUpdates['password'] = Hash::make($request->password);
            }

            $guru->user->update($userUpdates);
        });

        ActivityLog::log("Memperbarui data guru: {$request->nama} (NIP: {$nip})");

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil diperbarui');
    }

    /**
     * Menghapus akun user dan data guru dalam satu transaksi.
     */
    public function destroy($nip)
    {
        $guru = Guru::with('user')->findOrFail($nip);
        $nama = $guru->nama_guru;

        DB::transaction(function () use ($guru) {
            $guru->user->delete();
            $guru->delete();
        });

        ActivityLog::log("Menghapus data guru: {$nama} (NIP: {$nip})");

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil dihapus');
    }

    /**
     * Email import dibentuk stabil dari NIP dan diberi suffix bila sudah dipakai.
     */
    private function buildImportEmail(string $nip): string
    {
        $base = 'guru-' . Str::lower($nip) . '@gmail.com';
        $email = $base;
        $suffix = 2;

        while (User::where('email', $email)->exists()) {
            $email = 'guru-' . Str::lower($nip) . '-' . $suffix . '@gmail.com';
            $suffix++;
        }

        return $email;
    }

    /**
     * Cek kolom gender menjaga controller tetap kompatibel lintas migrasi.
     */
    private function hasGenderColumn(): bool
    {
        return Schema::hasColumn('guru', 'jenis_kelamin');
    }
}
