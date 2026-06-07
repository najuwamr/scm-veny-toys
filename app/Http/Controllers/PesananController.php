<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    // ===== ADMIN METHODS =====

    public function index()
    {
        $pesanans = Pesanan::with(['reseller', 'invoice'])
            ->orderByRaw("CASE status WHEN 'menunggu' THEN 1 WHEN 'diproses' THEN 2 WHEN 'dikirim' THEN 3 WHEN 'selesai' THEN 4 ELSE 5 END")
            ->orderByDesc('tgl_pesanan')
            ->get();

        return view('admin.list-pesanan', compact('pesanans'));
    }

    public function detail($id)
    {
        $pesanan = Pesanan::with(['reseller', 'items.produk', 'invoice'])->findOrFail($id);

        return view('admin.detail-pesanan', compact('pesanan'));
    }

    /**
     * Admin: Ubah status pesanan sesuai flow
     * Transisi: menunggu -> diproses -> dikirim -> selesai
     */
    public function action(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $action = $request->input('action');

        if ($action === 'accept' && $pesanan->status === 'menunggu') {
            $pesanan->status = 'diproses';
        } 
        elseif ($action === 'approve' && $pesanan->status === 'diproses') {
            // Cek apakah invoice sudah dibuat dan pembayaran lunas
            if (!$pesanan->invoice) {
                return back()->with('error', 'Invoice belum dibuat untuk pesanan ini.');
            }
            if ($pesanan->invoice->status_pembayaran !== 'lunas') {
                return back()->with('error', 'Pembayaran invoice belum lunas, pesanan tidak bisa disetujui.');
            }

            $pesanan->status = 'dikirim';
            $pesanan->delivered_at = now();
        } 
        elseif ($action === 'complete' && $pesanan->status === 'dikirim') {
            // Admin konfirmasi barang diterima reseller
            $pesanan->status = 'selesai';
            $pesanan->completed_at = now();

            // Jika metode COD, set pembayaran otomatis lunas
            if ($pesanan->invoice && $pesanan->invoice->metode_bayar === 'cod') {
                $pesanan->invoice->nominal_terbayar = $pesanan->invoice->jumlah_tagihan;
                $pesanan->invoice->sisa_tagihan = 0;
                $pesanan->invoice->status_pembayaran = 'lunas';
                $pesanan->invoice->tgl_bayar = now()->toDateString();
                $pesanan->invoice->save();
            }
        }
        else {
            return back()->with('error', 'Aksi tidak valid untuk status pesanan saat ini.');
        }

        $pesanan->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Admin: Buat invoice untuk pesanan yang diproses
     * Digunakan sebelum pesanan dikirim
     */
    public function createInvoice($id)
    {
        $pesanan = Pesanan::with('invoice')->findOrFail($id);

        if ($pesanan->status !== 'diproses') {
            return back()->with('error', 'Invoice hanya dapat dibuat untuk pesanan yang sedang diproses.');
        }

        if ($pesanan->invoice) {
            return back()->with('error', 'Invoice sudah dibuat untuk pesanan ini.');
        }

        // Tentukan metode bayar (default transfer, bisa diubah di form)
        $metode_bayar = request()->input('metode_bayar', 'transfer');

        $invoice = Invoice::create([
            'pesanan_id' => $pesanan->id,
            'no_invoice' => 'INV-' . strtoupper(Str::random(6)),
            'jumlah_tagihan' => $pesanan->total_harga,
            'nominal_terbayar' => 0,
            'sisa_tagihan' => $pesanan->total_harga,
            'status_pembayaran' => 'belum_bayar',
            'metode_bayar' => $metode_bayar,
        ]);

        return redirect()->route('admin.invoice.detail', $invoice->id)
            ->with('success', "Invoice berhasil dibuat dengan metode: " . ucfirst($metode_bayar));
    }

    // ===== RESELLER METHODS =====

    public function my_index()
    {
        $pesanans = Pesanan::where('reseller_id', Auth::user()->reseller->id)
            ->with(['items.produk', 'invoice'])
            ->orderByDesc('tgl_pesanan')
            ->get();

        return view('reseller.list-pesanan', compact('pesanans'));
    }

    public function create()
    {
        return view('reseller.form-pesan-produk');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'produk' => 'required|array|min:1',
            'produk.*.produk_id' => 'required|uuid|exists:produks,id',
            'produk.*.jumlah' => 'required|integer|min:1',
        ]);

        $reseller = Auth::user()->reseller;

        // Buat pesanan baru
        $no_pesanan = 'PES-' . date('Ymd') . '-' . strtoupper(Str::random(4));
        $total_harga = 0;

        $pesanan = Pesanan::create([
            'reseller_id' => $reseller->id,
            'no_pesanan' => $no_pesanan,
            'status' => 'menunggu',
            'tgl_pesanan' => now()->toDateString(),
        ]);

        // Tambahkan items
        foreach ($validated['produk'] as $item) {
            $produk = \App\Models\Produk::findOrFail($item['produk_id']);
            $subtotal = $produk->harga * $item['jumlah'];
            $total_harga += $subtotal;

            $pesanan->items()->create([
                'produk_id' => $item['produk_id'],
                'jumlah' => $item['jumlah'],
                'harga_satuan' => $produk->harga,
                'subtotal' => $subtotal,
            ]);
        }

        $pesanan->total_harga = $total_harga;
        $pesanan->save();

        return redirect()->route('reseller.pesanan.detail', $pesanan->id)
            ->with('success', 'Pesanan berhasil dibuat. Silakan tunggu persetujuan admin.');
    }

    public function my_detail($id)
    {
        $pesanan = Pesanan::with(['items.produk', 'invoice', 'invoice.verifiedBy'])->findOrFail($id);

        // Pastikan reseller hanya bisa lihat pesannya sendiri
        if ($pesanan->reseller_id !== Auth::user()->reseller->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        return view('reseller.detail-pesanan', compact('pesanan'));
    }

    public function my_upload_payment_proof($pesanan_id)
    {
        $pesanan = Pesanan::with('invoice')->findOrFail($pesanan_id);

        // Pastikan reseller
        if ($pesanan->reseller_id !== Auth::user()->reseller->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        // Pastikan ada invoice dan metode transfer
        if (!$pesanan->invoice || $pesanan->invoice->metode_bayar !== 'transfer') {
            return back()->with('error', 'Upload bukti hanya untuk metode Transfer.');
        }

        return view('reseller.upload-payment-proof', compact('pesanan'));
    }

    public function confirmReceived($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Pastikan reseller yang punya pesanan ini
        if ($pesanan->reseller_id !== Auth::user()->reseller->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if ($pesanan->status !== 'dikirim') {
            return back()->with('error', 'Pesanan harus dalam status "dikirim".');
        }

        $invoice = $pesanan->invoice;
        if (!$invoice || $invoice->metode_bayar !== 'cod') {
            return back()->with('error', 'Konfirmasi penerimaan hanya untuk metode COD.');
        }

        // Tandai selesai
        $pesanan->status = 'selesai';
        $pesanan->completed_at = now();
        $pesanan->save();

        // Pembayaran otomatis lunas
        $invoice->nominal_terbayar = $invoice->jumlah_tagihan;
        $invoice->sisa_tagihan = 0;
        $invoice->status_pembayaran = 'lunas';
        $invoice->tgl_bayar = now()->toDateString();
        $invoice->save();

        return back()->with('success', 'Barang berhasil dikonfirmasi diterima. Pesanan selesai.');
    }
}
