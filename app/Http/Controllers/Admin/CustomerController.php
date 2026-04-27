<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('pesanans')
            ->latest()
            ->paginate(10);

        return view('admin.customer.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = User::with(['pesanans' => function($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('admin.customer.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'no_hp' => 'required',
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat
        ];

        if ($request->hasFile('foto')) {
            if ($customer->foto) {
                Storage::disk('public')->delete($customer->foto);
            }
            $data['foto'] = $request->file('foto')->store('customer', 'public');
        }

        $customer->update($data);

        return redirect()->route('admin.customer.index')->with('success', 'Data customer berhasil diupdate');
    }

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        if ($customer->foto) {
            Storage::disk('public')->delete($customer->foto);
        }
        
        $customer->delete();

        return redirect()->route('admin.customer.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
