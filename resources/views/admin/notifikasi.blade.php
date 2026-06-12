@extends('layouts.admin')
@section('title', 'Notifikasi Stok')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Notifikasi Stok</h1>
        <p class="text-sm text-gray-400">Bahan baku yang perlu segera ditambah stoknya</p>
    </div>

    @if($stokKritis->isEmpty())
    <div class="flex flex-col items-center justify-center rounded-2xl bg-white py-20 shadow-sm border border-pink-100">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100 text-3xl">✓</div>
        <p class="text-lg font-bold text-gray-700">Semua stok aman!</p>
        <p class="mt-1 text-sm text-gray-400">Tidak ada bahan baku yang di bawah batas minimum</p>
    </div>
    @else
    <div class="mb-4 flex items-center gap-2">
        <span class="flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">{{ $stokKritis->count() }}</span>
        <span class="font-semibold text-red-600">bahan baku di bawah stok minimum</span>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @foreach($stokKritis as $bahan)
        @php $persen = $bahan->stok_minimum > 0 ? round(($bahan->stok_saat_ini / $bahan->stok_minimum) * 100) : 0; @endphp
        <div class="rounded-2xl bg-white p-5 shadow-sm border border-red-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-bold text-gray-800">{{ $bahan->nama_bahan }}</p>
                    <p class="text-sm text-gray-400">{{ $bahan->kode_bahan }} · {{ $bahan->satuan }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-red-500">{{ number_format($bahan->stok_saat_ini) }}</p>
                    <p class="text-xs text-gray-400">min: {{ number_format($bahan->stok_minimum) }} {{ $bahan->satuan }}</p>
                </div>
            </div>
            <div class="mt-4">
                <div class="mb-1 flex justify-between text-xs text-gray-400">
                    <span>Stok saat ini</span>
                    <span>{{ $persen }}% dari minimum</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-red-100">
                    <div class="h-2 rounded-full bg-red-400" style="width: {{ min($persen, 100) }}%"></div>
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <a href="{{ route('inventory.masuk') }}" class="rounded-lg bg-green-50 px-4 py-2 text-sm font-semibold text-green-700 hover:bg-green-100">+ Catat Masuk</a>
                <a href="{{ route('procurement.create') }}" class="rounded-lg bg-pink-50 px-4 py-2 text-sm font-semibold text-pink-700 hover:bg-pink-100">Buat Permintaan</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</main>
@endsection
