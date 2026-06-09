@extends('layouts.admin')

@section('title', 'Detail Invoice')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Detail Invoice</h1>
            <p class="text-sm text-slate-500">Lihat rincian invoice dan proses pembayaran sesuai metode.</p>
        </div>
        <a href="{{ route('admin.invoice.list') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke Daftar Invoice
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
                        <dt class="font-semibold text-slate-800">Metode Bayar</dt>
                        <dd>
                            @php
                                $badgeClass = $invoice->metode_bayar === 'transfer' 
                                    ? 'bg-blue-100 text-blue-800' 
                                    : 'bg-purple-100 text-purple-800';
                            @endphp
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                {{ ucfirst($invoice->metode_bayar) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">Jumlah Tagihan</dt>
                        <dd class="text-lg font-bold text-slate-900">Rp {{ number_format($invoice->jumlah_tagihan, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">Nominal Terbayar</dt>
                        <dd class="text-lg font-bold text-emerald-600">Rp {{ number_format($invoice->nominal_terbayar, 0, ',', '.') }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-800">Tanggal Bayar</dt>
                        <dd>{{ $invoice->tgl_bayar ? \Carbon\Carbon::parse($invoice->tgl_bayar)->translatedFormat('d M Y') : '-' }}</dd>
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
                    @if($invoice->verified_at)
                        <div>
                            <dt class="font-semibold text-slate-800">Diverifikasi Oleh</dt>
                            <dd>{{ optional($invoice->verifiedBy)->nama ?? '-' }} ({{ optional($invoice->verified_at)->translatedFormat('d M Y H:i') }})</dd>
                        </div>
                    @endif
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

    @if(Auth::user()->role === 'admin' && $invoice->metode_bayar === 'transfer' && !$invoice->verified_at)
        <div class="mt-6 rounded-3xl bg-blue-50 p-6 shadow-sm border border-blue-200">
            <h2 class="mb-4 text-lg font-semibold text-blue-900">Bukti Pembayaran Transfer</h2>
            @if($invoice->bukti_pembayaran)
                <div class="space-y-4">
                    <div class="rounded-lg bg-white p-4">
                        <p class="mb-2 text-sm font-semibold text-slate-700">File Bukti:</p>
                        <a href="{{ Storage::url($invoice->bukti_pembayaran) }}" target="_blank" class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Lihat Bukti Pembayaran
                        </a>
                    </div>
                    <div class="flex gap-3">
                        <form action="{{ route('admin.invoice.verify', $invoice->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white hover:bg-emerald-700">
                                ✓ Verifikasi Pembayaran
                            </button>
                        </form>
                        <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            ✗ Tolak
                        </button>
                    </div>
                    <div id="rejectForm" class="hidden mt-4 rounded-lg bg-white p-4 border border-red-200">
                        <form action="{{ route('admin.invoice.reject', $invoice->id) }}" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Alasan Penolakan</label>
                                <textarea name="alasan" rows="3" class="w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" placeholder="Contoh: Bukti tidak sesuai dengan nominal yang diklaim" required></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-red-600 px-5 py-2 text-sm font-semibold text-white hover:bg-red-700">
                                    Tolak Pembayaran
                                </button>
                                <button type="button" onclick="document.getElementById('rejectForm').classList.add('hidden')" class="inline-flex items-center justify-center rounded-full bg-slate-300 px-5 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-400">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <p class="text-slate-600">Menunggu reseller mengunggah bukti pembayaran.</p>
            @endif
        </div>
    @endif

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
                <select name="metode_bayar" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                    <option value="" {{ old('metode_bayar', $invoice->metode_bayar) === null ? 'selected' : '' }}>Pilih metode pembayaran</option>
                    <option value="transfer bank" {{ old('metode_bayar', $invoice->metode_bayar) === 'transfer bank' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="e-wallet" {{ old('metode_bayar', $invoice->metode_bayar) === 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                    <option value="cod" {{ old('metode_bayar', $invoice->metode_bayar) === 'cod' ? 'selected' : '' }}>COD</option>
                </select>
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
