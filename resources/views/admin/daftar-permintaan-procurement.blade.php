@extends('layouts.admin')
@section('title', 'Daftar Permintaan')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Permintaan Bahan</h1>
            <p class="text-sm text-gray-400">Daftar semua permintaan bahan baku ke supplier</p>
        </div>
        <a href="{{ route('procurement.create') }}" class="rounded-xl bg-pink-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-pink-800">
            + Buat Permintaan
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100">
        <table class="w-full text-sm">
            <thead class="bg-pink-50">
                <tr>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Kode</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Bahan Baku</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Supplier</th>
                    <th class="px-5 py-4 text-right font-semibold text-gray-600">Jumlah</th>
                    <th class="px-5 py-4 text-center font-semibold text-gray-600">Status</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-5 py-4 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($permintaan as $item)
                @php
                    $statusConfig = [
                        'menunggu'  => ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                        'pending'   => ['bg-yellow-100 text-yellow-700', 'Menunggu'],
                        'disetujui' => ['bg-blue-100 text-blue-700', 'Disetujui'],
                        'approved'  => ['bg-blue-100 text-blue-700', 'Disetujui'],
                        'ditolak'   => ['bg-red-100 text-red-600', 'Ditolak'],
                        'rejected'  => ['bg-red-100 text-red-600', 'Ditolak'],
                        'dikirim'   => ['bg-purple-100 text-purple-700', 'Dalam Pengiriman'],
                        'diterima'  => ['bg-green-100 text-green-700', 'Diterima'],
                        'selesai'   => ['bg-green-100 text-green-700', 'Selesai'],
                    ];
                    [$cls, $label] = $statusConfig[$item->status] ?? ['bg-gray-100 text-gray-500', ucfirst($item->status)];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $item->kode_permintaan }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $item->bahanBaku->nama_bahan }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $item->bahanBakuSupplier->supplier->user?->name ?? $item->bahanBakuSupplier->supplier->nama_perusahaan ?? '-' }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-800">
                        {{ number_format($item->jumlah_diminta) }} <span class="text-xs font-normal text-gray-400">{{ $item->bahanBaku->satuan }}</span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $cls }}">{{ $label }}</span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-400">{{ $item->created_at->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-center">
                        <a href="{{ route('procurement.show', $item) }}" class="rounded-lg bg-pink-50 px-3 py-1.5 text-xs font-semibold text-pink-700 hover:bg-pink-100">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">Belum ada permintaan bahan</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-gray-100 px-5 py-4">{{ $permintaan->links() }}</div>
    </div>

</main>
@endsection
