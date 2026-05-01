<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Pesanan;
use App\Models\ItemPesanan;
use App\Models\Reseller;
use App\Models\Produk;

class PesananSeeder extends Seeder
{
    public function run(): void
    {
        $resellers = Reseller::all();
        $produks = Produk::all();

        for ($i = 1; $i <= 5; $i++) {

            $reseller = $resellers->random();
            $pesananId = Str::uuid();

            $selectedProduks = $produks->random(rand(2, 3));

            $totalHarga = 0;
            $items = [];

            // hitung dulu semua item
            foreach ($selectedProduks as $produk) {
                $jumlah = rand(1, 4);
                $subtotal = $jumlah * $produk->harga;

                $totalHarga += $subtotal;

                $items[] = [
                    'produk_id' => $produk->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $produk->harga,
                    'subtotal' => $subtotal,
                ];
            }

            // ✅ BUAT PESANAN DULU
            $pesanan = Pesanan::create([
                'id' => $pesananId,
                'reseller_id' => $reseller->id,
                'no_pesanan' => 'ORD-' . strtoupper(Str::random(6)),
                'status' => collect(['menunggu','diproses','dikirim','selesai'])->random(),
                'total_harga' => $totalHarga,
                'tgl_pesanan' => now()->subDays(rand(1, 7)),
                'catatan' => rand(0,1) ? 'Segera kirim' : null,
            ]);

            // ✅ BARU INSERT ITEM
            foreach ($items as $item) {
                ItemPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
        }
    }
}