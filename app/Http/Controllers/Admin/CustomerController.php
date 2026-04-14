<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pesanan;

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

    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return redirect()->route('admin.customer.index')
            ->with('success', 'Customer berhasil dihapus');
    }
}
