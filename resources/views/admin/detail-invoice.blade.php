@extends('layouts.admin')

@section('title', 'Detail Invoice')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Detail Invoice</h1>
            <p class="text-sm text-slate-500">Lihat rincian invoice dan ubah status pembayaran sesuai konfirmasi reseller.</p>
        </div>
        <a href="{{ route('admin.invoice.list') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke List Invoice
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

    <div class="rounded-3xl bg-white p-6 shadow-sm">
        <div class="grid gap-6 lg:grid-cols-2">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Informasi Invoice</h2>
                <dl class="mt-4 space-y-4 text-sm text-slate-600">
                    <div>
                        <dt class="font-semibold text-slate-800">Nomor Invoice</dt>
                        <dd>{{ $invoice->no_invoice }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">No Pesanan</dt>
                        <dd>{{ optional($invoice->pesanan)->no_pesanan ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">Jumlah Tagihan</dt>
                        <dd>Rp {{ number_format($invoice->jumlah_tagihan, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">Metode Bayar</dt>
                        <dd>{{ $invoice->metode_bayar ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">Tanggal Bayar</dt>
                        <dd>{{ optional($invoice->tgl_bayar)->translatedFormat('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">Status Pembayaran</dt>
                        @php
                            $statusClasses = [
                                'belum_bayar' => 'bg-yellow-100 text-yellow-800',
                                'sebagian' => 'bg-orange-100 text-orange-800',
                                'lunas' => 'bg-emerald-100 text-emerald-800',
                            ];
                        @endphp
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$invoice->status_pembayaran] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $invoice->status_pembayaran)) }}
                        </span>
                    </div>
                </dl>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-slate-900">Data Reseller</h2>
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

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Perbarui Status Pembayaran</h2>
        <p class="mb-6 text-sm text-slate-500">Gunakan form berikut untuk mengubah status pembayaran menjadi sebagian atau lunas.</p>

        <form action="{{ route('admin.invoice.action', $invoice->id) }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Status Pembayaran</label>
                <select name="status_pembayaran" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                    <option value="belum_bayar" {{ $invoice->status_pembayaran === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="sebagian" {{ $invoice->status_pembayaran === 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                    <option value="lunas" {{ $invoice->status_pembayaran === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Metode Bayar</label>
                <input type="text" name="metode_bayar" value="{{ old('metode_bayar', $invoice->metode_bayar) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" placeholder="Contoh: Transfer Bank / COD">
            </div>

            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                Simpan Status Pembayaran
            </button>
        </form>

        @if($invoice->status_pembayaran === 'lunas' && optional($invoice->pesanan)->status === 'diproses')
            <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                Invoice sudah lunas. Sekarang admin dapat menyetujui pembayaran di detail pesanan untuk melanjutkan ke pengiriman.
            </div>
        @endif
    </div>
</div>
@endsection
