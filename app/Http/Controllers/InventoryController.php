<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\Mutation;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    // ─── DASHBOARD ───────────────────────────────────────────────────────────
    public function dashboard()
    {
        $totalBahan      = BahanBaku::count();
        $totalProduk     = Produk::count();
        $stokKritis      = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->get();
        $produkKritis    = Produk::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->get();
        $totalMasukHari  = Mutation::where('jenis_mutasi', 'masuk')
                            ->whereDate('created_at', today())
                            ->sum('jumlah');
        $totalKeluarHari = Mutation::where('jenis_mutasi', 'keluar')
                            ->whereDate('created_at', today())
                            ->sum('jumlah');
        $semuaBahan      = BahanBaku::orderBy('kode_bahan')->get();
        $semuaProduk     = Produk::orderBy('nama')->get();

        return view('admin.dashboard-inventory', compact(
            'totalBahan', 'totalProduk', 'stokKritis', 'produkKritis',
            'totalMasukHari', 'totalKeluarHari', 'semuaBahan', 'semuaProduk'
        ));
    }

    // ─── BAHAN BAKU ─────────────────────────────────────────────────────────
    public function indexBahan()
    {
        $bahanBaku = BahanBaku::orderBy('kode_bahan')->paginate(15);
        return view('admin.view-bahan-baku', compact('bahanBaku'));
    }

    public function storeBahan(Request $request)
    {
        $request->validate([
            'kode_bahan'  => 'required|string|max:50|unique:bahan_bakus,kode_bahan',
            'nama_bahan'  => 'required|string|max:150',
            'kategori'    => ['nullable', Rule::in(['kain', 'isi boneka', 'aksesoris'])],
            'satuan'      => ['required', Rule::in(['meter', 'kg', 'pcs'])],
            'stok_saat_ini' => 'required|numeric|min:0',
            'stok_minimum'  => 'required|numeric|min:0',
            'keterangan'  => 'nullable|string',
        ]);

        BahanBaku::create($request->only([
            'kode_bahan',
            'nama_bahan',
            'kategori',
            'satuan',
            'stok_saat_ini',
            'stok_minimum',
            'keterangan',
        ]));

        return redirect()->route('admin.inventory.bahan')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function updateBahan(Request $request, BahanBaku $bahan)
    {
        $request->validate([
            'kode_bahan'  => ['required', 'string', 'max:50', Rule::unique('bahan_bakus', 'kode_bahan')->ignore($bahan)],
            'nama_bahan'  => 'required|string|max:150',
            'kategori'    => ['nullable', Rule::in(['kain', 'isi boneka', 'aksesoris'])],
            'satuan'      => ['required', Rule::in(['meter', 'kg', 'pcs'])],
            'stok_saat_ini' => 'required|numeric|min:0',
            'stok_minimum'  => 'required|numeric|min:0',
            'keterangan'  => 'nullable|string',
        ]);

        $bahan->update($request->only([
            'kode_bahan',
            'nama_bahan',
            'kategori',
            'satuan',
            'stok_saat_ini',
            'stok_minimum',
            'keterangan',
        ]));

        return back()->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function createBahan()
    {
        return view('admin.create-bahan-baku');
    }

    public function editBahan(BahanBaku $bahan)
    {
        return view('admin.edit-bahan-baku', compact('bahan'));
    }

    public function indexProduk()
    {
        $produks = Produk::orderBy('nama')->paginate(15);
        return view('admin.view-produk', compact('produks'));
    }

    public function createProduk()
    {
        return view('admin.create-produk');
    }

    public function editProduk(Produk $produk)
    {
        return view('admin.edit-produk', compact('produk'));
    }

    public function storeProduk(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'ukuran' => ['required', Rule::in(['S', 'M', 'L', 'XL', 'Jumbo'])],
            'harga' => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        Produk::create($request->only([
            'nama',
            'ukuran',
            'harga',
            'stok_saat_ini',
            'stok_minimum',
        ]));

        return redirect()->route('admin.inventory.produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function updateProduk(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|string|max:150',
            'ukuran' => ['required', Rule::in(['S', 'M', 'L', 'XL', 'Jumbo'])],
            'harga' => 'required|numeric|min:0',
            'stok_saat_ini' => 'required|integer|min:0',
            'stok_minimum' => 'required|integer|min:0',
        ]);

        $produk->update($request->only([
            'nama',
            'ukuran',
            'harga',
            'stok_saat_ini',
            'stok_minimum',
        ]));

        return back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function indexMasuk()
    {
        $stokMasuk = Mutation::with('bahanBaku')
            ->where('jenis_mutasi', 'masuk')
            ->latest()
            ->paginate(15);
        $bahanBaku = BahanBaku::orderBy('kode_bahan')->get();
        return view('admin.view-stok-masuk', compact('stokMasuk', 'bahanBaku'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah'        => 'required|numeric|min:0.01',
            'tanggal'       => 'nullable|date',
            'sumber'        => 'nullable|string|max:255',
            'catatan'       => 'nullable|string',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_baku_id);
        $catatan = trim(implode(' | ', array_filter([
            $request->sumber,
            $request->catatan,
        ])));
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : now();

        DB::transaction(function () use ($bahan, $request, $catatan, $tanggal) {
            $bahan->mutations()->create([
                'jenis_mutasi' => 'masuk',
                'jumlah'       => $request->jumlah,
                'catatan'      => $catatan ?: null,
                'created_at'   => $tanggal,
            ]);

            $bahan->increment('stok_saat_ini', $request->jumlah);
        });

        return redirect()->route('admin.inventory.masuk')->with('success', 'Stok masuk berhasil dicatat.');
    }

    // ─── STOK KELUAR ─────────────────────────────────────────────────────────
    public function indexKeluar()
    {
        $stokKeluar = Mutation::with('bahanBaku')
            ->where('jenis_mutasi', 'keluar')
            ->latest()
            ->paginate(15);
        $bahanBaku = BahanBaku::orderBy('kode_bahan')->get();
        return view('admin.view-stok-keluar', compact('stokKeluar', 'bahanBaku'));
    }

    public function storeKeluar(Request $request)
    {
        $request->validate([
            'bahan_baku_id' => 'required|exists:bahan_bakus,id',
            'jumlah'        => 'required|numeric|min:0.01',
            'tanggal'       => 'nullable|date',
            'keperluan'     => 'nullable|string|max:255',
            'keterangan'    => 'nullable|string',
        ]);

        $bahan = BahanBaku::findOrFail($request->bahan_baku_id);

        if ($bahan->stok_saat_ini < $request->jumlah) {
            return back()->withErrors(['jumlah' => 'Stok tidak mencukupi. Stok saat ini: ' . $bahan->stok_saat_ini . ' ' . $bahan->satuan]);
        }

        $catatan = trim(implode(' | ', array_filter([
            $request->keperluan,
            $request->keterangan,
        ])));
        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : now();

        DB::transaction(function () use ($request, $bahan, $catatan, $tanggal) {
            $bahan->mutations()->create([
                'jenis_mutasi' => 'keluar',
                'jumlah'       => $request->jumlah,
                'catatan'      => $catatan ?: null,
                'created_at'   => $tanggal,
            ]);

            $bahan->decrement('stok_saat_ini', $request->jumlah);
        });

        return redirect()->route('admin.inventory.keluar')->with('success', 'Stok keluar berhasil dicatat.');
    }

    // ─── NOTIFIKASI STOK KRITIS ───────────────────────────────────────────────
    public function notifikasi()
    {
        $stokKritis = BahanBaku::whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini')
            ->get();

        return view('admin.notifikasi', compact('stokKritis'));
    }
}
