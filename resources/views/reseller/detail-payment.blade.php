@extends('layouts.reseller')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Detail Pembayaran</h1>
            <p class="text-sm text-slate-500">Lihat invoice, batas masa berlaku, dan rincian pesanan Anda.</p>
        </div>
        <a href="{{ route('reseller.payment.list') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke Pembayaran
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Informasi Invoice</h2>
            <dl class="mt-4 space-y-4 text-sm text-slate-600">
                <div>
                    <dt class="font-semibold text-slate-800">Nomor Invoice</dt>
                    <dd>{{ $invoice->no_invoice }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Nomor Pesanan</dt>
                    <dd>{{ optional($invoice->pesanan)->no_pesanan ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Jumlah Tagihan</dt>
                    <dd>Rp {{ number_format($invoice->jumlah_tagihan, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Status Pembayaran</dt>
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
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Berlaku Sampai</dt>
                    <dd>{{ optional($invoice->created_at)->addDays(7)->translatedFormat('d M Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Tanggal Bayar</dt>
                    <dd>{{ optional($invoice->tgl_bayar)->translatedFormat('d M Y') ?? '-' }}</dd>
                </div>
            </dl>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Data Pesanan</h2>
            <dl class="mt-4 space-y-4 text-sm text-slate-600">
                <div>
                    <dt class="font-semibold text-slate-800">Nama Toko</dt>
                    <dd>{{ optional(optional($invoice->pesanan)->reseller)->nama_toko ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Nama Reseller</dt>
                    <dd>{{ optional(optional($invoice->pesanan)->reseller->user)->nama ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Alamat</dt>
                    <dd>{{ optional(optional($invoice->pesanan)->reseller)->alamat ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Telepon</dt>
                    <dd>{{ optional(optional($invoice->pesanan)->reseller->user)->telepon ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Rincian Item</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm text-slate-600">
                <thead class="border-b border-slate-200 text-xs uppercase tracking-widest text-slate-500">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Produk</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Harga Satuan</th>
                        <th class="px-4 py-3">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse(optional($invoice->pesanan)->items ?? [] as $item)
                        <tr>
                            <td class="px-4 py-4">{{ $loop->iteration }}</td>
                            <td class="px-4 py-4 font-medium text-slate-900">{{ optional($item->produk)->nama ?? 'Produk tidak ditemukan' }}</td>
                            <td class="px-4 py-4">{{ $item->jumlah }}</td>
                            <td class="px-4 py-4">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-4">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Tidak ada item pesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6 rounded-3xl border border-yellow-200 bg-yellow-50 px-4 py-4 text-sm text-yellow-800">
        Konfirmasi pembayaran dilakukan di luar sistem. Pastikan Anda menginformasikan bukti pembayaran langsung kepada admin.
    </div>
</div>
@endsection
