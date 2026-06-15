@extends('layouts.supplier')

@section('title', 'Dashboard Supplier')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Dashboard Supplier</h1>
            <p class="text-sm text-slate-500">Pantau bahan baku, harga, dan permintaan pengadaan dari produsen.</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Total Bahan</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $materialCount }}</p>
            <p class="text-sm text-slate-400">Jumlah bahan baku dengan harga supplier.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Permintaan Menunggu</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $requestsPending }}</p>
            <p class="text-sm text-slate-400">Permintaan perlu ditindaklanjuti.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Permintaan Disetujui</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">{{ $requestsApproved }}</p>
            <p class="text-sm text-slate-400">Permintaan yang sudah disetujui.</p>
        </div>
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Nilai Outstanding</p>
            <p class="mt-3 text-3xl font-bold text-slate-900">Rp {{ number_format($outstandingValue, 0, ',', '.') }}</p>
            <p class="text-sm text-slate-400">Estimasi nilai permintaan yang belum selesai.</p>
        </div>
    </div>
</div>
@endsection
