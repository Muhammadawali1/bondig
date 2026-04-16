<?php

namespace App\Http\Controllers\atasan;

use App\Models\BonBarang;
use App\Models\BonBarangDetail;
use App\Models\Barang;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonBarangController extends \App\Http\Controllers\Controller
{
    // ATASAN METHODS
    public function index()
    {
        $userDivisi = auth()->user()->divisi;
        
        $bonBarangs = BonBarang::with(['details.barang', 'pegawai'])
            ->where('divisi', $userDivisi)
            ->whereIn('status', ['menunggu_atasan', 'menunggu_gudang', 'ditolak'])
            ->latest()
            ->get();
        
        return view('atasan.bon.index', compact('bonBarangs'));
    }

    public function show($id)
    {
        $bonBarang = BonBarang::with(['details.barang', 'pegawai'])
            ->findOrFail($id);

        // Prevent access to bon approved by gudang
        if ($bonBarang->status === 'disetujui') {
            return redirect()->route('atasan.bon.index');
        }

        $barangs = Barang::all();

        return view('atasan.bon.show', compact('bonBarang', 'barangs'));
    }

    public function approve(Request $request, $id)
    {
        $request->validate([
            'detail_id' => 'required|array',
            'detail_id.*' => 'exists:bon_barang_details,id',
            'jumlah_disetujui' => 'required|array',
            'jumlah_disetujui.*' => 'required|integer|min:1',
            'status_detail' => 'required|array',
            'status_detail.*' => 'required|in:disetujui,sebagian,ditolak',
            'catatan' => 'nullable|array',
            'catatan.*' => 'nullable|string|max:255'
        ]);

        $bonBarang = BonBarang::findOrFail($id);

        DB::beginTransaction();
        try {
            // Update bon details
            foreach ($request->detail_id as $index => $detailId) {
                $detail = BonBarangDetail::find($detailId);
                if ($detail) {
                    $detail->update([
                        'jumlah_disetujui' => $request->jumlah_disetujui[$index],
                        'status_detail' => $request->status_detail[$index],
                        'catatan' => $request->catatan[$index] ?? null,
                    ]);
                }
            }

            // Update bon status
            $bonBarang->update([
                'status' => 'menunggu_gudang',
                'tanggal_atasan' => now(),
            ]);

            // Kirim notifikasi ke pegawai dan gudang
            NotifikasiController::notifyPegawaiBonDisetujuiAtasan($bonBarang);
            NotifikasiController::notifyGudangBonDisetujuiAtasan($bonBarang);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return redirect()->route('atasan.bon.index')
            ->with('success', 'Bon barang berhasil diproses!');
    }

    public function reject(Request $request, $id)
    {
        \Log::info('Atasan Reject Debug - Start', [
            'request_data' => $request->all(),
            'bon_id' => $id,
            'user_id' => auth()->id()
        ]);

        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        \Log::info('Atasan Reject Debug - After Validation', [
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        $bonBarang = BonBarang::findOrFail($id);

        DB::beginTransaction();
        try {
            $bonBarang->update([
                'status' => 'ditolak',
                'tanggal_atasan' => now(),
            ]);

            // Kirim notifikasi ke pegawai
            NotifikasiController::notifyPegawaiBonDitolakAtasan($bonBarang, $request->alasan_penolakan);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        \Log::info('Atasan Reject Debug - Success', [
            'bon_id' => $bonBarang->id,
            'new_status' => $bonBarang->status
        ]);

        return redirect()->route('atasan.bon.index')
            ->with('success', 'Bon barang ditolak!');
    }

    // ATASAN BON SENDIRI
    public function myBonIndex()
    {
        $bonBarangs = BonBarang::with(['details.barang'])
            ->where('pegawai_id', auth()->id())
            ->where('status', '!=', 'disetujui') // Exclude approved bon from atasan my bon view
            ->latest()
            ->get();
        
        return view('atasan.bon.my-index', compact('bonBarangs'));
    }

    public function myBonCreate(Request $request)
    {
        $barangs = Barang::all();
        $selectedBarang = null;
        
        if ($request->has('barang_id')) {
            $selectedBarang = Barang::find($request->barang_id);
        }
        
        return view('atasan.bon.my-create', compact('barangs', 'selectedBarang'));
    }

    public function myBonStore(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|array',
            'barang_id.*' => 'required|exists:barangs,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $user = auth()->user();
        
        $bonBarang = BonBarang::create([
            'pegawai_id' => $user->id,
            'divisi' => $user->divisi,
            'status' => 'menunggu_gudang', // Langsung ke gudang
            'keterangan' => $request->keterangan,
            'tanggal_pengajuan' => now(),
            'tanggal_atasan' => now(), // Auto approve oleh atasan sendiri
        ]);

        // Create bon details
        foreach ($request->barang_id as $index => $barangId) {
            BonBarangDetail::create([
                'bon_barang_id' => $bonBarang->id,
                'barang_id' => $barangId,
                'jumlah_diminta' => $request->jumlah[$index],
                'jumlah_disetujui' => $request->jumlah[$index],
                'status_detail' => 'disetujui',
            ]);
        }

        // Kirim notifikasi ke gudang
        NotifikasiController::notifyGudangBonDisetujuiAtasan($bonBarang);

        return redirect()->route('atasan.bon.my')
            ->with('success', 'Bon barang berhasil diajukan dan langsung disetujui!');
    }

    public function myBonShow($id)
    {
        $bonBarang = BonBarang::with(['details.barang'])
            ->where('pegawai_id', auth()->id())
            ->findOrFail($id);

        // Prevent access to bon approved by gudang
        if ($bonBarang->status === 'disetujui') {
            return redirect()->route('atasan.bon.my');
        }

        return view('atasan.bon.my-show', compact('bonBarang'));
    }

    public function myBonEdit($id)
    {
        $bonBarang = BonBarang::with(['details.barang'])
            ->where('pegawai_id', auth()->id())
            ->where('status', 'menunggu_gudang')
            ->findOrFail($id);
        
        $barangs = Barang::all();
        
        return view('atasan.bon.my-edit', compact('bonBarang', 'barangs'));
    }

    public function addItem(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'jumlah' => 'required|integer|min:1'
        ]);

        $bonBarang = BonBarang::findOrFail($id);

        if ($bonBarang->status !== 'menunggu_atasan') {
            return redirect()->back()
                ->with('error', 'Hanya bisa menambah barang ke bon yang statusnya menunggu atasan.');
        }

        BonBarangDetail::create([
            'bon_barang_id' => $bonBarang->id,
            'barang_id' => $request->barang_id,
            'jumlah_diminta' => $request->jumlah,
            'jumlah_disetujui' => $request->jumlah,
            'status_detail' => 'menunggu',
        ]);

        return redirect()->back()
            ->with('success', 'Barang berhasil ditambahkan ke bon!');
    }

    public function removeItem($detailId)
    {
        $detail = BonBarangDetail::findOrFail($detailId);
        $bonId = $detail->bon_barang_id;
        
        $bonBarang = BonBarang::findOrFail($bonId);
        
        if ($bonBarang->status !== 'menunggu_atasan') {
            return redirect()->back()
                ->with('error', 'Hanya bisa menghapus barang dari bon yang statusnya menunggu atasan.');
        }

        $detail->delete();

        return redirect()->back()
            ->with('success', 'Barang berhasil dihapus dari bon!');
    }
}
