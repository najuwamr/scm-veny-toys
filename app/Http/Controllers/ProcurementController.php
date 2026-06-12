<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanBakuSupplier;
use App\Models\PermintaanPengadaan;
use App\Models\PengirimanPengadaan;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcurementController extends Controller
{
    // ─── DAFTAR SEMUA PERMINTAAN (admin) ─────────────────────────────────────
    public function index()
    {
        $permintaan = PermintaanPengadaan::with([
                'bahanBakuSupplier.bahanBaku',
                'bahanBakuSupplier.supplier.user',
                'pengiriman'
            ])
            ->latest()
            ->paginate(15);

        return view('admin.daftar-permintaan-procurement', compact('permintaan'));
    }

    // ─── FORM BUAT PERMINTAAN ─────────────────────────────────────────────────
    public function create()
    {
        $bahanBaku = BahanBaku::orderBy('kode_bahan')->get();
        $suppliers = Supplier::with('user')->get()->sortBy(function ($supplier) {
            return $supplier->user?->name ?? $supplier->nama_perusahaan;
        });

        return view('admin.form-permintaan-bahanbaku', compact('bahanBaku', 'suppliers'));
    }

    // ─── SIMPAN PERMINTAAN BARU ───────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'supplier_id'   => 'required|exists:suppliers,id',
            'jumlah_diminta' => 'required|numeric|min:0.01',
            'catatan_admin' => 'nullable|string',
        ]);

        $bahanBakuSupplier = BahanBakuSupplier::firstOrCreate([
            'bahan_baku_id' => $request->bahan_baku_id,
            'supplier_id'   => $request->supplier_id,
        ], [
            'harga' => 0,
        ]);

        PermintaanPengadaan::create([
            'bahan_baku_supplier_id' => $bahanBakuSupplier->id,
            'jumlah'                 => $request->jumlah_diminta,
            'catatan'                => $request->catatan_admin,
            'status'                 => 'menunggu',
        ]);

        return redirect()->route('procurement.index')->with('success', 'Permintaan berhasil dibuat.');
    }

    // ─── DETAIL PERMINTAAN ────────────────────────────────────────────────────
    public function show(PermintaanPengadaan $permintaan)
    {
        $permintaan->load([
            'bahanBakuSupplier.bahanBaku',
            'bahanBakuSupplier.supplier.user',
            'pengiriman'
        ]);
        return view('admin.detail-permintaan', compact('permintaan'));
    }

    // ─── TRACKING PENGIRIMAN (admin) ───────────────────────────────────────────
    public function tracking()
    {
        $permintaan = PermintaanPengadaan::with([
                'bahanBakuSupplier.bahanBaku',
                'bahanBakuSupplier.supplier.user',
                'pengiriman'
            ])
            ->latest()
            ->paginate(15);

        return view('admin.tracking-pengiriman', compact('permintaan'));
    }

    // ─── KONFIRMASI TERIMA BARANG (admin) ─────────────────────────────────────
    public function konfirmasiTerima(PermintaanPengadaan $permintaan)
    {
        if ($permintaan->status !== 'disetujui') {
            return back()->with('error', 'Permintaan belum dalam status disetujui.');
        }

        // Cek pengiriman sudah ada dan statusnya dikirim/dalam_perjalanan
        if (!$permintaan->pengiriman || !in_array($permintaan->pengiriman->status, ['dikirim', 'dalam_perjalanan'])) {
            return back()->with('error', 'Barang belum dalam status dikirim.');
        }

        DB::transaction(function () use ($permintaan) {
            $bahan = $permintaan->bahanBakuSupplier->bahanBaku;

            // Update status permintaan
            $permintaan->update(['status' => 'selesai']);

            // Update status pengiriman
            $permintaan->pengiriman->update([
                'status'     => 'diterima',
                'tgl_terima' => now(),
            ]);

            // Catat mutasi masuk
            $bahan->mutations()->create([
                'jenis_mutasi' => 'masuk',
                'jumlah'       => $permintaan->jumlah,
                'catatan'      => 'Dari procurement #' . $permintaan->id,
            ]);

            // Update stok
            $bahan->increment('stok_saat_ini', $permintaan->jumlah);
        });

        return redirect()->route('procurement.show', $permintaan)
            ->with('success', 'Barang diterima! Stok telah diperbarui otomatis.');
    }
}
