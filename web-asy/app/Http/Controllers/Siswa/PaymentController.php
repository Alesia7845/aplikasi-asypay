<?php

namespace App\Http\Controllers\Siswa;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Midtrans\Snap;
use Midtrans\Notification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Create a new payment and return Snap token.
     */
    public function createPayment(Request $request)
    {
        // Validasi input
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
        ]);

        // Buat order ID unik
        $orderId = Str::uuid()->toString();

        // Simpan ke database dulu
        $pembayaran = Pembayaran::create([
            'order_id'           => $orderId,
            'user_id'            => Auth::id(),
            'nama'               => Auth::user()->name,
            'kelas'              => Auth::user()->kelas ?? 'Unknown',
            'total'              => $request->amount,
            'tanggal_buat'       => now(),
            'status_pembayaran'  => 'Pending',
            'deskripsi'          => $request->description,
        ]);

        // Siapkan data untuk Midtrans
        $transactionDetails = [
            'order_id' => $orderId,
            'gross_amount' => (int) $request->amount,
        ];

        $itemDetails = [[
            'id' => uniqid(),
            'price' => (int) $request->amount,
            'quantity' => 1,
            'name' => 'Pembayaran: ' . $request->description,
        ]];

        $customerDetails = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
        ];

        $paymentTransaction = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($paymentTransaction);
            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment initiation failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans callback notification.
     */
    public function handleNotification(Request $request)
    {
        // Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.isProduction');
        \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is3ds');

        // Tangkap notifikasi dari Midtrans
        $notification = new Notification();

        // Get transaction status and order ID
        $transactionStatus = $notification->transaction_status;
        $orderId = $notification->order_id;

        // Cari pembayaran berdasarkan order_id
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Data pembayaran tidak ditemukan.'], 404);
        }

        // Check payment status and update accordingly
        switch ($transactionStatus) {
            case 'settlement':
                $pembayaran->update([
                    'status_pembayaran' => 'Lunas',
                    'tanggal_pembayaran' => now(),
                ]);
                Log::info("Payment successful for Order ID: $orderId");
                break;

            case 'pending':
                $pembayaran->update([
                    'status_pembayaran' => 'Pending',
                ]);
                Log::info("Payment is pending for Order ID: $orderId");
                break;

            case 'cancel':
            case 'expire':
            case 'deny':
                $pembayaran->update([
                    'status_pembayaran' => 'Gagal',
                ]);
                Log::info("Payment failed for Order ID: $orderId");
                break;

            default:
                Log::warning("Unknown transaction status: $transactionStatus for Order ID: $orderId");
        }

        // Return a success response
        return response()->json(['message' => 'Notifikasi diproses'], 200);
    }
}
