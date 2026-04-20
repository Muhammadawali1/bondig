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
        
        // Use sequence table for atomic sequence tracking
        // This is called from within a transaction in the controller
        $sequenceRecord = \DB::table('bon_sequence')
            ->where('year', $currentYear)
            ->lockForUpdate()
            ->first();
        
        if ($sequenceRecord) {
            $sequence = $sequenceRecord->last_sequence + 1;
            \DB::table('bon_sequence')
                ->where('year', $currentYear)
                ->update(['last_sequence' => $sequence]);
        } else {
            $sequence = 1;
            \DB::table('bon_sequence')->insert([
                'year' => $currentYear,
                'last_sequence' => $sequence
            ]);
        }
        
        return 'AT-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
    }
}
