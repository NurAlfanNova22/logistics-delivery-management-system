<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sopir;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Kendaraan;
use Illuminate\Support\Facades\Storage;

class SopirController extends Controller
{
    public function index()
    {
        $sopirs = Sopir::all();
        return view('admin.sopir.index', compact('sopirs'));
    }

    public function create()
    {
        $kendaraans = Kendaraan::all();

        return view('admin.sopir.create', compact('kendaraans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'no_hp' => 'required',
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('sopir', 'public');
        }

        // buat akun login driver
        $user = User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'sopir'
        ]);

        // simpan data sopir
        Sopir::create([
            'user_id' => $user->id,
            'kendaraan_id' => $request->kendaraan_id,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto' => $fotoPath
        ]);

        return redirect('/admin/sopir')->with('success','Data sopir ditambahkan');
    }

    public function edit(Sopir $sopir)
    {
        $kendaraans = Kendaraan::all();

        return view('admin.sopir.edit', compact('sopir','kendaraans'));
    }

    public function update(Request $request, Sopir $sopir)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'no_hp' => 'required',
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $dataSopir = [
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'kendaraan_id' => $request->kendaraan_id
        ];

        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($sopir->foto) {
                Storage::disk('public')->delete($sopir->foto);
            }
            $dataSopir['foto'] = $request->file('foto')->store('sopir', 'public');
        }

        // update user login
        $sopir->user->update([
            'name' => $request->nama,
            'email' => $request->email
        ]);

        // update data sopir
        $sopir->update($dataSopir);

        return redirect('/admin/sopir')->with('success','Data sopir diupdate');
    }

    public function destroy(Sopir $sopir)
    {
        // Hapus foto jika ada
        if ($sopir->foto) {
            Storage::disk('public')->delete($sopir->foto);
        }

        // hapus user yang terkait
        if ($sopir->user) {
            $sopir->user->delete();
        }

        // hapus data sopir
        $sopir->delete();

        return back()->with('success','Data sopir dan akun login dihapus');
    }
}