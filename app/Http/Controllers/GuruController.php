<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('user', 'mapels')->orderBy('nama_guru')->get();
        return view('admin.guru.guru-index', compact('guru'));
    }

    public function create()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('admin.guru.guru-create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip'        => 'required|string|max:50|unique:guru,NIP',
            'nama'       => 'required|string|max:255',
            'kd_mapel'   => 'required|array',
            'kd_mapel.*' => 'exists:mapel,kd_mapel',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'guru',
                'ref_id'   => $request->nip,
            ]);

            $guru = Guru::create([
                'NIP'       => $request->nip,
                'user_id'   => $user->id,
                'nama_guru' => $request->nama,
                'kd_mapel'  => $request->kd_mapel[0], // Keep first for backward compatibility
            ]);

            $guru->mapels()->sync($request->kd_mapel);
        });

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil ditambahkan');
    }

    public function edit($nip)
    {
        $guru  = Guru::with('user', 'mapels')->findOrFail($nip);
        $mapel = Mapel::orderBy('nama_mapel')->get();

        return view('admin.guru.guru-edit', compact('guru', 'mapel'));
    }

    public function update(Request $request, $nip)
    {
        $guru = Guru::with('user')->findOrFail($nip);

        $request->validate([
            'nama'       => 'required|string|max:255',
            'kd_mapel'   => 'required|array',
            'kd_mapel.*' => 'exists:mapel,kd_mapel',
        ]);

        DB::transaction(function () use ($request, $guru) {
            $guru->update([
                'nama_guru' => $request->nama,
                'kd_mapel'  => $request->kd_mapel[0], // Keep first
            ]);

            $guru->mapels()->sync($request->kd_mapel);

            $guru->user->update([
                'name' => $request->nama,
            ]);
        });

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil diperbarui');
    }

    public function destroy($nip)
    {
        $guru = Guru::with('user')->findOrFail($nip);

        DB::transaction(function () use ($guru) {
            $guru->user->delete();
            $guru->delete();
        });

        return redirect()->route('guru.index')
            ->with('success', 'Guru berhasil dihapus');
    }
}
