@extends('layouts.supplier')
@section('title', 'Permintaan Masuk')

@section('content')
<main class="ml-60 flex-1 p-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Permintaan Masuk</h1>
        <p class="text-sm text-gray-400">Daftar permintaan bahan baku yang perlu kamu proses</p>
    </div>

    @if(session('success'))
    <div class="mb-4 rounded-xl bg-green-50 border border-green-200 px-4 py-3 text-sm font-semibold text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm font-semibold text-red-700">{{ session('error') }}</div>
    @endif

    <div class="flex flex-col gap-4">
        @forelse($permintaan as $item)
        @php
            $statusConfig = [
                'menunggu'  => ['bg-yellow-100 text-yellow-700','Menunggu Responmu'],
                'pending'   => ['bg-yellow-100 text-yellow-700','Menunggu Responmu'],
                'disetujui' => ['bg-blue-100 text-blue-700','Disetujui'],
                'approved'  => ['bg-blue-100 text-blue-700','Disetujui'],
                'ditolak'   => ['bg-red-100 text-red-600','Ditolak'],
                'rejected'  => ['bg-red-100 text-red-600','Ditolak'],
                'dikirim'   => ['bg-purple-100 text-purple-700','Sudah Dikirim'],
                'diterima'  => ['bg-green-100 text-green-700','Diterima Admin'],
            ];
            [$cls, $label] = $statusConfig[$item->status] ?? ['bg-gray-100 text-gray-500', ucfirst($item->status)];
        @endphp
        <div class="rounded-2xl bg-white p-6 shadow-sm border border-pink-100">
            <div class="flex items-start justify-between">
                <div>
                    <p class="font-mono text-xs text-gray-400">{{ $item->kode_permintaan }}</p>
                    <p class="mt-1 text-lg font-bold text-gray-800">{{ $item->bahanBaku->nama_bahan }}</p>
                    <p class="text-sm text-gray-400">Dibuat pada: {{ $item->created_at->format('d M Y') }}</p>
                </div>
                <span class="rounded-full px-3 py-1.5 text-xs font-semibold {{ $cls }}">{{ $label }}</span>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-xs text-gray-400">Jumlah Diminta</p>
                    <p class="font-bold text-pink-700 text-lg">{{ number_format($item->jumlah_diminta) }} <span class="text-sm font-normal text-gray-400">{{ $item->bahanBaku->satuan }}</span></p>
                </div>
                @if($item->catatan_admin)
                <div class="rounded-xl bg-gray-50 p-3">
                    <p class="text-xs text-gray-400">Catatan Admin</p>
                    <p class="text-sm text-gray-700">{{ $item->catatan_admin }}</p>
                </div>
                @endif
            </div>

            {{-- Aksi berdasarkan status --}}
            @if(in_array($item->status, ['menunggu', 'pending']))
            <div class="mt-4 flex gap-3">
                {{-- Approve --}}
                <form action="{{ route('supplier.approve', $item) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="catatan_supplier" value="">
                    <button type="submit" class="w-full rounded-xl bg-green-600 py-2.5 text-sm font-bold text-white hover:bg-green-700">✓ Setujui</button>
                </form>
                {{-- Reject --}}
                <button onclick="document.getElementById('modal-reject-{{ $item->id }}').classList.remove('hidden')"
                    class="flex-1 rounded-xl border border-red-200 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50">✕ Tolak</button>
            </div>
            @endif

            @if(in_array($item->status, ['disetujui', 'approved']))
            <div class="mt-4">
                <button onclick="document.getElementById('modal-kirim-{{ $item->id }}').classList.remove('hidden')"
                    class="w-full rounded-xl bg-purple-600 py-2.5 text-sm font-bold text-white hover:bg-purple-700">
                    🚚 Input Data Pengiriman
                </button>
            </div>
            @endif

            @if($item->catatan_supplier && !in_array($item->status, ['menunggu', 'pending']))
            <div class="mt-4 rounded-xl bg-blue-50 p-3">
                <p class="text-xs font-semibold text-blue-400">CATATANMU</p>
                <p class="text-sm text-gray-700">{{ $item->catatan_supplier }}</p>
            </div>
            @endif
        </div>

        {{-- Modal Reject --}}
        <div id="modal-reject-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="mb-4 text-lg font-bold text-gray-800">Tolak Permintaan</h2>
                <form action="{{ route('supplier.reject', $item) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Alasan penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_supplier" rows="3" required
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="(function(el){el.classList.add('hidden');el.classList.remove('flex');})(document.getElementById('modal-reject-{{ $item->id }}'))"
                            class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="flex-1 rounded-xl bg-red-500 py-2.5 text-sm font-bold text-white hover:bg-red-600">Tolak</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Input Pengiriman --}}
        <div id="modal-kirim-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
                <h2 class="mb-4 text-lg font-bold text-gray-800">Input Data Pengiriman</h2>
                <form action="{{ route('supplier.kirim', $item) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Tanggal Kirim</label>
                            <input type="date" name="tanggal_kirim" required value="{{ date('Y-m-d') }}"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-semibold text-gray-700">Est. Tiba</label>
                            <input type="date" name="estimasi_tiba"
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none">
                        </div>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Ekspedisi</label>
                        <input type="text" name="ekspedisi" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="JNE, Sicepat, dll">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">No. Resi</label>
                        <input type="text" name="no_resi" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none" placeholder="Nomor resi pengiriman">
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-gray-700">Catatan</label>
                        <textarea name="catatan" rows="2" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-pink-400 focus:outline-none"></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="(function(el){el.classList.add('hidden');el.classList.remove('flex');})(document.getElementById('modal-kirim-{{ $item->id }}'))"
                            class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-semibold text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="flex-1 rounded-xl bg-purple-600 py-2.5 text-sm font-bold text-white hover:bg-purple-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        @empty
        <div class="flex flex-col items-center justify-center rounded-2xl bg-white py-20 shadow-sm border border-pink-100">
            <p class="text-lg font-bold text-gray-700">Tidak ada permintaan</p>
            <p class="mt-1 text-sm text-gray-400">Belum ada permintaan bahan baku yang masuk</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $permintaan->links() }}</div>

</main>
@endsection
