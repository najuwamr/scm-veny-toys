<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanBakuSupplier;
use App\Models\Mutation;
use App\Models\PermintaanPengadaan;
use App\Models\PrakiraanProduksi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProdusenController extends Controller
{
    public function dashboard()
    {
        $bahanBakus = BahanBaku::all();
        $produks = Produk::all();

        $lowBahanBakus = $bahanBakus->filter(function ($item) {
            return $item->stok_saat_ini <= $item->stok_minimum;
        });

        $lowProduks = $produks->filter(function ($item) {
            return $item->stok_saat_ini <= $item->stok_minimum;
        });

        $stokBahanTotal = $bahanBakus->sum('stok_saat_ini');
        $stokProdukTotal = $produks->sum('stok_saat_ini');

        $produksiHariIni = Mutation::whereDate('created_at', now()->toDateString())
            ->where('mutatable_type', Produk::class)
            ->where('jenis_mutasi', 'masuk')
            ->sum('jumlah');

        $pemakaianBahanHariIni = Mutation::whereDate('created_at', now()->toDateString())
            ->where('mutatable_type', BahanBaku::class)
            ->where('jenis_mutasi', 'keluar')
            ->sum('jumlah');

        $permintaanSummary = PermintaanPengadaan::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('produsen.dashboard', compact(
            'bahanBakus',
            'produks',
            'lowBahanBakus',
            'lowProduks',
            'stokBahanTotal',
            'stokProdukTotal',
            'produksiHariIni',
            'pemakaianBahanHariIni',
            'permintaanSummary'
        ));
    }

    public function bahanBakuIndex()
    {
        $bahanBakus = BahanBaku::with('mutations')->get();
        $lowStocks = $bahanBakus->filter(function ($item) {
            return $item->stok_saat_ini <= $item->stok_minimum;
        });

        return view('produsen.inventory.bahan-baku', compact('bahanBakus', 'lowStocks'));
    }

    public function produkIndex()
    {
        $produks = Produk::with('mutations')->get();
        $lowStocks = $produks->filter(function ($item) {
            return $item->stok_saat_ini <= $item->stok_minimum;
        });

        return view('produsen.inventory.produk', compact('produks', 'lowStocks'));
    }

    public function mutasiForm()
    {
        $bahanBakus = BahanBaku::all();
        $produks = Produk::all();
        $mutations = Mutation::with('mutatable')
            ->orderByDesc('created_at')
            ->limit(30)
            ->get();

        return view('produsen.inventory.mutasi', compact('bahanBakus', 'produks', 'mutations'));
    }

    public function createPermintaan()
    {
        $supplierItems = BahanBakuSupplier::with(['supplier', 'bahanBaku'])->get();

        return view('produsen.procurement.create', compact('supplierItems'));
    }

    public function createProduksi()
    {
        $produks = Produk::all();
        $bahanBakus = BahanBaku::all();

        return view('produsen.produksi.create', compact('produks', 'bahanBakus'));
    }

    public function storeMutasi(Request $request)
    {
        $data = $request->validate([
            'mutasi_tipe' => ['required', Rule::in(['bahan_baku', 'produk'])],
            'jenis_mutasi' => ['required', Rule::in(['masuk', 'keluar'])],
            'item_id_bahan_baku' => ['nullable', 'uuid', 'exists:bahan_bakus,id'],
            'item_id_produk' => ['nullable', 'uuid', 'exists:produks,id'],
            'jumlah' => ['required', 'numeric', 'min:0.01'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $itemId = $data['mutasi_tipe'] === 'bahan_baku'
            ? $data['item_id_bahan_baku']
            : $data['item_id_produk'];

        if (!$itemId) {
            return back()->withInput()->withErrors(['item_id' => 'Pilih item bahan baku atau produk terlebih dahulu.']);
        }

        $modelClass = $data['mutasi_tipe'] === 'bahan_baku' ? BahanBaku::class : Produk::class;
        $item = $modelClass::find($itemId);

        if (!$item) {
            return back()->withInput()->withErrors(['item_id' => 'Item tidak ditemukan.']);
        }

        if ($data['jenis_mutasi'] === 'keluar' && $item->stok_saat_ini < $data['jumlah']) {
            return back()->withInput()->withErrors(['jumlah' => 'Stok tidak cukup untuk pengeluaran.']);
        }

        $item->stok_saat_ini = $data['jenis_mutasi'] === 'masuk'
            ? $item->stok_saat_ini + $data['jumlah']
            : $item->stok_saat_ini - $data['jumlah'];
        $item->save();

        $item->mutations()->create([
            'jenis_mutasi' => $data['jenis_mutasi'],
            'jumlah' => $data['jumlah'],
            'catatan' => $data['catatan'],
        ]);

        $redirectRoute = $data['mutasi_tipe'] === 'bahan_baku'
            ? 'produsen.inventory.bahan-baku.index'
            : 'produsen.inventory.produk.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Mutasi berhasil disimpan.');
    }

    public function permintaanIndex()
    {
        $permintaanPengadaans = PermintaanPengadaan::with(['bahanBakuSupplier.bahanBaku', 'bahanBakuSupplier.supplier'])
            ->orderByDesc('created_at')
            ->get();

        $supplierItems = BahanBakuSupplier::with(['supplier', 'bahanBaku'])->get();

        return view('produsen.procurement.index', compact('permintaanPengadaans', 'supplierItems'));
    }

    public function storePermintaan(Request $request)
    {
        $data = $request->validate([
            'bahan_baku_supplier_id' => ['required', 'integer', 'exists:bahan_baku_supplier,id'],
            'jumlah' => ['required', 'numeric', 'min:0.01'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        PermintaanPengadaan::create([
            'bahan_baku_supplier_id' => $data['bahan_baku_supplier_id'],
            'jumlah' => $data['jumlah'],
            'catatan' => $data['catatan'],
            'status' => 'menunggu',
        ]);

        return redirect()->route('produsen.procurement.index')
            ->with('success', 'Permintaan pengadaan berhasil dibuat.');
    }

    public function produksiIndex()
    {
        $produks = Produk::all();
        $bahanBakus = BahanBaku::all();
        $forecasts = PrakiraanProduksi::with('produk')->orderByDesc('created_at')->limit(10)->get();

        return view('produsen.produksi.index', compact('produks', 'bahanBakus', 'forecasts'));
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

        return redirect()->route('produsen.produksi.index')
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
                    'usage_bahan_baku' => 'Stok bahan baku "' . optional($bahanBaku)->nama . '" tidak cukup untuk penggunaan yang diminta.',
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

        return redirect()->route('produsen.produksi.index')
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

        return view('produsen.forecast.index', compact(
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
