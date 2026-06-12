<aside class="fixed left-0 top-0 z-40 flex h-screen w-60 flex-col rounded-r-4xl border-r-4 border-pink-200 bg-white">
    {{-- Nama Sistem + Logo --}}
    <div class="flex items-center gap-3 border-b border-pink-200 px-5 py-4">
        {{-- logo --}}
        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-pink-700 p-1">
            <img src="{{ asset('images/logo.svg') }}" alt="Logo">
        </div>
        {{-- nama sistem --}}
        <div>
            <p class="text-lg font-bold text-gray-800">Veny Toys</p>
            <p class="text-sm text-gray-400">Supply Chain</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-3 [&::-webkit-scrollbar]:hidden">
        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Inventory</p>


        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Procurement</p>
        <a href="{{ route('admin.procurement.produksi.index') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md font-semibold text-pink-700">Produksi</span>
        </a>
        <a href="{{ route('admin.procurement.forecast.index') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <span class="text-md font-semibold text-pink-700">Forecast</span>
        </a>

        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Distribution</p>

        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Order & Payment</p>
        <a href="{{ route('admin.pesanan.list') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M9.5 19.5C10.0523 19.5 10.5 19.0523 10.5 18.5C10.5 17.9477 10.0523 17.5 9.5 17.5C8.94772 17.5 8.5 17.9477 8.5 18.5C8.5 19.0523 8.94772 19.5 9.5 19.5ZM9.5 20.5C10.6046 20.5 11.5 19.6046 11.5 18.5C11.5 17.3954 10.6046 16.5 9.5 16.5C8.39543 16.5 7.5 17.3954 7.5 18.5C7.5 19.6046 8.39543 20.5 9.5 20.5Z" fill="#000000"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M16.5 19.5C17.0523 19.5 17.5 19.0523 17.5 18.5C17.5 17.9477 17.0523 17.5 16.5 17.5C15.9477 17.5 15.5 17.9477 15.5 18.5C15.5 19.0523 15.9477 19.5 16.5 19.5ZM16.5 20.5C17.6046 20.5 18.5 19.6046 18.5 18.5C18.5 17.3954 17.6046 16.5 16.5 16.5C15.3954 16.5 14.5 17.3954 14.5 18.5C14.5 19.6046 15.3954 20.5 16.5 20.5Z" fill="#000000"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3 4C3 3.72386 3.22386 3.5 3.5 3.5H5.5C5.71767 3.5 5.91033 3.64082 5.97641 3.84822L9.36993 14.5H17C17.2761 14.5 17.5 14.7239 17.5 15C17.5 15.2761 17.2761 15.5 17 15.5H9.00446C8.78679 15.5 8.59413 15.3592 8.52805 15.1518L5.13453 4.5H3.5C3.22386 4.5 3 4.27614 3 4Z" fill="#000000"/>
                <path d="M8.5 13L6 6H19.3371C19.6693 6 19.9092 6.31795 19.8179 6.63736L18.1036 12.6374C18.0423 12.852 17.8461 13 17.6228 13H8.5Z" fill="#c6005c"/>
            </svg>
            <span class="text-md font-semibold text-pink-700">Daftar Pesanan</span>
        </a>
        <a href="{{ route('admin.invoice.list') }}" class="group relative flex items-center gap-2 px-4 py-2 transition-colors hover:bg-pink-50">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 4C6.67157 4 6 4.67157 6 5.5V18.5C6 19.3284 6.67157 20 7.5 20H16.5C17.3284 20 18 19.3284 18 18.5V5.5C18 4.67157 17.3284 4 16.5 4H7.5ZM14.3536 8.35355C14.5488 8.15829 14.5488 7.84171 14.3536 7.64645C14.1583 7.45118 13.8417 7.45118 13.6464 7.64645L9.64645 11.6464C9.45118 11.8417 9.45118 12.1583 9.64645 12.3536C9.84171 12.5488 10.1583 12.5488 10.3536 12.3536L14.3536 8.35355ZM11.5 8.5C11.5 9.05228 11.0523 9.5 10.5 9.5C9.94772 9.5 9.5 9.05228 9.5 8.5C9.5 7.94772 9.94772 7.5 10.5 7.5C11.0523 7.5 11.5 7.94772 11.5 8.5ZM13.5 12.5C14.0523 12.5 14.5 12.0523 14.5 11.5C14.5 10.9477 14.0523 10.5 13.5 10.5C12.9477 10.5 12.5 10.9477 12.5 11.5C12.5 12.0523 12.9477 12.5 13.5 12.5ZM8.5 15C8.5 14.7239 8.72386 14.5 9 14.5H15C15.2761 14.5 15.5 14.7239 15.5 15C15.5 15.2761 15.2761 15.5 15 15.5H9C8.72386 15.5 8.5 15.2761 8.5 15ZM9 16.5C8.72386 16.5 8.5 16.7239 8.5 17C8.5 17.2761 8.72386 17.5 9 17.5H15C15.2761 17.5 15.5 17.2761 15.5 17C15.5 16.7239 15.2761 16.5 15 16.5H9Z" fill="#c6005c"/>
            </svg>
            <span class="text-md font-semibold text-pink-700">Daftar Invoice</span>
        </a>

        <p class="px-5 py-2 text-sm font-semibold uppercase tracking-widest text-pink-200">Analytic & Report</p>
    </nav>

    <div class="border-t border-pink-200 p-4">
        <a href="{{ route('proses_logout') }}"
            class="flex items-center justify-center gap-2 rounded-xl bg-pink-700 px-4 py-3 font-semibold text-white transition hover:bg-pink-800">

            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                <polyline points="16 17 21 12 16 7"/>
                <line x1="21" y1="12" x2="9" y2="12"/>
            </svg>

            Logout
        </a>
    </div>
</aside>