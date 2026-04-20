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
        
        // Use transaction with row locking to ensure atomic operation
        return \DB::transaction(function () use ($currentYear) {
            // Get the last sequence number for this year with row lock
            $lastSequence = \DB::selectOne("
                SELECT CAST(SUBSTRING_INDEX(kode_bon, '-', -1) AS UNSIGNED) as sequence
                FROM bon_barangs
                WHERE kode_bon IS NOT NULL
                AND kode_bon != ''
                AND tahun = ?
                ORDER BY CAST(SUBSTRING_INDEX(kode_bon, '-', -1) AS UNSIGNED) DESC
                LIMIT 1
                FOR UPDATE
            ", [$currentYear]);
            
            if ($lastSequence && $lastSequence->sequence) {
                $sequence = $lastSequence->sequence + 1;
            } else {
                $sequence = 1;
            }
            
            $newCode = 'AT-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
            
            // Double-check if this code already exists (in case of race condition)
            $exists = self::where('kode_bon', $newCode)
                ->where('tahun', $currentYear)
                ->exists();
            
            if ($exists) {
                // If somehow still exists, increment and retry
                do {
                    $sequence++;
                    $newCode = 'AT-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
                    $exists = self::where('kode_bon', $newCode)
                        ->where('tahun', $currentYear)
                        ->exists();
                } while ($exists);
            }
            
            return $newCode;
        });
    }
}
