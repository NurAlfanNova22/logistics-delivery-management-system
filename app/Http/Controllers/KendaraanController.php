<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Sopir;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('sopirs')->get();
        return view('admin.kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        return view('admin.kendaraan.create');
    }

    public function store(Request $request)
    {
        Kendaraan::create([
            'no_polisi' => $request->no_polisi,
            'jenis' => $request->jenis,
            'merk' => $request->merk
        ]);

        return redirect('/admin/kendaraan')->with('success','Data kendaraan ditambahkan');
    }

    public function edit(Kendaraan $kendaraan)
    {
        return view('admin.kendaraan.edit', compact('kendaraan'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $kendaraan->update([
            'no_polisi' => $request->no_polisi,
            'jenis' => $request->jenis,
            'merk' => $request->merk
        ]);

        return redirect('/admin/kendaraan')->with('success','Data kendaraan diperbarui');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return back()->with('success','Data kendaraan dihapus');
    }
}