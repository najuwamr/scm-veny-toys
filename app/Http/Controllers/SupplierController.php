<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\BahanBakuSupplier;
use App\Models\PermintaanPengadaan;
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

        $offerCount = BahanBakuSupplier::where('supplier_id', $supplier->id)->count();
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
            'offerCount',
            'requestsPending',
            'requestsApproved',
            'requestsTotal',
            'outstandingValue'
        ));
    }

    public function offersIndex()
    {
        $supplier = $this->getSupplier();

        if (!$supplier) {
            return redirect()->route('supplier.dashboard')
                ->with('error', 'Akun supplier Anda belum dihubungkan dengan data supplier. Silakan hubungi admin.');
        }

        $offers = BahanBakuSupplier::with('bahanBaku')
            ->where('supplier_id', $supplier->id)
            ->get();

        return view('supplier.offers.index', compact('offers'));
    }

    public function createOffer()
    {
        $bahanBakus = BahanBaku::all();

        return view('supplier.offers.create', compact('bahanBakus'));
    }

    public function storeOffer(Request $request)
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

        return redirect()->route('supplier.offers.index')
            ->with('success', 'Penawaran bahan baku berhasil disimpan.');
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
}
