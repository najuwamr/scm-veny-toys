@extends('layouts.reseller')

@section('title', 'Pembayaran Saya')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Pembayaran Saya</h1>
            <p class="text-sm text-slate-500">Lihat invoice yang diterbitkan untuk pesanan Anda dan ketahui sampai kapan tagihan berlaku.</p>
        </div>
        <a href="{{ route('reseller.pesanan.list') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke Pesanan Saya
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

    <div class="mb-6 rounded-3xl bg-white p-6 shadow-sm">
        <p class="text-sm text-slate-500">Semua konfirmasi pembayaran dilakukan di luar sistem. Pastikan bukti transfer atau pembayaran disampaikan langsung ke tim admin.</p>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full border-separate border-spacing-0 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th class="px-6 py-4">No Invoice</th>
                    <th class="px-6 py-4">No Pesanan</th>
                    <th class="px-6 py-4">Tagihan</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Berlaku Sampai</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($invoices as $invoice)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $invoice->no_invoice }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($invoice->pesanan)->no_pesanan ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format($invoice->jumlah_tagihan, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $paymentClasses = [
                                    'belum_bayar' => 'bg-yellow-100 text-yellow-800',
                                    'sebagian' => 'bg-orange-100 text-orange-800',
                                    'lunas' => 'bg-emerald-100 text-emerald-800',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentClasses[$invoice->status_pembayaran] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $invoice->status_pembayaran)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ optional($invoice->created_at)->addDays(7)->translatedFormat('d M Y') ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('reseller.payment.detail', $invoice->id) }}" class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-800">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada invoice untuk pesanan Anda.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
