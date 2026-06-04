<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use Carbon\Carbon;

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
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
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
            'no_hp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        // Update User
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Sopir
        if ($sopir) {
            $sopirData = [
                'nama' => $request->name,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
            ];

            // Update Sopir Photo if exists
            if ($request->hasFile('foto')) {
                if ($sopir->foto) {
                    Storage::disk('public')->delete($sopir->foto);
                }
                $sopirData['foto'] = $request->file('foto')->store('sopir', 'public');
            }

            $sopir->update($sopirData);
        }

        return response()->json([
            'message' => 'Profil sopir berhasil diperbarui',
            'user' => $user,
            'sopir' => $user->sopir
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'Email tidak terdaftar'], 404);
        }

        // Generate 6 digit OTP
        $otp = rand(100000, 999999);

        // Save to DB
        DB::table('password_reset_otps')->updateOrInsert(
            ['email' => $request->email],
            [
                'otp' => Hash::make($otp),
                'created_at' => Carbon::now()
            ]
        );

        // Send Email
        Mail::to($request->email)->send(new OtpMail($otp));

        return response()->json(['message' => 'Kode OTP telah dikirim ke email Anda']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed'
        ]);

        $otpRecord = DB::table('password_reset_otps')->where('email', $request->email)->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'Permintaan tidak valid'], 400);
        }

        // Check if OTP expired (15 minutes)
        if (Carbon::parse($otpRecord->created_at)->addMinutes(15)->isPast()) {
            DB::table('password_reset_otps')->where('email', $request->email)->delete();
            return response()->json(['message' => 'Kode OTP telah kadaluarsa'], 400);
        }

        // Verify OTP
        if (!Hash::check($request->otp, $otpRecord->otp)) {
            return response()->json(['message' => 'Kode OTP tidak valid'], 400);
        }

        // Update Password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete OTP record
        DB::table('password_reset_otps')->where('email', $request->email)->delete();

        return response()->json(['message' => 'Password berhasil direset. Silakan login kembali.']);
    }
    public function updateFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required']);
        $request->user()->update(['fcm_token' => $request->fcm_token]);
        return response()->json(['message' => 'FCM Token updated']);
    }
}