@extends('layouts.admin')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Upload Bukti Pembayaran</h1>
        <p class="text-sm text-slate-500">Pesanan: {{ $pesanan->no_pesanan }}</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-2xl rounded-3xl bg-white p-6 shadow-sm">
        <div class="mb-6 border-b border-slate-200 pb-6">
            <h2 class="mb-4 text-lg font-semibold text-slate-900">Detail Invoice</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-slate-600">No Invoice:</span>
                    <span class="font-medium text-slate-900">{{ $pesanan->invoice->no_invoice }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Total Tagihan:</span>
                    <span class="font-medium text-slate-900">Rp {{ number_format($pesanan->invoice->jumlah_tagihan, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Sudah Terbayar:</span>
                    <span class="font-medium text-slate-900">Rp {{ number_format($pesanan->invoice->nominal_terbayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-600">Sisa Tagihan:</span>
                    <span class="font-medium text-red-600">Rp {{ number_format($pesanan->invoice->sisa_tagihan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <form action="{{ route('pesanan.store-bukti', $pesanan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label for="nominal" class="block text-sm font-medium text-slate-900">
                    Nominal Pembayaran <span class="text-red-600">*</span>
                </label>
                <input 
                    type="number" 
                    name="nominal" 
                    id="nominal"
                    class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('nominal') border-red-500 @enderror"
                    placeholder="Contoh: 500000"
                    max="{{ $pesanan->invoice->sisa_tagihan }}"
                    value="{{ old('nominal') }}"
                    required
                />
                @error('nominal')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="bukti_pembayaran" class="block text-sm font-medium text-slate-900">
                    Upload Bukti Pembayaran <span class="text-red-600">*</span>
                </label>
                <p class="mt-1 text-xs text-slate-500">Format: PDF, JPG, atau PNG (Maksimal 5MB)</p>
                <input 
                    type="file" 
                    name="bukti_pembayaran" 
                    id="bukti_pembayaran"
                    class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-2 text-slate-900 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-600 hover:file:bg-blue-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 @error('bukti_pembayaran') border-red-500 @enderror"
                    accept=".pdf,.jpg,.jpeg,.png"
                    required
                />
                @error('bukti_pembayaran')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-6">
                <a href="{{ route('pesanan.detail', $pesanan->id) }}" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-6 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-50">
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="inline-flex items-center rounded-full bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Upload Bukti
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
