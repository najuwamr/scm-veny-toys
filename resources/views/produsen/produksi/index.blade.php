@extends('layouts.produsen')

@section('title', 'Produksi')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Produksi</h1>
            <p class="text-sm text-slate-500">Kelola jadwal produksi, pantau proses, dan lihat hasil produksi.</p>
        </div>
        <a href="{{ route('produsen.produksi.create') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Tambah Produksi
        </a>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full border-separate border-spacing-0 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Produk</th>
                    <th class="px-6 py-4">Jumlah</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($produks as $produksi)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $produksi->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $produksi->nama ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($produksi->stok_saat_ini, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $produksi->stok_saat_ini <= $produksi->stok_minimum ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $produksi->stok_saat_ini <= $produksi->stok_minimum ? 'Perlu produksi' : 'Stabil' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $produksi->ukuran ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada data produk untuk produksi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
