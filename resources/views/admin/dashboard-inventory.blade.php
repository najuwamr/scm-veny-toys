@extends('layouts.admin')
@section('title', 'Dashboard Inventory')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Inventory</h1>
        <p class="text-sm text-gray-400">Pemantauan stok bahan baku secara real-time</p>
    </div>

    {{-- Alert stok kritis --}}
    @if($stokKritis->count() > 0)
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 p-4">
        <svg class="mt-0.5 shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2ZM12 7C12.5523 7 13 7.44772 13 8V12C13 12.5523 12.5523 13 12 13C11.4477 13 11 12.5523 11 12V8C11 7.44772 11.4477 7 12 7ZM12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z" fill="#ef4444"/>
        </svg>
        <div>
            <p class="font-semibold text-red-700">{{ $stokKritis->count() }} bahan baku stok kritis!</p>
            <p class="text-sm text-red-500">{{ $stokKritis->pluck('nama_bahan')->join(', ') }}</p>
        </div>
        <a href="{{ route('admin.inventory.notifikasi') }}" class="ml-auto shrink-0 rounded-lg bg-red-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-red-600">Lihat</a>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="mb-8 grid grid-cols-4 gap-4">
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-pink-100">
            <p class="text-sm text-gray-400">Total Bahan Baku</p>
            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $totalBahan }}</p>
            <p class="mt-1 text-xs text-gray-300">jenis bahan</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-pink-100">
            <p class="text-sm text-gray-400">Total Produk</p>
            <p class="mt-1 text-3xl font-bold text-gray-800">{{ $totalProduk }}</p>
            <p class="mt-1 text-xs text-gray-300">jenis produk</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-pink-100">
            <p class="text-sm text-gray-400">Masuk Hari Ini</p>
            <p class="mt-1 text-3xl font-bold text-green-600">+{{ $totalMasukHari }}</p>
            <p class="mt-1 text-xs text-gray-300">unit masuk</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-pink-100">
            <p class="text-sm text-gray-400">Keluar Hari Ini</p>
            <p class="mt-1 text-3xl font-bold text-orange-500">-{{ $totalKeluarHari }}</p>
            <p class="mt-1 text-xs text-gray-300">unit keluar</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- Tabel semua bahan baku --}}
        <div class="col-span-2 rounded-2xl bg-white p-6 shadow-sm border border-pink-100">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold text-gray-800">Stok Bahan Baku</h2>
                <a href="{{ route('admin.inventory.bahan') }}" class="text-sm font-semibold text-pink-600 hover:text-pink-800">Kelola →</a>
            </div>
            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Bahan</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Stok</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Min</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($semuaBahan as $bahan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $bahan->nama_bahan }}</p>
                                <p class="text-xs text-gray-400">{{ $bahan->kode_bahan }}</p>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                {{ number_format($bahan->stok_saat_ini) }} <span class="text-xs text-gray-400">{{ $bahan->satuan }}</span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-400">{{ number_format($bahan->stok_minimum) }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($bahan->isStokKritis())
                                    <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-600">Kritis</span>
                                @else
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-600">Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada data bahan baku</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-span-2 rounded-2xl bg-white p-6 shadow-sm border border-pink-100">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold text-gray-800">Stok Produk</h2>
                <a href="{{ route('admin.inventory.produk') }}" class="text-sm font-semibold text-pink-600 hover:text-pink-800">Kelola →</a>
            </div>
            <div class="overflow-hidden rounded-xl border border-gray-100">
                <table class="w-full text-sm">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-600">Produk</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Stok</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-600">Min</th>
                            <th class="px-4 py-3 text-center font-semibold text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($semuaProduk as $produk)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-800">{{ $produk->nama }}</td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-800">{{ $produk->stok_saat_ini }}</td>
                            <td class="px-4 py-3 text-right text-gray-400">{{ $produk->stok_minimum }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($produk->stok_saat_ini <= $produk->stok_minimum)
                                    <span class="rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-600">Kritis</span>
                                @else
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-600">Aman</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada produk</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick actions + stok kritis --}}
        <div class="flex flex-col gap-4">
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-pink-100">
                <h2 class="mb-4 font-bold text-gray-800">Aksi Cepat</h2>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('admin.inventory.produk') }}" class="flex items-center gap-3 rounded-xl bg-blue-50 px-4 py-3 text-sm font-semibold text-blue-700 hover:bg-blue-100">
                        📦 Manajemen Produk
                    </a>
                    <a href="{{ route('admin.inventory.bahan') }}" class="flex items-center gap-3 rounded-xl bg-green-50 px-4 py-3 text-sm font-semibold text-green-700 hover:bg-green-100">
                        🧱 Manajemen Bahan
                    </a>
                    <a href="{{ route('admin.inventory.masuk') }}" class="flex items-center gap-3 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 hover:bg-emerald-100">
                        ↑ Catat Stok Masuk
                    </a>
                    <a href="{{ route('admin.inventory.keluar') }}" class="flex items-center gap-3 rounded-xl bg-orange-50 px-4 py-3 text-sm font-semibold text-orange-700 hover:bg-orange-100">
                        ↓ Catat Stok Keluar
                    </a>
                    <a href="{{ route('admin.procurement.create') }}" class="flex items-center gap-3 rounded-xl bg-pink-50 px-4 py-3 text-sm font-semibold text-pink-700 hover:bg-pink-100">
                        + Buat Permintaan
                    </a>
                </div>
            </div>

            @if($stokKritis->count() > 0)
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-red-100">
                <h2 class="mb-4 font-bold text-red-600">⚠ Perlu Diperhatikan</h2>
                <div class="flex flex-col gap-2">
                    @foreach($stokKritis->take(4) as $bahan)
                    <div class="flex items-center justify-between rounded-lg bg-red-50 px-3 py-2">
                        <span class="text-sm font-semibold text-gray-700">{{ $bahan->nama_bahan }}</span>
                        <span class="text-sm font-bold text-red-600">{{ $bahan->stok_saat_ini }} {{ $bahan->satuan }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

</main>
@endsection
