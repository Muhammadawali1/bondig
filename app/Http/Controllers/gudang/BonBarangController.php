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
            ->whereIn('status', ['disetujui', 'disetujui_sebagian'])
            ->whereNotNull('kode_bon')
            ->where('kode_bon', '!=', '');
        
        // Filter berdasarkan kategori
        if ($request->has('category') && $request->category === 'disetujui') {
            $query->whereIn('status', ['disetujui', 'disetujui_sebagian']);
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
            ->whereIn('status', ['disetujui', 'disetujui_sebagian'])
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
            // Check if any details have partial approval or if all are rejected
            $hasPartialApproval = false;
            $allRejected = true;
            foreach ($request->status_detail as $status) {
                if ($status === 'sebagian') {
                    $hasPartialApproval = true;
                    $allRejected = false;
                } elseif ($status === 'disetujui') {
                    $allRejected = false;
                }
            }

            // Update bon details and barang stock
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
                    
                    // Update barang stock if approved (full or partial)
                    if (($request->status_detail[$index] === 'disetujui' || $request->status_detail[$index] === 'sebagian') && $jumlahFinal > 0) {
                        $barang->update([
                            'stok' => $barang->stok - $jumlahFinal
                        ]);
                    }
                }
            }

            // Update bon status dan generate kode bon (only if not all rejected)
            if ($allRejected) {
                $bonStatus = 'ditolak';
                $bonBarang->update([
                    'status' => $bonStatus,
                    'tanggal_gudang' => now(),
                ]);
            } else {
                $bonStatus = $hasPartialApproval ? 'disetujui_sebagian' : 'disetujui';
                $bonBarang->update([
                    'kode_bon' => BonBarang::generateKodeBon($bonBarang->tahun),
                    'status' => $bonStatus,
                    'tanggal_gudang' => now(),
                ]);
            }

            // Kirim notifikasi ke pegawai dan atasan berdasarkan status
            if ($hasPartialApproval) {
                NotifikasiController::notifyPegawaiBonDisetujuiSebagian($bonBarang);
                NotifikasiController::notifyAtasanBonDisetujuiSebagian($bonBarang);
            } else {
                NotifikasiController::notifyPegawaiBonDisetujuiGudang($bonBarang);
                NotifikasiController::notifyAtasanBonDisetujuiGudang($bonBarang);
            }

            \Log::info('Gudang Approve Debug - Success', [
                'bon_id' => $bonBarang->id,
                'new_status' => $bonBarang->status,
                'has_partial_approval' => $hasPartialApproval
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
            // Update all detail statuses to 'ditolak'
            $bonBarang->details()->update(['status_detail' => 'ditolak']);

            $bonBarang->update([
                'status' => 'ditolak',
                'alasan_penolakan' => $request->alasan_penolakan,
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

    public function deleteAll(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2100'
        ]);

        $tahun = $request->tahun;
        \Log::info('Delete Bon By Year - Start', ['year' => $tahun]);

        DB::beginTransaction();
        try {
            // Get bon records for the specified year
            $bonBarangs = BonBarang::whereYear('tanggal_pengajuan', $tahun)->get();
            \Log::info('Delete Bon By Year - Found ' . $bonBarangs->count() . ' bon records for year ' . $tahun);

            if ($bonBarangs->count() === 0) {
                return redirect()->route('gudang.bon.history')
                    ->with('error', 'Tidak ada bon barang pada tahun ' . $tahun);
            }

            // Delete bon details first (to handle foreign key constraints)
            foreach ($bonBarangs as $bon) {
                $bon->details()->delete();
            }
            \Log::info('Delete Bon By Year - Deleted all bon details for year ' . $tahun);

            // Delete bon records for the specified year
            BonBarang::whereYear('tanggal_pengajuan', $tahun)->delete();
            \Log::info('Delete Bon By Year - Deleted all bon records for year ' . $tahun);

            // Reset sequence in bon_sequence table for the specified year
            \DB::table('bon_sequence')->where('year', $tahun)->delete();
            \Log::info('Delete Bon By Year - Reset sequence for year ' . $tahun);

            DB::commit();
            \Log::info('Delete Bon By Year - Transaction committed');

            return redirect()->route('gudang.bon.history')
                ->with('success', 'Semua bon barang tahun ' . $tahun . ' berhasil dihapus dari database! Total: ' . $bonBarangs->count() . ' bon');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Delete Bon By Year Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('gudang.bon.history')
                ->with('error', 'Gagal menghapus bon barang tahun ' . $tahun . '. Error: ' . $e->getMessage());
        }
    }

    public function showEditDetail($bonId, $detailId)
    {
        $bonBarang = BonBarang::with(['details.barang', 'pegawai'])
            ->whereIn('status', ['disetujui', 'disetujui_sebagian'])
            ->whereNotNull('kode_bon')
            ->where('kode_bon', '!=', '')
            ->findOrFail($bonId);
        
        $detail = BonBarangDetail::with('barang')->findOrFail($detailId);
        
        return view('gudang.bon.edit-detail', compact('bonBarang', 'detail'));
    }

    public function showAddDetail($bonId)
    {
        $bonBarang = BonBarang::with(['details.barang', 'pegawai'])
            ->whereIn('status', ['disetujui', 'disetujui_sebagian'])
            ->whereNotNull('kode_bon')
            ->where('kode_bon', '!=', '')
            ->findOrFail($bonId);
        
        $existingBarangIds = $bonBarang->details->pluck('barang_id')->toArray();
        $allBarangs = \App\Models\Barang::whereNotIn('id', $existingBarangIds)->get();
        
        return view('gudang.bon.add-detail', compact('bonBarang', 'allBarangs'));
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
        $barang = Barang::findOrFail($detail->barang_id);

        DB::beginTransaction();
        try {
            // Calculate stock difference
            $oldJumlah = $detail->jumlah_disetujui;
            $newJumlah = $request->jumlah_disetujui;
            $difference = $newJumlah - $oldJumlah;

            // Update bon detail
            $detail->update([
                'jumlah_disetujui' => $request->jumlah_disetujui,
                'status_detail' => $request->status_detail,
                'catatan' => $request->catatan
            ]);

            // Adjust stock based on difference
            // If new amount > old amount, deduct more from stock
            // If new amount < old amount, add back to stock
            if ($request->status_detail === 'disetujui' || $request->status_detail === 'sebagian') {
                $barang->update([
                    'stok' => $barang->stok - $difference
                ]);
            } elseif ($request->status_detail === 'ditolak' && $oldJumlah > 0) {
                // If changing from approved to rejected, add back all stock
                $barang->update([
                    'stok' => $barang->stok + $oldJumlah
                ]);
            }

            DB::commit();

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('success', 'Detail barang berhasil diupdate dan stok telah disesuaikan!');
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

    public function deleteDetail(Request $request, $bonId)
    {
        $request->validate([
            'detail_id' => 'required|exists:bon_barang_details,id'
        ]);

        $detail = BonBarangDetail::findOrFail($request->detail_id);
        $barang = Barang::findOrFail($detail->barang_id);

        DB::beginTransaction();
        try {
            // Restore stock since this item is being removed from an approved bon
            $barang->update([
                'stok' => $barang->stok + $detail->jumlah_disetujui
            ]);

            // Delete the detail
            $detail->delete();

            DB::commit();

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('success', 'Barang berhasil dihapus dan stok telah dikembalikan!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Delete Detail Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('error', 'Gagal menghapus barang.');
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
            // Create new bon detail
            BonBarangDetail::create([
                'bon_barang_id' => $bonBarang->id,
                'barang_id' => $request->barang_id,
                'jumlah_diminta' => $request->jumlah,
                'jumlah_disetujui' => $request->jumlah,
                'status_detail' => 'disetujui',
                'catatan' => $request->catatan
            ]);

            // Deduct stock since this is being added to an approved bon
            $barang->update([
                'stok' => $barang->stok - $request->jumlah
            ]);

            DB::commit();

            return redirect()->route('gudang.bon.show-history', $bonId)
                ->with('success', 'Barang berhasil ditambahkan ke bon dan stok telah dikurangi!');
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
