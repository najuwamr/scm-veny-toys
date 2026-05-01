{{-- resources/views/components/sidebar.blade.php --}}
{{-- Panggil dengan: <x-sidebar /> --}}
{{-- Tambahkan ml-60 di wrapper konten utama --}}

<aside class="fixed left-0 top-0 z-40 flex h-screen w-60 flex-col border-r border-pink-50 bg-white">

    {{-- Brand --}}
    <div class="flex items-center gap-3 border-b border-pink-100 px-5 py-4">
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-[#c4487a]">
        </div>
        <div class="leading-tight">
            <p class="text-lg font-bold text-gray-800">Veny Toys</p>
            <p class="text-sm text-gray-400">Supply Chain</p>
        </div>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-3 [&::-webkit-scrollbar]:hidden">

        {{-- ── STOK ── --}}
        <p class="px-5 pb-1 pt-2 text-sm font-bold uppercase tracking-widest text-pink-200">Stok</p>

        <a href="#" class="group relative flex items-center gap-sm px-5 py-2 transition-colors hover:bg-pink-50 bg-pink-50">
            <span class="absolute left-0 top-[6px] bottom-[6px] w-[3px] rounded-r bg-[#c4487a]"></span>
            <svg class="h-8 w-8 flex-shrink-0 fill-[#c4487a]" viewBox="0 0 24 24">
                <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
            </svg>
            <span class="text-md font-medium text-[#c4487a]">Manajemen Stok</span>
        </a>

        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] shrink-0 fill-none stroke-gray-300" viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M7 11H17M7 15H17M5 3h14a2 2 0 012 2v16a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Mutasi Stok</span>
        </a>

        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] shrink-0 fill-gray-300" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Notifikasi Tipis</span>
            <span class="ml-auto rounded-full bg-red-50 px-[6px] py-[2px] text-[10px] font-semibold text-red-500">3</span>
        </a>

        <div class="mx-4 my-2 h-px bg-pink-50"></div>

        {{-- ── OPERASIONAL ── --}}
        <p class="px-5 pb-1 pt-2 text-[10px] font-semibold uppercase tracking-widest text-pink-200">Operasional</p>

        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] shrink-0 fill-gray-300" viewBox="0 0 24 24">
                <path d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Procurement</span>
            <span class="ml-auto rounded-full bg-amber-50 px-[6px] py-[2px] text-[10px] font-semibold text-amber-600">2</span>
        </a>

        {{-- Produksi + sub --}}
        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] flex-shrink-0 fill-gray-300" viewBox="0 0 24 24">
                <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.49.49 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54A.484.484 0 0012.41 7H8.57c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96a.49.49 0 00-.59.22L1.22 13.47a.49.49 0 00.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32a.49.49 0 00-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Produksi</span>
        </a>
        <a href="#" class="flex items-center py-[7px] pl-[46px] pr-5 text-[12.5px] text-gray-400 transition-colors hover:bg-pink-50">Perencanaan</a>
        <a href="#" class="flex items-center py-[7px] pl-[46px] pr-5 text-[12.5px] text-gray-400 transition-colors hover:bg-pink-50">Forecasting</a>

        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] flex-shrink-0 fill-gray-300" viewBox="0 0 24 24">
                <path d="M20 8h-3V4H3c-1.1 0-2 .9-2 2v11h2c0 1.66 1.34 3 3 3s3-1.34 3-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3h2v-5l-3-4zm-1.5 1.5l1.96 2.5H17V9.5h1.5zM6 18.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm13-1.5c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Distribusi</span>
            <span class="ml-auto rounded-full bg-blue-50 px-[6px] py-[2px] text-[10px] font-semibold text-blue-500">5</span>
        </a>

        <div class="mx-4 my-2 h-px bg-pink-50"></div>

        {{-- ── KOMERSIAL ── --}}
        <p class="px-5 pb-1 pt-2 text-[10px] font-semibold uppercase tracking-widest text-pink-200">Komersial</p>

        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] flex-shrink-0 fill-gray-300" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 3c1.93 0 3.5 1.57 3.5 3.5S13.93 13 12 13s-3.5-1.57-3.5-3.5S10.07 6 12 6zm7 13H5v-.23c0-.62.28-1.2.76-1.58C7.47 15.82 9.64 15 12 15s4.53.82 6.24 2.19c.48.38.76.97.76 1.58V19z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Order</span>
            <span class="ml-auto rounded-full bg-green-50 px-[6px] py-[2px] text-[10px] font-semibold text-green-600">12</span>
        </a>

        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] flex-shrink-0 fill-gray-300" viewBox="0 0 24 24">
                <path d="M20 4H4c-1.11 0-2 .89-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Pembayaran</span>
            <span class="ml-auto rounded-full bg-amber-50 px-[6px] py-[2px] text-[10px] font-semibold text-amber-600">4</span>
        </a>

        <div class="mx-4 my-2 h-px bg-pink-50"></div>

        {{-- ── ANALITIK ── --}}
        <p class="px-5 pb-1 pt-2 text-[10px] font-semibold uppercase tracking-widest text-pink-200">Analitik</p>

        <a href="#" class="group relative flex items-center gap-[10px] px-5 py-2 transition-colors hover:bg-pink-50">
            <svg class="h-[15px] w-[15px] flex-shrink-0 fill-gray-300" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
            </svg>
            <span class="text-[13px] text-gray-500">Laporan</span>
        </a>

    </nav>
</aside>
