@extends('layouts.admin')
@section('title', 'Tambah Bahan Baku')

@section('content')
<main class="ml-60 flex-1 p-8">
    <div class="mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Bahan Baku</h1>
            <p class="text-sm text-gray-400">Masukkan data bahan baku baru untuk stok produksi.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100 p-6">
        <form action="{{ route('inventory.bahan.store') }}" method="POST" class="grid gap-4 md:grid-cols-2">
            @csrf

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Kode Bahan</label>
                <input type="text" name="kode_bahan" value="{{ old('kode_bahan') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="BB-001">
                @error('kode_bahan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Bahan</label>
                <input type="text" name="nama_bahan" value="{{ old('nama_bahan') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="Kain Rasfur">
                @error('nama_bahan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Kategori</label>
                <select name="kategori" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="kain" {{ old('kategori') == 'kain' ? 'selected' : '' }}>Kain</option>
                    <option value="isi boneka" {{ old('kategori') == 'isi boneka' ? 'selected' : '' }}>Isi Boneka</option>
                    <option value="aksesoris" {{ old('kategori') == 'aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                </select>
                @error('kategori')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Satuan</label>
                <select name="satuan" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                    <option value="">-- Pilih Satuan --</option>
                    <option value="meter" {{ old('satuan') == 'meter' ? 'selected' : '' }}>Meter</option>
                    <option value="kg" {{ old('satuan') == 'kg' ? 'selected' : '' }}>Kg</option>
                    <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>Pcs</option>
                </select>
                @error('satuan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Stok Saat Ini</label>
                <input type="number" step="0.01" name="stok_saat_ini" value="{{ old('stok_saat_ini', 0) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                @error('stok_saat_ini')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Stok Minimum</label>
                <input type="number" step="0.01" name="stok_minimum" value="{{ old('stok_minimum', 0) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                @error('stok_minimum')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-gray-700">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">{{ old('keterangan') }}</textarea>
                @error('keterangan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                <a href="{{ route('inventory.bahan') }}" class="rounded-xl border border-gray-200 py-2.5 px-6 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-xl bg-pink-700 py-2.5 px-6 text-sm font-semibold text-white hover:bg-pink-800">Simpan</button>
            </div>
        </form>
    </div>
</main>
@endsection
