@extends('layouts.produsen')

@section('title', 'Buat Permintaan Pengadaan')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Buat Permintaan Pengadaan</h1>
            <p class="text-sm text-slate-500">Ajukan permintaan bahan baku kepada supplier terdaftar.</p>
        </div>
        <a href="{{ route('produsen.procurement.index') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke Dashboard Pengadaan
        </a>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm">
        <form action="{{ route('produsen.procurement.store') }}" method="post" class="grid gap-6">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Supplier & Bahan Baku</label>
                    <select name="bahan_baku_supplier_id" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                        <option value="">Pilih supplier dan bahan baku</option>
                        @foreach($supplierItems as $item)
                            <option value="{{ $item->id }}" {{ old('bahan_baku_supplier_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->supplier->nama ?? 'Supplier tidak dikenal' }} — {{ $item->bahanBaku->nama ?? 'Bahan baku tidak dikenal' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Jumlah</label>
                    <input type="number" step="0.01" name="jumlah" value="{{ old('jumlah') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Masukkan jumlah" />
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Catatan</label>
                <textarea name="catatan" rows="4" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Opsional">{{ old('catatan') }}</textarea>
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

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Kirim Permintaan</button>
        </form>
    </div>
</div>
@endsection
