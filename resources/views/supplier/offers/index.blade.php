@extends('layouts.supplier')

@section('title', 'Daftar Bahan & Harga')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Daftar Bahan & Harga</h1>
            <p class="text-sm text-slate-500">Kelola bahan baku yang Anda suplai beserta harganya.</p>
        </div>
        <a href="{{ route('supplier.materials.create') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Tambah Bahan
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
                    <th class="px-6 py-4">Bahan Baku</th>
                    <th class="px-6 py-4">Satuan</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Dibuat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($materials as $material)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $material->bahanBaku->nama_bahan ?? 'Tidak diketahui' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $material->bahanBaku->satuan ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format($material->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $material->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada data bahan baku dan harga.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
