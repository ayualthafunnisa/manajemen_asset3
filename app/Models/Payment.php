<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'user_id',
        'license_id',
        'order_id',
        'transaction_id',
        'snap_token',
        'amount',
        'payment_type',
        'status',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'midtrans_response' => 'array',
        'paid_at' => 'datetime',
    ];

    // ─── Relasi ────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    // ─── Helpers ───────────────────────────────────────────────
    public function isSuccessful(): bool
    {
        return in_array($this->status, ['settlement', 'capture']);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expire';
    }

    // ─── Scopes ────────────────────────────────────────────────
    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', ['settlement', 'capture']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}