@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">Detail Tagihan</h1>

        <div class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <strong>Nama Tagihan:</strong> {{ $tagihan->nama }}
            </div>
            <div class="mb-4">
                <strong>Jumlah:</strong> Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
            </div>
            <div class="mb-4">
                <strong>Tanggal Tempo:</strong> {{ \Carbon\Carbon::parse($tagihan->tanggal_tempo)->format('d-m-Y') }}
            </div>
            <div class="mb-4">
                <strong>Status Pembayaran:</strong> 
                <span class="px-2 py-1 rounded 
                    {{ $tagihan->status == 'lunas' ? 'bg-green-500' : 
                       ($tagihan->status == 'belum-lunas' ? 'bg-red-500' : 'bg-yellow-500') }} text-white">
                    {{ ucfirst(str_replace('-', ' ', $tagihan->status)) }}
                </span>
            </div>

            <!-- Aksi Pembayaran -->
            @if($tagihan->status == 'belum-lunas')
                <a href="{{ route('siswa.pembayaran.bayarTunai', $tagihan->id) }}" class="text-blue-500 hover:underline">Bayar Tunai</a> |
                <a href="{{ route('siswa.pembayaran.bayarTransfer', $tagihan->id) }}" class="text-blue-500 hover:underline">Bayar Transfer</a>
            @elseif($tagihan->status == 'menunggu-verifikasi')
                <a href="{{ route('siswa.pembayaran.prosesPembayaran', $tagihan->id) }}" class="text-yellow-500 hover:underline">Batalkan Pembayaran</a>
            @else
                <span class="text-gray-500">Pembayaran Selesai</span>
            @endif
        </div>
    </div>
@endsection
