<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nip',
        'password',
        'remember_token',
        'role',
        'divisi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }

    /**
     * Override the username field used for authentication.
     */
    public function username()
    {
        return 'nip'; // field email digunakan sebagai NIP
    }

    // Role constants
    const ROLE_ATASAN = 'atasan';
    const ROLE_PEGAWAI = 'pegawai';
    const ROLE_GUDANG = 'gudang';
    const ROLE_ADMINISTRATOR = 'administrator';

    // Check user roles
    public function isAtasan()
    {
        return $this->role === self::ROLE_ATASAN;
    }

    public function isPegawai()
    {
        return $this->role === self::ROLE_PEGAWAI;
    }

    public function isGudang()
    {
        return $this->role === self::ROLE_GUDANG;
    }

    public function isAdministrator()
    {
        return $this->role === self::ROLE_ADMINISTRATOR;
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function unreadNotifications(): HasMany
    {
        return $this->notifikasis()->unread();
    }

    public function passwordChangeRequests(): HasMany
    {
        return $this->hasMany(PasswordChangeRequest::class);
    }

    public function divisiRelation(): BelongsTo
    {
        return $this->belongsTo(Divisi::class, 'divisi');
    }

    // Get all available roles
    public static function getRoles()
    {
        return [
            self::ROLE_ATASAN => 'Atasan',
            self::ROLE_PEGAWAI => 'Pegawai',
            self::ROLE_GUDANG => 'Gudang',
            self::ROLE_ADMINISTRATOR => 'Administrator',
        ];
    }
}
