<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        $totalGuru = Guru::count();

        return view('admin.mapel.index', compact('mapel', 'totalGuru'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $nextKodeMapel = $this->generateNextKodeMapel();

        return view('admin.mapel.create', compact('nextKodeMapel'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        Mapel::create([
            'kd_mapel'   => $this->generateNextKodeMapel(),
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()
            ->route('mapel.index')
            ->with('success', 'Mapel berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mapel $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mapel $mapel)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mapel->update([
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()
            ->route('mapel.index')
            ->with('success', 'Mapel berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mapel $mapel)
    {
        $mapel->delete();

        return redirect()
            ->route('mapel.index')
            ->with('success', 'Mapel berhasil dihapus');
    }

    private function generateNextKodeMapel(): string
    {
        $maxNumber = Mapel::query()
            ->where('kd_mapel', 'like', 'KD_%')
            ->pluck('kd_mapel')
            ->pipe(function (Collection $kodeMapels): int {
                return $kodeMapels
                    ->map(function (string $kodeMapel): int {
                        if (preg_match('/^KD_(\d+)$/', $kodeMapel, $matches) !== 1) {
                            return 0;
                        }

                        return (int) $matches[1];
                    })
                    ->max() ?? 0;
            });

        return 'KD_' . ($maxNumber + 1);
    }
}
