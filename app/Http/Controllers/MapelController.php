<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('admin.mapel.index', compact('mapel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mapel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kd_mapel'   => 'required|string|max:50|unique:mapel,kd_mapel',
            'nama_mapel' => 'required|string|max:100',
        ]);

        Mapel::create([
            'kd_mapel'   => $request->kd_mapel,
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
            'kd_mapel'   => 'required|string|max:50|unique:mapel,kd_mapel,' . $mapel->kd_mapel . ',kd_mapel',
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mapel->update([
            'kd_mapel'   => $request->kd_mapel,
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
}
