@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="container">
    <h2>Detail Pembayaran</h2>

    {{-- Notifikasi flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $pembayaran->nama }}</td>
                    </tr>
                    <tr>
                        <th>Kelas</th>
                        <td>{{ $pembayaran->kelas }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Status Pembayaran</th>
                        <td>
                            @php
                                // safety net: normalisasi lagi di blade
                                $statusBlade = strtolower(trim($statusRaw ?? 'belum-lunas'));
                                $statusBlade = str_replace(['_', ' '], '-', $statusBlade);
                            @endphp

                            @if ($statusBlade === 'belum-lunas')
                                <span class="badge bg-warning text-dark">Belum Lunas</span>
                            @elseif ($statusBlade === 'menunggu-verifikasi')
                                <span class="badge bg-info text-dark">Menunggu Verifikasi</span>
                            @elseif ($statusBlade === 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @elseif ($statusBlade === 'dibatalkan')
                                <span class="badge bg-danger">Dibatalkan</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst(str_replace('-', ' ', $statusBlade)) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Metode Pembayaran</th>
                        <td>
                            @if ($statusBlade === 'belum-lunas')
                                -
                            @elseif ($statusBlade === 'menunggu-verifikasi')
                                Tunai
                            @else
                                {{ $metodePivot ? ucfirst(str_replace('-', ' ', $metodePivot)) : '-' }}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            @if ($statusBlade === 'menunggu-verifikasi')
                <p class="mt-3 text-warning">Silakan tunggu verifikasi pembayaran tunai dari kasir.</p>
            @elseif ($statusBlade === 'lunas')
                <div class="alert alert-success">
                    🎉 Pembayaran Anda telah <strong>lunas</strong>. Terima kasih telah melakukan pembayaran.
                </div>
            @elseif ($statusBlade === 'dibatalkan')
                <p class="mt-3 text-danger">Pembayaran Anda dibatalkan. Silakan coba bayar ulang.</p>
            @endif
        </div>
    </div>

    {{-- Tampilkan pilihan metode jika masih belum-lunas atau dibatalkan --}}
    @if (in_array($statusBlade, ['belum-lunas','dibatalkan']))
        <div class="card mb-3">
            <div class="card-body">
                <h5>Pilih Metode Pembayaran</h5>

                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" id="btnTunai">Tunai</button>
                    <button class="btn btn-outline-success" id="btnTransfer">Transfer</button>
                </div>

                {{-- Form Konfirmasi Tunai --}}
                <form id="formTunai"
                      action="{{ route('siswa.pembayaran.konfirmasiTunai', $pembayaran->id) }}"
                      method="POST"
                      class="mt-3 d-none">
                    @csrf
                    <p>Anda memilih metode <strong>TUNAI</strong>. Klik tombol di bawah untuk mengonfirmasi pembayaran tunai.</p>
                    <button type="submit" class="btn btn-primary">Konfirmasi Tunai</button>
                </form>

                {{-- Flash success (jika ada) --}}
                @if(session('success'))
                    <div class="alert alert-success mt-3">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Placeholder untuk Tombol Konfirmasi Transfer --}}
                <div id="formTransfer" class="mt-3 d-none">
                    <p>Anda memilih metode <strong>TRANSFER</strong>. Klik tombol di bawah untuk membayar via Midtrans.</p>
                    <button type="button" id="btnFetchToken" class="btn btn-success">
                        Konfirmasi Transfer
                    </button>
                    <p id="transferError" class="text-danger mt-2" style="display:none;"></p>
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('siswa.pembayaran.index') }}" class="btn btn-secondary">Kembali ke Daftar Pembayaran</a>
</div>

{{-- Snap.js --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnTunai     = document.getElementById('btnTunai');
    const btnTransfer  = document.getElementById('btnTransfer');
    const formTunai    = document.getElementById('formTunai');
    const formTransfer = document.getElementById('formTransfer');
    const btnFetch     = document.getElementById('btnFetchToken');
    const errText      = document.getElementById('transferError');

    // Tampilkan form Tunai
    btnTunai?.addEventListener('click', function () {
        formTunai.classList.remove('d-none');
        formTransfer.classList.add('d-none');
    });

    // Tampilkan opsi Transfer
    btnTransfer?.addEventListener('click', function () {
        formTransfer.classList.remove('d-none');
        formTunai.classList.add('d-none');
    });

    // Saat tombol “Konfirmasi Transfer” diklik, ambil token via AJAX
    btnFetch?.addEventListener('click', function () {
        errText.style.display = 'none';
        errText.innerText = '';

        const urlGenerate = "{{ route('siswa.pembayaran.generateToken', $pembayaran->id) }}";

        fetch(urlGenerate, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.snapToken) {
                snap.pay(data.snapToken, {
                    onSuccess: function(result) {
                        window.location.href = "{{ route('siswa.pembayaran.index') }}";
                    },
                    onPending: function(result) {
                        window.location.href = "{{ route('siswa.pembayaran.index') }}";
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal: " + (result.status_message || "Error tidak diketahui."));
                    },
                    onClose: function() {
                        alert("Anda menutup popup tanpa menyelesaikan pembayaran.");
                    }
                });
            } else {
                throw { error: "Snap token tidak ditemukan." };
            }
        })
        .catch(err => {
            console.error("Error generate token:", err);
            errText.innerText = err.error || "Gagal mendapatkan token, silakan hubungi admin.";
            errText.style.display = 'block';
        });
    });
});
</script>
@endsection
