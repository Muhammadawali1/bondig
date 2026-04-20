<?php

namespace App\Http\Controllers\gudang;

use App\Models\BonBarang;
use App\Models\BonBarangDetail;
use App\Models\Barang;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonBarangController extends \App\Http\Controllers\Controller
{
    // GUDANG METHODS
    public function index()
    {
        $bonBarangs = BonBarang::with(['details.barang', 'pegawai'])
            ->where('status', 'menunggu_gudang')
            ->latest()
            ->get();
        
        return view('gudang.bon.index', compact('bonBarangs'));
    }

    public function history(Request $request)
    {
        $query = BonBarang::with(['details.barang', 'pegawai'])
            ->whereIn('status', ['disetujui', 'sebagian'])
            ->whereNotNull('kode_bon')
            ->where('kode_bon', '!=', '');

        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category === 'disetujui') {
            $query->where('status', 'disetujui');
        }

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan) {
            $query->whereMonth('tanggal_pengajuan', $request->bulan);
        }

        $bonBarangs = $query->orderByRaw("CAST(SUBSTRING(kode_bon, 4) AS UNSIGNED) DESC")->get();

        // Check if 'semua' category is requested
        if ($request->has('category') && $request->category === 'semua') {
            // Return flattened data for 'semua' category
            $bonBarangs = $bonBarangs;
        } else {
            // Default: group by divisi for other categories
            $bonBarangs = $bonBarangs->groupBy('divisi');
        }

        return view('gudang.bon.history', compact('bonBarangs'));
    }

    public function show($id)
    {
        $bonBarang = BonBarang::with(['details.barang', 'pegawai'])
            ->where('status', 'menunggu_gudang')
            ->findOrFail($id);
        
        return view('gudang.bon.show', compact('bonBarang'));
    }

    public function showHistory($id)
    {
        $bonBarang = BonBarang::with(['details.barang', 'pegawai'])
            ->whereIn('status', ['disetujui', 'sebagian'])
            ->whereNotNull('kode_bon')
            ->where('kode_bon', '!=', '')
            ->findOrFail($id);

        return view('gudang.bon.show-history', compact('bonBarang'));
    }

    public function approve(Request $request, $id)
    {
        \Log::info('Gudang Approve Debug - Start', [
            'request_data' => $request->all(),
            'bon_id' => $id,
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'detail_id' => 'required|array',
            'detail_id.*' => 'exists:bon_barang_details,id',
            'jumlah_disetujui' => 'required|array',
            'jumlah_disetujui.*' => 'required|integer|min:0',
            'status_detail' => 'required|array',
            'status_detail.*' => 'required|in:disetujui,sebagian,ditolak',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:255'
        ]);

        $bonBarang = BonBarang::findOrFail($id);

        DB::beginTransaction();
        try {
            // Update bon details and barang stock
            $hasSebagian = false;
            $hasDisetujui = false;
            foreach ($request->detail_id as $index => $detailId) {
                $detail = BonBarangDetail::find($detailId);
                if ($detail) {
                    $barang = Barang::find($detail->barang_id);
                    $jumlahFinal = $request->jumlah_disetujui[$index];

                    // Update bon detail
                    $detail->update([
                        'jumlah_disetujui' => $jumlahFinal,
                        'status_detail' => $request->status_detail[$index],
                        'catatan' => $request->catatan[$index] ?? null,
                    ]);

                    // Track status
                    if ($request->status_detail[$index] === 'sebagian') {
                        $hasSebagian = true;
                    }
                    if ($request->status_detail[$index] === 'disetujui') {
                        $hasDisetujui = true;
                    }

                    // Update barang stock if approved or partial
                    if (($request->status_detail[$index] === 'disetujui' || $request->status_detail[$index] === 'sebagian') && $jumlahFinal > 0) {
                        $barang->update([
                            'stok' => $barang->stok - $jumlahFinal
                        ]);
                    }
                }
            }

            // Determine bon status based on detail statuses
            $finalStatus = 'disetujui';
            if ($hasSebagian) {
                $finalStatus = 'sebagian';
            }

            // Update bon status dan generate kode bon
            $bonBarang->update([
                'kode_bon' => BonBarang::generateKodeBon(),
                'status' => $finalStatus,
                'tanggal_gudang' => now(),
            ]);

            // Kirim notifikasi ke pegawai dan atasan
            NotifikasiController::notifyPegawaiBonDisetujuiGudang($bonBarang);
            NotifikasiController::notifyAtasanBonDisetujuiGudang($bonBarang);

            \Log::info('Gudang Approve Debug - Success', [
                'bon_id' => $bonBarang->id,
                'new_status' => $bonBarang->status
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Gudang Approve Debug - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        return redirect()->route('gudang.bon.index')
            ->with('success', 'Bon barang berhasil disetujui dan stok telah dikurangi!');
    }

    public function reject(Request $request, $id)
    {
        \Log::info('Gudang Reject Debug - Start', [
            'request_data' => $request->all(),
            'bon_id' => $id,
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        $bonBarang = BonBarang::findOrFail($id);

        DB::beginTransaction();
        try {
            $bonBarang->update([
                'status' => 'ditolak',
                'tanggal_gudang' => now(),
            ]);

            // Kirim notifikasi ke pegawai dan atasan
            NotifikasiController::notifyPegawaiBonDitolakGudang($bonBarang, $request->alasan_penolakan);
            NotifikasiController::notifyAtasanBonDitolakGudang($bonBarang, $request->alasan_penolakan);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        \Log::info('Gudang Reject Debug - Success', [
            'bon_id' => $bonBarang->id,
            'new_status' => $bonBarang->status
        ]);

        return redirect()->route('gudang.bon.index')
            ->with('success', 'Bon barang ditolak!');
    }

    public function deleteAll()
    {
        \Log::info('Delete All Bon - Start');

        DB::beginTransaction();
        try {
            // Get all bon records
            $bonBarangs = BonBarang::all();
            \Log::info('Delete All Bon - Found ' . $bonBarangs->count() . ' bon records');

            // Delete all bon details first (to handle foreign key constraints)
            foreach ($bonBarangs as $bon) {
                $bon->details()->delete();
            }
            \Log::info('Delete All Bon - Deleted all bon details');

            // Delete all bon records using delete() instead of truncate to avoid foreign key constraint
            BonBarang::query()->delete();
            \Log::info('Delete All Bon - Deleted all bon records');

            DB::commit();
            \Log::info('Delete All Bon - Transaction committed');

            return redirect()->route('gudang.bon.history')
                ->with('success', 'Semua bon barang berhasil dihapus dari database! Total: ' . $bonBarangs->count() . ' bon');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Delete All Bon Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('gudang.bon.history')
                ->with('error', 'Gagal menghapus semua bon barang. Error: ' . $e->getMessage());
        }
    }

    public function editDetail(Request $request, $bonId)
    {
        $request->validate([
            'detail_id' => 'required|exists:bon_barang_details,id',
            'jumlah_disetujui' => 'required|integer|min:0',
            'status_detail' => 'required|in:disetujui,sebagian,ditolak',
            'catatan' => 'nullable|string|max:255'
        ]);

        $detail = BonBarangDetail::findOrFail($request->detail_id);

        DB::beginTransaction();
        try {
            $detail->update([
                'jumlah_disetujui' => $request->jumlah_disetujui,
                'status_detail' => $request->status_detail,
                'catatan' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('success', 'Detail barang berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Edit Detail Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('error', 'Gagal mengupdate detail barang.');
        }
    }

    public function addDetail(Request $request, $bonId)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string|max:255'
        ]);

        $bonBarang = BonBarang::findOrFail($bonId);
        $barang = Barang::findOrFail($request->barang_id);

        DB::beginTransaction();
        try {
            BonBarangDetail::create([
                'bon_barang_id' => $bonBarang->id,
                'barang_id' => $request->barang_id,
                'jumlah_diminta' => $request->jumlah,
                'jumlah_disetujui' => $request->jumlah,
                'status_detail' => 'disetujui',
                'catatan' => $request->catatan
            ]);

            DB::commit();

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('success', 'Barang berhasil ditambahkan ke bon!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Add Detail Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('error', 'Gagal menambahkan barang ke bon.');
        }
    }

}
