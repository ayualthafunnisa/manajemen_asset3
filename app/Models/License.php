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
        'kode_lisensi',
        'license_type',      // tambahan dari migration
        'duration_months',   // tambahan dari migration
        'start_date',
        'expired_date',
        'is_active',
        'payment_status',
        'approval_status',   // tambahan dari migration
        'rejection_reason',  // tambahan dari migration
        'approved_by',       // tambahan dari migration
        'approved_at',       // tambahan dari migration
    ];

    protected $casts = [
        'start_date' => 'date',
        'expired_date' => 'date',
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // ─── Relasi ────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ─── Helpers ───────────────────────────────────────────────

    /** Lisensi valid = aktif, approved, DAN belum expired */
    public function isValid(): bool
    {
        return $this->is_active && 
               $this->approval_status === 'approved' && 
               now()->lte($this->expired_date);
    }

    /** Sisa hari (0 jika sudah expired) */
    public function daysRemaining(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->expired_date, false));
    }

    /** Label status untuk tampilan */
    public function statusLabel(): string
    {
        if ($this->approval_status === 'pending') return 'Menunggu Approval';
        if ($this->approval_status === 'rejected') return 'Ditolak';
        if (!$this->is_active) return 'Nonaktif';
        if (now()->gt($this->expired_date)) return 'Expired';
        if ($this->daysRemaining() <= 30) return 'Segera Expired';
        return 'Aktif';
    }

    /** Perpanjang lisensi N bulan */
    public function extend(int $months = 12, ?string $notes = null): self
    {
        $base = now()->gt($this->expired_date) ? now() : $this->expired_date;

        $this->update([
            'expired_date' => $base->copy()->addMonths($months),
            'is_active'    => true,
        ]);

        return $this;
    }

    // ─── Scopes ────────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('approval_status', 'approved')
                     ->whereDate('expired_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->whereDate('expired_date', '<', now());
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->where('is_active', true)
            ->where('approval_status', 'approved')
            ->whereDate('expired_date', '>=', now())
            ->whereDate('expired_date', '<=', now()->addDays($days));
    }
}