@extends('layouts.admin')

@section('title', 'Buat Rencana Produksi')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Buat Rencana Produksi</h1>
            <p class="text-sm text-slate-500">Rencanakan produksi atau catat realisasi produksi baru.</p>
        </div>
        <a href="{{ route('admin.procurement.produksi.index') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke Produksi
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Rencana Produksi</h2>
            <form action="{{ route('admin.procurement.produksi.store-rencana') }}" method="post" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Produk</label>
                    <select name="produk_id" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                        <option value="">Pilih produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>{{ $produk->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Bulan</label>
                        <input type="number" name="bulan" min="1" max="12" value="{{ old('bulan') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="1-12" />
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tahun</label>
                        <input type="number" name="tahun" min="2000" value="{{ old('tahun', now()->year) }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="2026" />
                    </div>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Estimasi Jumlah</label>
                    <input type="number" name="qty_prediksi" min="1" value="{{ old('qty_prediksi') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Jumlah prediksi" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Metode</label>
                    <input type="text" name="metode" value="{{ old('metode') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Metode peramalan" />
                </div>
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Simpan Rencana</button>
            </form>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Realisasi Produksi</h2>
            <form action="{{ route('admin.procurement.produksi.store-realisasi') }}" method="post" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Produk</label>
                    <select name="produk_id" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                        <option value="">Pilih produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>{{ $produk->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Jumlah Produksi</label>
                    <input type="number" name="qty_produksi" min="1" value="{{ old('qty_produksi') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Jumlah produksi" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Catatan</label>
                    <textarea name="catatan" rows="3" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Tidak wajib">{{ old('catatan') }}</textarea>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-900">Pemakaian Bahan Baku</p>
                    <p class="mt-2 text-sm text-slate-500">Tambahkan penggunaan bahan baku untuk realisasi produksi.</p>
                    <div class="mt-4 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Bahan Baku</label>
                                <select name="usage_bahan_baku[0][bahan_baku_id]" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                                    <option value="">Pilih bahan baku</option>
                                    @foreach($bahanBakus as $bahan)
                                        <option value="{{ $bahan->id }}">{{ $bahan->nama }} ({{ $bahan->satuan }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">Jumlah Penggunaan</label>
                                <input type="number" step="0.01" name="usage_bahan_baku[0][jumlah]" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Jumlah bahan baku" />
                            </div>
                        </div>
                    </div>
                </div>
                @if($errors->any())
                    <div class="rounded-3xl bg-rose-50 p-4 text-sm text-rose-700">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Catat Realisasi</button>
            </form>
        </div>
    </div>
</div>
@endsection
