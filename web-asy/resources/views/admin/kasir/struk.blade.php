<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .struk { border: 1px solid #000; padding: 20px; width: 400px; }
    </style>
</head>
<body>
    <div class="struk">
        <h2>Struk Pembayaran</h2>
        <p><strong>Nama Siswa:</strong> {{ $pembayaran->nama_siswa }}</p>
        <p><strong>Kelas:</strong> {{ $pembayaran->kelas }}</p>
        <p><strong>Nama Pembayaran:</strong> {{ $pembayaran->nama_pembayaran }}</p>
        <p><strong>Jumlah Bayar:</strong> Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</p>
        <p><strong>Tanggal Bayar:</strong> {{ $pembayaran->tanggal_bayar }}</p>
        <hr>
        <p>Terima kasih telah melakukan pembayaran.</p>
    </div>
    <script>
        window.print(); // auto print saat halaman dibuka
    </script>
</body>
</html>
