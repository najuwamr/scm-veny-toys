<?php

namespace App\Http\Controllers;

use App\Models\BahanBakuSupplier;
use App\Models\PermintaanPengadaan;
use App\Models\PengirimanPengadaan;
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
        $bahanBakuSuppliers = BahanBakuSupplier::with(['bahanBaku', 'supplier.user'])
            ->get()
            ->sortBy(fn ($item) => ($item->bahanBaku?->nama_bahan ?? '') . '|' . ($item->supplier?->name ?? ''))
            ->values();

        return view('admin.form-permintaan-bahanbaku', compact('bahanBakuSuppliers'));
    }

    // ─── SIMPAN PERMINTAAN BARU ───────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'bahan_baku_supplier_id' => 'required|exists:bahan_baku_supplier,id',
            'jumlah_diminta' => 'required|numeric|min:0.01',
            'catatan_admin' => 'nullable|string',
        ]);

        $bahanBakuSupplier = BahanBakuSupplier::findOrFail($request->bahan_baku_supplier_id);

        PermintaanPengadaan::create([
            'bahan_baku_supplier_id' => $bahanBakuSupplier->id,
            'jumlah'                 => $request->jumlah_diminta,
            'catatan'                => $request->catatan_admin,
            'status'                 => 'menunggu',
        ]);

        return redirect()->route('admin.procurement.index')->with('success', 'Permintaan berhasil dibuat.');
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

        return redirect()->route('admin.procurement.show', $permintaan)
            ->with('success', 'Barang diterima! Stok telah diperbarui otomatis.');
    }
}
