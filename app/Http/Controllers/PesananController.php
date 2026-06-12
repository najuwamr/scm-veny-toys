<?php

namespace App\Http\Controllers;

use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        if (Auth::check() && Auth::user()->role === 'reseller') {
            if ($pesanan->reseller_id !== Auth::user()->reseller->id) {
                abort(403);
            }

            return view('reseller.detail-pesanan', compact('pesanan'));
        }

        return view('admin.detail-pesanan', compact('pesanan'));
    }

    /**
     * Admin: Ubah status pesanan sesuai flow
     * Transisi: menunggu -> diproses -> dikirim -> selesai
     */
    public function action(Request $request, $id)
    {
        $pesanan = Pesanan::with('items.produk')->findOrFail($id);
        $action = $request->input('action');

        if ($action === 'accept' && $pesanan->status === 'menunggu') {
            // Validasi stok tersedia untuk semua item
            foreach ($pesanan->items as $item) {
                $product = $item->produk;
                if ($item->jumlah > $product->stok_saat_ini) {
                    return back()->with('error', "Stok produk '{$product->nama}' tidak cukup. Stok tersedia: {$product->stok_saat_ini}, diminta: {$item->jumlah}");
                }
            }

            // Kurangi stok dan catat mutasi untuk setiap item
            foreach ($pesanan->items as $item) {
                $product = $item->produk;
                $product->stok_saat_ini -= $item->jumlah;
                $product->save();

                // Catat mutasi ke tabel mutations untuk forecasting
                $product->mutations()->create([
                    'jenis_mutasi' => 'keluar',
                    'jumlah' => $item->jumlah,
                    'catatan' => "Pesanan {$pesanan->no_pesanan} dari {$pesanan->reseller->nama_toko}",
                ]);
            }

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
        $resellerId = Auth::user()->reseller->id;

        $pesanans = Pesanan::with(['items.produk', 'invoice'])
            ->where('reseller_id', $resellerId)
            ->orderByRaw("CASE status WHEN 'menunggu' THEN 1 WHEN 'diproses' THEN 2 WHEN 'dikirim' THEN 3 WHEN 'selesai' THEN 4 ELSE 5 END")
            ->orderByDesc('tgl_pesanan')
            ->get();

        $pesanans = Pesanan::where('reseller_id', Auth::user()->reseller->id)
            ->with(['items.produk', 'invoice'])
            ->orderByDesc('tgl_pesanan')
            ->get();

        return view('reseller.list-pesanan', compact('pesanans'), compact('pesanans'));
    }

    public function create()
    {
        $products = Produk::orderBy('nama')->get();

        return view('reseller.form-pesan-produk', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $product = Produk::findOrFail($request->input('produk_id'));
        $quantity = (int) $request->input('jumlah');
        $reseller = Auth::user()->reseller;

        // Validasi stok
        if ($quantity > $product->stok_saat_ini) {
            return back()
                ->withInput()
                ->withErrors(['jumlah' => "Stok tidak cukup. Stok tersedia: {$product->stok_saat_ini}"]);
        }

        $pesanan = Pesanan::create([
            'reseller_id' => $reseller->id,
            'no_pesanan' => 'PO-' . strtoupper(Str::random(8)),
            'status' => 'menunggu',
            'total_harga' => $product->harga * $quantity,
            'tgl_pesanan' => now()->format('Y-m-d'),
            'catatan' => $request->input('catatan'),
        ]);

        ItemPesanan::create([
            'pesanan_id' => $pesanan->id,
            'produk_id' => $product->id,
            'jumlah' => $quantity,
            'harga_satuan' => $product->harga,
            'subtotal' => $product->harga * $quantity,
        ]);

        return redirect()->route('reseller.pesanan.list')->with('success', 'Pesanan berhasil dibuat. Silakan cek status pada Pesanan Saya.');
    }
}
