<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MetodePengiriman;

class MetodePengirimanSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['pick up', 'pesan antar', 'ekspedisi'];

        foreach ($names as $n) {
            MetodePengiriman::create(['nama' => $n]);
        }
    }
}
