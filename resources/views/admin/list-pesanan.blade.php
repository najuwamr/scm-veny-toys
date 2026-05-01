@extends('layouts.admin')

@section('title', 'List Pesanan')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">List Pesanan</h1>
            <p class="text-sm text-slate-500">Lihat semua pesanan masuk dan kelola statusnya dari admin.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-3xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full border-separate border-spacing-0 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">No Pesanan</th>
                    <th class="px-6 py-4">Reseller</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pesanans as $pesanan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $pesanan->no_pesanan }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($pesanan->reseller)->nama_toko ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($pesanan->tgl_pesanan)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClasses = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                                    'diproses' => 'bg-blue-100 text-blue-800',
                                    'dikirim' => 'bg-indigo-100 text-indigo-800',
                                    'selesai' => 'bg-emerald-100 text-emerald-800',
                                    'dibatalkan' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$pesanan->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst($pesanan->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.pesanan.detail', $pesanan->id) }}" class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                    Detail
                                </a>

                                @if($pesanan->status === 'menunggu')
                                    <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="action" value="accept">
                                        <button type="submit" class="inline-flex items-center rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">
                                            Terima Permintaan
                                        </button>
                                    </form>
                                @elseif($pesanan->status === 'diproses')
                                    @if(!$pesanan->invoice)
                                        <a href="{{ route('admin.invoice.create', $pesanan->id) }}" class="inline-flex items-center rounded-full bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700">
                                            Buat Invoice
                                        </a>
                                    @else
                                        <a href="{{ route('admin.invoice.detail', $pesanan->invoice->id) }}" class="inline-flex items-center rounded-full bg-slate-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                            Lihat Invoice
                                        </a>

                                        @if($pesanan->invoice->status_pembayaran === 'lunas')
                                            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="inline-flex items-center rounded-full bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-700">
                                                    Setujui Pembayaran
                                                </button>
                                            </form>
                                        @else
                                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1.5 text-xs font-semibold text-yellow-800">
                                                Invoice belum lunas
                                            </span>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-xs text-slate-500">Tidak ada aksi</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">Belum ada pesanan masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
