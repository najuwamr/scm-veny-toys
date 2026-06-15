@extends('layouts.admin')
@section('title', 'Stok Keluar')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Stok Keluar</h1>
            <p class="text-sm text-gray-400">Pencatatan pemakaian bahan baku untuk produksi</p>
        </div>
        <button type="button" onclick="const modal=document.getElementById('modal-keluar'); modal.classList.remove('hidden'); modal.classList.add('flex');"
            class="rounded-xl bg-pink-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-pink-800">
            + Catat Stok Keluar
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-semibold text-red-700">{{ session('error') }}</div>
    @endif

    <div class="rounded-2xl bg-white shadow-sm border border-pink-100">
        <table class="w-full text-sm">
            <thead class="bg-pink-50">
                <tr>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Tanggal</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Bahan Baku</th>
                    <th class="px-5 py-4 text-right font-semibold text-gray-600">Jumlah</th>
                    <th class="px-5 py-4 text-left font-semibold text-gray-600">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($stokKeluar as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-600">{{ optional($item->created_at)->format('d M Y') }}</td>
                    <td class="px-5 py-3">
                        <p class="font-semibold text-gray-800">{{ $item->bahanBaku->nama_bahan }}</p>
                        <p class="text-xs text-gray-400">Sisa: {{ $item->bahanBaku->stok_saat_ini }} {{ $item->bahanBaku->satuan }}</p>
                    </td>
                    <td class="px-5 py-3 text-right font-bold text-orange-500">
                        -{{ number_format($item->jumlah) }} <span class="text-xs font-normal text-gray-400">{{ $item->bahanBaku->satuan }}</span>
                    </td>
                    <td class="px-5 py-3 text-gray-400">{{ $item->catatan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-12 text-center text-gray-400">Belum ada data stok keluar</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="border-t border-gray-100 px-5 py-4">{{ $stokKeluar->links() }}</div>
    </div>

</main>

{{-- Modal --}}
<div id="modal-keluar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">Catat Stok Keluar</h2>
            <button type="button" onclick="const modal=document.getElementById('modal-keluar'); modal.classList.add('hidden'); modal.classList.remove('flex');" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <form action="{{ route('admin.inventory.keluar.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Bahan Baku</label>
                <select name="bahan_baku_id" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                    <option value="">-- Pilih Bahan Baku --</option>
                    @foreach($bahanBaku as $bahan)
                    <option value="{{ $bahan->id }}">{{ $bahan->nama_bahan }} (stok: {{ $bahan->stok_saat_ini }} {{ $bahan->satuan }})</option>
                    @endforeach
                </select>
                @error('bahan_baku_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Jumlah</label>
                    <input type="number" name="jumlah" min="1" required class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="0">
                    @error('jumlah')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold text-gray-700">Tanggal</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Keperluan</label>
                <input type="text" name="keperluan" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="Untuk produksi apa?">
            </div>
            <div>
                <label class="mb-1 block text-sm font-semibold text-gray-700">Keterangan</label>
                <textarea name="keterangan" rows="2" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="const modal=document.getElementById('modal-keluar'); modal.classList.add('hidden'); modal.classList.remove('flex');"
                    class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</button>
                <button type="submit" class="flex-1 rounded-xl bg-pink-700 py-2.5 text-sm font-semibold text-white hover:bg-pink-800">Simpan</button>
            </div>
        </form>
    </div>
</div>
@if($errors->any())
<script>const modal=document.getElementById('modal-keluar'); modal.classList.remove('hidden'); modal.classList.add('flex');</script>
@endif
@endsection
