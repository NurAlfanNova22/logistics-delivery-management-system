<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
use App\Models\Sopir;

class KendaraanController extends Controller
{
    public function index()
    {
        $kendaraans = Kendaraan::with('sopir')->get();
        return view('admin.kendaraan.index', compact('kendaraans'));
    }

    public function create()
    {
        $sopirs = Sopir::all();
        return view('admin.kendaraan.create', compact('sopirs'));
    }

    public function store(Request $request)
    {
        Kendaraan::create($request->all());
        return redirect('/admin/kendaraan')->with('success','Data kendaraan ditambahkan');
    }

    public function edit(Kendaraan $kendaraan)
    {
        $sopirs = Sopir::all();
        return view('admin.kendaraan.edit', compact('kendaraan','sopirs'));
    }

    public function update(Request $request, Kendaraan $kendaraan)
    {
        $kendaraan->update($request->all());
        return redirect('/admin/kendaraan')->with('success','Data kendaraan diperbarui');
    }

    public function destroy(Kendaraan $kendaraan)
    {
        $kendaraan->delete();
        return back()->with('success','Data kendaraan dihapus');
    }
}