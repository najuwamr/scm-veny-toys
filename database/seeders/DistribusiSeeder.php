<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Distribusi;
use App\Models\Pesanan;
use Illuminate\Support\Str;

class DistribusiSeeder extends Seeder
{
    public function run(): void
    {
        $pesanans = Pesanan::limit(5)->get();
        $statuses = ['dijadwalkan', 'dikirim', 'dalam_perjalanan', 'diterima'];

        foreach ($pesanans as $p) {
            $status = $statuses[array_rand($statuses)];
            $data = [
                'id' => (string) Str::uuid(),
                'pesanan_id' => $p->id,
                'metode_pengiriman_id' => 1,
                'status' => $status,
                'tgl_dijadwalkan' => now()->subDays(rand(1, 5)),
            ];

            if ($status === 'diterima') {
                $data['tgl_diterima'] = now()->subDays(rand(0, 2));
            }

            Distribusi::create($data);
        }
    }
}
