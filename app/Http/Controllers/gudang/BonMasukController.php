<?php

namespace App\Http\Controllers\gudang;

use App\Models\BonMasuk;
use App\Models\BonMasukDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonMasukController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $query = BonMasuk::with(['details.barang', 'gudang']);
        
        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal_masuk', $request->bulan);
        }
        
        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun) {
            $query->whereYear('tanggal_masuk', $request->tahun);
        }
        
        $bonMasuks = $query->latest()->get();
        
        return view('gudang.bon-masuk.index', compact('bonMasuks'));
    }

    public function create()
    {
        $barangs = Barang::all();
        return view('gudang.bon-masuk.create', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier' => 'nullable|string|max:255',
            'tanggal_masuk' => 'nullable|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barangs,id',
            'jumlah_masuk' => 'required|array',
            'jumlah_masuk.*' => 'required|integer|min:1',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create bon masuk
            $bonMasuk = BonMasuk::create([
                'gudang_id' => auth()->id(),
                'supplier' => $request->supplier,
                'tanggal_masuk' => $request->tanggal_masuk ?? now(),
                'status' => 'selesai',
            ]);

            // Add details and update stock
            foreach ($request->barang_id as $index => $barangId) {
                $barang = Barang::find($barangId);
                $jumlahMasuk = $request->jumlah_masuk[$index];

                // Create bon masuk detail
                BonMasukDetail::create([
                    'bon_masuk_id' => $bonMasuk->id,
                    'barang_id' => $barangId,
                    'jumlah_masuk' => $jumlahMasuk,
                    'catatan' => $request->catatan[$index] ?? null,
                ]);

                // Update barang stock (add stock)
                $barang->update([
                    'stok' => $barang->stok + $jumlahMasuk
                ]);
            }

            DB::commit();

            return redirect()->route('gudang.bon-masuk.index')
                ->with('success', 'Bon masuk berhasil disimpan dan stok telah ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Bon Masuk Store Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('gudang.bon-masuk.create')
                ->with('error', 'Gagal menyimpan bon masuk. Error: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $bonMasuk = BonMasuk::with(['details.barang', 'gudang'])
            ->findOrFail($id);
        
        return view('gudang.bon-masuk.show', compact('bonMasuk'));
    }
}
