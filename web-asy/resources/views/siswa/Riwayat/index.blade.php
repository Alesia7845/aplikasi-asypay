@extends('layouts.admin')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container">
    <h1 class="mt-3 mb-4">Riwayat Pembayaran</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    {{-- Filter sederhana, action = URL halaman sekarang (anti error nama route) --}}
    <form class="row g-2 mb-3" method="GET" action="{{ url()->current() }}">
        <div class="col-md-4">
            <input type="text" name="pembayaran" class="form-control" placeholder="Cari nama pembayaran…"
                   value="{{ request('pembayaran') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div class="col-md-2 d-grid">
            <button class="btn btn-primary">Terapkan</button>
        </div>
    </form>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
        <tr>
            <th style="width:5%;">#</th>
            <th>Pembayaran</th>
            <th>Total Tagihan</th>
            <th>Jumlah Bayar</th>
            <th>Tanggal Bayar</th>
            <th>Metode</th>
            <th>Status</th>
            <th style="width:20%;">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse($riwayat as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_pembayaran }}</td>
                <td>{{ number_format($item->total_tagihan ?? 0, 0, ',', '.') }}</td>
                <td>{{ number_format($item->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                <td>{{ $item->tanggal_bayar ? date('d/m/Y H:i', strtotime($item->tanggal_bayar)) : '-' }}</td>
                <td>{{ ucfirst($item->metode ?? '-') }}</td>
                <td>{{ ucfirst($item->status ?? '-') }}</td>
                <td>
                    {{-- Pakai path langsung untuk menghindari error nama route --}}
                    <a href="{{ url('/siswa/riwayat/cetak/'.$item->id) }}" class="btn btn-sm btn-primary">Lihat Struk</a>
                    <a href="{{ url('/siswa/riwayat/cetak-pdf/'.$item->id) }}" class="btn btn-sm btn-secondary" target="_blank">Download PDF</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Belum ada riwayat pembayaran.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
