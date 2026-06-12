<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Supplier;
use App\Models\User;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $supplierUsers = User::where('role', 'supplier')->get();

        foreach ($supplierUsers as $user) {
            Supplier::firstOrCreate([
                'user_id' => $user->id,
            ], [
                'id' => Str::uuid(),
                'nama_perusahaan' => $user->nama . ' Corp',
                'alamat' => 'Jl. Supplier No. 1, Jakarta',
            ]);
        }
    }
}
