@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Pembayaran Tunai</h1>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-secondary">
        <h2 class="text-xl font-semibold mb-3">{{ $tagihan->nama }}</h2>
        <p><strong>Jumlah:</strong> Rp. {{ number_format($tagihan->jumlah, 0, ',', '.') }}</p>
        <p><strong>Untuk Pembayaran Tunai:</strong> Silakan ke kasir untuk melakukan verifikasi pembayaran. Pembayaran akan diproses oleh kasir, dan status pembayaran akan diperbarui.</p>
        <p><strong>Tanggal Tempo:</strong> {{ \Carbon\Carbon::parse($tagihan->tanggal_tempo)->format('d-m-Y') }}</p>
        
        <form action="{{ route('admin.kasir.bayarForm', $tagihan->id) }}" method="GET" class="mt-4 mb-4">
            @csrf
            <button type="submit" class="btn btn-primary w-100">Lanjutkan ke Kasir</button>
        </form>

        {{-- Form langsung konfirmasi bayar tunai --}}
        <form action="{{ route('siswa.pembayaran.bayarTunai', $tagihan->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success w-100">Konfirmasi Bayar Tunai</button>
        </form>
    </div>

    {{-- Optional flash message --}}
    @if(session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif
</div>
@endsection
