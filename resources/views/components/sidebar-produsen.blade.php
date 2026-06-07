<aside class="fixed left-0 top-0 z-40 flex h-screen w-60 flex-col rounded-r-4xl border-r-4 border-pink-200 bg-white">
    <div class="flex items-center gap-3 border-b border-pink-200 px-5 py-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-pink-700">
        </div>
        <div>
            <p class="text-lg font-bold text-gray-800">Veny Toys</p>
            <p class="text-sm text-gray-400">Produsen</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 [&::-webkit-scrollbar]:hidden">
        <a href="{{ route('produsen.dashboard') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md text-gray-500">Dashboard</span>
        </a>
        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Inventory</p>
        <a href="{{ route('produsen.inventory.bahan-baku.index') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md text-gray-500">Bahan Baku</span>
        </a>
        <a href="{{ route('produsen.inventory.produk.index') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md text-gray-500">Produk Jadi</span>
        </a>
        <a href="{{ route('produsen.inventory.mutasi.create') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md text-gray-500">Mutasi Stok</span>
        </a>

        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Pengadaan</p>
        <a href="{{ route('produsen.procurement.index') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md text-gray-500">Permintaan Bahan</span>
        </a>

        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Produksi</p>
        <a href="{{ route('produsen.produksi.index') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md text-gray-500">Rencana & Realisasi</span>
        </a>
        <a href="{{ route('produsen.forecast.index') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md text-gray-500">Forecast</span>
        </a>
    </nav>

    <div class="border-t border-pink-200 px-4 py-4 space-y-3">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nama }}</p>
                <p class="text-xs text-gray-500">Produsen</p>
            </div>
        </div>
        <a href="{{ route('proses_logout') }}" class="flex w-full items-center justify-center gap-2 rounded-full bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 transition-colors">
            Logout
        </a>
    </div>
</aside>
