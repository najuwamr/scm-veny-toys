<?php

namespace App\Http\Controllers;

use App\Models\PermintaanPengadaan;
use App\Models\PengirimanPengadaan;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // ─── Helper: ambil supplier dari user login ───────────────────────────────
    private function getSupplier()
    {
        $supplier = auth()->user()?->supplier;

        if (!$supplier) {
            abort(403, 'Data supplier tidak ditemukan. Pastikan akun supplier sudah terhubung dengan profil supplier.');
        }

        return $supplier;
    }

    // ─── Helper: cek apakah permintaan milik supplier yang login ──────────────
    private function cekMilikSupplier(PermintaanPengadaan $permintaan)
    {
        $supplier = $this->getSupplier();

        if ($permintaan->bahanBakuSupplier->supplier_id !== $supplier->id) {
            abort(403, 'Anda tidak memiliki akses untuk permintaan ini.');
        }
    }

    // ─── DAFTAR PERMINTAAN MASUK (supplier) ──────────────────────────────────
    public function index()
    {
        $supplier  = $this->getSupplier();
        $permintaan = PermintaanPengadaan::with([
                'bahanBakuSupplier.bahanBaku',
                'pengiriman'
            ])
            ->whereHas('bahanBakuSupplier', fn($q) =>
                $q->where('supplier_id', $supplier->id)
            )
            ->latest()
            ->paginate(15);

        return view('supplier.daftar-permintaan-procurement', compact('permintaan'));
    }

    // ─── APPROVE PERMINTAAN ───────────────────────────────────────────────────
    public function approve(Request $request, PermintaanPengadaan $permintaan)
    {
        $this->cekMilikSupplier($permintaan);

        if ($permintaan->status !== 'menunggu') {
            return back()->with('error', 'Permintaan sudah diproses sebelumnya.');
        }

        $permintaan->update([
            'status'        => 'disetujui',
            'disetujui_pada' => now(),
            'catatan'       => $request->catatan ?? $permintaan->catatan,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Permintaan disetujui.');
    }

    // ─── REJECT PERMINTAAN ────────────────────────────────────────────────────
    public function reject(Request $request, PermintaanPengadaan $permintaan)
    {
        $this->cekMilikSupplier($permintaan);
        $request->validate(['catatan' => 'required|string']);

        if ($permintaan->status !== 'menunggu') {
            return back()->with('error', 'Permintaan sudah diproses sebelumnya.');
        }

        $permintaan->update([
            'status'  => 'ditolak',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Permintaan ditolak.');
    }

    // ─── TRACKING PENGIRIMAN (supplier) ───────────────────────────────────────
    public function tracking()
    {
        $supplier = $this->getSupplier();

        $pengiriman = PengirimanPengadaan::with([
                'permintaan.bahanBakuSupplier.bahanBaku'
            ])
            ->whereHas('permintaan.bahanBakuSupplier', fn($query) =>
                $query->where('supplier_id', $supplier->id)
            )
            ->latest()
            ->paginate(15);

        return view('supplier.tracking-pengiriman', compact('pengiriman'));
    }

    // ─── INPUT DATA PENGIRIMAN ────────────────────────────────────────────────
    public function kirim(Request $request, PermintaanPengadaan $permintaan)
    {
        $this->cekMilikSupplier($permintaan);

        $request->validate([
            'tgl_kirim' => 'required|date',
            'estimasi_tiba' => 'nullable|date',
            'ekspedisi' => 'nullable|string|max:100',
            'no_resi' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
        ]);

        if ($permintaan->status !== 'disetujui') {
            return back()->with('error', 'Permintaan belum disetujui.');
        }

        PengirimanPengadaan::updateOrCreate(
            ['permintaan_pengadaan_id' => $permintaan->id],
            [
                'status'         => 'dikirim',
                'tgl_kirim'      => $request->tgl_kirim,
                'estimasi_tiba'  => $request->estimasi_tiba,
                'ekspedisi'      => $request->ekspedisi,
                'no_resi'        => $request->no_resi,
                'catatan'        => $request->catatan,
            ]
        );

        return redirect()->route('supplier.index')->with('success', 'Data pengiriman berhasil disimpan.');
    }
}
