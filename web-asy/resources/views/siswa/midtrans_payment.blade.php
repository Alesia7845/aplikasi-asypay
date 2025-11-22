@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Proses Pembayaran Midtrans</h2>
    <p>Silakan tunggu, Anda akan diarahkan ke halaman pembayaran...</p>
</div>

{{-- Panggil Snap.js, gunakan key yang benar (huruf kecil: midtrans) --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    // Pastikan snapToken tersedia
    if ("{{ $snapToken ?? '' }}" !== "") {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                console.log('success', result);
                window.location.href = '/dashboard'; // atau rute lain sesuai kebutuhan
            },
            onPending: function(result) {
                console.log('pending', result);
                window.location.href = '/dashboard';
            },
            onError: function(result) {
                console.log('error', result);
                alert('Terjadi kesalahan saat memproses pembayaran.');
            },
            onClose: function() {
                alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
            }
        });
    } else {
        console.error('Midtrans Snap token kosong.');
        alert('Gagal mendapatkan token, silakan hubungi admin.');
    }
</script>
@endsection
