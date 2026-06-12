<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BahanBaku;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Bahan kain utama
            ['kode_bahan' => 'BB-001', 'nama_bahan' => 'Kain Rasfur',      'satuan' => 'rol', 'stok_saat_ini' => 40,  'stok_minimum' => 20, 'keterangan' => 'Bahan kain utama pembuatan boneka'],
            ['kode_bahan' => 'BB-002', 'nama_bahan' => 'Kain Velboa',      'satuan' => 'rol', 'stok_saat_ini' => 35,  'stok_minimum' => 20, 'keterangan' => 'Bahan kain utama pembuatan boneka'],
            ['kode_bahan' => 'BB-003', 'nama_bahan' => 'Kain Nylex',       'satuan' => 'rol', 'stok_saat_ini' => 15,  'stok_minimum' => 20, 'keterangan' => 'Bahan kain utama pembuatan boneka'], // kritis
            // Bahan isian
            ['kode_bahan' => 'BB-004', 'nama_bahan' => 'Fiber / Dakron',   'satuan' => 'kg',  'stok_saat_ini' => 300, 'stok_minimum' => 200, 'keterangan' => 'Bahan setengah jadi, diproses jadi kapas polyester sintetis untuk isian boneka'],
            // Bahan pendukung
            ['kode_bahan' => 'BB-005', 'nama_bahan' => 'Benang Jahit',     'satuan' => 'rol', 'stok_saat_ini' => 50,  'stok_minimum' => 30, 'keterangan' => 'Benang untuk proses jahit boneka'],
            ['kode_bahan' => 'BB-006', 'nama_bahan' => 'Mata Boneka',      'satuan' => 'pcs', 'stok_saat_ini' => 800, 'stok_minimum' => 500, 'keterangan' => 'Aksesoris mata boneka berbagai ukuran'],
            ['kode_bahan' => 'BB-007', 'nama_bahan' => 'Hidung Boneka',    'satuan' => 'pcs', 'stok_saat_ini' => 200, 'stok_minimum' => 300, 'keterangan' => 'Aksesoris hidung boneka'], // kritis
            ['kode_bahan' => 'BB-008', 'nama_bahan' => 'Pita Dekorasi',    'satuan' => 'rol', 'stok_saat_ini' => 25,  'stok_minimum' => 15, 'keterangan' => 'Pita untuk finishing dan dekorasi boneka'],
            // Packing
            ['kode_bahan' => 'BB-009', 'nama_bahan' => 'Plastik Kemasan',  'satuan' => 'pcs', 'stok_saat_ini' => 500, 'stok_minimum' => 300, 'keterangan' => 'Plastik untuk packing boneka'],
            ['kode_bahan' => 'BB-010', 'nama_bahan' => 'Kardus Box',       'satuan' => 'pcs', 'stok_saat_ini' => 80,  'stok_minimum' => 100, 'keterangan' => 'Kardus untuk packing pengiriman'], // kritis
            ['kode_bahan' => 'BB-011', 'nama_bahan' => 'Hang Tag / Label', 'satuan' => 'pcs', 'stok_saat_ini' => 600, 'stok_minimum' => 400, 'keterangan' => 'Label merek untuk boneka'],
        ];

        foreach ($data as $item) {
            BahanBaku::firstOrCreate(['kode_bahan' => $item['kode_bahan']], $item);
        }
    }
}
