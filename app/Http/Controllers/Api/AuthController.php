<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user = Auth::user();

        // Batasi hanya customer (ubah sesuai kebutuhan)
        if ($user->role !== 'customer') {
            return response()->json([
                'message' => 'Akses ditolak'
            ], 403);
        }

        $token = $user->createToken('flutter_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('customer', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'foto' => $fotoPath
        ]);

        $token = $user->createToken('flutter_token')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function loginDriver(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        $user = Auth::user();

        if ($user->role !== 'sopir') {
            return response()->json([
                'message' => 'Akses hanya untuk sopir'
            ], 403);
        }

        // ambil data sopir
        $sopir = $user->sopir;

        $token = $user->createToken('driver_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ],
            'sopir' => $sopir,
            'token' => $token
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::disk('public')->delete($user->foto);
            }
            $data['foto'] = $request->file('foto')->store('customer', 'public');
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil berhasil diperbarui',
            'user' => $user
        ]);
    }

    public function updateDriverProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $sopir = $user->sopir;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Update User
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Sopir Photo if exists
        if ($request->hasFile('foto')) {
            if ($sopir && $sopir->foto) {
                Storage::disk('public')->delete($sopir->foto);
            }
            if ($sopir) {
                $sopir->update([
                    'foto' => $request->file('foto')->store('sopir', 'public')
                ]);
            }
        }

        return response()->json([
            'message' => 'Profil sopir berhasil diperbarui',
            'user' => $user,
            'sopir' => $user->sopir
        ]);
    }
}