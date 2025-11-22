@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Pilih Metode Pembayaran</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <!-- Bayar Tunai -->
            <a href="{{ route('siswa.pembayaran.tunai', $pivot->id) }}" class="btn btn-primary btn-block">
                Bayar Tunai
            </a>
        </div>
        <div class="col-md-6">
            <!-- Bayar Transfer -->
            <a href="{{ route('siswa.pembayaran.transfer', $pivot->id) }}" class="btn btn-success btn-block">
                Bayar Transfer
            </a>
        </div>
    </div>
</div>
@endsection
