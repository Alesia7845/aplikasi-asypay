<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Pembayaran</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Pembayaran Siswa</h2>
    <p>Periode: {{ $start ?? '-' }} s.d {{ $end ?? '-' }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Nama Pembayaran</th>
                <th>Jumlah (Rp)</th>
                <th>Tanggal Bayar</th>
                <th>Metode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_siswa }}</td>
                <td>{{ $item->kelas }}</td>
                <td>{{ $item->nama_pembayaran }}</td>
                <td>Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d-m-Y H:i') }}</td>
                <td>{{ ucfirst($item->metode) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
