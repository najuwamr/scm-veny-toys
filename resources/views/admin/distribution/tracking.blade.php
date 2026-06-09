@extends('layouts.admin')

@section('title', 'Tracking Pengiriman')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">Tracking Pengiriman</h1>
        <p class="text-sm text-slate-500">Detail status pengiriman untuk pesanan {{ optional($distribusi->pesanan)->no_pesanan ?? '-' }}</p>
    </div>

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.distribution.index') }}" class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">Kembali ke Daftar Distribusi</a>
    </div>

    <div class="rounded-3xl bg-white p-6 shadow-sm">
        @if(session('success'))
            <div class="mb-4 rounded-3xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.distribution.update', $distribusi->id) }}" method="POST" class="grid gap-4">
            @csrf
            <div>
                <label class="text-sm text-slate-500">Status</label>
                <select name="status" class="mt-1 rounded-lg border px-3 py-2">
                    @foreach(['dijadwalkan','dikirim','dalam_perjalanan','diterima'] as $s)
                        <option value="{{ $s }}" {{ $distribusi->status === $s ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-slate-500">Tanggal Dijadwalkan (optional)</label>
                <input type="datetime-local" name="tgl_dijadwalkan" value="{{ optional($distribusi->tgl_dijadwalkan) ? \Carbon\Carbon::parse($distribusi->tgl_dijadwalkan)->format('Y-m-d\TH:i') : '' }}" class="mt-1 rounded-lg border px-3 py-2 w-full">
            </div>

            <div>
                <label class="text-sm text-slate-500">Tanggal Diterima (optional)</label>
                <input type="datetime-local" name="tgl_diterima" value="" class="mt-1 rounded-lg border px-3 py-2 w-full">
            </div>

            <div class="flex justify-end">
                <button class="rounded-full bg-pink-700 px-4 py-2 text-sm font-semibold text-white">Simpan Perubahan</button>
            </div>
        </form>

        <hr class="my-6">

        <div class="grid gap-4">
            <div>
                <span class="text-sm text-slate-500">Metode</span>
                <div>{{ optional($distribusi->metodePengiriman)->nama }}</div>
            </div>
            <div>
                <span class="text-sm text-slate-500">Tanggal Dijadwalkan</span>
                <div>{{ optional($distribusi->tgl_dijadwalkan) ? \Carbon\Carbon::parse($distribusi->tgl_dijadwalkan)->translatedFormat('d M Y H:i') : '-' }}</div>
            </div>
            <div>
                <span class="text-sm text-slate-500">Tanggal Diterima</span>
                <div>{{ optional($distribusi->tgl_diterima) ? \Carbon\Carbon::parse($distribusi->tgl_diterima)->translatedFormat('d M Y H:i') : '-' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
