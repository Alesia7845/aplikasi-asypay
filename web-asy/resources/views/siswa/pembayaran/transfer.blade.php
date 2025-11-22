@extends('layouts.admin')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Pembayaran dengan Transfer</h1>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-secondary">
        <h2 class="text-xl font-semibold mb-3">{{ $tagihan->nama }}</h2>
        <p><strong>Jumlah:</strong> Rp. {{ number_format($tagihan->jumlah, 0, ',', '.') }}</p>

        <!-- Midtrans Payment -->
        <form action="{{ $midtrans }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-success w-100">Bayar via Midtrans</button>
        </form>
    </div>
</div>
@endsection
