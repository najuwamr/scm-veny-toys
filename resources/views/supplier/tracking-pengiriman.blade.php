@extends('layouts.supplier')
@section('title', 'Tracking Pengiriman')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Tracking Pengiriman</h1>
        <p class="text-sm text-gray-400">Status pengiriman yang sudah kamu kirim</p>
    </div>

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100">
        <table class="w-full text-sm">
            <thead class="bg-pink-50">
                <tr>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Kode</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Bahan Baku</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Ekspedisi / Resi</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Tgl Kirim</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Est. Tiba</th>
                    <th class="px-5 py-4 text-center font-semibold text-gray-600">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($pengiriman as $item)
                @php
                    $cfg = ['diproses'=>'bg-yellow-100 text-yellow-700','dikirim'=>'bg-purple-100 text-purple-700','tiba'=>'bg-green-100 text-green-700'];
                    $lbl = ['diproses'=>'Diproses','dikirim'=>'Dalam Perjalanan','tiba'=>'Sudah Diterima'];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">{{ $item->permintaan->kode_permintaan }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-800">{{ $item->permintaan->bahanBaku->nama_bahan }}</td>
                    <td class="px-5 py-3">
                        <p class="font-semibold text-gray-700">{{ $item->ekspedisi ?? '-' }}</p>
                        <p class="font-mono text-xs text-gray-400">{{ $item->no_resi ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $item->tanggal_kirim->format('d M Y') }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $item->estimasi_tiba?->format('d M Y') ?? '-' }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $cfg[$item->status_pengiriman] ?? 'bg-gray-100 text-gray-500' }}">
                            {{ $lbl[$item->status_pengiriman] ?? $item->status_pengiriman }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada data pengiriman</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-gray-100 px-5 py-4">{{ $pengiriman->links() }}</div>
    </div>

</main>
@endsection
