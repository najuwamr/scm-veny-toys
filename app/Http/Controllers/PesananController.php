<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

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
        return view('reseller.list-pesanan');
    }

    public function create()
    {
        return view('reseller.form-pesan-produk');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            
        ]);

        // Logika penyimpanan pesanan baru
        // ...

        return redirect()->route('pesanan.list')->with('success', 'Pesanan berhasil dibuat.');
    }
}
