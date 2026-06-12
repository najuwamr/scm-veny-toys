@extends('layouts.admin')

@section('title', 'Peramalan Produksi')

@section('content')
<div class="ml-60 flex-1 p-8">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Peramalan Produksi</h1>
            <p class="text-sm text-slate-500">Input data historis, hitung forecast otomatis, dan lihat rekomendasi produksi untuk kategori Boneka Domba dan Reseller Shopee.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            @if($selectedResult)
                <a href="{{ route('admin.procurement.forecast.exportPdf', ['result_id' => $selectedResult->id]) }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Download PDF
                </a>
                <a href="{{ route('admin.procurement.forecast.exportExcel', ['result_id' => $selectedResult->id]) }}" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                    Download Excel
                </a>
            @endif
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-[1.75fr_1fr]">
        <div class="space-y-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Pengaturan Forecast</h2>
                <div class="grid gap-4 lg:grid-cols-3">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Kategori Forecast</label>
                        <select id="forecast-category" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none">
                            <option value="domba">Boneka Domba</option>
                            <option value="shopee">Reseller Shopee</option>
                        </select>
                    </div>
                    <div id="sku-wrapper" class="hidden">
                        <label class="mb-2 block text-sm font-medium text-slate-700">SKU Reseller Shopee</label>
                        <select id="forecast-sku" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none"></select>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Produk Pilihan</label>
                        <p id="selected-product-name" class="rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700"></p>
                    </div>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-2">
                    <form action="{{ route('admin.procurement.forecast.store') }}" method="post" class="space-y-4 rounded-3xl bg-slate-50 p-6">
                        @csrf
                        <input type="hidden" name="forecast_product_id" id="store-product-id" class="forecast-product-id" value="{{ $selectedProduct?->id }}">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Periode Historis</label>
                            <input type="date" name="period" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" required>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-700">Jumlah Aktual</label>
                            <input type="number" name="actual_qty" min="0" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Jumlah aktual" required>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-slate-700">Catatan</label>
                            <textarea name="notes" rows="3" class="w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-pink-500 focus:outline-none" placeholder="Catatan tambahan (opsional)"></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center rounded-full bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">
                            Simpan Data Historis
                        </button>
                    </form>

                    <form action="{{ route('admin.procurement.forecast.calculate') }}" method="post" class="space-y-4 rounded-3xl bg-slate-50 p-6">
                        @csrf
                        <input type="hidden" name="forecast_product_id" id="calculate-product-id" class="forecast-product-id" value="{{ $selectedProduct?->id }}">
                        <div>
                            <p class="text-sm text-slate-500">Gunakan data historis tersimpan untuk melakukan forecast.</p>
                        </div>
                        <button type="submit" class="inline-flex items-center rounded-full bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700">
                            Hitung Forecast
                        </button>
                    </form>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Data Historis</h2>
                        <p class="text-sm text-slate-500">Lihat seluruh periode yang sudah disimpan untuk produk yang dipilih.</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-slate-600">
                        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Periode</th>
                                <th class="px-4 py-3">Aktual Qty</th>
                                <th class="px-4 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historicalData as $item)
                                <tr class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($item['period'])->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ number_format($item['actual_qty'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">-</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-12 text-center text-slate-500">Belum ada data historis untuk produk ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Grafik Aktual vs Forecast</h2>
                <canvas id="forecastChart" class="h-80 w-full"></canvas>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <h2 class="mb-3 text-lg font-semibold text-slate-900">Ringkasan Produk</h2>
                @if($selectedProduct)
                    <div class="space-y-3">
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Produk</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $selectedProduct->name }}</p>
                        </div>
                        <div class="grid gap-3">
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Kategori</p>
                                <p class="mt-2 text-slate-900">{{ ucfirst($selectedProduct->category) }}</p>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Metode Forecast</p>
                                <p class="mt-2 text-slate-900">{{ strtoupper($selectedProduct->forecast_method) }}</p>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-4">
                                <p class="text-sm text-slate-500">Granularitas</p>
                                <p class="mt-2 text-slate-900">{{ ucfirst($selectedProduct->granularity) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-slate-500">Tidak ada produk forecast yang tersedia.</p>
                @endif
            </div>

            @if($selectedResult)
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="mb-3 text-lg font-semibold text-slate-900">Hasil Forecast Terbaru</h2>
                    <div class="space-y-3">
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Forecast Periode</p>
                            <p class="mt-2 text-slate-900">{{ \Carbon\Carbon::parse($selectedResult->forecast_period)->format('Y-m-d') }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">Forecast Qty</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900">{{ number_format($selectedResult->forecast_qty, 0, ',', '.') }} pcs</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">MAD</p>
                            <p class="mt-2 text-slate-900">{{ number_format($selectedResult->mad, 4, ',', '.') }}</p>
                        </div>
                        <div class="rounded-3xl bg-slate-50 p-4">
                            <p class="text-sm text-slate-500">MAPE</p>
                            <p class="mt-2 text-slate-900">{{ number_format($selectedResult->mape, 4, ',', '.') }}%</p>
                        </div>
                        <div class="rounded-3xl bg-rose-50 p-4 border border-rose-100">
                            <p class="text-sm text-rose-700 font-semibold">ℹ️ Rincian Rekomendasi</p>
                            <div class="mt-3 text-xs text-slate-600 space-y-2 leading-relaxed">
                                @if($historicalData && $historicalData->isNotEmpty())
                                    @php
                                        $firstHistory = \Carbon\Carbon::parse($historicalData->first()['period']);
                                        $lastHistory = \Carbon\Carbon::parse($historicalData->last()['period']);
                                        $targetPeriod = \Carbon\Carbon::parse($selectedResult->forecast_period);
                                        
                                        $isMonthly = $selectedProduct->granularity === 'monthly';
                                        $historyFormat = $isMonthly ? 'F Y' : 'd M Y';
                                        $targetFormat = $isMonthly ? 'F Y' : 'd M Y';
                                    @endphp
                                    <p>📅 <strong>Rentang Historis:</strong> Dihitung berdasarkan data historis dari 
                                       <span class="font-semibold text-slate-900">{{ $firstHistory->translatedFormat($historyFormat) }}</span> 
                                       s/d 
                                       <span class="font-semibold text-slate-900">{{ $lastHistory->translatedFormat($historyFormat) }}</span>.
                                    </p>
                                    <p>🚀 <strong>Target Produksi:</strong> Direkomendasikan memproduksi 
                                       <span class="font-bold text-rose-600">{{ number_format($selectedResult->forecast_qty, 0, ',', '.') }} pcs</span> 
                                       untuk kebutuhan periode 
                                       <span class="font-bold text-slate-900">{{ $targetPeriod->translatedFormat($targetFormat) }}</span>.
                                    </p>
                                @else
                                    <p class="text-slate-500">Data historis tidak mencukupi untuk memetakan rentang tanggal.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Riwayat Forecast</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-slate-600">
                        <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-[0.12em] text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Periode</th>
                                <th class="px-4 py-3">Forecast</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($historyResults as $result)
                                <tr class="border-b border-slate-100 hover:bg-slate-50">
                                    <td class="px-4 py-3">{{ $result->product->name }}</td>
                                    <td class="px-4 py-3">{{ \Carbon\Carbon::parse($result->forecast_period)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ number_format($result->forecast_qty, 0, ',', '.') }} pcs</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-12 text-center text-slate-500">Belum ada riwayat forecast.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </aside>
    </div>
</div>

@php
    $forecastProductsJson = $forecastProducts->map(function ($product) {
        return [
            'id' => $product->id,
            'category' => $product->category,
            'name' => $product->name,
            'sku' => $product->sku,
        ];
    })->values();
@endphp

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const forecastProducts = @json($forecastProductsJson);

    const initialProductId = '{{ $selectedProduct?->id ?? '' }}';
    const categorySelect = document.getElementById('forecast-category');
    const skuSelect = document.getElementById('forecast-sku');
    const skuWrapper = document.getElementById('sku-wrapper');
    const selectedProductName = document.getElementById('selected-product-name');
    const hiddenInputs = document.querySelectorAll('.forecast-product-id');

    function getProductById(productId) {
        return forecastProducts.find(product => product.id === productId);
    }

    function redirectToProduct(productId) {
        if (!productId) {
            return;
        }

        window.location.href = '?product_id=' + productId;
    }

    function updateSelectedProduct(productId) {
        const product = getProductById(productId);
        if (!product) {
            selectedProductName.textContent = 'Pilih produk forecast terlebih dahulu.';
            hiddenInputs.forEach(input => input.value = '');
            return;
        }

        selectedProductName.textContent = product.name;
        hiddenInputs.forEach(input => input.value = product.id);
        categorySelect.value = product.category;
        if (product.category === 'shopee') {
            skuWrapper.classList.remove('hidden');
            renderSkuOptions(product.id);
            skuSelect.value = product.id;
        } else {
            skuWrapper.classList.add('hidden');
            skuSelect.value = '';
        }
    }

    function updateSkuVisibility() {
        const category = categorySelect.value;
        if (category === 'shopee') {
            skuWrapper.classList.remove('hidden');
            renderSkuOptions();
        } else {
            skuWrapper.classList.add('hidden');
        }
    }

    function renderSkuOptions(selectedProductId = null) {
        skuSelect.innerHTML = '';
        const shopeeProducts = forecastProducts.filter(product => product.category === 'shopee');
        shopeeProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product.id;
            option.textContent = product.name;
            skuSelect.appendChild(option);
        });

        if (selectedProductId && shopeeProducts.some(product => product.id === selectedProductId)) {
            skuSelect.value = selectedProductId;
        } else if (!skuSelect.value && shopeeProducts.length) {
            skuSelect.value = shopeeProducts[0].id;
        }
    }

    function selectProductAndReload(productId) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('product_id', productId);
        currentUrl.searchParams.delete('result_id');
        window.location.href = currentUrl.toString();
    }

    categorySelect.addEventListener('change', () => {
        const category = categorySelect.value;
        if (category === 'domba') {
            const boneka = forecastProducts.find(product => product.category === 'domba');
            if (boneka) {
                selectProductAndReload(boneka.id);
            }
        } else {
            const shopee = forecastProducts.find(product => product.category === 'shopee');
            if (shopee) {
                selectProductAndReload(shopee.id);
            }
        }
    });

    skuSelect.addEventListener('change', () => {
        selectProductAndReload(skuSelect.value);
    });

    if (initialProductId) {
        updateSelectedProduct(initialProductId);
    } else if (forecastProducts.length) {
        updateSelectedProduct(forecastProducts[0].id);
    }

    const ctx = document.getElementById('forecastChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [
                    {
                        label: 'Aktual',
                        data: @json($chartActuals),
                        borderColor: '#ec4899',
                        backgroundColor: 'rgba(236, 72, 153, 0.15)',
                        tension: 0.35,
                        fill: false,
                        spanGaps: true,
                    },
                    {
                        label: 'Forecast',
                        data: @json($chartForecasts),
                        borderColor: '#111827',
                        backgroundColor: 'rgba(17, 24, 39, 0.15)',
                        borderDash: [6, 4],
                        tension: 0.35,
                        fill: false,
                        spanGaps: true,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                },
                scales: {
                    x: { display: true, title: { display: true, text: 'Periode' } },
                    y: { display: true, title: { display: true, text: 'Jumlah (pcs)' }, beginAtZero: true },
                },
            },
        });
    }
</script>
@endsection
