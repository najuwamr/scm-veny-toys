<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\MetodePengiriman;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DistributionController extends Controller
{
    public function index()
    {
        $distribusis = Distribusi::with(['pesanan.reseller', 'metodePengiriman'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.distribution.index', compact('distribusis'));
    }

    public function metodeIndex()
    {
        $metodes = MetodePengiriman::orderBy('nama')->get();
        return view('admin.distribution.metode', compact('metodes'));
    }

    public function metodeStore(Request $request)
    {
        $request->validate(["nama" => "required|string|max:100"]);
        MetodePengiriman::create(["nama" => $request->nama]);
        return back()->with('success', 'Metode pengiriman berhasil ditambahkan.');
    }

    public function jadwalCreate($pesananId)
    {
        $pesanan = Pesanan::findOrFail($pesananId);
        $metodes = MetodePengiriman::all();
        return view('admin.distribution.jadwal', compact('pesanan', 'metodes'));
    }

    public function jadwalStore(Request $request, $pesananId)
    {
        $request->validate([
            'metode_pengiriman_id' => 'required|exists:metode_pengirimans,id',
            'tgl_dijadwalkan' => 'required|date',
        ]);

        Distribusi::create([
            'id' => (string) Str::uuid(),
            'pesanan_id' => $pesananId,
            'metode_pengiriman_id' => $request->metode_pengiriman_id,
            'status' => 'dijadwalkan',
            'tgl_dijadwalkan' => $request->tgl_dijadwalkan,
        ]);

        return redirect()->route('admin.distribution.index')->with('success', 'Penjadwalan pengiriman berhasil dibuat.');
    }

    public function tracking($id)
    {
        $distribusi = Distribusi::with(['pesanan.reseller', 'metodePengiriman'])->findOrFail($id);
        return view('admin.distribution.tracking', compact('distribusi'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:dijadwalkan,dikirim,dalam_perjalanan,diterima',
            'tgl_dijadwalkan' => 'nullable|date',
            'tgl_diterima' => 'nullable|date',
        ]);

        $distribusi = Distribusi::findOrFail($id);
        $distribusi->status = $request->status;

        if ($request->filled('tgl_dijadwalkan')) {
            $distribusi->tgl_dijadwalkan = $request->tgl_dijadwalkan;
        }

        if ($request->status === 'diterima') {
            $distribusi->tgl_diterima = $request->filled('tgl_diterima') ? $request->tgl_diterima : now();
        }

        $distribusi->save();

        return back()->with('success', 'Status distribusi berhasil diperbarui.');
    }
}
