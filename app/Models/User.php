<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';

    protected $fillable = [
        'InstansiID',
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'phone',
        'status'
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    public function penyusutanDibuat()
    {
        return $this->hasMany(Penyusutan::class, 'created_by');
    }

    public function penyusutanDiupdate()
    {
        return $this->hasMany(Penyusutan::class, 'updated_by');
    }

    public function pengajuanPenghapusan()
    {
        return $this->hasMany(Penghapusan::class, 'diajukan_oleh');
    }

    public function persetujuanPenghapusan()
    {
        return $this->hasMany(Penghapusan::class, 'disetujui_oleh');
    }

    public function laporanKerusakan()
    {
        return $this->hasMany(Kerusakan::class, 'dilaporkan_oleh');
    }

    public function penangananKerusakan()
    {
        return $this->hasMany(Kerusakan::class, 'ditangani_oleh');
    }

    public function license()
    {
        return $this->hasOne(License::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function approvedLicenses()
    {
        return $this->hasMany(License::class, 'approved_by');
    }

    // ─── Helper methods ────────────────────────────────────────
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminSekolah(): bool
    {
        return $this->role === 'admin_sekolah';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function hasActiveLicense(): bool
    {
        $license = $this->license;
        return $license && $license->isValid();
    }

    // ─── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

}
