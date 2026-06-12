@extends('layouts.admin')
@section('title', 'Buat Permintaan')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8">
        <a href="{{ route('procurement.index') }}" class="mb-2 inline-flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600">← Kembali</a>
        <h1 class="text-2xl font-bold text-gray-800">Buat Permintaan Bahan</h1>
        <p class="text-sm text-gray-400">Buat permintaan pengadaan bahan baku ke supplier</p>
    </div>

    <div class="max-w-2xl">
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-pink-100">
            <form action="{{ route('procurement.store') }}" method="POST" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">Bahan Baku <span class="text-red-500">*</span></label>
                    <select name="bahan_baku_id" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-pink-400 focus:outline-none">
                        <option value="">-- Pilih Bahan Baku --</option>
                        @foreach($bahanBaku as $bahan)
                        <option value="{{ $bahan->id }}" {{ old('bahan_baku_id') == $bahan->id ? 'selected' : '' }}>
                            {{ $bahan->nama_bahan }} — stok: {{ $bahan->stok_saat_ini }} {{ $bahan->satuan }}
                            @if($bahan->isStokKritis()) ⚠ KRITIS @endif
                        </option>
                        @endforeach
                    </select>
                    @error('bahan_baku_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">Supplier <span class="text-red-500">*</span></label>
                    <select name="supplier_id" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-pink-400 focus:outline-none">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->user?->name ?? $supplier->nama_perusahaan }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">Jumlah Diminta <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_diminta" min="1" required value="{{ old('jumlah_diminta') }}"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-pink-400 focus:outline-none" placeholder="Masukkan jumlah yang dibutuhkan">
                    @error('jumlah_diminta')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-gray-700">Catatan untuk Supplier</label>
                    <textarea name="catatan_admin" rows="3"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-pink-400 focus:outline-none"
                        placeholder="Spesifikasi, urgensi, atau catatan lain...">{{ old('catatan_admin') }}</textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <a href="{{ route('procurement.index') }}" class="flex-1 rounded-xl border border-gray-200 py-3 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="flex-1 rounded-xl bg-pink-700 py-3 text-sm font-semibold text-white hover:bg-pink-800">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>

</main>
@endsection
