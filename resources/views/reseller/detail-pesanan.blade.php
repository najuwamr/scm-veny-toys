@extends('layouts.admin')

@section('title', 'Detail Pesanan Saya')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Detail Pesanan Saya</h1>
            <p class="text-sm text-slate-500">Periksa status pesanan dan lakukan pembayaran atau konfirmasi penerimaan.</p>
        </div>
        <a href="{{ route('pesanan.list') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
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

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Ringkasan Pesanan</h2>
            <dl class="space-y-4 text-sm text-slate-600">
                <div>
                    <dt class="font-semibold text-slate-800">Nomor Pesanan</dt>
                    <dd class="text-base font-semibold text-slate-900">{{ $pesanan->no_pesanan }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Tanggal Pesanan</dt>
                    <dd>{{ \Carbon\Carbon::parse($pesanan->tgl_pesanan)->translatedFormat('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Total Harga</dt>
                    <dd class="text-lg font-bold text-slate-900">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Status Pesanan</dt>
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
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Toko Saya</h2>
            <dl class="space-y-4 text-sm text-slate-600">
                <div>
                    <dt class="font-semibold text-slate-800">Nama Toko</dt>
                    <dd class="text-base font-semibold text-slate-900">{{ Auth::user()->reseller->nama_toko }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Alamat</dt>
                    <dd>{{ Auth::user()->reseller->alamat }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-800">Telepon</dt>
                    <dd>{{ Auth::user()->telepon }}</dd>
                </div>
            </dl>
        </div>
    </div>

    @if($pesanan->invoice)
        <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Invoice & Pembayaran</h2>
            <div class="grid gap-4 sm:grid-cols-3">
                <div>
                    <p class="text-sm text-slate-500">Nomor Invoice</p>
                    <p class="text-base font-semibold text-slate-900">{{ $pesanan->invoice->no_invoice }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Metode Bayar</p>
                    @php
                        $metodeClass = $pesanan->invoice->metode_bayar === 'transfer' 
                            ? 'bg-blue-100 text-blue-800' 
                            : 'bg-purple-100 text-purple-800';
                    @endphp
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $metodeClass }}">
                        {{ ucfirst($pesanan->invoice->metode_bayar) }}
                    </span>
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
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-slate-50 p-4">
                    <p class="text-sm font-semibold text-slate-700">Total Tagihan</p>
                    <p class="text-lg font-bold text-slate-900">Rp {{ number_format($pesanan->invoice->jumlah_tagihan, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg bg-emerald-50 p-4">
                    <p class="text-sm font-semibold text-emerald-700">Sudah Terbayar</p>
                    <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($pesanan->invoice->nominal_terbayar, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg bg-red-50 p-4">
                    <p class="text-sm font-semibold text-red-700">Sisa Tagihan</p>
                    <p class="text-lg font-bold text-red-600">Rp {{ number_format($pesanan->invoice->sisa_tagihan, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-lg bg-blue-50 p-4">
                    <p class="text-sm font-semibold text-blue-700">Tanggal Bayar</p>
                    <p class="text-lg font-bold text-blue-600">{{ $pesanan->invoice->tgl_bayar?->translatedFormat('d M Y') ?? '-' }}</p>
                </div>
            </div>

            {{-- TRANSFER: Upload Bukti Pembayaran --}}
            @if($pesanan->invoice->metode_bayar === 'transfer' && $pesanan->invoice->status_pembayaran !== 'lunas')
                <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-blue-900">Upload Bukti Pembayaran Transfer</h3>
                    @if(!$pesanan->invoice->bukti_pembayaran)
                        <p class="mb-4 text-sm text-blue-700">Silakan upload bukti pembayaran Anda di bawah ini. Pastikan nominal pembayaran sesuai.</p>
                        <a href="{{ route('pesanan.upload-bukti', $pesanan->id) }}" class="inline-flex items-center rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                            Upload Bukti Pembayaran
                        </a>
                    @else
                        <p class="mb-2 text-sm text-blue-700">✓ Bukti pembayaran sudah diupload</p>
                        <p class="text-sm text-blue-600">Status: {{ $pesanan->invoice->verified_at ? '✓ Sudah Diverifikasi Admin' : '⏱ Menunggu Verifikasi Admin' }}</p>
                    @endif
                </div>
            @endif

            {{-- COD: Tombol Konfirmasi Penerimaan --}}
            @if($pesanan->invoice->metode_bayar === 'cod' && $pesanan->status === 'dikirim')
                <div class="mt-6 rounded-lg border border-purple-200 bg-purple-50 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-purple-900">Konfirmasi Penerimaan Barang (COD)</h3>
                    <p class="mb-4 text-sm text-purple-700">Barang sudah dikirim. Setelah Anda konfirmasi penerimaan, pembayaran akan otomatis dianggap lunas.</p>
                    <form action="{{ route('pesanan.confirm-received', $pesanan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-full bg-purple-600 px-4 py-2 text-sm font-semibold text-white hover:bg-purple-700">
                            ✓ Konfirmasi Barang Diterima
                        </button>
                    </form>
                </div>
            @endif

            @if($pesanan->invoice->verified_at)
                <div class="mt-4 rounded-lg bg-emerald-50 p-4 border border-emerald-200">
                    <p class="text-sm text-emerald-700">✓ Pembayaran sudah diverifikasi oleh {{ optional($pesanan->invoice->verifiedBy)->nama ?? 'Admin' }} pada {{ $pesanan->invoice->verified_at->translatedFormat('d M Y H:i') }}</p>
                </div>
            @endif
        </div>
    @endif

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-slate-900">Detail Item Pesanan</h2>
            <p class="text-sm text-slate-500">Rincian produk yang dipesan</p>
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
                            <td class="px-4 py-4 font-medium text-slate-900">{{ optional($item->produk)->nama ?? '-' }}</td>
                            <td class="px-4 py-4">{{ $item->jumlah }}</td>
                            <td class="px-4 py-4">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-4 font-semibold text-slate-900">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">Tidak ada item.</td>
                        </tr>
                    @endforelse
                    <tr class="bg-slate-50 font-semibold">
                        <td colspan="4" class="px-4 py-4 text-right">Total:</td>
                        <td class="px-4 py-4 text-slate-900">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
