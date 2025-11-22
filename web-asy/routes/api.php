<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\SiswaTagihanController;
use App\Http\Controllers\Webhook\MidtransWebhookController;
use App\Http\Controllers\Api\SiswaController;

// Webhook (tanpa auth)
Route::post('/webhooks/midtrans', [MidtransWebhookController::class, 'handle'])->name('webhooks.midtrans');

// === AUTH ===

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user/{nis}', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
// === DATA UNTUK APP ===

    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran');
    Route::get('/pembayaran/{id}/{nis}', [PembayaranController::class, 'show']);     // detail tagihan
    Route::post('/pembayaran/{id}/bayar', [PembayaranController::class, 'bayar']);
    Route::get('/riwayat',     [PembayaranController::class, 'riwayat'])->name('riwayat');

    Route::get('/tagihan/{nis}', [SiswaTagihanController::class, 'summary']);
    Route::get('/tagihan/tunggakan/{nis}', [SiswaTagihanController::class, 'getTunggakan']);


    Route::get('/getNama', [SiswaController::class, 'getNama']);
// Route::get('/test', function () {
//     return response()->json([
//         'status' => 'success',
//         'message' => 'Api berhasil terhubung ke flutter!'
//     ]);
// });

