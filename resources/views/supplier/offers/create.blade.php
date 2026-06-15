@extends('layouts.supplier')

@section('title', 'Tambah Bahan & Harga')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Tambah Bahan & Harga</h1>
            <p class="text-sm text-slate-500">Tambahkan atau perbarui bahan baku yang Anda suplai.</p>
        </div>
        <a href="{{ route('supplier.materials.index') }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke Daftar Bahan
        </a>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm">
        <form action="{{ route('supplier.materials.store') }}" method="post" class="grid gap-6">
            @csrf
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Bahan Baku</label>
                <select name="bahan_baku_id" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                    <option value="">Pilih bahan baku</option>
                    @foreach($bahanBakus as $bahan)
                        <option value="{{ $bahan->id }}" {{ old('bahan_baku_id') == $bahan->id ? 'selected' : '' }}>{{ $bahan->nama_bahan }} ({{ $bahan->satuan }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Harga</label>
                <input type="number" name="harga" min="0" step="0.01" value="{{ old('harga') }}" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Rp 0.00" />
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

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-3xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Simpan Bahan & Harga</button>
        </form>
    </div>
</div>
@endsection
