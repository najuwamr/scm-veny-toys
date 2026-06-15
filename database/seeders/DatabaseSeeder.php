<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SupplierSeeder::class,
            ResellerSeeder::class,
            BahanBakuSeeder::class,
            ProdukSeeder::class,
            PesananSeeder::class,
            MetodePengirimanSeeder::class,
            DistribusiSeeder::class,
            PrakiraanProduksiSeeder::class,
            InvoiceSeeder::class,
            ForecastSeeder::class,
        ]);
    }
}
