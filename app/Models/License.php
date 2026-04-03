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
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'expired_date' => 'date',
        'is_active'    => 'boolean',
    ];

    // ─── Relasi ────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ───────────────────────────────────────────────

    /** Lisensi valid = aktif DAN belum expired */
    public function isValid(): bool
    {
        return $this->is_active && now()->lte($this->expired_date);
    }

    /** Sisa hari (0 jika sudah expired) */
    public function daysRemaining(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->expired_date, false));
    }

    /** Label status untuk tampilan */
    public function statusLabel(): string
    {
        if (!$this->is_active)            return 'Nonaktif';
        if (now()->gt($this->expired_date)) return 'Expired';
        if ($this->daysRemaining() <= 30)  return 'Segera Expired';
        return 'Aktif';
    }

    /** Perpanjang lisensi N bulan */
    public function extend(int $months = 12, ?string $notes = null): self
    {
        $base = now()->gt($this->expired_date) ? now() : $this->expired_date;

        $this->update([
            'expired_date' => $base->copy()->addMonths($months),
            'is_active'    => true,
            'notes'        => $notes,
        ]);

        return $this;
    }

    // ─── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereDate('expired_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('expired_date', '<', now());
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('is_active', true)
            ->whereDate('expired_date', '>=', now())
            ->whereDate('expired_date', '<=', now()->addDays($days));
    }
}