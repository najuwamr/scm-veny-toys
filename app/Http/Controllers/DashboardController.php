<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\ForecastProduct;
use App\Models\ForecastResult;
use App\Models\Mutation;
use App\Models\PrakiraanProduksi;
use App\Models\Produk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard_admin()
    {
        return view('admin.dashboard');
    }

    public function produksiIndex()
    {
        $produks = Produk::all();
        $bahanBakus = BahanBaku::all();

        $forecastProducts = ForecastProduct::orderBy('category')->orderBy('name')->get();
        $latestResults = ForecastResult::with('product')
            ->latest('created_at')
            ->get()
            ->groupBy('forecast_product_id')
            ->map(fn ($group) => $group->first());

        $forecastSummary = $forecastProducts->map(function ($product) use ($latestResults) {
            $latest = $latestResults->get($product->id);
            if (!$latest) {
                return null;
            }

            return sprintf('%s: %s pcs (%s)', $product->name, number_format($latest->forecast_qty, 0, ',', '.'), strtoupper($product->forecast_method));
        })->filter()->implode(' · ');

        $latestForecastResult = ForecastResult::with('product')->latest('created_at')->first();

        return view('admin.procurement.produksi.index', compact(
            'produks',
            'bahanBakus',
            'forecastSummary',
            'latestForecastResult'
        ));
    }

    public function createProduksi()
    {
        $produks = Produk::all();
        $bahanBakus = BahanBaku::all();

        return view('admin.procurement.produksi.create', compact('produks', 'bahanBakus'));
    }

    public function storeRencanaProduksi(Request $request)
    {
        $data = $request->validate([
            'produk_id' => ['required', 'uuid', 'exists:produks,id'],
            'bulan' => ['required', 'integer', 'between:1,12'],
            'tahun' => ['required', 'integer', 'min:2000'],
            'qty_prediksi' => ['required', 'integer', 'min:1'],
            'metode' => ['required', 'string', 'max:50'],
        ]);

        PrakiraanProduksi::create($data);

        return redirect()->route('admin.procurement.produksi.index')
            ->with('success', 'Rencana produksi berhasil disimpan.');
    }

    public function storeRealisasiProduksi(Request $request)
    {
        $data = $request->validate([
            'produk_id' => ['required', 'uuid', 'exists:produks,id'],
            'qty_produksi' => ['required', 'integer', 'min:1'],
            'catatan' => ['nullable', 'string', 'max:500'],
            'usage_bahan_baku' => ['required', 'array', 'min:1'],
            'usage_bahan_baku.*.bahan_baku_id' => ['required', 'uuid', 'exists:bahan_bakus,id'],
            'usage_bahan_baku.*.jumlah' => ['required', 'numeric', 'min:0.01'],
        ]);

        $produk = Produk::findOrFail($data['produk_id']);

        foreach ($data['usage_bahan_baku'] as $usage) {
            $bahanBaku = BahanBaku::find($usage['bahan_baku_id']);
            if ($bahanBaku && $bahanBaku->stok_saat_ini < $usage['jumlah']) {
                return back()->withInput()->withErrors([
                    'usage_bahan_baku' => 'Stok bahan baku "' . optional($bahanBaku)->nama_bahan . '" tidak cukup untuk penggunaan yang diminta.',
                ]);
            }
        }

        $produk->stok_saat_ini += $data['qty_produksi'];
        $produk->save();
        $produk->mutations()->create([
            'jenis_mutasi' => 'masuk',
            'jumlah' => $data['qty_produksi'],
            'catatan' => 'Realisasi produksi: ' . ($data['catatan'] ?? 'Tidak ada catatan'),
        ]);

        foreach ($data['usage_bahan_baku'] as $usage) {
            $bahanBaku = BahanBaku::find($usage['bahan_baku_id']);
            if (!$bahanBaku) {
                continue;
            }
            $bahanBaku->stok_saat_ini -= $usage['jumlah'];
            $bahanBaku->save();
            $bahanBaku->mutations()->create([
                'jenis_mutasi' => 'keluar',
                'jumlah' => $usage['jumlah'],
                'catatan' => 'Pemakaian bahan baku untuk produksi ' . $produk->nama,
            ]);
        }

        return redirect()->route('admin.procurement.produksi.index')
            ->with('success', 'Realisasi produksi berhasil disimpan dan stok diperbarui.');
    }

    public function forecastIndex()
    {
        $forecasts = PrakiraanProduksi::with('produk')->orderByDesc('created_at')->get();
        $totalForecastQty = $forecasts->sum('qty_prediksi');
        $stokProdukTotal = Produk::sum('stok_saat_ini');
        $estimatedBahan = $totalForecastQty * 1.5;
        $estimatedProduk = $totalForecastQty;
        $fulfillmentRate = $estimatedProduk > 0
            ? round(min(100, ($stokProdukTotal / $estimatedProduk) * 100), 2)
            : 100;
        $predictedShortage = max(0, $estimatedProduk - $stokProdukTotal);
        $riskBahanCount = BahanBaku::whereColumn('stok_saat_ini', '<', 'stok_minimum')->count();
        $riskProdukCount = Produk::whereColumn('stok_saat_ini', '<', 'stok_minimum')->count();
        $recommendedAction = $predictedShortage > 0
            ? 'Tingkatkan produksi atau ajukan pengadaan bahan baku.'
            : 'Stok produk cukup, terus pantau permintaan.';

        return view('admin.procurement.forecast.index', compact(
            'forecasts',
            'estimatedBahan',
            'estimatedProduk',
            'fulfillmentRate',
            'predictedShortage',
            'riskBahanCount',
            'riskProdukCount',
            'recommendedAction'
        ));
    }

    public function useForecast($id)
    {
        $forecast = PrakiraanProduksi::with('produk')->findOrFail($id);

        return back()->with('success', 'Forecast produksi "' . optional($forecast->produk)->nama . '" telah digunakan sebagai acuan planning produksi.');
    }
}
