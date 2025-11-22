<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function __construct()
    {
        // Optional: if authentication is required for payments
        // $this->middleware('auth');
    }

    /**
     * Endpoint untuk Generate Snap Token dan redirect ke Snap Payment
     * Route: POST /bayar-midtrans
     */
    public function pay(Request $request)
    {
        $user = Auth::user();
        $idPembayaran = $request->input('pembayaran_id');
        $pembayaran   = Pembayaran::where('id', $idPembayaran)
                                  ->where('kelas', $user->kelas)
                                  ->firstOrFail();

        // Load konfigurasi Midtrans
        Config::$serverKey    = config('midtrans.midtrans.server_key');
        Config::$isProduction = config('midtrans.midtrans.is_production');
        Config::$isSanitized  = config('midtrans.midtrans.is_sanitized');
        Config::$is3ds        = config('midtrans.midtrans.is_3ds');

        // Persiapkan parameter
        $orderId = 'ORDER-' . $pembayaran->id . '-USER-' . $user->id . '-' . time();
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $pembayaran->jumlah,
            ],
            'customer_details'    => [
                'first_name' => $user->name,
                'email'      => $user->email ?? 'guest@example.com',
            ],
            'enabled_payments'    => ['bank_transfer', 'gopay', 'credit_card'],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Simpan data pivot dengan status menunggu-pembayaran
            $pembayaran->siswa()->syncWithoutDetaching([
                $user->id => [
                    'status'             => 'menunggu-pembayaran',
                    'order_id_midtrans'  => $orderId, // kolom tambahan jika perlu
                ]
            ]);

            // Return the Snap Token to the front-end for further processing
            return response()->json([
                'snap_token' => $snapToken,
                'order_id'   => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans payment error: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan pada pembayaran.'], 500);
        }
    }
}
