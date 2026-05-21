<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;

class KakonsliController extends Controller
{
    public function index()
    {
        // Fetch the single Kakonsli (since only 1 is allowed)
        $kakonsli = User::where('role', 'kakonsli')->first();
        return view('admin.kakonsli.index', compact('kakonsli'));
    }

    public function create()
    {
        // Limit to 1 Kakonsli
        if (User::where('role', 'kakonsli')->exists()) {
            return redirect()->route('admin.kakonsli.index')
                ->with('error', 'Hanya diperbolehkan memiliki 1 akun Kakonsli secara eksklusif.');
        }

        return view('admin.kakonsli.create');
    }

    public function store(Request $request)
    {
        // Limit to 1 Kakonsli
        if (User::where('role', 'kakonsli')->exists()) {
            return redirect()->route('admin.kakonsli.index')
                ->with('error', 'Hanya diperbolehkan memiliki 1 akun Kakonsli secara eksklusif.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'kakonsli',
        ]);

        ActivityLog::log("Menambahkan akun Kakonsli baru: {$request->name} ({$request->email})");

        return redirect()->route('admin.kakonsli.index')
            ->with('success', 'Akun Kakonsli berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kakonsli = User::where('role', 'kakonsli')->findOrFail($id);
        return view('admin.kakonsli.edit', compact('kakonsli'));
    }

    public function update(Request $request, $id)
    {
        $kakonsli = User::where('role', 'kakonsli')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $kakonsli->id,
        ]);

        $kakonsli->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $kakonsli->update(['password' => Hash::make($request->password)]);
        }

        ActivityLog::log("Memperbarui akun Kakonsli: {$kakonsli->name} ({$kakonsli->email})");

        return redirect()->route('admin.kakonsli.index')
            ->with('success', 'Akun Kakonsli berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kakonsli = User::where('role', 'kakonsli')->findOrFail($id);
        
        $name = $kakonsli->name;
        $email = $kakonsli->email;
        
        $kakonsli->delete();

        ActivityLog::log("Menghapus akun Kakonsli: {$name} ({$email})");

        return redirect()->route('admin.kakonsli.index')
            ->with('success', 'Akun Kakonsli berhasil dihapus');
    }
}
