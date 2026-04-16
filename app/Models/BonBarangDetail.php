<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonBarangDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'bon_barang_id',
        'barang_id',
        'jumlah_diminta',
        'jumlah_disetujui',
        'status_detail',
        'catatan',
    ];

    protected $casts = [
        'jumlah_diminta' => 'integer',
        'jumlah_disetujui' => 'integer',
    ];

    public function bonBarang(): BelongsTo
    {
        return $this->belongsTo(BonBarang::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function getJumlahDiterimaAttribute()
    {
        return $this->jumlah_disetujui ?? 0;
    }

    public function isDisetujui()
    {
        return $this->status_detail === 'disetujui';
    }

    public function isDitolak()
    {
        return $this->status_detail === 'ditolak';
    }

    public function isSebagian()
    {
        return $this->status_detail === 'sebagian';
    }

    public function isMenunggu()
    {
        return $this->status_detail === 'menunggu';
    }
}
