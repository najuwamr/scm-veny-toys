@extends('layouts.admin')
@section('title', 'Detail Permintaan')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8">
        <a href="{{ route('procurement.index') }}" class="mb-2 inline-flex items-center gap-1 text-sm text-gray-400 hover:text-gray-600">← Kembali</a>
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Permintaan</h1>
                <p class="font-mono text-sm text-gray-400">{{ $permintaan->kode_permintaan }}</p>
            </div>
            @php
                $statusConfig = [
                    'menunggu'=>'bg-yellow-100 text-yellow-700',
                    'pending'=>'bg-yellow-100 text-yellow-700',
                    'disetujui'=>'bg-blue-100 text-blue-700',
                    'approved'=>'bg-blue-100 text-blue-700',
                    'ditolak'=>'bg-red-100 text-red-600',
                    'rejected'=>'bg-red-100 text-red-600',
                    'dikirim'=>'bg-purple-100 text-purple-700',
                    'diterima'=>'bg-green-100 text-green-700',
                    'selesai'=>'bg-green-100 text-green-700',
                ];
                $statusLabel  = [
                    'menunggu'=>'Menunggu Supplier',
                    'pending'=>'Menunggu Supplier',
                    'disetujui'=>'Disetujui Supplier',
                    'approved'=>'Disetujui Supplier',
                    'ditolak'=>'Ditolak Supplier',
                    'rejected'=>'Ditolak Supplier',
                    'dikirim'=>'Dalam Pengiriman',
                    'diterima'=>'Diterima',
                    'selesai'=>'Selesai',
                ];
            @endphp
            <span class="rounded-full px-4 py-1.5 text-sm font-semibold {{ $statusConfig[$permintaan->status] ?? 'bg-gray-100 text-gray-500' }}">
                {{ $statusLabel[$permintaan->status] ?? $permintaan->status }}
            </span>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-semibold text-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        <div class="col-span-2 flex flex-col gap-6">

            {{-- Info utama --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-pink-100">
                <h2 class="mb-4 font-bold text-gray-800">Informasi Permintaan</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Bahan Baku</p>
                        <p class="font-semibold text-gray-800">{{ $permintaan->bahanBaku->nama_bahan }}</p>
                        <p class="text-xs text-gray-400">{{ $permintaan->bahanBaku->kode_bahan }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Jumlah Diminta</p>
                        <p class="text-2xl font-bold text-pink-700">{{ number_format($permintaan->jumlah_diminta) }} <span class="text-sm font-normal text-gray-400">{{ $permintaan->bahanBaku->satuan }}</span></p>
                    </div>
                    <div>
                        <p class="text-gray-400">Supplier</p>
                        <p class="font-semibold text-gray-800">{{ $permintaan->supplier->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Dibuat pada</p>
                        <p class="font-semibold text-gray-800">{{ $permintaan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                @if($permintaan->catatan_admin)
                <div class="mt-4 rounded-xl bg-gray-50 p-4">
                    <p class="mb-1 text-xs font-semibold text-gray-400">CATATAN ADMIN</p>
                    <p class="text-sm text-gray-700">{{ $permintaan->catatan_admin }}</p>
                </div>
                @endif
                @if($permintaan->catatan_supplier)
                <div class="mt-3 rounded-xl bg-blue-50 p-4">
                    <p class="mb-1 text-xs font-semibold text-blue-400">CATATAN SUPPLIER</p>
                    <p class="text-sm text-gray-700">{{ $permintaan->catatan_supplier }}</p>
                </div>
                @endif
            </div>

            {{-- Info pengiriman --}}
            @if($permintaan->pengiriman)
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-purple-100">
                <h2 class="mb-4 font-bold text-gray-800">Info Pengiriman</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400">Ekspedisi</p>
                        <p class="font-semibold text-gray-800">{{ $permintaan->pengiriman->ekspedisi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">No. Resi</p>
                        <p class="font-mono font-semibold text-gray-800">{{ $permintaan->pengiriman->no_resi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Tanggal Kirim</p>
                        <p class="font-semibold text-gray-800">{{ $permintaan->pengiriman->tanggal_kirim->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Estimasi Tiba</p>
                        <p class="font-semibold text-gray-800">{{ $permintaan->pengiriman->estimasi_tiba?->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>
                @if($permintaan->pengiriman->catatan)
                <div class="mt-4 rounded-xl bg-gray-50 p-4">
                    <p class="mb-1 text-xs font-semibold text-gray-400">CATATAN PENGIRIMAN</p>
                    <p class="text-sm text-gray-700">{{ $permintaan->pengiriman->catatan }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-4">

            {{-- Timeline --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm border border-pink-100">
                <h2 class="mb-4 font-bold text-gray-800">Progress</h2>
                @php
                    $steps = [
                        'menunggu'=>'Permintaan Dibuat',
                        'pending'=>'Permintaan Dibuat',
                        'disetujui'=>'Disetujui Supplier',
                        'approved'=>'Disetujui Supplier',
                        'dikirim'=>'Barang Dikirim',
                        'diterima'=>'Barang Diterima',
                    ];
                    $statusOrder = array_keys($steps);
                    $currentIndex = array_search($permintaan->status, $statusOrder) ?: 0;
                @endphp
                <div class="flex flex-col gap-3">
                    @foreach($steps as $key => $label)
                    @php $idx = array_search($key, $statusOrder); @endphp
                    <div class="flex items-center gap-3">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold
                            {{ $permintaan->status === 'ditolak' && $key === 'disetujui' ? 'bg-red-100 text-red-500' : ($idx <= $currentIndex ? 'bg-pink-700 text-white' : 'bg-gray-100 text-gray-400') }}">
                            {{ $permintaan->status === 'ditolak' && $key === 'disetujui' ? '✕' : ($idx < $currentIndex ? '✓' : $idx + 1) }}
                        </div>
                        <span class="text-sm {{ $idx <= $currentIndex ? 'font-semibold text-gray-800' : 'text-gray-400' }}">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Konfirmasi terima --}}
            @if($permintaan->status === 'dikirim')
            <div class="rounded-2xl bg-green-50 p-6 border border-green-200">
                <p class="mb-2 font-bold text-green-700">Barang Sudah Tiba?</p>
                <p class="mb-4 text-sm text-green-600">Konfirmasi penerimaan akan otomatis menambah stok di inventory.</p>
                <form action="{{ route('procurement.terima', $permintaan) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-green-600 py-3 text-sm font-bold text-white hover:bg-green-700">
                        ✓ Konfirmasi Terima Barang
                    </button>
                </form>
            </div>
            @endif

            @if($permintaan->status === 'diterima')
            <div class="rounded-2xl bg-green-50 p-4 border border-green-200 text-center">
                <p class="text-sm font-semibold text-green-700">✓ Selesai — Stok sudah diperbarui</p>
            </div>
            @endif

        </div>
    </div>

</main>
@endsection
