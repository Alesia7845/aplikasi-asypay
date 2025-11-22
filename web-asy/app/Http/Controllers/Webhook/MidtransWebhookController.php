<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\RiwayatPembayaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Memproses notifikasi Midtrans.
 */
class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
{
    $payload = $request->all();

    $orderId     = $payload['order_id']      ?? null;
    $statusCode  = $payload['status_code']   ?? null;
    $grossAmount = $payload['gross_amount']  ?? null; // string
    $signature   = $payload['signature_key'] ?? null;
    $paymentType = $payload['payment_type']  ?? null;
    $transactionStatus = $payload['transaction_status'] ?? null;
    $fraudStatus = $payload['fraud_status'] ?? null;

    // PAKAI KUNCI YANG SAMA dengan controller lain
    $serverKey = config('midtrans.midtrans.server_key'); // <— tadinya 'services.midtrans.server_key'
    $localSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

    if (!$signature || !hash_equals($signature, $localSignature)) {
        Log::warning('Midtrans signature mismatch', ['order_id' => $orderId]);
        return response()->json(['message' => 'Invalid signature'], 200); // balas 200
    }

    $riwayat = RiwayatPembayaran::where('order_id', $orderId)->first();
    if (!$riwayat) {
        Log::warning('Midtrans order not found (riwayat)', ['order_id' => $orderId]);
        return response()->json(['message' => 'Order not found'], 200);
    }

    DB::transaction(function () use ($riwayat, $paymentType, $transactionStatus, $fraudStatus, $payload) {
        $updates = [
            'payment_type'       => $paymentType,
            'transaction_status' => $transactionStatus,
            'fraud_status'       => $fraudStatus,
            'settlement_time'    => null,
            'gross_amount'       => $payload['gross_amount'] ?? $riwayat->gross_amount, // pastikan ada kolomnya
        ];

        if (in_array($transactionStatus, ['settlement','capture'], true) && ($fraudStatus ?? 'accept') !== 'challenge') {
            if ($riwayat->status !== 'lunas') {
                $updates['status']          = 'lunas';
                $updates['tanggal_bayar']   = isset($payload['settlement_time'])
                                              ? Carbon::parse($payload['settlement_time'])
                                              : Carbon::now();
                $updates['settlement_time'] = $updates['tanggal_bayar'];
            }
        } elseif (in_array($transactionStatus, ['cancel','expire','deny'], true)) {
            $updates['status'] = 'gagal';
        } else {
            $updates['status'] = 'pending';
        }

        $riwayat->update($updates);
    });

    return response()->json(['message' => 'Webhook processed'], 200);
}

}
