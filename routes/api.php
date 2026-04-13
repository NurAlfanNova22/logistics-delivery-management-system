<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PesananApiController;
use App\Http\Controllers\Api\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pesanan', [PesananApiController::class, 'index']);
Route::post('/pesanan', [PesananApiController::class, 'store']);
Route::post('/payment/callback', [PesananApiController::class, 'paymentCallback']);
Route::post('/pesanan/{id}/selesaikan', [PesananApiController::class, 'selesaikanPesanan']);
Route::post('/pesanan/{id}/batal', [PesananApiController::class, 'batalkanPesanan']);

Route::get('/driver/orders/{sopir_id}', [PesananApiController::class, 'driverOrders']);

Route::post('/driver/update-status/{id}', [PesananApiController::class, 'updateStatusPengiriman']);

Route::get('/tracking/{resi}', [PesananApiController::class, 'trackingResi']);
Route::post('/driver/add-checkpoint', [PesananApiController::class, 'addCheckpoint']);
Route::post('/driver/toggle-online', [PesananApiController::class, 'toggleOnline']);

Route::get('/dashboard', function () {
    return response()->json([
        'total' => \App\Models\Pesanan::count(),

        'diproses' => \App\Models\Pesanan::where('status','LIKE','%MENUNGGU%')->count(),

        'dikirim' => \App\Models\Pesanan::where('status_pengiriman','DALAM PERJALANAN')->count(),

        'selesai' => \App\Models\Pesanan::where('status','SELESAI')->count(),
    ]);
});

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/driver/stats/{sopir_id}', [PesananApiController::class,'driverStats']);

Route::post('/driver/login', [AuthController::class, 'loginDriver']);