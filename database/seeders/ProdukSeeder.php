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
            ['nama' => 'Boneka Domba', 'harga' => 65000, 'ukuran' => 'M'],
            ['nama' => 'Jojon 1M', 'harga' => 80000, 'ukuran' => 'L'],
            ['nama' => 'Jojon 1,5M', 'harga' => 120000, 'ukuran' => 'Jumbo'],
            ['nama' => 'Bear Polos 1M', 'harga' => 75000, 'ukuran' => 'L'],
            ['nama' => 'Bear Polos 1,5M', 'harga' => 110000, 'ukuran' => 'Jumbo'],
        ];

        foreach ($data as $item) {
            Produk::create([
                'id' => Str::uuid(),
                'nama' => $item['nama'],
                'ukuran' => $item['ukuran'],
                'harga' => $item['harga'],
                'stok_saat_ini' => rand(500, 1500),
                'stok_minimum' => 50,
            ]);
        }
    }
}