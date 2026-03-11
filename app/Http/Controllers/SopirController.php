<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sopir;

class SopirController extends Controller
{
    public function index()
    {
        $sopirs = Sopir::all();
        return view('admin.sopir.index', compact('sopirs'));
    }

    public function create()
    {
        return view('admin.sopir.create');
    }

    public function store(Request $request)
    {
        Sopir::create($request->all());
        return redirect('/admin/sopir')->with('success','Data sopir ditambahkan');
    }

    public function edit(Sopir $sopir)
    {
        return view('admin.sopir.edit', compact('sopir'));
    }

    public function update(Request $request, Sopir $sopir)
    {
        $sopir->update($request->all());
        return redirect('/admin/sopir')->with('success','Data sopir diupdate');
    }

    public function destroy(Sopir $sopir)
    {
        $sopir->delete();
        return back()->with('success','Data sopir dihapus');
    }
}