<aside class="fixed left-0 top-0 z-40 flex h-screen w-60 flex-col rounded-r-4xl border-r-4 border-pink-200 bg-white">
    {{-- Nama Sistem + Logo --}}
    <div class="flex items-center gap-3 border-b border-pink-200 px-5 py-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-pink-700 p-1">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo">
        </div>
        <div>
            <p class="text-lg font-bold text-gray-800">Veny Toys</p>
            <p class="text-sm text-gray-400">Supplier Portal</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 [&::-webkit-scrollbar]:hidden">

        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Procurement</p>

        <a href="{{ route('supplier.index') }}"
            class="group flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50 {{ request()->routeIs('supplier.index') ? 'bg-pink-50' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M4 4C4 2.89543 4.89543 2 6 2H14.5858C15.1162 2 15.6249 2.21071 16 2.58579L19.4142 6C19.7893 6.37507 20 6.88378 20 7.41421V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4ZM8 11C8 10.4477 8.44772 10 9 10H15C15.5523 10 16 10.4477 16 11C16 11.5523 15.5523 12 15 12H9C8.44772 12 8 11.5523 8 11ZM9 14C8.44772 14 8 14.4477 8 15C8 15.5523 8.44772 16 9 16H15C15.5523 16 16 15.5523 16 15C16 14.4477 15.5523 14 15 14H9Z" fill="{{ request()->routeIs('supplier.index') ? '#c6005c' : '#d1d5db' }}"/>
            </svg>
            <span class="text-md font-semibold {{ request()->routeIs('supplier.index') ? 'text-pink-700' : 'text-gray-400' }}">Permintaan Masuk</span>
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
            class="group flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50 {{ request()->routeIs('supplier.tracking') ? 'bg-pink-50' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 6C3 5.44772 3.44772 5 4 5H20C20.5523 5 21 5.44772 21 6C21 6.55228 20.5523 7 20 7H4C3.44772 7 3 6.55228 3 6ZM3 12C3 11.4477 3.44772 11 4 11H14C14.5523 11 15 11.4477 15 12C15 12.5523 14.5523 13 14 13H4C3.44772 13 3 12.5523 3 12ZM4 17C3.44772 17 3 17.4477 3 18C3 18.5523 3.44772 19 4 19H9C9.55228 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17H4ZM18 14C18.5523 14 19 14.4477 19 15V17H21C21.5523 17 22 17.4477 22 18C22 18.5523 21.5523 19 21 19H19V21C19 21.5523 18.5523 22 18 22C17.4477 22 17 21.5523 17 21V19H15C14.4477 19 14 18.5523 14 18C14 17.4477 14.4477 17 15 17H17V15C17 14.4477 17.4477 14 18 14Z" fill="{{ request()->routeIs('supplier.tracking') ? '#c6005c' : '#d1d5db' }}"/>
            </svg>
            <span class="text-md font-semibold {{ request()->routeIs('supplier.tracking') ? 'text-pink-700' : 'text-gray-400' }}">Tracking Pengiriman</span>
        </a>

    </nav>

    <div class="border-t border-pink-200 p-4">
        {{-- Info user --}}
        <div class="mb-3 flex items-center gap-2 px-1">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-100 text-sm font-bold text-pink-700">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400">Supplier</p>
            </div>
        </div>
        <a href="{{ route('proses_logout') }}"
            class="flex items-center justify-center gap-2 rounded-xl bg-pink-700 px-4 py-3 font-semibold text-white transition hover:bg-pink-800">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Logout
        </a>
    </div>
</aside>
