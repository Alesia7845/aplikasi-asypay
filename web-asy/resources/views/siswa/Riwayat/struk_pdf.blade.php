<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        /* Reset & base */
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; }
        body {
            font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding: 24px 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Container / receipt card */
        .container {
            width: 100%;
            max-width: 420px;           /* ukuran struk yang nyaman untuk cetak */
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(16,24,40,0.08);
            overflow: hidden;
            border: 1px solid rgba(17,24,39,0.06);
        }

        /* Header */
        .header {
            text-align: center;
            padding: 18px 20px;
            border-bottom: 1px dashed rgba(17,24,39,0.06);
            background: linear-gradient(180deg, rgba(99,102,241,0.04), transparent);
        }
        .header h2 {
            margin: 0;
            font-size: 18px;
            letter-spacing: 0.2px;
            color: #0f172a;
            font-weight: 700;
        }
        .header small {
            display: block;
            color: #6b7280;
            font-size: 12px;
            margin-top: 6px;
        }

        /* Detail (main body) */
        .detail {
            padding: 18px 20px;
        }
        .items {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px 12px;
            align-items: center;
            margin-bottom: 12px;
        }
        .items p {
            margin: 0;
            font-size: 14px;
            color: #111827;
        }
        .items .label {
            color: #374151;
            font-weight: 600;
            font-size: 13px;
        }
        .items .value {
            color: #111827;
            text-align: right;
            font-weight: 600;
            font-size: 13px;
        }

        /* Jumlah bayar menonjol */
        .amount {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            padding: 12px;
            border-radius: 8px;
            background: #f8fafc;
            border: 1px solid rgba(17,24,39,0.04);
            margin: 12px 0;
        }
        .amount .title { font-size: 13px; color: #374151; font-weight: 600; }
        .amount .money {
            font-family: "Courier New", Courier, monospace;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.6px;
            color: #0b5b3f; /* hijau gelap untuk nominal */
        }

        /* Meta info */
        .meta {
            font-size: 12px;
            color: #6b7280;
            margin-top: 8px;
        }

        /* Footer */
        .footer {
            padding: 16px 20px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 1px dashed rgba(17,24,39,0.06);
            background: #fff;
        }
        .signature {
            margin-top: 12px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:12px;
        }
        .signature .left { font-size:12px; color:#374151; }
        .signature .line {
            flex:1;
            border-bottom:1px dashed rgba(55,65,81,0.12);
            margin-left:12px;
            margin-right:12px;
        }

        /* Small / mobile tweaks */
        @media (max-width: 420px) {
            body { padding: 12px; }
            .container { max-width: 100%; }
            .header h2 { font-size: 16px; }
            .amount .money { font-size: 16px; }
        }

        /* Print-friendly */
        @media print {
            body { background: white; padding: 0; }
            .container {
                box-shadow: none;
                border: none;
                max-width: 100%;
                width: 100%;
            }
            .header { background: transparent; border-bottom: 1px solid #ddd; }
            .footer { border-top: 1px solid #ddd; }
            @page { margin: 10mm; }
            * { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Struk Pembayaran</h2>
            <small>Terima kasih telah melakukan pembayaran</small>
        </div>

        <div class="detail">
            <div class="items">
                <p class="label">Nama Siswa</p>
                <p class="value">{{ $data->nama_siswa }}</p>

                <p class="label">Kelas</p>
                <p class="value">{{ $data->kelas }}</p>

                <p class="label">Nama Pembayaran</p>
                <p class="value">{{ $data->nama_pembayaran }}</p>

                <p class="label">Tanggal Bayar</p>
                <p class="value">{{ \Carbon\Carbon::parse($data->tanggal_bayar)->format('d-m-Y H:i') }}</p>

                <p class="label">Metode</p>
                <p class="value">{{ ucfirst($data->metode) }}</p>
            </div>

            <div class="amount" aria-hidden="true">
                <div class="title">Jumlah Bayar</div>
                <div class="money">Rp {{ number_format($data->jumlah_bayar, 0, ',', '.') }}</div>
            </div>

            <div class="meta">
                <div>Jika ada pertanyaan, hubungi bagian administrasi.</div>
                <div style="margin-top:6px;">No. Transaksi: <strong>{{ $data->id ?? '-' }}</strong></div>
            </div>
        </div>

        <div class="footer">
            <div>Terima kasih telah melakukan pembayaran.</div>
            <div class="signature">
                <div class="left">Petugas</div>
                <div class="line"></div>
                <div style="font-size:12px;color:#6b7280">Tanda Tangan</div>
            </div>
        </div>
    </div>
</body>
</html>
