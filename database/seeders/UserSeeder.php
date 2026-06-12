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
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'id' => Str::uuid(),
                'nama' => 'Admin Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'telepon' => '081234567890',
            ]
        );

        // SUPPLIER
        User::firstOrCreate(
            ['username' => 'supplier'],
            [
                'id' => Str::uuid(),
                'nama' => 'Supplier A',
                'password' => Hash::make('password'),
                'role' => 'supplier',
                'telepon' => '081234567892',
            ]
        );

        // 5 RESELLER
        for ($i = 1; $i <= 5; $i++) {
            User::firstOrCreate(
                ['username' => 'reseller' . $i],
                [
                    'id' => Str::uuid(),
                    'nama' => 'Reseller ' . $i,
                    'password' => Hash::make('password'),
                    'role' => 'reseller',
                    'telepon' => '08123456789' . (4 + $i),
                ]
            );
        }
    }
}