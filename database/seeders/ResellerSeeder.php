<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reseller;
use App\Models\User;

class ResellerSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'reseller')->get();

        foreach ($users as $user) {
            Reseller::create([
                'user_id' => $user->id,
                'nama_toko' => 'Toko ' . $user->nama,
                'alamat' => 'Alamat ' . rand(1, 100),
            ]);
        }
    }
}