<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'Boneka Teddy', 'harga' => 50000],
            ['nama' => 'Boneka Panda', 'harga' => 60000],
            ['nama' => 'Boneka Kucing', 'harga' => 45000],
            ['nama' => 'Boneka Beruang Jumbo', 'harga' => 150000],
            ['nama' => 'Boneka Anime', 'harga' => 80000],
        ];

        foreach ($data as $item) {
            Produk::create([
                'id' => Str::uuid(),
                'nama' => $item['nama'],
                'ukuran' => collect(['S','M','L','XL','Jumbo'])->random(),
                'harga' => $item['harga'],
                'stok_saat_ini' => rand(10, 100),
                'stok_minimum' => 5,
            ]);
        }
    }
}