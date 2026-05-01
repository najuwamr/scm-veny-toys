@extends('layouts.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Detail Pesanan</h1>
            <p class="text-sm text-slate-500">Periksa detail pesanan dan lakukan validasi status barang.</p>
        </div>
        <a href="{{ route('admin.pesanan.list') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke List Pesanan
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

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Ringkasan Pesanan</h2>
            <dl class="space-y-4 text-sm text-slate-600">
                <div>
                    <dt class="font-semibold text-slate-800">Nomor Pesanan</dt>
                    <dd>{{ $pesanan->no_pesanan }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Tanggal Pesanan</dt>
                    <dd>{{ \Carbon\Carbon::parse($pesanan->tgl_pesanan)->translatedFormat('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Total Harga</dt>
                    <dd>Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Status Saat Ini</dt>
                    <dd>
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
                    </dd>
                </div>
                @if($pesanan->catatan)
                <div>
                    <dt class="font-semibold text-slate-800">Catatan</dt>
                    <dd>{{ $pesanan->catatan }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Data Reseller</h2>
            <dl class="space-y-4 text-sm text-slate-600">
                <div>
                    <dt class="font-semibold text-slate-800">Nama</dt>
                    <dd>{{ optional($pesanan->reseller->user)->nama ?? 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Nama Toko</dt>
                    <dd>{{ optional($pesanan->reseller)->nama_toko ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Alamat</dt>
                    <dd>{{ optional($pesanan->reseller)->alamat ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Telepon</dt>
                    <dd>{{ optional($pesanan->reseller->user)->telepon ?? '-' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Invoice Pesanan</h2>
        @if($pesanan->invoice)
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Nomor Invoice</p>
                    <p class="text-base font-semibold text-slate-900">{{ $pesanan->invoice->no_invoice }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Status Pembayaran</p>
                    @php
                        $invoiceBadge = [
                            'belum_bayar' => 'bg-yellow-100 text-yellow-800',
                            'sebagian' => 'bg-orange-100 text-orange-800',
                            'lunas' => 'bg-emerald-100 text-emerald-800',
                        ];
                    @endphp
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $invoiceBadge[$pesanan->invoice->status_pembayaran] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ ucfirst(str_replace('_', ' ', $pesanan->invoice->status_pembayaran)) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Jumlah Tagihan</p>
                    <p class="text-base font-semibold text-slate-900">Rp {{ number_format($pesanan->invoice->jumlah_tagihan, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Tanggal Bayar</p>
                    <p class="text-base font-semibold text-slate-900">{{ optional($pesanan->invoice->tgl_bayar)->translatedFormat('d M Y') ?? 'Belum dibayar' }}</p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('admin.invoice.detail', $pesanan->invoice->id) }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Lihat Detail Invoice
                </a>
            </div>
        @elseif($pesanan->status === 'diproses')
            <p class="mb-4 text-sm text-slate-500">Invoice belum dibuat. Buat invoice setelah pesanan diterima untuk menugaskan pembayaran reseller.</p>
            <a href="{{ route('admin.invoice.create', $pesanan->id) }}" class="inline-flex items-center rounded-full bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                Buat Invoice
            </a>
        @else
            <p class="text-sm text-slate-500">Alamat invoice akan tersedia setelah admin menerima permintaan pesanan.</p>
        @endif
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Detail Item</h2>
                <p class="text-sm text-slate-500">Periksa nama produk, jumlah, dan subtotal setiap item.</p>
            </div>
        </div>

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
                    @forelse($pesanan->items as $item)
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
        <h2 class="mb-4 text-lg font-semibold text-slate-900">Tindakan Admin</h2>
        <p class="mb-4 text-sm text-slate-500">Lakukan validasi status sesuai alur pesanan.</p>

        @if($pesanan->status === 'menunggu')
            <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @csrf
                <input type="hidden" name="action" value="accept">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Terima Permintaan (Validasi stok tersedia)
                </button>
            </form>
        @elseif($pesanan->status === 'diproses')
            @if($pesanan->invoice && $pesanan->invoice->status_pembayaran === 'lunas')
                <form action="{{ route('admin.pesanan.action', $pesanan->id) }}" method="POST" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-700">
                        Setujui Pembayaran dan Kirim
                    </button>
                </form>
            @elseif($pesanan->invoice)
                <div class="rounded-3xl border border-yellow-200 bg-yellow-50 px-4 py-4 text-sm text-yellow-800">
                    Invoice sudah dibuat, tetapi pembayaran belum lunas. Mohon periksa invoice sebelum menyetujui pengiriman.
                </div>
            @else
                <div class="space-y-3">
                    <p class="text-sm text-slate-500">Invoice belum dibuat untuk pesanan ini. Buat invoice dan konfirmasi pembayaran terlebih dahulu.</p>
                    <a href="{{ route('admin.invoice.create', $pesanan->id) }}" class="inline-flex items-center rounded-full bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700">
                        Buat Invoice
                    </a>
                </div>
            @endif
        @else
            <div class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-4 text-sm text-slate-600">
                Pesanan saat ini berstatus <span class="font-semibold text-slate-900">{{ ucfirst($pesanan->status) }}</span>. Tidak ada aksi tambahan.
            </div>
        @endif
    </div>
</div>
@endsection
