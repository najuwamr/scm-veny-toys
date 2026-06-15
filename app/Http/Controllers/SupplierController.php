<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanBakuSupplier;
use App\Models\PermintaanPengadaan;
use App\Models\PengirimanPengadaan;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected function getSupplier()
    {
        $supplier = auth()->user()->supplier;

        if (!$supplier) {
            return null;
        }

        return $supplier;
    }

    public function dashboard()
    {
        $supplier = $this->getSupplier();

        if (!$supplier) {
            return view('supplier.no-profile');
        }

        $materialCount = BahanBakuSupplier::where('supplier_id', $supplier->id)->count();
        $requestsPending = PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->where('status', 'menunggu')->count();
        $requestsApproved = PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->where('status', 'disetujui')->count();
        $requestsTotal = PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->count();
        $outstandingValue = PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->whereIn('status', ['menunggu', 'disetujui'])
            ->with('bahanBakuSupplier')
            ->get()
            ->sum(function ($item) {
                return $item->jumlah * optional($item->bahanBakuSupplier)->harga;
            });

        return view('supplier.dashboard', compact(
            'materialCount',
            'requestsPending',
            'requestsApproved',
            'requestsTotal',
            'outstandingValue'
        ));
    }

    public function materialsIndex()
    {
        $supplier = $this->getSupplier();

        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

        $materials = BahanBakuSupplier::with('bahanBaku')
            ->where('supplier_id', $supplier->id)
            ->latest()
            ->get();

        return view('supplier.offers.index', compact('materials'));
    }

    public function createMaterial()
    {
        $bahanBakus = BahanBaku::orderBy('nama_bahan')->get();

        return view('supplier.offers.create', compact('bahanBakus'));
    }

    public function storeMaterial(Request $request)
    {
        $supplier = $this->getSupplier();

        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

        $data = $request->validate([
            'bahan_baku_id' => ['required', 'uuid', 'exists:bahan_bakus,id'],
            'harga' => ['required', 'numeric', 'min:0'],
        ]);

        BahanBakuSupplier::updateOrCreate(
            [
                'supplier_id' => $supplier->id,
                'bahan_baku_id' => $data['bahan_baku_id'],
            ],
            ['harga' => $data['harga']]
        );

        return redirect()->route('supplier.materials.index')
            ->with('success', 'Data bahan baku dan harga berhasil disimpan.');
    }

    public function requestsIndex()
    {
        $supplier = $this->getSupplier();

        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

        $requests = PermintaanPengadaan::with(['bahanBakuSupplier.bahanBaku', 'bahanBakuSupplier.supplier'])
            ->whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
                $query->where('supplier_id', $supplier->id);
            })
            ->orderByDesc('created_at')
            ->get();

        return view('supplier.requests.index', compact('requests'));
    }

    public function approveRequest($id)
    {
        $supplier = $this->getSupplier();
        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

        $request = PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->findOrFail($id);

        $request->update([
            'status' => 'disetujui',
            'disetujui_pada' => now(),
        ]);

        return redirect()->route('supplier.requests.index')
            ->with('success', 'Permintaan pengadaan disetujui.');
    }

    public function rejectRequest($id)
    {
        $supplier = $this->getSupplier();
        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

        $request = PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->findOrFail($id);

        $request->update(['status' => 'ditolak']);

        return redirect()->route('supplier.requests.index')
            ->with('success', 'Permintaan pengadaan ditolak.');
    }

    public function completeRequest($id)
    {
        $supplier = $this->getSupplier();
        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

        $request = PermintaanPengadaan::whereHas('bahanBakuSupplier', function ($query) use ($supplier) {
            $query->where('supplier_id', $supplier->id);
        })->findOrFail($id);

        $request->update(['status' => 'selesai']);

        return redirect()->route('supplier.requests.index')
            ->with('success', 'Permintaan pengadaan ditandai selesai.');
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

        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

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
            'catatan'       => $request->catatan ?? $request->catatan_supplier ?? $permintaan->catatan,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Permintaan disetujui.');
    }

    // ─── REJECT PERMINTAAN ────────────────────────────────────────────────────
    public function reject(Request $request, PermintaanPengadaan $permintaan)
    {
        $this->cekMilikSupplier($permintaan);
        $request->merge([
            'catatan' => $request->catatan ?? $request->catatan_supplier,
        ]);

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

        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

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

        $request->merge([
            'tgl_kirim' => $request->tgl_kirim ?? $request->tanggal_kirim,
        ]);

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
