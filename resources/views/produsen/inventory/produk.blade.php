@extends('layouts.produsen')

@section('title', 'Daftar Produk')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Daftar Produk</h1>
            <p class="text-sm text-slate-500">Pantau stok produk jadi dan lihat barang yang perlu segera diproduksi ulang.</p>
        </div>
        <a href="{{ route('produsen.inventory.mutasi.create') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Input Mutasi Stok
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-3xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full border-separate border-spacing-0 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th class="px-6 py-4">Nama Produk</th>
                    <th class="px-6 py-4">Ukuran</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Stok Saat Ini</th>
                    <th class="px-6 py-4">Stok Minimum</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($produks as $produk)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $produk->nama }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $produk->ukuran }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $produk->stok_saat_ini }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $produk->stok_minimum }}</td>
                        <td class="px-6 py-4">
                            @if($produk->stok_saat_ini <= $produk->stok_minimum)
                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Stok Rendah</span>
                            @else
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($lowStocks->isNotEmpty())
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Produk dengan Stok Minimum</h2>
            <div class="grid gap-3">
                @foreach($lowStocks as $item)
                    <div class="rounded-3xl border border-red-100 bg-red-50 p-4">
                        <p class="font-semibold text-slate-900">{{ $item->nama }}</p>
                        <p class="text-sm text-slate-600">Stok: {{ $item->stok_saat_ini }} | Minimum: {{ $item->stok_minimum }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
