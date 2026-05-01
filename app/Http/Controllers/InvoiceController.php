<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['pesanan.reseller'])
            ->orderByRaw("CASE status_pembayaran WHEN 'belum_bayar' THEN 1 WHEN 'sebagian' THEN 2 WHEN 'lunas' THEN 3 ELSE 4 END")
            ->orderByDesc('created_at')
            ->get();

        return view('admin.list-invoice', compact('invoices'));
    }

    public function detail($id)
    {
        $invoice = Invoice::with(['pesanan.reseller', 'pesanan.items.produk'])->findOrFail($id);

        return view('admin.detail-invoice', compact('invoice'));
    }

    public function create($id)
    {
        $pesanan = Pesanan::with('invoice')->findOrFail($id);

        if ($pesanan->status !== 'diproses') {
            return back()->with('error', 'Invoice hanya dapat dibuat untuk pesanan yang sedang diproses.');
        }

        if ($pesanan->invoice) {
            return back()->with('error', 'Invoice sudah dibuat untuk pesanan ini.');
        }

        $invoice = Invoice::create([
            'pesanan_id' => $pesanan->id,
            'no_invoice' => 'INV-' . strtoupper(Str::random(6)),
            'jumlah_tagihan' => $pesanan->total_harga,
            'status_pembayaran' => 'belum_bayar',
        ]);

        return redirect()->route('admin.invoice.detail', $invoice->id)->with('success', 'Invoice berhasil dibuat.');
    }

    public function action(Request $request, $id)
    {
        $invoice = Invoice::with('pesanan')->findOrFail($id);
        $status = $request->input('status_pembayaran');
        $allowed = ['belum_bayar', 'sebagian', 'lunas'];

        if (!in_array($status, $allowed, true)) {
            return back()->with('error', 'Status pembayaran tidak valid.');
        }

        $invoice->status_pembayaran = $status;
        $invoice->metode_bayar = $request->input('metode_bayar');

        if ($status === 'lunas') {
            $invoice->tgl_bayar = Carbon::now()->format('Y-m-d');
        } else {
            $invoice->tgl_bayar = null;
        }

        $invoice->save();

        return back()->with('success', 'Status pembayaran invoice berhasil diperbarui.');
    }
}
