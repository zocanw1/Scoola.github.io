<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Guru;
use Illuminate\Http\Request;

class AdminWaliKelasController extends Controller
{
    /**
     * Daftar semua kelas dan wali kelasnya
     */
    public function index()
    {
        $kelasList = Kelas::with(['waliKelas', 'siswa'])
            ->orderBy('nama_kelas')
            ->get();

        return view('admin.walikelas.walikelas-index', compact('kelasList'));
    }

    /**
     * Form assign guru sebagai wali kelas
     */
    public function create()
    {
        // Guru yang belum jadi wali kelas
        $guruList = Guru::whereDoesntHave('kelasWali')
            ->orderBy('nama_guru')
            ->get();

        // Kelas yang belum punya wali
        $kelasAvailable = Kelas::whereNull('wali_kelas_nip')
            ->orderBy('nama_kelas')
            ->get();

        return view('admin.walikelas.walikelas-create', compact('guruList', 'kelasAvailable'));
    }

    /**
     * Simpan assignment wali kelas
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'  => 'required|exists:kelas,id',
            'guru_nip'  => 'required|exists:guru,NIP',
        ]);

        $kelas = Kelas::findOrFail($request->kelas_id);

        // Pastikan kelas belum punya wali
        if ($kelas->wali_kelas_nip) {
            return back()->with('error', 'Kelas ini sudah memiliki wali kelas.');
        }

        // Pastikan guru belum jadi wali kelas lain
        $sudahWali = Kelas::where('wali_kelas_nip', $request->guru_nip)->exists();
        if ($sudahWali) {
            return back()->with('error', 'Guru ini sudah menjadi wali kelas di kelas lain.');
        }

        $kelas->update(['wali_kelas_nip' => $request->guru_nip]);

        return redirect()->route('admin.walikelas.index')
            ->with('success', 'Wali kelas berhasil ditambahkan.');
    }

    /**
     * Form edit assignment wali kelas
     */
    public function edit($id)
    {
        $kelas = Kelas::with('waliKelas')->findOrFail($id);

        // Guru yang tersedia: belum jadi wali + guru saat ini
        $guruList = Guru::where(function ($q) use ($kelas) {
            $q->whereDoesntHave('kelasWali')
              ->orWhere('NIP', $kelas->wali_kelas_nip);
        })->orderBy('nama_guru')->get();

        return view('admin.walikelas.walikelas-edit', compact('kelas', 'guruList'));
    }

    /**
     * Update assignment wali kelas
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            'guru_nip' => 'required|exists:guru,NIP',
        ]);

        // Pastikan guru belum jadi wali kelas di kelas LAIN
        $sudahWali = Kelas::where('wali_kelas_nip', $request->guru_nip)
            ->where('id', '!=', $id)
            ->exists();

        if ($sudahWali) {
            return back()->with('error', 'Guru ini sudah menjadi wali kelas di kelas lain.');
        }

        $kelas->update(['wali_kelas_nip' => $request->guru_nip]);

        return redirect()->route('admin.walikelas.index')
            ->with('success', 'Wali kelas berhasil diperbarui.');
    }

    /**
     * Hapus assignment wali kelas (set null)
     */
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update(['wali_kelas_nip' => null]);

        return redirect()->route('admin.walikelas.index')
            ->with('success', 'Wali kelas berhasil dihapus dari kelas ' . $kelas->nama_kelas);
    }
}
