@extends('layouts.admin')
@section('title', 'Manajemen Bahan')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Bahan</h1>
            <p class="text-sm text-gray-400">Kelola daftar bahan baku dan stok terkait produksi</p>
        </div>
        <a href="{{ route('admin.inventory.bahan.create') }}"
            class="rounded-xl bg-pink-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-pink-800">
            + Tambah Bahan Baku
        </a>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
    @endif

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-pink-50">
                <tr>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Kode</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Nama</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Kategori</th>
                    <th class="px-5 py-4 text-right font-semibold text-gray-600">Stok</th>
                    <th class="px-5 py-4 text-right font-semibold text-gray-600">Minimum</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Keterangan</th>
                    <th class="px-5 py-4 text-center font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bahanBaku as $bahan)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 text-gray-700">{{ $bahan->kode_bahan }}</td>
                    <td class="px-5 py-4 text-gray-800">{{ $bahan->nama_bahan }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $bahan->kategori ?? '-' }}</td>
                    <td class="px-5 py-4 text-right font-semibold text-gray-800">{{ number_format($bahan->stok_saat_ini) }} {{ $bahan->satuan }}</td>
                    <td class="px-5 py-4 text-right text-gray-600">{{ number_format($bahan->stok_minimum) }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $bahan->keterangan ?? '-' }}</td>
                    <td class="px-5 py-4 text-center">
                        <a
                            href="{{ route('admin.inventory.bahan.edit', $bahan) }}"
                            class="inline-flex rounded-xl bg-pink-700 px-3 py-2 text-xs font-semibold text-white hover:bg-pink-800"
                        >Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada bahan baku</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-gray-100 px-5 py-4">{{ $bahanBaku->links() }}</div>
    </div>
</main>

@endsection
