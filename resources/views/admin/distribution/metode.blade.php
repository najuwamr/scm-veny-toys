@extends('layouts.admin')

@section('title', 'Metode Pengiriman')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Manajemen Metode Pengiriman</h1>
            <p class="text-sm text-slate-500">Tambah, ubah, atau hapus metode pengiriman.</p>
        </div>
        <div>
            <a href="{{ route('admin.distribution.index') }}" class="inline-flex items-center rounded-full bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white">Kembali</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-3xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6 rounded-3xl bg-white p-6 shadow-sm">
        <form action="{{ route('admin.distribution.metode.store') }}" method="POST" class="flex items-center gap-3">
            @csrf
            <select name="nama" class="rounded-lg border px-3 py-2">
                <option value="pick up">Pick Up</option>
                <option value="pesan antar">Pesan Antar</option>
                <option value="ekspedisi">Ekspedisi</option>
            </select>
            <button class="rounded-full bg-pink-700 px-4 py-2 text-sm font-semibold text-white">Tambah</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full border-separate border-spacing-0 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($metodes as $m)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ ucfirst($m->nama) }}</td>
                        <td class="px-6 py-4">-</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
