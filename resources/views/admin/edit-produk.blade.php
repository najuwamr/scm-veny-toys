@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<main class="ml-60 flex-1 p-8">
    <div class="mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Produk</h1>
            <p class="text-sm text-gray-400">Perbarui informasi produk yang sudah ada.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100 p-6">
        <form action="{{ route('inventory.produk.update', $produk) }}" method="POST" class="grid gap-4 md:grid-cols-2">
            @csrf
            @method('PUT')
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama', $produk->nama) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="Boneka Panda">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Ukuran</label>
                <select name="ukuran" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                    <option value="">-- Pilih Ukuran --</option>
                    <option value="S" {{ old('ukuran', $produk->ukuran) == 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ old('ukuran', $produk->ukuran) == 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ old('ukuran', $produk->ukuran) == 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ old('ukuran', $produk->ukuran) == 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="Jumbo" {{ old('ukuran', $produk->ukuran) == 'Jumbo' ? 'selected' : '' }}>Jumbo</option>
                </select>
                @error('ukuran')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Harga</label>
                <input type="number" name="harga" value="{{ old('harga', $produk->harga) }}" step="0.01" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="100000">
                @error('harga')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Stok Saat Ini</label>
                <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', $produk->stok_saat_ini) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                @error('stok_saat_ini')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Stok Minimum</label>
                <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $produk->stok_minimum) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                @error('stok_minimum')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                <a href="{{ route('inventory.produk') }}" class="rounded-xl border border-gray-200 py-2.5 px-6 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-xl bg-pink-700 py-2.5 px-6 text-sm font-semibold text-white hover:bg-pink-800">Simpan</button>
            </div>
        </form>
    </div>
</main>
@endsection
