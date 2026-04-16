<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_bon',
        'pegawai_id',
        'divisi',
        'status',
        'keterangan',
        'tanggal_pengajuan',
        'tanggal_atasan',
        'tanggal_gudang',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'tanggal_atasan' => 'datetime',
        'tanggal_gudang' => 'datetime',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BonBarangDetail::class);
    }

    public static function generateKodeBon()
    {
        // Cari semua bon yang memiliki kode bon
        $bonsWithKode = self::whereNotNull('kode_bon')
            ->where('kode_bon', '!=', '')
            ->get();
        
        // Extract nomor urut dari semua kode bon yang ada
        $sequences = [];
        foreach ($bonsWithKode as $bon) {
            // Extract angka dari kode bon (AT-01, AT-02, dll)
            $parts = explode('-', $bon->kode_bon);
            if (count($parts) >= 2) {
                $sequences[] = intval($parts[1]);
            }
        }
        
        // Cari nomor urut tertinggi
        $lastSequence = !empty($sequences) ? max($sequences) : 0;
        $sequence = $lastSequence + 1;
        
        return 'AT-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
    }
}
