@extends('layouts.admin')

@section('content')
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">Verifikasi Pembayaran Tunai</h1>

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Detail Pembayaran -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Detail Pembayaran</h2>
                    <div class="space-y-2">
                        <p><strong>Nama Siswa:</strong> {{ $pembayaran->nama_siswa }}</p>
                        <p><strong>Kelas:</strong> {{ $pembayaran->kelas }}</p>
                        <p><strong>Jumlah Tagihan:</strong> Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</p>
                        <p><strong>Tanggal Tempo:</strong> {{ \Carbon\Carbon::parse($pembayaran->tanggal_tempo)->format('d-m-Y') }}</p>
                        <p><strong>Status:</strong>
                            <span class="px-2 py-1 bg-yellow-500 text-white rounded">
                                {{ ucfirst(str_replace('-', ' ', $pembayaran->status)) }}
                            </span>
                        </p>
                        @if($pembayaran->keterangan)
                            <p><strong>Keterangan:</strong> {{ $pembayaran->keterangan }}</p>
                        @else
                            <p><strong>Keterangan:</strong> Tidak ada keterangan</p>
                        @endif
                    </div>
                </div>

                <!-- Form Verifikasi -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Verifikasi Pembayaran</h2>
                    {{-- GANTI: route('kasir.proses') → route('admin.kasir.proses') --}}
                    <form action="{{ route('admin.kasir.proses', $pembayaran->pivot_id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Uang Diterima (Rp)
                            </label>
                            <input
                                type="number"
                                id="jumlah_bayar"
                                name="jumlah_bayar"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value="{{ old('jumlah_bayar', $pembayaran->jumlah) }}"
                                min="{{ $pembayaran->jumlah }}"
                                required
                            >
                            <p class="text-sm text-gray-600 mt-1">
                                Minimal: Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="mb-4" id="kembalian-section" style="display: none;">
                            <p class="text-sm font-medium text-gray-700">
                                Kembalian: <span id="kembalian" class="text-green-600 font-bold"></span>
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit"
                                    class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded">
                                Verifikasi & Proses Pembayaran
                            </button>
                            {{-- GANTI: route('kasir.index') → route('admin.kasir.index') --}}
                            <a href="{{ route('admin.kasir.index') }}"
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hitung kembalian otomatis
        document.getElementById('jumlah_bayar').addEventListener('input', function() {
            const jumlahBayar = parseInt(this.value) || 0;
            const totalTagihan = {{ $pembayaran->jumlah }};
            
            if (jumlahBayar >= totalTagihan) {
                const kembalian = jumlahBayar - totalTagihan;
                document.getElementById('kembalian').textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
                document.getElementById('kembalian-section').style.display = 'block';
            } else {
                document.getElementById('kembalian-section').style.display = 'none';
            }
        });

        // Hitung kembalian saat halaman pertama kali load
        document.getElementById('jumlah_bayar').dispatchEvent(new Event('input'));
    </script>
@endsection
