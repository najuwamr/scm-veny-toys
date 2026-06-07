@extends('layouts.produsen')

@section('title', 'Daftar Bahan Baku')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Daftar Bahan Baku</h1>
            <p class="text-sm text-slate-500">Kelola stok bahan baku dan pantau item yang mencapai batas minimum.</p>
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
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Satuan</th>
                    <th class="px-6 py-4">Stok Saat Ini</th>
                    <th class="px-6 py-4">Stok Minimum</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bahanBakus as $bahan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $bahan->nama }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ ucfirst($bahan->kategori) }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $bahan->satuan }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($bahan->stok_saat_ini, 2, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($bahan->stok_minimum, 2, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @if($bahan->stok_saat_ini <= $bahan->stok_minimum)
                                <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Stok Rendah</span>
                            @else
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada data bahan baku.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($lowStocks->isNotEmpty())
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Daftar Stok Minimum</h2>
            <div class="grid gap-3">
                @foreach($lowStocks as $item)
                    <div class="rounded-3xl border border-red-100 bg-red-50 p-4">
                        <p class="font-semibold text-slate-900">{{ $item->nama }}</p>
                        <p class="text-sm text-slate-600">Stok: {{ number_format($item->stok_saat_ini, 2, ',', '.') }} {{ $item->satuan }} | Minimum: {{ number_format($item->stok_minimum, 2, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
