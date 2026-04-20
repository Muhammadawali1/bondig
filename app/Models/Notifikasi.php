<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $fillable = [
        'user_id',
        'bon_barang_id',
        'password_change_request_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    // Tipe notifikasi constants
    const TIPE_BON_MASUK = 'bon_masuk';
    const TIPE_BON_DISETUJUI_ATASAN = 'bon_disetujui_atasan';
    const TIPE_BON_DISETUJUI_GUDANG = 'bon_disetujui_gudang';
    const TIPE_BON_DISETUJUI_SEBAGIAN = 'bon_disetujui_sebagian';
    const TIPE_BON_DITOLAK_ATASAN = 'bon_ditolak_atasan';
    const TIPE_BON_DITOLAK_GUDANG = 'bon_ditolak_gudang';
    const TIPE_PASSWORD_REQUEST = 'password_request';
    const TIPE_PASSWORD_APPROVED = 'password_approved';
    const TIPE_PASSWORD_REJECTED = 'password_rejected';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bonBarang(): BelongsTo
    {
        return $this->belongsTo(BonBarang::class);
    }

    public function passwordChangeRequest(): BelongsTo
    {
        return $this->belongsTo(PasswordChangeRequest::class);
    }

    public static function getTipeNotifikasi()
    {
        return [
            self::TIPE_BON_MASUK => 'Bon Masuk',
            self::TIPE_BON_DISETUJUI_ATASAN => 'Bon Disetujui Atasan',
            self::TIPE_BON_DISETUJUI_GUDANG => 'Bon Disetujui Gudang',
            self::TIPE_BON_DISETUJUI_SEBAGIAN => 'Bon Disetujui Sebagian',
            self::TIPE_BON_DITOLAK_ATASAN => 'Bon Ditolak Atasan',
            self::TIPE_BON_DITOLAK_GUDANG => 'Bon Ditolak Gudang',
            self::TIPE_PASSWORD_REQUEST => 'Permintaan Ubah Password',
            self::TIPE_PASSWORD_APPROVED => 'Password Disetujui',
            self::TIPE_PASSWORD_REJECTED => 'Password Ditolak',
        ];
    }

    public function scopeUnread($query)
    {
        return $query->where('dibaca', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function markAsRead()
    {
        $this->dibaca = true;
        $this->save();
    }
}
