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
            // Create bon masuk with user input date
            $tanggalMasuk = $request->tanggal_masuk ?? now();
            $bonMasuk = BonMasuk::create([
                'gudang_id' => auth()->id(),
                'supplier' => $request->supplier,
                'tanggal_masuk' => $tanggalMasuk,
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

    public function print($id)
    {
        try {
            $bonMasuk = BonMasuk::with(['details.barang', 'gudang'])
                ->findOrFail($id);
            
            return view('gudang.print.print-harga-form', compact('bonMasuk'));
        } catch (\Exception $e) {
            \Log::error('Print Bon Masuk Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('gudang.bon-masuk.index')
                ->with('error', 'Gagal memuat halaman print. Error: ' . $e->getMessage());
        }
    }

    public function processPrint(Request $request, $id)
    {
        
        // Validate input
        try {
            $request->validate([
                'harga_satuan' => 'array',
                'harga_satuan.*' => 'nullable|numeric|min:0',
                'tanggal_faktur' => 'nullable|integer|min:1|max:31',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('gudang.bon-masuk.print', $id)
                ->with('error', 'Validation failed: ' . implode(', ', $e->errors()->all()));
        }

        // Update harga_satuan and tanggal_faktur
        try {
            $bonMasuk = BonMasuk::findOrFail($id);
            
            // Update harga_satuan for each detail
            if ($request->has('harga_satuan') && is_array($request->harga_satuan)) {
                foreach ($request->harga_satuan as $detailId => $hargaSatuan) {
                    $cleanHarga = str_replace('.', '', $hargaSatuan);
                    $cleanHarga = str_replace(',', '.', $cleanHarga);
                    $numericHarga = is_numeric($cleanHarga) ? floatval($cleanHarga) : 0;
                    
                    // If empty, set default price
                    if ($numericHarga == 0) {
                        $numericHarga = 20000; // Default price
                    }
                    
                    BonMasukDetail::where('id', $detailId)
                        ->where('bon_masuk_id', $bonMasuk->id)
                        ->update(['harga_satuan' => $numericHarga]);
                }
            } else {
                // Set default prices for all details if no data provided
                foreach ($bonMasuk->details as $detail) {
                    BonMasukDetail::where('id', $detail->id)
                        ->update(['harga_satuan' => 20000]);
                }
            }

            // Update tanggal_faktur if provided
            if ($request->has('tanggal_faktur') && $request->tanggal_faktur) {
                $tahun = $bonMasuk->tanggal_masuk->year;
                $bulan = $bonMasuk->tanggal_masuk->month;
                $hari = intval($request->tanggal_faktur);
                
                // Simple validation
                if ($hari >= 1 && $hari <= 31) {
                    $maxDay = \Carbon\Carbon::create($tahun, $bulan, 1)->daysInMonth;
                    $hari = min($hari, $maxDay);
                    $tanggalFaktur = \Carbon\Carbon::create($tahun, $bulan, $hari);
                    $bonMasuk->update(['tanggal_faktur' => $tanggalFaktur]);
                }
            }

            // Refresh the model to get updated data
            $bonMasuk->refresh();
            $bonMasuk->load(['details.barang', 'gudang']);

            return view('gudang.print.print-bon-masuk', compact('bonMasuk'));
        } catch (\Exception $e) {
            return redirect()->route('gudang.bon-masuk.show', $id)
                ->with('error', 'Gagal memproses print. Error: ' . $e->getMessage());
        }
    }

    public function editHarga($id)
    {
        $bonMasuk = BonMasuk::with(['details.barang', 'gudang'])
            ->findOrFail($id);
        
        return view('gudang.bon-masuk.edit-harga', compact('bonMasuk'));
    }

    public function updateHarga(Request $request, $id)
    {
        $request->validate([
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $bonMasuk = BonMasuk::findOrFail($id);
            
            foreach ($request->harga_satuan as $detailId => $hargaSatuan) {
                $detail = BonMasukDetail::findOrFail($detailId);
                $detail->update([
                    'harga_satuan' => $hargaSatuan ? $hargaSatuan : null,
                ]);
            }

            DB::commit();

            return redirect()->route('gudang.bon-masuk.show', $id)
                ->with('success', 'Harga satuan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Update Harga Satuan Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('gudang.bon-masuk.edit-harga', $id)
                ->with('error', 'Gagal memperbarui harga satuan. Error: ' . $e->getMessage());
        }
    }
}
