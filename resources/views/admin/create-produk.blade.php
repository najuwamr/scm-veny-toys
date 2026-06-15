@extends('layouts.admin')
@section('title', 'Tambah Produk')

@section('content')
<main class="ml-60 flex-1 p-8">
    <div class="mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Produk</h1>
            <p class="text-sm text-gray-400">Tambahkan produk baru ke dalam sistem.</p>
        </div>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100 p-6">
        <form action="{{ route('admin.inventory.produk.store') }}" method="POST" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div class="md:col-span-2">
                <label class="mb-1 block text-sm font-semibold text-gray-700">Nama Produk</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="Boneka Panda">
                @error('nama')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Ukuran</label>
                <select name="ukuran" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                    <option value="">-- Pilih Ukuran --</option>
                    <option value="S" {{ old('ukuran') == 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ old('ukuran') == 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ old('ukuran') == 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ old('ukuran') == 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="Jumbo" {{ old('ukuran') == 'Jumbo' ? 'selected' : '' }}>Jumbo</option>
                </select>
                @error('ukuran')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Harga</label>
                <input type="number" name="harga" value="{{ old('harga') }}" step="0.01" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="100000">
                @error('harga')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Stok Saat Ini</label>
                <input type="number" name="stok_saat_ini" value="{{ old('stok_saat_ini', 0) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                @error('stok_saat_ini')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Stok Minimum</label>
                <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 0) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                @error('stok_minimum')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="md:col-span-2 flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.inventory.produk') }}" class="rounded-xl border border-gray-200 py-2.5 px-6 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</a>
                <button type="submit" class="rounded-xl bg-pink-700 py-2.5 px-6 text-sm font-semibold text-white hover:bg-pink-800">Simpan</button>
            </div>
        </form>
    </div>
</main>
@endsection
