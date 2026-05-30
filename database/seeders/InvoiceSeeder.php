<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Pesanan;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $pesanans = Pesanan::all();

        // pastikan ada data pesanan
        if ($pesanans->count() < 5) {
            throw new \Exception('Data pesanan minimal harus 5');
        }

        // ambil 5 pesanan random
        $selectedPesanans = $pesanans->random(5);

        foreach ($selectedPesanans as $pesanan) {

            $status = collect(['belum_bayar', 'sebagian', 'lunas'])->random();

            $tglBayar = null;
            $metodeBayar = 'transfer';
            $nominalTerbayar = 0;
            $sisaTagihan = $pesanan->total_harga;

            if ($status !== 'belum_bayar') {
                $tglBayar = Carbon::parse($pesanan->tgl_pesanan)
                    ->addDays(rand(1, 5));

                $metodeBayar = collect([
                    'transfer',
                    'e-wallet',
                    'cod'
                ])->random();

                if ($status === 'sebagian') {
                    $nominalTerbayar = rand(1, max(1, $pesanan->total_harga - 1));
                } elseif ($status === 'lunas') {
                    $nominalTerbayar = $pesanan->total_harga;
                }

                $sisaTagihan = max(0, $pesanan->total_harga - $nominalTerbayar);
            }

            Invoice::create([
                'id' => Str::uuid(),
                'pesanan_id' => $pesanan->id,
                'no_invoice' => 'INV-' . strtoupper(Str::random(6)),
                'jumlah_tagihan' => $pesanan->total_harga,
                'nominal_terbayar' => $nominalTerbayar,
                'sisa_tagihan' => $sisaTagihan,
                'status_pembayaran' => $status,
                'tgl_bayar' => $tglBayar,
                'metode_bayar' => $metodeBayar,
            ]);
        }
    }
}