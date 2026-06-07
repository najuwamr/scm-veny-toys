@extends('layouts.produsen')

@section('title', 'Permintaan Pengadaan')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Permintaan Pengadaan</h1>
            <p class="text-sm text-slate-500">Kelola kebutuhan bahan baku dan pantau status pengadaannya.</p>
        </div>
        <a href="{{ route('produsen.procurement.create') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Buat Permintaan Baru
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
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Bahan Baku</th>
                    <th class="px-6 py-4">Jumlah</th>
                    <th class="px-6 py-4">Supplier</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($permintaanPengadaans as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $item->bahanBakuSupplier->bahanBaku->nama ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($item->jumlah, 2, ',', '.') }} {{ $item->bahanBakuSupplier->bahanBaku->satuan ?? '' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->bahanBakuSupplier->supplier->nama ?? 'Belum dipilih' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($item->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $item->catatan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada permintaan pengadaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
