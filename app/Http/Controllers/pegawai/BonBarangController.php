<?php

namespace App\Http\Controllers\pegawai;

use App\Models\BonBarang;
use App\Models\BonBarangDetail;
use App\Models\Barang;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BonBarangController extends \App\Http\Controllers\Controller
{
    // PEGAWAI METHODS
    public function index()
    {
        $user = auth()->user();
        $userId = auth()->id();
        $status = request('status');
        
        \Log::info('Pegawai Index Debug - Auth Check', [
            'auth_id' => $userId,
            'user_object' => $user->toArray(),
            'user_id_from_object' => $user->id,
            'is_same' => $userId == $user->id,
            'status_filter' => $status
        ]);
        
        $query = BonBarang::where('pegawai_id', $user->id)
            ->with(['details.barang'])
            ->whereNotIn('status', ['disetujui', 'disetujui_sebagian']); // Exclude approved and partially approved bon from pegawai view

        if ($status) {
            $query->where('status', $status);
        }

        $bonBarangs = $query->latest()->get();
        
        return view('pegawai.bon.index', compact('bonBarangs', 'status'));
    }

    public function create(Request $request)
    {
        $barangs = Barang::where('stok', '>', 0)->get();
        $selectedBarang = null;
        
        if ($request->has('barang_id')) {
            $selectedBarang = Barang::find($request->barang_id);
        }
        
        return view('pegawai.bon.create', compact('barangs', 'selectedBarang'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('Pegawai Store Debug - Start', [
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'user_role' => auth()->user()->role,
                'user_divisi' => auth()->user()->divisi
            ]);

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
                'tahun' => now()->year,
                'status' => 'menunggu_atasan',
                'keterangan' => $request->keterangan,
                'tanggal_pengajuan' => now(),
            ]);

            // Create bon details
            foreach ($request->barang_id as $index => $barangId) {
                BonBarangDetail::create([
                    'bon_barang_id' => $bonBarang->id,
                    'barang_id' => $barangId,
                    'jumlah_diminta' => $request->jumlah[$index],
                    'jumlah_disetujui' => $request->jumlah[$index],
                    'status_detail' => 'menunggu',
                ]);
            }

            // Kirim notifikasi ke atasan
            NotifikasiController::notifyAtasanBonMasuk($bonBarang);

            \Log::info('Pegawai Store Debug - Success', [
                'bon_id' => $bonBarang->id,
                'kode_bon' => $bonBarang->kode_bon
            ]);

            return redirect()->route('pegawai.bon.index')
                ->with('success', 'Bon barang berhasil diajukan!');

        } catch (\Exception $e) {
            \Log::error('Pegawai Store Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengajukan bon barang.')
                ->withInput();
        }
    }

    public function show($id)
    {
        $bonBarang = BonBarang::with(['details.barang', 'pegawai'])
            ->where('pegawai_id', auth()->id())
            ->findOrFail($id);

        // Prevent access to bon approved or partially approved by gudang
        if ($bonBarang->status === 'disetujui' || $bonBarang->status === 'disetujui_sebagian') {
            return redirect()->route('pegawai.bon.index');
        }

        return view('pegawai.bon.show', compact('bonBarang'));
    }
}
