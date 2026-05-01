<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'id' => Str::uuid(),
            'nama' => 'Admin Utama',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'telepon' => '081234567890',
        ]);

        // SUPPLIER
        User::create([
            'id' => Str::uuid(),
            'nama' => 'Supplier A',
            'username' => 'supplier',
            'password' => Hash::make('password'),
            'role' => 'supplier',
            'telepon' => '081234567892',
        ]);

        // DISTRIBUTOR
        User::create([
            'id' => Str::uuid(),
            'nama' => 'Distributor A',
            'username' => 'distributor',
            'password' => Hash::make('password'),
            'role' => 'distributor',
            'telepon' => '081234567893',
        ]);

        // 5 RESELLER
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'id' => Str::uuid(),
                'nama' => 'Reseller ' . $i,
                'username' => 'reseller' . $i,
                'password' => Hash::make('password'),
                'role' => 'reseller',
                'telepon' => '08123456789' . (4 + $i),
            ]);
        }
    }
}