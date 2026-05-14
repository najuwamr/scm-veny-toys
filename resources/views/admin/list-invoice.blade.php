@extends('layouts.admin')

@section('title', 'Daftar Invoice')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Daftar Invoice</h1>
            <p class="text-sm text-slate-500">Lihat semua invoice masuk dan kelola status pembayaran di sini.</p>
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
                    <th class="px-6 py-4">No Invoice</th>
                    <th class="px-6 py-4">No Pesanan</th>
                    <th class="px-6 py-4">Reseller</th>
                    <th class="px-6 py-4">Tagihan</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Tanggal Bayar</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $invoice->no_invoice }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($invoice->pesanan)->no_pesanan ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional(optional($invoice->pesanan)->reseller)->nama_toko ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format($invoice->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClasses = [
                                    'belum_bayar' => 'bg-yellow-100 text-yellow-800',
                                    'sebagian' => 'bg-orange-100 text-orange-800',
                                    'lunas' => 'bg-emerald-100 text-emerald-800',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClasses[$invoice->status_pembayaran] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $invoice->status_pembayaran)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $invoice->tgl_bayar?->translatedFormat('d M Y') ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.invoice.detail', $invoice->id) }}" class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-slate-500">Belum ada invoice masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
