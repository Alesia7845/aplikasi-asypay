<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .container { width: 100%; padding: 20px; }
        .title { font-size: 20px; margin-bottom: 20px; }
        .info { margin-bottom: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="title">Struk Pembayaran</div>

    <div class="info">Nama: {{ auth()->user()->name }}</div>
    <div class="info">NIS: {{ auth()->user()->nis }}</div>
    <div class="info">Kelas: {{ auth()->user()->kelas }}</div>
    <div class="info">Tanggal Pembayaran: {{ $pembayaran->tanggal_pembayaran }}</div>
    <div class="info">Kategori: {{ $pembayaran->kategori }}</div>
    <div class="info">Jumlah: Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
    <div class="info">Metode: {{ ucfirst($pembayaran->metode) }}</div>
    <div class="info">Status: {{ $pembayaran->status_pembayaran }}</div>

    <hr>
    <p>Terima kasih telah melakukan pembayaran.</p>
</div>
</body>
</html>
