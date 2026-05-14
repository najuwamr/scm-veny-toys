<?php

namespace App\Http\Controllers;

use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PesananController extends Controller
{
    // Admin Methods
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

    public function action(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $action = $request->input('action');

        if ($action === 'accept' && $pesanan->status === 'menunggu') {
            $pesanan->status = 'diproses';
        } elseif ($action === 'approve' && $pesanan->status === 'diproses') {
            if (!$pesanan->invoice) {
                return back()->with('error', 'Invoice belum dibuat untuk pesanan ini.');
            }
            if ($pesanan->invoice->status_pembayaran !== 'lunas') {
                return back()->with('error', 'Pembayaran invoice belum lunas, pesanan tidak bisa disetujui.');
            }

            $pesanan->status = 'dikirim';
        } else {
            return back()->with('error', 'Aksi tidak valid untuk status pesanan saat ini.');
        }

        $pesanan->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // Reseller Methods
    public function my_index()
    {
        $resellerId = Auth::user()->reseller->id;

        $pesanans = Pesanan::with(['items.produk', 'invoice'])
            ->where('reseller_id', $resellerId)
            ->orderByRaw("CASE status WHEN 'menunggu' THEN 1 WHEN 'diproses' THEN 2 WHEN 'dikirim' THEN 3 WHEN 'selesai' THEN 4 ELSE 5 END")
            ->orderByDesc('tgl_pesanan')
            ->get();

        return view('reseller.list-pesanan', compact('pesanans'));
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
