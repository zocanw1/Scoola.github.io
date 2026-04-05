<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAkunController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->orderBy('name')->get();
        return view('admin.akun.admin-index', compact('admins'));
    }

    public function create()
    {
        return view('admin.akun.admin-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
        ]);

        return redirect()->route('admin.akun.index')
            ->with('success', 'Admin berhasil ditambahkan');
    }

    public function edit($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);
        return view('admin.akun.admin-edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
        ]);

        $admin->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $admin->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.akun.index')
            ->with('success', 'Admin berhasil diperbarui');
    }

    public function destroy($id)
    {
        $admin = User::where('role', 'admin')->findOrFail($id);

        if ($admin->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $admin->delete();

        return redirect()->route('admin.akun.index')
            ->with('success', 'Admin berhasil dihapus');
    }
}
