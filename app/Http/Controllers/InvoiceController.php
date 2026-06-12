<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Admin: Daftar invoice dengan filter berdasarkan metode pembayaran dan status
     */
    public function index(Request $request)
    {
        if (Auth::check() && Auth::user()->role === 'reseller') {
            $resellerId = Auth::user()->reseller->id;

            $invoices = Invoice::with(['pesanan.reseller'])
                ->whereHas('pesanan', function ($query) use ($resellerId) {
                    $query->where('reseller_id', $resellerId);
                })
                ->orderByRaw("CASE status_pembayaran WHEN 'belum_bayar' THEN 1 WHEN 'sebagian' THEN 2 WHEN 'lunas' THEN 3 ELSE 4 END")
                ->orderByDesc('created_at')
                ->get();

            return view('reseller.list-payment', compact('invoices'));
        }

        $query = Invoice::with(['pesanan.reseller', 'verifiedBy']);

        if (Auth::user()->role === 'reseller') {
            $query->whereHas('pesanan', function ($q) {
                $q->where('reseller_id', Auth::user()->reseller->id);
            });
        }

        // Filter metode pembayaran
        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->metode_bayar);
        }

        // Filter status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        $invoices = $query
            ->orderByRaw("CASE status_pembayaran WHEN 'belum_bayar' THEN 1 WHEN 'sebagian' THEN 2 WHEN 'lunas' THEN 3 ELSE 4 END")
            ->orderByDesc('created_at')
            ->get();

        return view(Auth::user()->role === 'reseller' ? 'admin.list-invoice' : 'admin.list-invoice', compact('invoices'));
    }

    /**
     * Detail invoice lengkap dengan opsi verifikasi/pembayaran
     */
    public function detail($id)
    {
        $invoice = Invoice::with(['pesanan.reseller', 'pesanan.items.produk'])->findOrFail($id);

        if (Auth::check() && Auth::user()->role === 'reseller') {
            if ($invoice->pesanan->reseller_id !== Auth::user()->reseller->id) {
                abort(403);
            }

            return view('reseller.detail-payment', compact('invoice'));
        }
        $invoice = Invoice::with([
            'pesanan.reseller',
            'pesanan.items.produk',
            'verifiedBy'
        ])->findOrFail($id);

        if (Auth::user()->role === 'reseller' && $invoice->pesanan->reseller_id !== Auth::user()->reseller->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke invoice ini.');
        }

        return view('admin.detail-invoice', compact('invoice'));
    }

    public function confirm(Request $request, $id)
    {
        $invoice = Invoice::with('pesanan')->findOrFail($id);

        if (!Auth::check() || Auth::user()->role !== 'reseller' || $invoice->pesanan->reseller_id !== Auth::user()->reseller->id) {
            abort(403);
        }

        $request->validate([
            'metode_bayar' => 'required|in:' . implode(',', Invoice::PAYMENT_METHODS),
        ]);

        $invoice->metode_bayar = $request->input('metode_bayar');
        $invoice->save();

        return back()->with('success', 'Metode pembayaran tersimpan. Silakan konfirmasi melalui WhatsApp ke admin.');
    }

    /**
     * Admin: Buat invoice baru dari pesanan (diproses)
     */
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
            'nominal_terbayar' => 0,
            'sisa_tagihan' => $pesanan->total_harga,
            'status_pembayaran' => 'belum_bayar',
            'metode_bayar' => 'transfer', // default transfer
        ]);

        return redirect()->route('admin.invoice.detail', $invoice->id)->with('success', 'Invoice berhasil dibuat.');
    }

    /**
     * Reseller: Upload bukti pembayaran untuk metode TRANSFER
     */
    public function uploadPaymentProof(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        // Hanya reseller yang punya pesanan ini atau admin yang bisa upload
        if (Auth::user()->role === 'reseller' && $invoice->pesanan->reseller_id !== Auth::user()->reseller->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke invoice ini.');
        }

        // Cek metode pembayaran
        if ($invoice->metode_bayar !== 'transfer') {
            return back()->with('error', 'Upload bukti hanya tersedia untuk metode Transfer.');
        }

        $validated = $request->validate([
            'nominal' => 'required|integer|min:1|max:' . $invoice->jumlah_tagihan,
            'bukti_pembayaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ], [
            'nominal.required' => 'Nominal pembayaran harus diisi.',
            'nominal.min' => 'Nominal pembayaran minimal 1 rupiah.',
            'nominal.max' => 'Nominal pembayaran tidak boleh lebih dari tagihan.',
            'bukti_pembayaran.required' => 'Bukti pembayaran harus diupload.',
            'bukti_pembayaran.mimes' => 'File harus berformat PDF, JPG, atau PNG.',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 5MB.',
        ]);

        // Simpan file
        $path = $request->file('bukti_pembayaran')->store('payment-proofs', 'public');

        // Update invoice
        $invoice->nominal_terbayar += $validated['nominal'];
        $invoice->bukti_pembayaran = $path;
        $invoice->updatePaymentStatus(); // Hitung ulang status
        $invoice->save();

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }

    /**
     * Admin: Verifikasi pembayaran (TRANSFER)
     */
    public function verifyPayment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->metode_bayar !== 'transfer') {
            return back()->with('error', 'Verifikasi hanya untuk metode Transfer.');
        }

        $invoice->verified_at = now();
        $invoice->verified_by = Auth::id();
        $invoice->save();

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Admin: Tolak pembayaran dan kembalikan nominal (TRANSFER)
     */
    public function rejectPayment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

        if ($invoice->metode_bayar !== 'transfer') {
            return back()->with('error', 'Penolakan hanya untuk metode Transfer.');
        }

        $metodeBayar = $request->input('metode_bayar');
        if ($metodeBayar && !in_array($metodeBayar, Invoice::PAYMENT_METHODS, true)) {
            return back()->with('error', 'Metode pembayaran tidak valid.');
        }

        // Reset nominal dan bukti
        $invoice->nominal_terbayar = 0;
        $invoice->sisa_tagihan = $invoice->jumlah_tagihan;
        $invoice->bukti_pembayaran = null;
        $invoice->verified_at = null;
        $invoice->verified_by = null;
        $invoice->status_pembayaran = 'belum_bayar';

        // Simpan catatan penolakan (bisa di table terpisah atau di sini)
        $invoice->save();

        return back()->with('success', 'Pembayaran ditolak. Nominal pembayaran direset. Alasan: ' . $validated['alasan']);
    }

    /**
     * Admin: Perbarui status pembayaran secara manual
     */
    public function updateStatus(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $request->validate([
            'status_pembayaran' => 'required|in:belum_bayar,sebagian,lunas',
            'metode_bayar' => 'nullable|in:transfer,e-wallet,cod',
        ]);

        $status = $request->input('status_pembayaran');
        $metode = $request->input('metode_bayar');

        $invoice->status_pembayaran = $status;
        $invoice->metode_bayar = $metode;

        if ($status === 'lunas') {
            $invoice->nominal_terbayar = $invoice->jumlah_tagihan;
            $invoice->sisa_tagihan = 0;
            $invoice->tgl_bayar = now()->toDateString();
        } elseif ($status === 'belum_bayar') {
            $invoice->nominal_terbayar = 0;
            $invoice->sisa_tagihan = $invoice->jumlah_tagihan;
            $invoice->tgl_bayar = null;
        } else { // sebagian
            if ($invoice->nominal_terbayar <= 0) {
                $invoice->nominal_terbayar = (int) ($invoice->jumlah_tagihan / 2);
            }
            $invoice->sisa_tagihan = $invoice->jumlah_tagihan - $invoice->nominal_terbayar;
            $invoice->tgl_bayar = null;
        }

        $invoice->save();

        return back()->with('success', 'Status pembayaran invoice berhasil diperbarui.');
    }

    /**
     * Admin: Konfirmasi penerimaan barang dan tandai pesanan selesai
     * Jika metode COD, pembayaran otomatis lunas
     */
    public function confirmDelivery(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $invoice = $pesanan->invoice;

        if ($pesanan->status !== 'dikirim') {
            return back()->with('error', 'Pesanan harus dalam status "dikirim" untuk dikonfirmasi.');
        }

        // Tandai pesanan selesai
        $pesanan->status = 'selesai';
        $pesanan->delivered_at = now();
        $pesanan->completed_at = now();

        // Jika metode COD, pembayaran otomatis lunas
        if ($invoice && $invoice->metode_bayar === 'cod') {
            $invoice->nominal_terbayar = $invoice->jumlah_tagihan;
            $invoice->sisa_tagihan = 0;
            $invoice->status_pembayaran = 'lunas';
            $invoice->tgl_bayar = now()->toDateString();
            $invoice->save();
        }

        $pesanan->save();

        return back()->with('success', 'Pesanan berhasil dikonfirmasi diterima dan ditandai selesai.');
    }

    /**
     * Reseller: Konfirmasi penerimaan barang (hanya COD)
     * Setelah konfirmasi, pesanan otomatis selesai dan pembayaran lunas
     */
    public function confirmReceived(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $invoice = $pesanan->invoice;

        // Pastikan reseller yang punya pesanan ini
        if (Auth::user()->role === 'reseller' && $pesanan->reseller_id !== Auth::user()->reseller->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
        }

        if ($pesanan->status !== 'dikirim') {
            return back()->with('error', 'Pesanan harus dalam status "dikirim" untuk dikonfirmasi diterima.');
        }

        // Pastikan invoice menggunakan metode COD
        if (!$invoice || $invoice->metode_bayar !== 'cod') {
            return back()->with('error', 'Konfirmasi penerimaan hanya untuk metode COD.');
        }

        // Tandai pesanan selesai
        $pesanan->status = 'selesai';
        $pesanan->delivered_at = now();
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
