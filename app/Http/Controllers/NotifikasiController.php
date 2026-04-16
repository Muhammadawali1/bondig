<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->notifikasis()->with('bonBarang');
        
        // Jika user adalah atasan, filter notifikasi yang terkait bon dari divisinya
        if ($user->role === User::ROLE_ATASAN) {
            $query->whereHas('bonBarang', function ($q) use ($user) {
                $q->where('divisi', $user->divisi);
            });
        }
        
        $notifikasis = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('notifikasi.index', compact('notifikasis'));
    }

    public function unreadCount(): JsonResponse
    {
        $user = auth()->user();
        
        $query = $user->unreadNotifications();
        
        // Jika user adalah atasan, filter notifikasi yang terkait bon dari divisinya
        if ($user->role === User::ROLE_ATASAN) {
            $query->whereHas('bonBarang', function ($q) use ($user) {
                $q->where('divisi', $user->divisi);
            });
        }
        
        $count = $query->count();

        return response()->json(['count' => $count]);
    }

    public function recent(): JsonResponse
    {
        $user = auth()->user();
        
        $query = $user->notifikasis()
            ->with('bonBarang')
            ->unread();
            
        // Jika user adalah atasan, filter notifikasi yang terkait bon dari divisinya
        if ($user->role === User::ROLE_ATASAN) {
            $query->whereHas('bonBarang', function ($q) use ($user) {
                $q->where('divisi', $user->divisi);
            });
        }
        
        $notifikasis = $query->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json($notifikasis);
    }

    public function markAsRead($id): JsonResponse
    {
        $notifikasi = Notifikasi::findOrFail($id);
        
        if ($notifikasi->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notifikasi->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead(): JsonResponse
    {
        $user = auth()->user();
        
        $query = $user->unreadNotifications();
        
        // Jika user adalah atasan, filter notifikasi yang terkait bon dari divisinya
        if ($user->role === User::ROLE_ATASAN) {
            $query->whereHas('bonBarang', function ($q) use ($user) {
                $q->where('divisi', $user->divisi);
            });
        }
        
        $query->update(['dibaca' => true]);

        return response()->json(['success' => true]);
    }

    public static function createNotifikasi($userId, $judul, $pesan, $tipe, $bonBarangId = null)
    {
        return Notifikasi::create([
            'user_id' => $userId,
            'bon_barang_id' => $bonBarangId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'dibaca' => false,
        ]);
    }

    public static function notifyAtasanBonMasuk($bonBarang)
    {
        $atasans = User::where('role', User::ROLE_ATASAN)
            ->where('divisi', $bonBarang->divisi)
            ->get();
        
        foreach ($atasans as $atasan) {
            self::createNotifikasi(
                $atasan->id,
                'Bon Baru Masuk',
                "Bon {$bonBarang->kode_bon} dari {$bonBarang->pegawai->name} menunggu persetujuan Anda",
                Notifikasi::TIPE_BON_MASUK,
                $bonBarang->id
            );
        }
    }

    public static function notifyPegawaiBonDisetujuiAtasan($bonBarang)
    {
        self::createNotifikasi(
            $bonBarang->pegawai_id,
            'Bon Disetujui Atasan',
            "Bon {$bonBarang->kode_bon} telah disetujui oleh atasan dan menunggu persetujuan gudang",
            Notifikasi::TIPE_BON_DISETUJUI_ATASAN,
            $bonBarang->id
        );
    }

    public static function notifyGudangBonDisetujuiAtasan($bonBarang)
    {
        // Gudang universal, tidak perlu filter divisi
        $gudangs = User::where('role', User::ROLE_GUDANG)->get();
        
        foreach ($gudangs as $gudang) {
            self::createNotifikasi(
                $gudang->id,
                'Bon Menunggu Persetujuan Gudang',
                "Bon {$bonBarang->kode_bon} dari divisi {$bonBarang->divisi} telah disetujui atasan dan menunggu persetujuan Anda",
                Notifikasi::TIPE_BON_DISETUJUI_ATASAN,
                $bonBarang->id
            );
        }
    }

    public static function notifyPegawaiBonDisetujuiGudang($bonBarang)
    {
        self::createNotifikasi(
            $bonBarang->pegawai_id,
            'Bon Disetujui Gudang',
            "Bon {$bonBarang->kode_bon} telah disetujui oleh gudang dan siap diproses",
            Notifikasi::TIPE_BON_DISETUJUI_GUDANG,
            $bonBarang->id
        );
    }

    public static function notifyAtasanBonDisetujuiGudang($bonBarang)
    {
        $atasans = User::where('role', User::ROLE_ATASAN)
            ->where('divisi', $bonBarang->divisi)
            ->get();
        
        foreach ($atasans as $atasan) {
            self::createNotifikasi(
                $atasan->id,
                'Bon Disetujui Gudang',
                "Bon {$bonBarang->kode_bon} telah disetujui oleh gudang",
                Notifikasi::TIPE_BON_DISETUJUI_GUDANG,
                $bonBarang->id
            );
        }
    }

    public static function notifyPegawaiBonDitolakAtasan($bonBarang, $alasan = null)
    {
        $pesan = "Bon {$bonBarang->kode_bon} ditolak oleh atasan";
        if ($alasan) {
            $pesan .= ". Alasan: " . $alasan;
        }
        
        self::createNotifikasi(
            $bonBarang->pegawai_id,
            'Bon Ditolak Atasan',
            $pesan,
            Notifikasi::TIPE_BON_DITOLAK_ATASAN,
            $bonBarang->id
        );
    }

    public static function notifyAtasanBonDitolakGudang($bonBarang, $alasan = null)
    {
        $atasans = User::where('role', User::ROLE_ATASAN)
            ->where('divisi', $bonBarang->divisi)
            ->get();
        
        foreach ($atasans as $atasan) {
            $pesan = "Bon {$bonBarang->kode_bon} dari {$bonBarang->pegawai->name} ditolak oleh gudang";
            if ($alasan) {
                $pesan .= ". Alasan: " . $alasan;
            }
            
            self::createNotifikasi(
                $atasan->id,
                'Bon Ditolak Gudang',
                $pesan,
                Notifikasi::TIPE_BON_DITOLAK_GUDANG,
                $bonBarang->id
            );
        }
    }

    public static function notifyPegawaiBonDitolakGudang($bonBarang, $alasan = null)
    {
        $pesan = "Bon {$bonBarang->kode_bon} ditolak oleh gudang";
        if ($alasan) {
            $pesan .= ". Alasan: " . $alasan;
        }
        
        self::createNotifikasi(
            $bonBarang->pegawai_id,
            'Bon Ditolak Gudang',
            $pesan,
            Notifikasi::TIPE_BON_DITOLAK_GUDANG,
            $bonBarang->id
        );
    }
}
