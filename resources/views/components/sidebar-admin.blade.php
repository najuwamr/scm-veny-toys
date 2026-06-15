<aside class="fixed left-0 top-0 z-40 flex h-screen w-60 flex-col rounded-r-4xl border-r-4 border-pink-200 bg-white">
    {{-- Nama Sistem + Logo --}}
    <div class="flex items-center gap-2 border-b border-pink-200 px-4 py-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-pink-700 p-1">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo">
        </div>
        <div>
            <p class="text-base font-bold text-gray-800">Veny Toys</p>
            <p class="text-xs text-gray-400">Supply Chain</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-2 [&::-webkit-scrollbar]:hidden">

        {{-- INVENTORY --}}
        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Inventory</p>

        <a href="{{ route('admin.inventory.dashboard') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.inventory.dashboard') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="8" height="8" rx="1.5" fill="{{ request()->routeIs('admin.inventory.dashboard') ? '#c6005c' : '#d1d5db' }}"/><rect x="13" y="3" width="8" height="8" rx="1.5" fill="{{ request()->routeIs('admin.inventory.dashboard') ? '#c6005c' : '#d1d5db' }}"/><rect x="3" y="13" width="8" height="8" rx="1.5" fill="{{ request()->routeIs('admin.inventory.dashboard') ? '#c6005c' : '#d1d5db' }}"/><rect x="13" y="13" width="8" height="8" rx="1.5" fill="{{ request()->routeIs('admin.inventory.dashboard') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.inventory.dashboard') ? 'text-pink-700' : 'text-gray-400' }}">Dashboard Inventory</span>
        </a>

        <a href="{{ route('admin.inventory.produk') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.inventory.produk*') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 6H20V18H4V6Z" fill="{{ request()->routeIs('admin.inventory.produk*') ? '#c6005c' : '#d1d5db' }}"/><path d="M7 9H17V11H7V9Z" fill="white"/><path d="M7 13H13V15H7V13Z" fill="white"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.inventory.produk*') ? 'text-pink-700' : 'text-gray-400' }}">Manajemen Produk</span>
        </a>

        <a href="{{ route('admin.inventory.bahan') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.inventory.bahan*') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 5H20V19H4V5ZM5 6V18H19V6H5Z" fill="{{ request()->routeIs('admin.inventory.bahan*') ? '#c6005c' : '#d1d5db' }}"/><path d="M7 9H17V11H7V9Z" fill="{{ request()->routeIs('admin.inventory.bahan*') ? '#c6005c' : '#d1d5db' }}"/><path d="M7 13H13V15H7V13Z" fill="{{ request()->routeIs('admin.inventory.bahan*') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.inventory.bahan*') ? 'text-pink-700' : 'text-gray-400' }}">Manajemen Bahan</span>
        </a>

        <a href="{{ route('admin.inventory.notifikasi') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.inventory.notifikasi') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C12.5523 2 13 2.44772 13 3V4.06189C15.8377 4.55399 18 7.02699 18 10V15L20 17H4L6 15V10C6 7.02699 8.16229 4.55399 11 4.06189V3C11 2.44772 11.4477 2 12 2ZM10 19C10 20.1046 10.8954 21 12 21C13.1046 21 14 20.1046 14 19H10Z" fill="{{ request()->routeIs('admin.inventory.notifikasi') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.inventory.notifikasi') ? 'text-pink-700' : 'text-gray-400' }}">Notifikasi Stok</span>
            @php $kritis = \App\Models\BahanBaku::whereColumn('stok_saat_ini','<=','stok_minimum')->count(); @endphp
            @if($kritis > 0)
                <span class="ml-auto mr-2 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">{{ $kritis }}</span>
            @endif
        </a>

        {{-- PROCUREMENT --}}
        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Procurement</p>

        <a href="{{ route('admin.procurement.index') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.procurement.index') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M4 4C4 2.89543 4.89543 2 6 2H14.5858C15.1162 2 15.6249 2.21071 16 2.58579L19.4142 6C19.7893 6.37507 20 6.88378 20 7.41421V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4ZM8 11C8 10.4477 8.44772 10 9 10H15C15.5523 10 16 10.4477 16 11C16 11.5523 15.5523 12 15 12H9C8.44772 12 8 11.5523 8 11ZM9 14C8.44772 14 8 14.4477 8 15C8 15.5523 8.44772 16 9 16H15C15.5523 16 16 15.5523 16 15C16 14.4477 15.5523 14 15 14H9Z" fill="{{ request()->routeIs('admin.procurement.index') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.procurement.index') ? 'text-pink-700' : 'text-gray-400' }}">Daftar Permintaan</span>
        </a>

        <a href="{{ route('admin.procurement.tracking') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.procurement.tracking') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 6H21V8H3V6Z" fill="{{ request()->routeIs('admin.procurement.tracking') ? '#c6005c' : '#d1d5db' }}"/><path d="M3 11H21V13H3V11Z" fill="{{ request()->routeIs('admin.procurement.tracking') ? '#c6005c' : '#d1d5db' }}"/><path d="M3 16H15V18H3V16Z" fill="{{ request()->routeIs('admin.procurement.tracking') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.procurement.tracking') ? 'text-pink-700' : 'text-gray-400' }}">Tracking Pengiriman</span>
        </a>

        {{-- PRODUCTION --}}
        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Production</p>

        <a href="{{ route('admin.procurement.produksi.index') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.procurement.produksi.*') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M3 3h7v7H3zM14 3h7v7h-7zM3 14h7v7H3zM14 14h7v7h-7z" fill="{{ request()->routeIs('admin.procurement.produksi.*') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.procurement.produksi.*') ? 'text-pink-700' : 'text-gray-400' }}">Perencanaan Produksi</span>
        </a>

        <a href="{{ route('admin.procurement.forecast.index') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.procurement.forecast.*') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 17h16M6 13l4-4 3 5 4-7" stroke="{{ request()->routeIs('admin.procurement.forecast.*') ? '#c6005c' : '#d1d5db' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.procurement.forecast.*') ? 'text-pink-700' : 'text-gray-400' }}">Forecasting Produksi</span>
        </a>

        {{-- ORDER & PAYMENT --}}
        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Order & Payment</p>

        <a href="{{ route('admin.pesanan.list') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.pesanan.*') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3 4C3 3.72386 3.22386 3.5 3.5 3.5H5.5C5.71767 3.5 5.91033 3.64082 5.97641 3.84822L9.36993 14.5H17C17.2761 14.5 17.5 14.7239 17.5 15C17.5 15.2761 17.2761 15.5 17 15.5H9.00446C8.78679 15.5 8.59413 15.3592 8.52805 15.1518L5.13453 4.5H3.5C3.22386 4.5 3 4.27614 3 4Z" fill="{{ request()->routeIs('admin.pesanan.*') ? '#c6005c' : '#d1d5db' }}"/><path d="M8.5 13L6 6H19.3371C19.6693 6 19.9092 6.31795 19.8179 6.63736L18.1036 12.6374C18.0423 12.852 17.8461 13 17.6228 13H8.5Z" fill="{{ request()->routeIs('admin.pesanan.*') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.pesanan.*') ? 'text-pink-700' : 'text-gray-400' }}">Daftar Pesanan</span>
        </a>

        <a href="{{ route('admin.invoice.list') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.invoice.*') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 4C6.67157 4 6 4.67157 6 5.5V18.5C6 19.3284 6.67157 20 7.5 20H16.5C17.3284 20 18 19.3284 18 18.5V5.5C18 4.67157 17.3284 4 16.5 4H7.5ZM14.3536 8.35355C14.5488 8.15829 14.5488 7.84171 14.3536 7.64645C14.1583 7.45118 13.8417 7.45118 13.6464 7.64645L9.64645 11.6464C9.45118 11.8417 9.45118 12.1583 9.64645 12.3536C9.84171 12.5488 10.1583 12.5488 10.3536 12.3536L14.3536 8.35355ZM11.5 8.5C11.5 9.05228 11.0523 9.5 10.5 9.5C9.94772 9.5 9.5 9.05228 9.5 8.5C9.5 7.94772 9.94772 7.5 10.5 7.5C11.0523 7.5 11.5 7.94772 11.5 8.5ZM13.5 12.5C14.0523 12.5 14.5 12.0523 14.5 11.5C14.5 10.9477 14.0523 10.5 13.5 10.5C12.9477 10.5 12.5 10.9477 12.5 11.5C12.5 12.0523 12.9477 12.5 13.5 12.5ZM8.5 15C8.5 14.7239 8.72386 14.5 9 14.5H15C15.2761 14.5 15.5 14.7239 15.5 15C15.5 15.2761 15.2761 15.5 15 15.5H9C8.72386 15.5 8.5 15.2761 8.5 15ZM9 16.5C8.72386 16.5 8.5 16.7239 8.5 17C8.5 17.2761 8.72386 17.5 9 17.5H15C15.2761 17.5 15.5 17.2761 15.5 17C15.5 16.7239 15.2761 16.5 15 16.5H9Z" fill="{{ request()->routeIs('admin.invoice.*') ? '#c6005c' : '#d1d5db' }}"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.invoice.*') ? 'text-pink-700' : 'text-gray-400' }}">Daftar Invoice</span>
        </a>

        {{-- REPORTING & MONITORING --}}
        <p class="px-4 py-1.5 text-[11px] font-semibold uppercase tracking-widest text-pink-200">Reporting</p>

        <a href="{{ route('admin.reporting.dashboard') }}"
            class="group flex items-center gap-2 px-3.5 py-1.5 transition-colors hover:bg-pink-50 {{ request()->routeIs('admin.reporting.dashboard') ? 'bg-pink-50' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M4 17h16" stroke="{{ request()->routeIs('admin.reporting.dashboard') ? '#c6005c' : '#d1d5db' }}" stroke-width="2" stroke-linecap="round"/><path d="M6 13l4-4 3 5 4-7" stroke="{{ request()->routeIs('admin.reporting.dashboard') ? '#c6005c' : '#d1d5db' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span class="text-sm font-semibold {{ request()->routeIs('admin.reporting.dashboard') ? 'text-pink-700' : 'text-gray-400' }}">Dashboard Laporan</span>
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
