@extends('layouts.admin')

@section('title', 'Jadwal Pengiriman')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Buat Penjadwalan Pengiriman</h1>
            <p class="text-sm text-slate-500">Jadwalkan pengiriman untuk pesanan ini.</p>
        </div>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm">
        <form action="{{ route('admin.distribution.jadwal.store', $pesanan->id) }}" method="POST" class="grid gap-4">
            @csrf
            <div>
                <label class="text-sm text-slate-600">Pesanan</label>
                <div class="mt-1 text-slate-800">{{ $pesanan->no_pesanan }} - {{ optional($pesanan->reseller)->nama_toko }}</div>
            </div>

            <div>
                <label class="text-sm text-slate-600">Metode Pengiriman</label>
                <select name="metode_pengiriman_id" class="mt-1 rounded-lg border px-3 py-2">
                    @foreach($metodes as $m)
                        <option value="{{ $m->id }}">{{ ucfirst($m->nama) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-slate-600">Tanggal Dijadwalkan</label>
                <input type="datetime-local" name="tgl_dijadwalkan" class="mt-1 rounded-lg border px-3 py-2 w-full">
            </div>

            <div class="flex justify-end">
                <button class="rounded-full bg-pink-700 px-4 py-2 text-sm font-semibold text-white">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>
@endsection
