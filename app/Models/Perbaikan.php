<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    protected $table      = 'perbaikans';
    protected $primaryKey = 'perbaikanID';

    protected $fillable = [
        'kerusakanID',
        'InstansiID',
        'kode_perbaikan',
        'tindakan_perbaikan',
        'catatan_perbaikan',
        'komponen_diganti',
        'biaya_aktual',
        'foto_sesudah',
        'status',
        'alasan_tidak_bisa',
        'mulai_perbaikan',
        'selesai_perbaikan',
        'teknisi_id',
        'ditugaskan_oleh',
    ];

    protected $casts = [
        'mulai_perbaikan'   => 'datetime',
        'selesai_perbaikan' => 'datetime',
        'biaya_aktual'      => 'decimal:2',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // RELASI
    // ──────────────────────────────────────────────────────────────────────────

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class, 'kerusakanID', 'kerusakanID');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function penugasOleh()
    {
        return $this->belongsTo(User::class, 'ditugaskan_oleh');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EVENTS
    // ──────────────────────────────────────────────────────────────────────────
    
    protected static function booted()
    {
        static::addGlobalScope('instansi', function ($query) {
            if (auth()->check() && auth()->user()->role !== 'super_admin') {
                $query->where('perbaikans.InstansiID', auth()->user()->InstansiID);
            }
        });

        // Sinkronisasi status_asset pada Asset saat status perbaikan berubah
        static::updated(function ($perbaikan) {
            if (! $perbaikan->wasChanged('status')) {
                return;
            }

            $kerusakan = $perbaikan->kerusakan;
            $asset     = $kerusakan?->asset;

            if (! $asset) {
                return;
            }

            match ($perbaikan->status) {
                'tidak_bisa_diperbaiki' => $asset->update([
                    'status_asset' => 'non_aktif',
                    'kondisi'      => 'tidak_berfungsi',
                ]),
                'selesai' => $asset->update([
                    'status_asset' => 'aktif',
                    'kondisi'      => 'baik',
                ]),
                'dalam_perbaikan' => $asset->update(['status_asset' => 'diperbaiki']),
                default => null,
            };
        });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ACCESSOR
    // ──────────────────────────────────────────────────────────────────────────

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu'               => 'Menunggu',
            'dalam_perbaikan'        => 'Dalam Perbaikan',
            'selesai'                => 'Selesai',
            'tidak_bisa_diperbaiki'  => 'Tidak Bisa Diperbaiki',
            default                  => ucfirst($this->status),
        };
    }

    public function getBadgeStatusAttribute(): string
    {
        return match ($this->status) {
            'menunggu'               => 'warning',
            'dalam_perbaikan'        => 'info',
            'selesai'                => 'success',
            'tidak_bisa_diperbaiki'  => 'danger',
            default                  => 'secondary',
        };
    }
}