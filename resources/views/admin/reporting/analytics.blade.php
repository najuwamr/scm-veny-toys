@extends('layouts.admin')

@section('title', 'Analisis Data')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Analisis Data</h1>
            <p class="text-sm text-slate-500">Analisis pendapatan dan penjualan berdasarkan rentang waktu yang ditentukan.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.reporting.dashboard') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Kembali ke Dashboard Laporan</a>
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm">
        <form action="{{ route('admin.reporting.analytics') }}" method="GET" class="grid gap-4 sm:grid-cols-3">
            <div>
                <label class="text-sm font-semibold text-slate-700">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $start->format('Y-m-d') }}" class="mt-2 w-full rounded-2xl border px-3 py-2" />
            </div>
            <div>
                <label class="text-sm font-semibold text-slate-700">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $end->format('Y-m-d') }}" class="mt-2 w-full rounded-2xl border px-3 py-2" />
            </div>
            <div class="flex items-end">
                <button class="w-full rounded-full bg-pink-700 px-4 py-3 text-sm font-semibold text-white hover:bg-pink-800">Tampilkan</button>
            </div>
        </form>
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Pendapatan</p>
            <h2 class="mt-3 text-3xl font-semibold text-slate-900">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</h2>
            <p class="mt-2 text-sm text-slate-500">Periode: {{ $start->translatedFormat('d M Y') }} - {{ $end->translatedFormat('d M Y') }}</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Pesanan</p>
            <h2 class="mt-3 text-3xl font-semibold text-slate-900">{{ $summary['orders'] }}</h2>
            <p class="mt-2 text-sm text-slate-500">Jumlah pesanan pada rentang waktu tersebut.</p>
        </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Detail Pendapatan Harian</h2>
        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($revenueByDate as $row)
                        <tr>
                            <td class="px-5 py-3">{{ \Carbon\Carbon::parse($row->date)->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3">Rp {{ number_format($row->revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-6 text-center text-slate-500" colspan="2">Tidak ada data pendapatan untuk rentang waktu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Detail Pesanan Harian</h2>
        <div class="mt-4 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Total Pesanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ordersByDate as $row)
                        <tr>
                            <td class="px-5 py-3">{{ \Carbon\Carbon::parse($row->date)->translatedFormat('d M Y') }}</td>
                            <td class="px-5 py-3">{{ $row->orders }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-5 py-6 text-center text-slate-500" colspan="2">Tidak ada data pesanan untuk rentang waktu ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
