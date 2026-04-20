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
        'tahun',
        'status',
        'keterangan',
        'alasan_penolakan',
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

    public static function generateKodeBon($year = null)
    {
        $currentYear = $year ?? date('Y');
        
        // Get the last sequence number for this year using a database lock
        $lastBon = self::whereNotNull('kode_bon')
            ->where('kode_bon', '!=', '')
            ->where('tahun', $currentYear)
            ->lockForUpdate()
            ->orderByRaw("CAST(SUBSTRING_INDEX(kode_bon, '-', -1) AS UNSIGNED) DESC")
            ->first();
        
        if ($lastBon) {
            // Extract the sequence number from the last code
            $parts = explode('-', $lastBon->kode_bon);
            $lastSequence = intval(end($parts));
            $sequence = $lastSequence + 1;
        } else {
            $sequence = 1;
        }
        
        return 'AT-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
    }
}
