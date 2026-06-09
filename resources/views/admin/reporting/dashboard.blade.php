@extends('layouts.admin')

@section('title', 'Dashboard Laporan')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard Laporan</h1>
            <p class="text-sm text-slate-500">Monitoring stok, produksi, distribusi, dan penjualan untuk pengambilan keputusan.</p>
        </div>
        <a href="{{ route('admin.reporting.analytics') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Analisis Data Pendapatan & Penjualan
        </a>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Produk</p>
            <h2 class="mt-3 text-3xl font-semibold text-slate-900">{{ $stockSummary['totalProduk'] }}</h2>
            <p class="mt-2 text-sm text-slate-500">Total stok saat ini {{ number_format($stockSummary['totalStok']) }} unit.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Stok Menipis</p>
            <h2 class="mt-3 text-3xl font-semibold text-slate-900">{{ $stockSummary['lowStock'] }}</h2>
            <p class="mt-2 text-sm text-slate-500">Produk dengan stok kurang dari 20 unit.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Forecast Produksi</p>
            <h2 class="mt-3 text-3xl font-semibold text-slate-900">{{ $productionSummary['totalForecast'] }}</h2>
            <p class="mt-2 text-sm text-slate-500">Total prediksi produksi {{ number_format($productionSummary['forecastQty']) }} unit.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Pendapatan Lunas</p>
            <h2 class="mt-3 text-3xl font-semibold text-slate-900">Rp {{ number_format($salesSummary['paidRevenue'], 0, ',', '.') }}</h2>
            <p class="mt-2 text-sm text-slate-500">Total pendapatan dari invoice lunas.</p>
        </div>
    </div>

    <div class="mt-6 grid gap-4 xl:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Distribusi</h2>
            <p class="text-sm text-slate-500">Status pengiriman produk saat ini.</p>
            <div class="mt-4 space-y-3">
                @foreach(['dijadwalkan','dikirim','dalam_perjalanan','diterima'] as $status)
                    <div class="grid grid-cols-[1fr_auto] items-center gap-3">
                        <div>
                            <div class="mb-1 text-sm font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $status)) }}</div>
                            <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-pink-600" style="width: {{ isset($distributionSummary[$status]) ? min($distributionSummary[$status] * 15, 100) : 5 }}%"></div>
                            </div>
                        </div>
                        <div class="text-sm font-semibold text-slate-900">{{ $distributionSummary[$status] ?? 0 }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Penjualan</h2>
            <p class="text-sm text-slate-500">Jumlah pesanan dan invoice.</p>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Pesanan</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $salesSummary['orders'] }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Invoice</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $salesSummary['invoices'] }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Invoice Lunas</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $salesSummary['paidInvoices'] }}</p>
                </div>
                <div class="rounded-3xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Pendapatan</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">Rp {{ number_format($salesSummary['paidRevenue'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Stok Produk Teratas</h2>
        <p class="text-sm text-slate-500">Produk dengan jumlah stok terbesar.</p>
        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                    <tr>
                        <th class="px-5 py-3">No</th>
                        <th class="px-5 py-3">Produk</th>
                        <th class="px-5 py-3">Stok Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($stockSummary['topProducts'] as $product)
                        <tr>
                            <td class="px-5 py-3">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3 font-medium text-slate-900">{{ $product->nama }}</td>
                            <td class="px-5 py-3">{{ $product->stok_saat_ini }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
