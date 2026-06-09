<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\PrakiraanProduksi;
use App\Models\Produk;

class PrakiraanProduksiSeeder extends Seeder
{
    public function run(): void
    {
        $produks = Produk::take(5)->get();
        $months = [
            now()->month,
            now()->addMonth()->month,
        ];

        foreach ($produks as $produk) {
            foreach ($months as $month) {
                PrakiraanProduksi::firstOrCreate([
                    'produk_id' => $produk->id,
                    'bulan' => $month,
                    'tahun' => now()->year,
                ], [
                    'id' => (string) Str::uuid(),
                    'qty_prediksi' => rand(10, 60),
                    'metode' => 'forecast',
                ]);
            }
        }
    }
}
