<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Invoice;
use App\Models\Pesanan;
use App\Models\PrakiraanProduksi;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function dashboard()
    {
        $stockSummary = [
            'totalProduk' => Produk::count(),
            'totalStok' => Produk::sum('stok_saat_ini'),
            'lowStock' => Produk::where('stok_saat_ini', '<', 20)->count(),
            'topProducts' => Produk::orderByDesc('stok_saat_ini')->take(5)->get(),
        ];

        $productionSummary = [
            'totalForecast' => PrakiraanProduksi::count(),
            'forecastQty' => PrakiraanProduksi::sum('qty_prediksi'),
            'topForecasts' => PrakiraanProduksi::with('produk')->orderByDesc('qty_prediksi')->take(5)->get(),
        ];

        $distributionSummary = Distribusi::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $salesSummary = [
            'orders' => Pesanan::count(),
            'invoices' => Invoice::count(),
            'paidRevenue' => Invoice::where('status_pembayaran', 'lunas')->sum('jumlah_tagihan'),
            'paidInvoices' => Invoice::where('status_pembayaran', 'lunas')->count(),
        ];

        return view('admin.reporting.dashboard', compact('stockSummary', 'productionSummary', 'distributionSummary', 'salesSummary'));
    }

    public function analytics(Request $request)
    {
        $start = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(30)->startOfDay();
        $end = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();

        $revenueByDate = Invoice::where('status_pembayaran', 'lunas')
            ->whereBetween('tgl_bayar', [$start, $end])
            ->selectRaw('DATE(tgl_bayar) as date, SUM(jumlah_tagihan) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $ordersByDate = Pesanan::whereBetween('tgl_pesanan', [$start, $end])
            ->selectRaw('DATE(tgl_pesanan) as date, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $summary = [
            'revenue' => $revenueByDate->sum('revenue'),
            'orders' => $ordersByDate->sum('orders'),
        ];

        return view('admin.reporting.analytics', compact('start', 'end', 'revenueByDate', 'ordersByDate', 'summary'));
    }
}
