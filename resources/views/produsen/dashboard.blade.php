@extends('layouts.produsen')

@section('title', 'Dashboard Produsen')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard Produsen</h1>
            <p class="text-sm text-slate-500">Ikhtisar operasional produksi, inventori, dan permintaan bahan baku.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-3xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Stok Bahan Baku</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($stokBahanTotal, 2, ',', '.') }}</p>
            <p class="text-sm text-slate-400">Total semua bahan baku</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Stok Produk Jadi</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($stokProdukTotal, 0, ',', '.') }}</p>
            <p class="text-sm text-slate-400">Total semua produk jadi</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Notifikasi Stok Minimum</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $lowBahanBakus->count() + $lowProduks->count() }}</p>
            <p class="text-sm text-slate-400">Item bahan baku & produk di bawah minimum</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Produksi Hari Ini</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($produksiHariIni, 0, ',', '.') }}</p>
            <p class="text-sm text-slate-400">Jumlah produk masuk hari ini</p>
        </div>
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Notifikasi Stok Minimum</h2>
            @if($lowBahanBakus->isEmpty() && $lowProduks->isEmpty())
                <p class="text-sm text-slate-600">Semua stok bahan baku dan produk dalam kondisi aman.</p>
            @else
                <div class="space-y-3">
                    @foreach($lowBahanBakus as $item)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-800">{{ $item->nama }} (Bahan Baku)</p>
                            <p class="text-sm text-slate-500">Stok: {{ number_format($item->stok_saat_ini, 2, ',', '.') }} {{ $item->satuan }}, minimal {{ number_format($item->stok_minimum, 2, ',', '.') }}</p>
                        </div>
                    @endforeach
                    @foreach($lowProduks as $item)
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="font-semibold text-slate-800">{{ $item->nama }} (Produk)</p>
                            <p class="text-sm text-slate-500">Stok: {{ $item->stok_saat_ini }}, minimal {{ $item->stok_minimum }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Ringkasan Produksi & Permintaan</h2>
            <div class="grid gap-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Produksi hari ini</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($produksiHariIni, 0, ',', '.') }}</p>
                    <p class="text-sm text-slate-500">Produk jadi masuk</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Pemakaian bahan baku hari ini</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($pemakaianBahanHariIni, 2, ',', '.') }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Permintaan menunggu</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $permintaanSummary['menunggu'] ?? 0 }}</p>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Permintaan selesai</p>
                    <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $permintaanSummary['selesai'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
