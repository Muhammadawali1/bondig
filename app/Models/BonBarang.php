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
        
        // Retry mechanism to ensure unique code
        $maxRetries = 5;
        for ($i = 0; $i < $maxRetries; $i++) {
            // Use raw SQL with FOR UPDATE to ensure atomic operation
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
            
            // Check if this code already exists (in case of race condition)
            $exists = self::where('kode_bon', $newCode)
                ->where('tahun', $currentYear)
                ->exists();
            
            if (!$exists) {
                return $newCode;
            }
            
            // If code exists, retry with next iteration
        }
        
        // If all retries failed, throw exception
        throw new \Exception('Failed to generate unique bon code after ' . $maxRetries . ' retries');
    }
}
