@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mx-auto py-6 space-y-6">

    {{-- Header + Filter Tanggal --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <h1 class="text-2xl font-bold">Ringkasan</h1>

        <form method="GET" action="{{ route('admin.dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-2 w-full md:w-auto">
            <div class="col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date"
                    value="{{ optional($startDate ?? null)->format('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date"
                    value="{{ optional($endDate ?? null)->format('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2 text-sm">
            </div>
            <div class="col-span-1 flex items-end">
                <button class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white rounded px-4 py-2 text-sm">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        {{-- Total Siswa + filter kelas (client-side) --}}
        <div class="bg-white p-5 rounded-2xl shadow border">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold">Total Siswa</h3>
                <select id="filterKelas"
                        class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasOptions as $k)
                        <option value="{{ $k }}">Kelas {{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-baseline gap-2">
                <p id="totalSiswaText" class="text-3xl font-extrabold">
                    {{ number_format($totalSiswa ?? 0, 0, ',', '.') }}
                </p>
                <span class="text-xs text-gray-500">siswa</span>
            </div>
            <div class="text-xs text-gray-500 mt-2">
                Data dari manajemen siswa.
            </div>
        </div>

        {{-- Total Nominal Lunas (sesuai filter tanggal) --}}
        <div class="bg-white p-5 rounded-2xl shadow border text-center">
            <h3 class="font-semibold mb-1">Total Nominal Lunas</h3>
            <p class="text-xs text-gray-500 mb-2">
                {{ optional($startDate)->format('d M Y') }} — {{ optional($endDate)->format('d M Y') }}
            </p>
            <p class="text-2xl font-extrabold">
                Rp {{ number_format($nominalLunas ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
                {{ number_format($nominalLunasCount ?? 0, 0, ',', '.') }} transaksi
            </p>
        </div>

        {{-- Siswa unik yang bayar (periode filter) --}}
        <div class="bg-white p-5 rounded-2xl shadow border text-center">
            <h3 class="font-semibold mb-1">Siswa Unik Membayar</h3>
            <p class="text-xs text-gray-500 mb-2">
                Periode yang sama
            </p>
            <p class="text-2xl font-extrabold">
                {{ number_format($uniqueStudentsPaidCount ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">siswa</p>
        </div>

        {{-- Jumlah Kategori Pembayaran --}}
        <div class="bg-white p-5 rounded-2xl shadow border text-center">
            <h3 class="font-semibold mb-1">Kategori Pembayaran</h3>
            <p class="text-2xl font-extrabold">
                {{ number_format($kategoriPembayaranCount ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-1">kategori aktif</p>
        </div>
    </div>

    {{-- Chart Bulanan (Wave) --}}
    <div class="bg-white p-5 rounded-2xl shadow border">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Total Pembayaran per Bulan — {{ $year }}</h3>
            <span class="text-xs text-gray-500">
                Bulan dengan transaksi: {{ number_format($chartMonthsWithTxnCount ?? 0) }}
            </span>
        </div>
        <div style="height:320px">
            <canvas id="chartMonthly"></canvas>
        </div>
    </div>

    {{-- Chart Harian (berdasarkan filter tanggal) --}}
    <div class="bg-white p-5 rounded-2xl shadow border">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Volume Transaksi Harian</h3>
            <span class="text-xs text-gray-500">
                Hari pada rentang: {{ number_format($chartDaysCount ?? 0) }}
            </span>
        </div>
        <div style="height:320px">
            <canvas id="chartDaily"></canvas>
        </div>
    </div>

    {{-- Recent Usage (5 terakhir) --}}
    <div class="bg-white p-5 rounded-2xl shadow border">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Recent Usage (Transaksi Terakhir)</h3>
            <div class="text-xs text-gray-500">
                Total transaksi lunas: {{ number_format($recentUsageTotalCount ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="py-2 px-3">#</th>
                        <th class="py-2 px-3">Nama (Kelas)</th>
                        <th class="py-2 px-3">Pembayaran</th>
                        <th class="py-2 px-3">Jumlah</th>
                        <th class="py-2 px-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsage as $i => $r)
                        <tr class="border-t">
                            <td class="py-2 px-3">{{ $i + 1 }}</td>
                            <td class="py-2 px-3">
                                {{ $r->nama_siswa ?? '-' }}
                                <span class="text-xs text-gray-500">({{ $r->kelas ?? '-' }})</span>
                            </td>
                            <td class="py-2 px-3">{{ $r->nama_pembayaran ?? '-' }}</td>
                            <td class="py-2 px-3">Rp {{ number_format((int)($r->jumlah ?? 0), 0, ',', '.') }}</td>
                            <td class="py-2 px-3">{{ \Carbon\Carbon::parse($r->tanggal_pembayaran)->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-3 px-3 text-gray-500" colspan="5">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(($recentUsageTotalCount ?? 0) > ($recentUsage->count() ?? 0))
            <div class="mt-4">
                {{-- Arahkan ke halaman laporan lengkap --}}
                <a href="{{ route('admin.laporan.index') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded px-4 py-2">
                    Lihat Semua Transaksi
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
/**
 * ======================
 *  Data dari Controller
 * ======================
 */
const kelasMap = @json($kelasCounts ?? []);
const totalSiswaAll = {{ (int) ($totalSiswa ?? 0) }};

// Dropdown "Total Siswa" live update
document.getElementById('filterKelas')?.addEventListener('change', function() {
    const k = this.value;
    const el = document.getElementById('totalSiswaText');
    if (!el) return;
    if (!k) {
        el.textContent = new Intl.NumberFormat('id-ID').format(totalSiswaAll);
    } else {
        const n = parseInt(kelasMap[k] || 0, 10);
        el.textContent = new Intl.NumberFormat('id-ID').format(n);
    }
});

/**
 * ======================
 *  Chart.js (Area/Wave)
 * ======================
 * Catatan: pastikan Chart.js sudah dimuat di layout.
 */

// Monthly (gelombang)
const monthlyLabels = @json($months ?? []);
const monthlyValues = @json($totals ?? []);

new Chart(document.getElementById('chartMonthly').getContext('2d'), {
    type: 'line',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'Total Pembayaran (Rp)',
            data: monthlyValues,
            borderWidth: 3,
            fill: true,
            tension: 0.35,    // wave
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + Number(v).toLocaleString('id-ID')
                },
                grid: { drawBorder: false }
            },
            x: {
                grid: { display: false }
            }
        },
        plugins: {
            legend: { display: true },
            tooltip: {
                callbacks: {
                    label: c => 'Rp ' + Number(c.parsed.y).toLocaleString('id-ID')
                }
            }
        }
    }
});

// Daily (sesuai filter tanggal)
const dailyLabels = @json($chartLabels ?? []);
const dailyValues = @json($chartValues ?? []);

new Chart(document.getElementById('chartDaily').getContext('2d'), {
    type: 'line',
    data: {
        labels: dailyLabels,
        datasets: [{
            label: 'Total Harian (Rp)',
            data: dailyValues,
            borderWidth: 3,
            fill: true,
            tension: 0.35,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: v => 'Rp ' + Number(v).toLocaleString('id-ID')
                },
                grid: { drawBorder: false }
            },
            x: {
                grid: { display: false }
            }
        },
        plugins: {
            legend: { display: true },
            tooltip: {
                callbacks: {
                    label: c => 'Rp ' + Number(c.parsed.y).toLocaleString('id-ID')
                }
            }
        }
    }
});
</script>
@endpush
