@extends('layouts.app')

@section('content')
    <h1>Laporan Pembayaran</h1>

    <div>
        <p><strong>Siswa:</strong> {{ $pembayaran->siswa->nama }}</p>
        <p><strong>Kategori Pembayaran:</strong> {{ $pembayaran->kategoriPembayaran->nama }}</p>
        <p><strong>Nominal:</strong> {{ $pembayaran->nominal }}</p>
        <p><strong>Status:</strong> {{ ucfirst($pembayaran->status) }}</p>
        <p><strong>Tanggal Jatuh Tempo:</strong> {{ $pembayaran->tanggal_jatuh_tempo }}</p>
    </div>
@endsection
