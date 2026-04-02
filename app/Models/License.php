<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_key',
        'start_date',
        'expired_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'expired_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cek apakah lisensi masih aktif & belum expired
     */
    public function isValid()
    {
        return $this->is_active && now()->lte($this->expired_date);
    }
}