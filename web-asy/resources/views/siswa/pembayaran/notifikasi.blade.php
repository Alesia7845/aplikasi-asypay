@extends('layouts.admin')

@section('content')
<h2>Instruksi Pembayaran Tunai</h2>

<p>Silakan tunjukkan data ini ke petugas kasir untuk verifikasi pembayaran:</p>

<ul>
    <li><strong>Nama:</strong> {{ auth()->user()->name }}</li>
    <li><strong>Kelas:</strong> {{ auth()->user()->kelas }}</li>
    <li><strong>Nama Pembayaran:</strong> {{ $tagihan->nama }}</li>
    <li><strong>Jumlah:</strong> Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}</li>
    <li><strong>Tanggal Pembayaran:</strong> {{ now()->format('d M Y H:i') }}</li>
    <li><strong>Status:</strong> Menunggu Verifikasi</li>
</ul>

<p>Setelah membayar tunai, Anda dapat menunggu verifikasi dari kasir.</p>
@endsection
