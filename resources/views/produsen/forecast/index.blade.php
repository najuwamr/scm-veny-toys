@extends('layouts.produsen')

@section('title', 'Peramalan Produksi')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Peramalan Produksi</h1>
        <p class="text-sm text-slate-500">Analisis kebutuhan bahan baku dan prediksi permintaan produk.</p>
    </div>

    <div class="grid gap-4 xl:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Perkiraan Kebutuhan Bahan</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($estimatedBahan, 2, ',', '.') }}</p>
            <p class="text-sm text-slate-400">Total kebutuhan estimasi</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Permintaan Produk Bulan Ini</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ number_format($estimatedProduk, 0, ',', '.') }}</p>
            <p class="text-sm text-slate-400">Estimasi produk terjual</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Tingkat Pemenuhan</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $fulfillmentRate }}%</p>
            <p class="text-sm text-slate-400">Prediksi ketersediaan stok</p>
        </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Rangkuman Peramalan</h2>
        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-3xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Bahan Baku Berisiko Kurang</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $riskBahanCount }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Produk yang Perlu Diproduksi Ulang</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $riskProdukCount }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Kekurangan Bahan Diprediksi</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($predictedShortage, 2, ',', '.') }}</p>
            </div>
            <div class="rounded-3xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Rekomendasi Tindakan</p>
                <p class="mt-2 text-2xl font-semibold text-slate-900">{{ $recommendedAction }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
