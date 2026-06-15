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
                    <dd>{{ $invoice->tgl_bayar ? \Carbon\Carbon::parse($invoice->tgl_bayar)->translatedFormat('d M Y') : '-' }}</dd>
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

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        @if(session('success'))
            <div class="mb-4 rounded-3xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="text-lg font-semibold text-slate-900">Pilih Metode Pembayaran</h2>
        <p class="mt-2 text-sm text-slate-500">Pilih salah satu metode pembayaran, lalu konfirmasi via WhatsApp ke admin.</p>

        <form action="{{ route('reseller.payment.confirm', $invoice->id) }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Metode Pembayaran</label>
                <select name="metode_bayar" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                    <option value="" {{ old('metode_bayar', $invoice->metode_bayar) === null ? 'selected' : '' }}>Pilih metode pembayaran</option>
                    <option value="transfer" {{ old('metode_bayar', $invoice->metode_bayar) === 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="e-wallet" {{ old('metode_bayar', $invoice->metode_bayar) === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="cod" {{ old('metode_bayar', $invoice->metode_bayar) === 'cod' ? 'selected' : '' }}>COD</option>
                </select>
                @error('metode_bayar')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                Simpan Metode Pembayaran
            </button>
        </form>

        <div class="mt-6 rounded-3xl border border-slate-200 bg-slate-50 p-5">
            <p class="text-sm font-semibold text-slate-900">Konfirmasi WhatsApp</p>
            <p class="mt-2 text-sm text-slate-600">Setelah memilih metode pembayaran, klik tombol di bawah untuk menghubungi admin. Konfirmasi pembayaran dilakukan di luar sistem.</p>
            @php
                $adminNumber = '6282230474146';
                $adminNumberWhatsapp = '6282230474146';
                $message = 'Halo admin, saya ingin mengkonfirmasi pembayaran untuk invoice ' . $invoice->no_invoice . ' dengan metode ' . ($invoice->metode_bayar ?? 'belum dipilih') . '. [Tambahkan detail pembayaran atau bukti transfer Anda di sini]';
            @endphp
            <a href="https://wa.me/{{ $adminNumberWhatsapp }}?text={{ urlencode($message) }}" target="_blank" class="mt-4 inline-flex w-full items-center justify-center rounded-full bg-green-600 px-5 py-3 text-sm font-semibold text-white hover:bg-green-700 {{ $invoice->metode_bayar ? '' : 'opacity-70 pointer-events-none' }}">
                Konfirmasi via WhatsApp ke Admin
            </a>
            <p class="mt-3 text-sm text-slate-600">Nomor admin: {{ $adminNumber }}</p>
            @unless($invoice->metode_bayar)
                <p class="mt-2 text-sm text-yellow-700">Pilih metode pembayaran terlebih dahulu untuk mengaktifkan tombol WhatsApp.</p>
            @endunless
        </div>
    </div>
</div>
@endsection
