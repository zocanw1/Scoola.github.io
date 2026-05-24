<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\ActivityLog;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('user')->orderBy('nama_siswa');

        if ($request->filled('q')) {
            $keyword = trim($request->q);
            $query->where(function ($builder) use ($keyword) {
                $builder->where('nama_siswa', 'like', '%' . $keyword . '%')
                    ->orWhere('NIS', 'like', '%' . $keyword . '%');
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

    public function create()
    {
        return view('admin.siswa.siswa-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis'      => 'required|string|max:50|unique:siswa,NIS',
            'nama'     => 'required|string|max:255',
            'kelas'    => 'required|in:XI-SIJA 1,XI-SIJA 2',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'siswa',
                'ref_id'   => $request->nis,
            ]);

            Siswa::create([
                'NIS'        => $request->nis,
                'user_id'    => $user->id,
                'nama_siswa' => $request->nama,
                'kelas'      => $request->kelas,
            ]);
        });

        ActivityLog::log("Menambahkan data siswa baru: {$request->nama} (NIS: {$request->nis})");

        return redirect()->route('siswa.index')
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    public function edit($nis)
    {
        $siswa = Siswa::with('user')->findOrFail($nis);
        return view('admin.siswa.siswa-edit', compact('siswa'));
    }

    public function update(Request $request, $nis)
    {
        $siswa = Siswa::with('user')->findOrFail($nis);

        $request->validate([
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|in:XI-SIJA 1,XI-SIJA 2',
            'email' => 'required|email|unique:users,email,' . $siswa->user_id,
        ]);

        DB::transaction(function () use ($request, $siswa) {
            $siswa->update([
                'nama_siswa' => $request->nama,
                'kelas'      => $request->kelas,
            ]);

            $siswa->user->update([
                'name'  => $request->nama,
                'email' => $request->email,
            ]);
        });

        ActivityLog::log("Memperbarui data siswa: {$request->nama} (NIS: {$nis})");

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui');
    }

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
}
