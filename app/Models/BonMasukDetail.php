<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonMasukDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'bon_masuk_id',
        'barang_id',
        'jumlah_masuk',
        'catatan',
    ];

    public function bonMasuk(): BelongsTo
    {
        return $this->belongsTo(BonMasuk::class);
    }

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
