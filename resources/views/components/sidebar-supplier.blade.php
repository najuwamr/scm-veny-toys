<aside class="fixed left-0 top-0 z-40 flex h-screen w-60 flex-col rounded-r-4xl border-r-4 border-pink-200 bg-white">
    <div class="flex items-center gap-2 border-b border-pink-200 px-4 py-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pink-700 p-1">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo">
        </div>
        <div>
            <p class="text-base font-bold text-gray-800">Veny Toys</p>
            <p class="text-xs text-gray-400">Supplier Portal</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-2 [&::-webkit-scrollbar]:hidden">
        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Dashboard</p>

        <a href="{{ route('supplier.dashboard') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('supplier.dashboard') ? 'bg-pink-50' : '' }}">
            <span class="text-sm font-semibold {{ request()->routeIs('supplier.dashboard') ? 'text-pink-700' : 'text-gray-400' }}">Dashboard</span>
        </a>

        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Bahan & Harga</p>

        <a href="{{ route('supplier.materials.index') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('supplier.materials.index') ? 'bg-pink-50' : '' }}">
            <span class="text-sm font-semibold {{ request()->routeIs('supplier.materials.index') ? 'text-pink-700' : 'text-gray-400' }}">Daftar Bahan</span>
        </a>

        <a href="{{ route('supplier.materials.create') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('supplier.materials.create') ? 'bg-pink-50' : '' }}">
            <span class="text-sm font-semibold {{ request()->routeIs('supplier.materials.create') ? 'text-pink-700' : 'text-gray-400' }}">Tambah Bahan</span>
        </a>

        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Pengadaan</p>

        <a href="{{ route('supplier.index') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('supplier.index') ? 'bg-pink-50' : '' }}">
            <span class="text-sm font-semibold {{ request()->routeIs('supplier.index') ? 'text-pink-700' : 'text-gray-400' }}">Permintaan Masuk</span>
            @php
                $pending = \App\Models\PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) {
                    $query->where('supplier_id', auth()->user()->supplier?->id);
                })->where('status', 'menunggu')->count();
            @endphp
            @if($pending > 0)
                <span class="ml-auto mr-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">{{ $pending }}</span>
            @endif
        </a>

        <a href="{{ route('supplier.tracking') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('supplier.tracking') ? 'bg-pink-50' : '' }}">
            <span class="text-sm font-semibold {{ request()->routeIs('supplier.tracking') ? 'text-pink-700' : 'text-gray-400' }}">Tracking Pengiriman</span>
        </a>
    </nav>

    <div class="border-t border-pink-200 p-3">
        <a href="{{ route('proses_logout') }}"
            class="flex items-center justify-center gap-2 rounded-xl bg-pink-700 px-3 py-2 text-sm font-semibold text-white transition hover:bg-pink-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </a>
    </div>
</aside>
