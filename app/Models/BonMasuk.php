<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonMasuk extends Model
{
    use HasFactory;

    protected $fillable = [
        'gudang_id',
        'supplier',
        'status',
        'tanggal_masuk',
        'tanggal_faktur',
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_faktur' => 'datetime',
    ];

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gudang_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BonMasukDetail::class);
    }
}
