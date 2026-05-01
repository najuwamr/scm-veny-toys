<aside class="fixed left-0 top-0 z-40 flex h-screen w-60 flex-col rounded-r-4xl border-r-4 border-pink-200 bg-white">
    {{-- Nama Sistem + Logo --}}
    <div class="flex items-center gap-3 border-b border-pink-200 px-5 py-4">
        {{-- logo --}}
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-pink-700">
        </div>
        {{-- nama sistem --}}
        <div>
            <p class="text-lg font-bold text-gray-800">Veny Toys</p>
            <p class="text-sm text-gray-400">Supply Chain</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 [&::-webkit-scrollbar]:hidden">
        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Inventory</p>

        <a href="#" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50 bg-pink-50">
            <span class="absolute left-0 top-1.5 bottom-1.5 w-0.5 rounded-r bg-pink-700"></span>
            <svg class="h-6 w-6 shrink-0 fill-pink-700" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <span class="text-md font-medium text-pink-700">Dashboard Stok</span>
        </a>

        <a href="#" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-6 w-6 shrink-0 fill-pink-700" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <span class="text-md text-gray-500">Mutasi Stok</span>
        </a>

        <a href="#" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-6 w-6 shrink-0 fill-pink-700" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <span class="text-md text-gray-500">Notifikasi Stok</span>
            <span class="ml-auto rounded-full bg-red-50 px-1.5 py-0.5 text-[10px] font-semibold text-red-500">3</span>
        </a>

        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Procurement</p>
        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Production</p>
        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Distribution</p>
        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Order & Payment</p>
        <a href="{{ route('admin.pesanan.list') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-6 w-6 shrink-0 fill-pink-700" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <span class="text-md text-gray-500">List Pesanan</span>
        </a>
        <a href="{{ route('admin.invoice.list') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-6 w-6 shrink-0 fill-pink-700" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <span class="text-md text-gray-500">List Invoice</span>
        </a>
        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Analytic & Report</p>
    </nav>
</aside>
