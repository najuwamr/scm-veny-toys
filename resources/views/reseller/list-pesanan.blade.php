@extends('layouts.reseller')

@section('title', 'Pesanan Saya')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Pesanan Saya</h1>
            <p class="text-sm text-slate-500">Pantau status pesanan, lihat invoice, dan cek apakah pembayaran Anda sudah tercatat.</p>
        </div>
        <a href="{{ route('reseller.pesanan.create') }}" class="inline-flex items-center justify-center rounded-full bg-pink-700 px-4 py-3 text-sm font-semibold text-white hover:bg-pink-800">
            Buat Pesanan Baru
        </a>
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
                    <th class="px-6 py-4">No Pesanan</th>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Pembayaran</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pesanans as $pesanan)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $pesanan->no_pesanan }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ \Carbon\Carbon::parse($pesanan->tgl_pesanan)->translatedFormat('d M Y') }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                                    'diproses' => 'bg-cyan-100 text-cyan-800',
                                    'dikirim' => 'bg-sky-100 text-sky-800',
                                    'selesai' => 'bg-emerald-100 text-emerald-800',
                                    'dibatalkan' => 'bg-rose-100 text-rose-800',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$pesanan->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $pesanan->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            @if($pesanan->invoice)
                                @php
                                    $paymentClasses = [
                                        'belum_bayar' => 'bg-yellow-100 text-yellow-800',
                                        'sebagian' => 'bg-orange-100 text-orange-800',
                                        'lunas' => 'bg-emerald-100 text-emerald-800',
                                    ];
                                @endphp
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentClasses[$pesanan->invoice->status_pembayaran] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst(str_replace('_', ' ', $pesanan->invoice->status_pembayaran)) }}
                                </span>
                            @else
                                <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">Belum Ada Invoice</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('reseller.pesanan.detail', $pesanan->id) }}" class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada pesanan. Buat pesanan sekarang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
