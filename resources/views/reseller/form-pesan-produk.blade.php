@extends('layouts.reseller')

@section('title', 'Form Pesan Produk')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Form Pesan Produk</h1>
            <p class="text-sm text-slate-500">Pilih produk, isi jumlah, dan ajukan pesanan ke tim supply chain.</p>
        </div>
        <a href="{{ route('reseller.pesanan.list') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">
            Kembali ke Pesanan Saya
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-3xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-3xl bg-white p-6 shadow-sm">
        <form action="{{ route('reseller.pesanan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Produk</label>
                    <select id="produk_id" name="produk_id" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none">
                        <option value="">Pilih produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->harga }}">
                                {{ $product->nama }} - Rp {{ number_format($product->harga, 0, ',', '.') }} @if($product->stok_saat_ini !== null)(Stok: {{ $product->stok_saat_ini }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Jumlah</label>
                    <input id="jumlah" type="number" name="jumlah" min="1" value="1" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" />
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan</label>
                <textarea name="catatan" rows="4" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-slate-400 focus:outline-none" placeholder="Contoh: minta dikirim setelah stok tersedia atau warna tertentu."></textarea>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-700">
                <p class="font-semibold text-slate-900">Perkiraan Total</p>
                <p class="mt-2 text-lg font-semibold text-slate-900" id="totalHarga">Rp 0</p>
                <p class="mt-2 text-slate-500">Total akan dihitung berdasarkan pilihan produk dan jumlah. Harga final akan dicatat pada saat pemesanan.</p>
            </div>

            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-pink-700 px-5 py-3 text-sm font-semibold text-white hover:bg-pink-800">
                Ajukan Pesanan
            </button>
        </form>
    </div>
</div>

<script>
    const productSelect = document.getElementById('produk_id');
    const quantityInput = document.getElementById('jumlah');
    const totalHarga = document.getElementById('totalHarga');

    function updateTotalHarga() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const price = Number(selectedOption.dataset.price || 0);
        const quantity = Number(quantityInput.value || 0);
        const total = price * quantity;
        const formatter = new Intl.NumberFormat('id-ID');
        totalHarga.textContent = total > 0 ? 'Rp ' + formatter.format(total) : 'Rp 0';
    }

    productSelect.addEventListener('change', updateTotalHarga);
    quantityInput.addEventListener('input', updateTotalHarga);
    updateTotalHarga();
</script>
@endsection
