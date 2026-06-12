<?php

namespace Database\Seeders;

use App\Models\ForecastProduct;
use App\Models\ForecastData;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForecastSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Boneka Domba',
                'category' => 'domba',
                'sku' => 'Boneka Domba',
                'unit_size' => '1 pcs',
                'forecast_method' => 'sma',
                'granularity' => 'monthly',
            ],
            [
                'name' => 'Jojon 1M',
                'category' => 'shopee',
                'sku' => 'Jojon 1M',
                'unit_size' => '1M',
                'forecast_method' => 'wma',
                'granularity' => 'weekly',
            ],
            [
                'name' => 'Jojon 1,5M',
                'category' => 'shopee',
                'sku' => 'Jojon 1,5M',
                'unit_size' => '1.5M',
                'forecast_method' => 'wma',
                'granularity' => 'weekly',
            ],
            [
                'name' => 'Bear Polos 1M',
                'category' => 'shopee',
                'sku' => 'Bear Polos 1M',
                'unit_size' => '1M',
                'forecast_method' => 'wma',
                'granularity' => 'weekly',
            ],
            [
                'name' => 'Bear Polos 1,5M',
                'category' => 'shopee',
                'sku' => 'Bear Polos 1,5M',
                'unit_size' => '1.5M',
                'forecast_method' => 'wma',
                'granularity' => 'weekly',
            ],
        ];

        foreach ($products as $pData) {
            // Cari produk riil yang sesuai
            $produk = Produk::where('nama', $pData['name'])->first();

            $forecastProduct = ForecastProduct::create([
                'id' => Str::uuid(),
                'produk_id' => $produk ? $produk->id : null,
                'name' => $pData['name'],
                'category' => $pData['category'],
                'sku' => $pData['sku'],
                'unit_size' => $pData['unit_size'],
                'forecast_method' => $pData['forecast_method'],
                'granularity' => $pData['granularity'],
            ]);

            // Seed Data Historis untuk pengujian peramalan
            if ($pData['granularity'] === 'monthly') {
                // Untuk Domba Aqiqah (bulanan - SMA)
                // Kita buat data historis untuk 6 bulan ke belakang
                $monthlyData = [
                    ['offset_months' => 5, 'qty' => 1200, 'notes' => 'Permintaan aqiqah awal tahun'],
                    ['offset_months' => 4, 'qty' => 1450, 'notes' => 'Bulan ramai aqiqah'],
                    ['offset_months' => 3, 'qty' => 1100, 'notes' => 'Permintaan stabil'],
                    ['offset_months' => 2, 'qty' => 1500, 'notes' => 'Bulan pernikahan & aqiqah padat'],
                    ['offset_months' => 1, 'qty' => 1300, 'notes' => 'Permintaan stabil'],
                    ['offset_months' => 0, 'qty' => 1650, 'notes' => 'Bulan lalu, permintaan naik'],
                ];

                foreach ($monthlyData as $dataItem) {
                    ForecastData::create([
                        'id' => Str::uuid(),
                        'forecast_product_id' => $forecastProduct->id,
                        'period' => Carbon::now()->subMonths($dataItem['offset_months'])->startOfMonth()->format('Y-m-d'),
                        'actual_qty' => $dataItem['qty'],
                        'notes' => $dataItem['notes'],
                    ]);
                }
            } else {
                // Untuk Reseller Shopee (mingguan - WMA)
                // Kita buat data historis untuk 8 minggu ke belakang
                $weeklyQtys = [90, 150, 110, 180, 95, 140, 125, 170]; // Fluktuatif
                
                foreach ($weeklyQtys as $index => $qty) {
                    $weeksAgo = 8 - $index;
                    ForecastData::create([
                        'id' => Str::uuid(),
                        'forecast_product_id' => $forecastProduct->id,
                        'period' => Carbon::now()->subWeeks($weeksAgo)->startOfWeek()->format('Y-m-d'),
                        'actual_qty' => $qty,
                        'notes' => 'Penjualan Shopee minggu ke-' . ($index + 1),
                    ]);
                }
            }
        }
    }
}
