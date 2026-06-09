@extends('layouts.admin')

@section('title', 'Distribusi')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Distribusi</h1>
            <p class="text-sm text-slate-500">Kelola penjadwalan dan tracking pengiriman produk.</p>
        </div>
        <div>
            <a href="{{ route('admin.distribution.metode') }}" class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">Manajemen Metode</a>
        </div>
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
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">No Pesanan</th>
                    <th class="px-6 py-4">Reseller</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Tanggal Dijadwalkan</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($distribusis as $d)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ optional($d->pesanan)->no_pesanan ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($d->pesanan->reseller)->nama_toko ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($d->metodePengiriman)->nama ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ ucfirst($d->status) }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($d->tgl_dijadwalkan) ? \Carbon\Carbon::parse($d->tgl_dijadwalkan)->translatedFormat('d M Y H:i') : '-' }}</td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.distribution.tracking', $d->id) }}" class="inline-flex items-center rounded-full bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white">Tracking</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="p-6" colspan="7">Belum ada data distribusi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
