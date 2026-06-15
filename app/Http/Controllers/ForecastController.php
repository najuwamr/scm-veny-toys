<?php

namespace App\Http\Controllers;

use App\Exports\ForecastResultsExport;
use App\Models\ForecastData;
use App\Models\ForecastProduct;
use App\Models\ForecastResult;
use App\Services\ForecastService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class ForecastController extends Controller
{
    private function normalizePeriod(Carbon $date, string $granularity): Carbon
    {
        return $granularity === 'weekly'
            ? $date->copy()->startOfWeek(Carbon::MONDAY)
            : $date->copy()->startOfMonth();
    }

    private function loadManualForecastData(ForecastProduct $product)
    {
        return ForecastData::where('forecast_product_id', $product->id)
            ->get()
            ->toBase()
            ->mapWithKeys(function (ForecastData $item) use ($product) {
                $normalized = $this->normalizePeriod(Carbon::parse($item->period), $product->granularity)->format('Y-m-d');

                return [$normalized => [
                    'period' => $normalized,
                    'actual_qty' => (int) $item->actual_qty,
                    'manual_override' => true,
                ]];
            });
    }

    private function loadHistoricalDataFromMutations(ForecastProduct $product)
    {
        if (!$product->produk) {
            return collect();
        }

        $mutationsQuery = $product->produk->mutations()
            ->where('jenis_mutasi', 'keluar');

        if ($product->granularity === 'weekly') {
            $rows = $mutationsQuery->selectRaw('YEAR(created_at) AS year, WEEK(created_at, 3) AS week, SUM(jumlah) AS total_qty, MIN(created_at) AS min_date')
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week')
                ->get();
        } else {
            $rows = $mutationsQuery->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, SUM(jumlah) AS total_qty, MIN(created_at) AS min_date')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get();
        }

        $historical = $rows->toBase()->mapWithKeys(function ($item) use ($product) {
            $period = $this->normalizePeriod(Carbon::parse($item->min_date), $product->granularity)->format('Y-m-d');

            return [$period => [
                'period' => $period,
                'actual_qty' => (int) ceil($item->total_qty),
            ]];
        });

        $manualOverrides = $this->loadManualForecastData($product);
        $merged = $historical->merge($manualOverrides)->sortBy('period')->values();

        return $merged;
    }

    public function index(Request $request)
    {
        $forecastProducts = ForecastProduct::with('produk')
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $selectedProductId = $request->query('product_id');
        $selectedProduct = null;

        if ($selectedProductId) {
            $selectedProduct = ForecastProduct::with([ 'produk', 'results' => fn ($query) => $query->latest('created_at') ])
                ->find($selectedProductId);
        }

        if (!$selectedProduct && $forecastProducts->isNotEmpty()) {
            $selectedProduct = ForecastProduct::with([ 'produk', 'results' => fn ($query) => $query->latest('created_at') ])
                ->first();
        }

        $selectedResult = null;
        if ($selectedProduct) {
            $selectedResult = $request->query('result_id')
                ? ForecastResult::with('product')->find($request->query('result_id'))
                : $selectedProduct->results->first();
        }

        $historyResults = $selectedProduct
            ? ForecastResult::with('product')
                ->where('forecast_product_id', $selectedProduct->id)
                ->latest('created_at')
                ->get()
            : collect();
        $historicalData = $selectedProduct ? $this->loadHistoricalDataFromMutations($selectedProduct) : collect();

        $chartLabels = [];
        $chartActuals = [];
        $chartForecasts = [];

        if ($selectedProduct) {
            $chartLabels = $historicalData->pluck('period')->map(function ($item) {
                return Carbon::parse($item)->format('Y-m-d');
            })->all();

            $chartActuals = $historicalData->pluck('actual_qty')->all();

            if ($selectedResult) {
                $chartLabels[] = Carbon::parse($selectedResult->forecast_period)->format('Y-m-d');
                $chartActuals[] = null;
                $chartForecasts = array_pad([], count($historicalData), null);
                $chartForecasts[] = $selectedResult->forecast_qty;
            }
        }

        return view('admin.procurement.forecast.index', compact(
            'forecastProducts',
            'selectedProduct',
            'selectedResult',
            'historyResults',
            'historicalData',
            'chartLabels',
            'chartActuals',
            'chartForecasts'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'forecast_product_id' => ['required', 'uuid', 'exists:forecast_products,id'],
            'period' => ['required', 'date'],
            'actual_qty' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        ForecastData::create($data);

        return Redirect::route('admin.procurement.forecast.index', ['product_id' => $data['forecast_product_id']])
            ->with('success', 'Data historis forecast berhasil disimpan.');
    }

    public function calculate(Request $request, ForecastService $service)
    {
        $data = $request->validate([
            'forecast_product_id' => ['required', 'uuid', 'exists:forecast_products,id'],
        ]);

        $product = ForecastProduct::with('produk')->findOrFail($data['forecast_product_id']);
        $historicalData = $this->loadHistoricalDataFromMutations($product);

        if ($historicalData->count() < 3) {
            return Redirect::back()->withErrors(['forecast_product_id' => 'Data historis minimal 3 periode diperlukan untuk melakukan forecast.']);
        }

        $actualValues = $historicalData->pluck('actual_qty')->map(fn ($qty) => (int) $qty)->all();
        $window = 3;
        $forecastQty = (int) ceil($service->nextForecast($actualValues, $product->forecast_method, [1, 2, 3], $window));
        $history = $service->buildForecastHistory($actualValues, $product->forecast_method, [1, 2, 3], $window);
        $latestPeriod = Carbon::parse($historicalData->last()['period']);

        if ($product->granularity === 'monthly') {
            $forecastPeriod = $latestPeriod->copy()->startOfMonth()->addMonth();
        } else {
            $forecastPeriod = $latestPeriod->copy()->startOfWeek(Carbon::MONDAY)->addWeek();
        }

        $result = ForecastResult::create([
            'forecast_product_id' => $product->id,
            'forecast_period' => $forecastPeriod->format('Y-m-d'),
            'forecast_qty' => $forecastQty,
            'mad' => round($history['mad'], 4),
            'mape' => round($history['mape'], 4),
            'method_used' => $product->forecast_method,
            'window_size' => $window,
        ]);

        return Redirect::route('admin.procurement.forecast.index', [
            'product_id' => $product->id,
            'result_id' => $result->id,
        ])->with('success', 'Forecast berhasil dihitung dan disimpan.');
    }

    public function exportPdf(Request $request)
    {
        $resultId = $request->query('result_id') ?? ForecastResult::latest('created_at')->value('id');
        $result = ForecastResult::with('product')->findOrFail($resultId);
        $recentData = $result->product->data()->orderByDesc('period')->limit(12)->get();

        $pdf = Pdf::loadView('admin.procurement.forecast.pdf', compact('result', 'recentData'));

        return $pdf->download('forecast-' . $result->id . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $resultId = $request->query('result_id') ?? ForecastResult::latest('created_at')->value('id');
        $result = ForecastResult::with('product')->findOrFail($resultId);

        return Excel::download(new ForecastResultsExport($result), 'forecast-' . $result->id . '.xlsx');
    }
}
