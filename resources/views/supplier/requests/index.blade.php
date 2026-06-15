@extends('layouts.supplier')

@section('title', 'Permintaan Masuk')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Permintaan Masuk</h1>
            <p class="text-sm text-slate-500">Kelola permintaan pengadaan bahan baku dari produsen.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-3xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-3xl bg-white shadow-sm">
        <table class="min-w-full border-separate border-spacing-0 text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-[0.12em] text-slate-500">
                <tr>
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">Bahan Baku</th>
                    <th class="px-6 py-4">Jumlah</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($requests as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4 text-slate-600">{{ $item->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-900">{{ $item->bahanBakuSupplier->bahanBaku->nama_bahan ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ number_format($item->jumlah, 2, ',', '.') }} {{ $item->bahanBakuSupplier->bahanBaku->satuan ?? '' }}</td>
                        <td class="px-6 py-4 text-slate-600">Rp {{ number_format(optional($item->bahanBakuSupplier)->harga ?? 0, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $item->status === 'selesai' ? 'bg-emerald-100 text-emerald-700' : ($item->status === 'disetujui' ? 'bg-sky-100 text-sky-700' : ($item->status === 'ditolak' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700')) }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <div class="flex flex-wrap gap-2">
                                @if($item->status === 'menunggu')
                                    <form method="post" action="{{ route('supplier.requests.approve', $item->id) }}">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 hover:bg-emerald-200">Setujui</button>
                                    </form>
                                    <form method="post" action="{{ route('supplier.requests.reject', $item->id) }}">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700 hover:bg-rose-200">Tolak</button>
                                    </form>
                                @elseif($item->status === 'disetujui')
                                    <form method="post" action="{{ route('supplier.requests.complete', $item->id) }}">
                                        @csrf
                                        <button type="submit" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200">Tandai Selesai</button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-500">Tidak ada aksi</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada permintaan pengadaan untuk supplier ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
