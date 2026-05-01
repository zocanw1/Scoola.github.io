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
    public function kelas(string $kelas, string $hari = null)
    {
        $query = JadwalPelajaran::with(['guru', 'mapel'])
            ->where('kelas', $kelas)
            ->orderBy('jam_mulai');

        if ($hari) {
            $query->where('hari', $hari);
        } else {
            $query->orderBy('hari');
        }

        $jadwal = $query->get();

        return view('admin.jadwal.index', compact('jadwal', 'kelas', 'hari'));
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

        $overlap = JadwalPelajaran::where('hari', $request->hari)
            ->where('kelas', $request->kelas)
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<=', $request->jam_selesai)
                      ->where('jam_selesai', '>=', $request->jam_mulai);
            })->get();

        if ($overlap->isNotEmpty() && !$request->has('force')) {
            $jamBentrok = $overlap->map(function ($j) {
                return $j->jam_mulai . '-' . $j->jam_selesai;
            })->implode(', ');
            
            return back()->withInput()->with('confirm_replace', "Terdapat jadwal yang bentrok pada jam ke ($jamBentrok). Jadwal yang lama akan diganti/ditimpa. Apakah Anda yakin?");
        }

        if ($request->has('force')) {
            JadwalPelajaran::where('hari', $request->hari)
                ->where('kelas', $request->kelas)
                ->where(function ($query) use ($request) {
                    $query->where('jam_mulai', '<=', $request->jam_selesai)
                          ->where('jam_selesai', '>=', $request->jam_mulai);
                })->delete();
        }

        JadwalPelajaran::create([
            'kd_jp'       => 'JP-' . Str::upper(Str::random(10)),
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

        $overlap = JadwalPelajaran::where('hari', $request->hari)
            ->where('kelas', $request->kelas)
            ->where('kd_jp', '!=', $kd_jp)
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<=', $request->jam_selesai)
                      ->where('jam_selesai', '>=', $request->jam_mulai);
            })->get();

        if ($overlap->isNotEmpty() && !$request->has('force')) {
            $jamBentrok = $overlap->map(function ($j) {
                return $j->jam_mulai . '-' . $j->jam_selesai;
            })->implode(', ');
            
            return back()->withInput()->with('confirm_replace', "Terdapat jadwal yang bentrok pada jam ke ($jamBentrok). Jadwal yang lama akan diganti/ditimpa. Apakah Anda yakin?");
        }

        if ($request->has('force')) {
            JadwalPelajaran::where('hari', $request->hari)
                ->where('kelas', $request->kelas)
                ->where('kd_jp', '!=', $kd_jp)
                ->where(function ($query) use ($request) {
                    $query->where('jam_mulai', '<=', $request->jam_selesai)
                          ->where('jam_selesai', '>=', $request->jam_mulai);
                })->delete();
        }

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
    /* ==============================
     * GET GURU BY MAPEL (AJAX)
     * ============================== */
    public function getGuruByMapel(string $kd_mapel)
    {
        $guru = Guru::whereHas('mapels', function($query) use ($kd_mapel) {
                $query->where('guru_mapel.kd_mapel', $kd_mapel);
            })
            ->orderBy('nama_guru')
            ->get(['NIP', 'nama_guru']);

        return response()->json($guru);
    }
}
