@extends('layouts.produsen')

@section('title', 'Mutasi Stok')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Mutasi Stok</h1>
        <p class="text-sm text-slate-500">Input perubahan stok dan telusuri riwayat mutasi bahan baku / produk.</p>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="rounded-3xl bg-white p-6 shadow-sm xl:col-span-1">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Form Mutasi</h2>
            <form action="{{ route('produsen.inventory.mutasi.store') }}" method="post" class="space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Tipe Mutasi</label>
                    <select name="mutasi_tipe" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                        <option value="bahan_baku" {{ old('mutasi_tipe') === 'bahan_baku' ? 'selected' : '' }}>Bahan Baku</option>
                        <option value="produk" {{ old('mutasi_tipe') === 'produk' ? 'selected' : '' }}>Produk Jadi</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Bahan Baku</label>
                    <select name="item_id_bahan_baku" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                        <option value="">Pilih bahan baku</option>
                        @foreach($bahanBakus as $bahan)
                            <option value="{{ $bahan->id }}" {{ old('item_id_bahan_baku') === $bahan->id ? 'selected' : '' }}>{{ $bahan->nama }} ({{ $bahan->satuan }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Produk</label>
                    <select name="item_id_produk" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                        <option value="">Pilih produk</option>
                        @foreach($produks as $produk)
                            <option value="{{ $produk->id }}" {{ old('item_id_produk') === $produk->id ? 'selected' : '' }}>{{ $produk->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Jenis Mutasi</label>
                    <select name="jenis_mutasi" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                        <option value="masuk" {{ old('jenis_mutasi') === 'masuk' ? 'selected' : '' }}>Masuk</option>
                        <option value="keluar" {{ old('jenis_mutasi') === 'keluar' ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Jumlah</label>
                    <input type="number" step="0.01" name="jumlah" value="{{ old('jumlah') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Masukkan jumlah mutasi" />
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Catatan</label>
                    <textarea name="catatan" rows="3" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Opsional">{{ old('catatan') }}</textarea>
                </div>
                @if($errors->any())
                    <div class="rounded-3xl bg-rose-50 p-4 text-sm text-rose-700">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Simpan Mutasi</button>
            </form>
        </div>

        <div class="xl:col-span-2 rounded-3xl bg-white p-6 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Riwayat Mutasi</h2>
                    <p class="text-sm text-slate-500">Daftar perubahan stok terbaru untuk bahan baku dan produk.</p>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full border-separate border-spacing-0 text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Tipe</th>
                            <th class="px-6 py-4">Nama Item</th>
                            <th class="px-6 py-4">Kuantitas</th>
                            <th class="px-6 py-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($mutations as $mutation)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 text-slate-600">{{ $mutation->created_at->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ ucfirst($mutation->jenis_mutasi) }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ optional($mutation->mutatable)->nama ?? 'Tidak dikenal' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ number_format($mutation->jumlah, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $mutation->catatan ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">Belum ada histori mutasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
