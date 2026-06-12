@extends('layouts.admin')
@section('title', 'Tracking Pengiriman')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Tracking Pengiriman</h1>
        <p class="text-sm text-gray-400">Pantau status pengiriman semua permintaan bahan baku</p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100">
        <table class="w-full text-sm">
            <thead class="bg-pink-50">
                <tr>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Kode</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Bahan Baku</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Supplier</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Status Permintaan</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Status Pengiriman</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Ekspedisi / Resi</th>
                    <th class="px-5 py-4 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($permintaan as $item)
                @php
                    $requestStatus = [
                        'menunggu'  => ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                        'pending'   => ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                        'disetujui' => ['bg-blue-100 text-blue-700', 'Disetujui'],
                        'approved'  => ['bg-blue-100 text-blue-700', 'Disetujui'],
                        'diproses'  => ['bg-cyan-100 text-cyan-700', 'Diproses'],
                        'ditolak'   => ['bg-red-100 text-red-600', 'Ditolak'],
                        'rejected'  => ['bg-red-100 text-red-600', 'Ditolak'],
                        'dikirim'   => ['bg-purple-100 text-purple-700', 'Dalam Pengiriman'],
                        'diterima'  => ['bg-green-100 text-green-700', 'Diterima'],
                        'selesai'   => ['bg-green-100 text-green-700', 'Selesai'],
                    ];
                    $shippingStatus = ['dikirim'=>'Dalam Perjalanan','diterima'=>'Sudah Tiba'];
                    [$reqCls, $reqLabel] = $requestStatus[$item->status] ?? ['bg-gray-100 text-gray-500', ucfirst($item->status)];
                    $shipLabel = $item->pengiriman ? ($shippingStatus[$item->pengiriman->status] ?? ucfirst($item->pengiriman->status)) : 'Belum Dikirim';
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $item->kode_permintaan }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $item->bahanBakuSupplier->bahanBaku->nama_bahan }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $item->bahanBakuSupplier->supplier->user?->name ?? $item->bahanBakuSupplier->supplier->nama_perusahaan ?? '-' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $reqCls }}">{{ $reqLabel }}</span>
                    </td>
                    <td class="px-5 py-3 text-center text-gray-700">{{ $shipLabel }}</td>
                    <td class="px-5 py-3">
                        <p class="font-semibold text-gray-700">{{ $item->pengiriman->ekspedisi ?? '-' }}</p>
                        <p class="font-mono text-xs text-gray-400">{{ $item->pengiriman->no_resi ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('procurement.show', $item) }}" class="rounded-lg bg-pink-50 px-3 py-1.5 text-xs font-semibold text-pink-700 hover:bg-pink-100">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">Belum ada permintaan</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-gray-100 px-5 py-4">{{ $permintaan->links() }}</div>
    </div>

</main>
@endsection
