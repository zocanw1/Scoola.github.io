<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JadwalPelajaranController extends Controller
{
    /* ==============================
     * INDEX (SEMUA JADWAL)
     * ============================== */
    public function index()
    {
        $jadwal = JadwalPelajaran::with(['guru', 'mapel'])
            ->orderBy('kelas')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.index', compact('jadwal'));
    }

    /* ==============================
     * JADWAL PER KELAS
     * ============================== */
    public function kelas(string $kelas)
    {
        $jadwal = JadwalPelajaran::with(['guru', 'mapel'])
            ->where('kelas', $kelas)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('admin.jadwal.index', compact('jadwal', 'kelas'));
    }

    /* ==============================
     * CREATE
     * ============================== */
    public function create()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        $guru  = Guru::orderBy('nama_guru')->get();

        return view('admin.jadwal.create', compact('mapel', 'guru'));
    }

    /* ==============================
     * STORE
     * ============================== */
    public function store(Request $request)
    {
        $request->validate([
            'hari'        => 'required|string|max:10',
            'kelas'       => 'required|string|max:10',
            'kd_mapel'    => 'nullable|string|max:10',
            'NIP'         => 'nullable|string|max:20',
            'jam_mulai'   => 'required|integer|min:1|max:12',
            'jam_selesai' => 'required|integer|min:1|max:12|gte:jam_mulai',
        ]);

        JadwalPelajaran::create([
            'kd_jp'       => 'JP-' . Str::uuid(),
            'hari'        => $request->hari,
            'kelas'       => $request->kelas,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kd_mapel'    => $request->kd_mapel,
            'NIP'         => $request->NIP,
        ]);

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan');
    }

    /* ==============================
     * EDIT
     * ============================== */
    public function edit(string $kd_jp)
    {
        $jadwal = JadwalPelajaran::findOrFail($kd_jp);
        $mapel  = Mapel::orderBy('nama_mapel')->get();
        $guru   = Guru::orderBy('nama_guru')->get();

        return view('admin.jadwal.edit', compact('jadwal', 'mapel', 'guru'));
    }

    /* ==============================
     * UPDATE
     * ============================== */
    public function update(Request $request, string $kd_jp)
    {
        $jadwal = JadwalPelajaran::findOrFail($kd_jp);

        $request->validate([
            'hari'        => 'required|string|max:10',
            'kelas'       => 'required|string|max:10',
            'kd_mapel'    => 'nullable|string|max:10',
            'NIP'         => 'nullable|string|max:20',
            'jam_mulai'   => 'required|integer|min:1|max:12',
            'jam_selesai' => 'required|integer|min:1|max:12|gte:jam_mulai',
        ]);

        $jadwal->update([
            'hari'        => $request->hari,
            'kelas'       => $request->kelas,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kd_mapel'    => $request->kd_mapel,
            'NIP'         => $request->NIP,
        ]);

        return redirect()
            ->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    /* ==============================
     * DELETE
     * ============================== */
    public function destroy(string $kd_jp)
    {
        JadwalPelajaran::findOrFail($kd_jp)->delete();

        return back()->with('success', 'Jadwal berhasil dihapus');
    }
}
