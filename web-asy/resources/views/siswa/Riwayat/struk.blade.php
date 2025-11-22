@extends('layouts.admin')

@section('title', 'Struk Pembayaran')

@section('content')
<div class="container">
    <h1 class="mt-3 mb-4">Struk Pembayaran</h1>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr><th style="width:220px;">Nama Siswa</th><td>{{ $data->nama_siswa }}</td></tr>
                <tr><th>Kelas</th><td>{{ $data->kelas }}</td></tr>
                <tr><th>Nama Pembayaran</th><td>{{ $data->nama_pembayaran }}</td></tr>
                <tr><th>Total Tagihan</th><td>{{ number_format($data->total_tagihan ?? 0, 0, ',', '.') }}</td></tr>
                <tr><th>Jumlah Bayar</th><td>{{ number_format($data->jumlah_bayar ?? 0, 0, ',', '.') }}</td></tr>
                <tr><th>Tanggal Bayar</th><td>{{ $data->tanggal_bayar ? date('d/m/Y H:i', strtotime($data->tanggal_bayar)) : '-' }}</td></tr>
                <tr><th>Metode</th><td>{{ ucfirst($data->metode ?? '-') }}</td></tr>
                <tr><th>Status</th><td>{{ ucfirst($data->status ?? '-') }}</td></tr>
            </table>

            <div class="mt-3 d-flex gap-2">
                <a href="{{ url('/siswa/riwayat/cetak-pdf/'.request()->route('id')) }}" class="btn btn-secondary">Download PDF</a>
                <a href="{{ url('/siswa/riwayat') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
