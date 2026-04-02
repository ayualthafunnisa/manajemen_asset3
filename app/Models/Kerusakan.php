<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kerusakan extends Model
{
    protected $table      = 'kerusakans';
    protected $primaryKey = 'kerusakanID';

    protected $fillable = [
        'assetID',
        'InstansiID',
        'LokasiID',
        'kode_laporan',
        'tanggal_laporan',
        'tanggal_kerusakan',
        'jenis_kerusakan',
        'tingkat_kerusakan',
        'foto_kerusakan',
        'deskripsi_kerusakan',
        'prioritas',
        'estimasi_biaya',
        'status_perbaikan',
        'dilaporkan_oleh',
    ];

    protected $casts = [
        'tanggal_laporan'   => 'date',
        'tanggal_kerusakan' => 'date',
    ];

    // ──────────────────────────────────────────────────────────────────────────
    // RELASI
    // ──────────────────────────────────────────────────────────────────────────

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assetID', 'assetID');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiAsset::class, 'LokasiID', 'LokasiID');
    }

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'dilaporkan_oleh');
    }

    /**
     * Relasi ke tabel perbaikans (satu kerusakan bisa punya satu catatan perbaikan aktif).
     */
    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'kerusakanID', 'kerusakanID');
    }

    public function perbaikans()
    {
        return $this->hasMany(Perbaikan::class, 'kerusakanID', 'kerusakanID');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ACCESSOR
    // ──────────────────────────────────────────────────────────────────────────

    public function getLabelPrioritasAttribute(): string
    {
        return match ($this->prioritas) {
            'kritis'  => 'Kritis',
            'tinggi'  => 'Tinggi',
            'sedang'  => 'Sedang',
            'rendah'  => 'Rendah',
            default   => ucfirst($this->prioritas),
        };
    }

    public function getBadgePrioritasAttribute(): string
    {
        return match ($this->prioritas) {
            'kritis' => 'danger',
            'tinggi' => 'warning',
            'sedang' => 'info',
            'rendah' => 'secondary',
            default  => 'secondary',
        };
    }

    public function getLabelStatusAttribute(): string
    {
        return match ($this->status_perbaikan) {
            'dilaporkan' => 'Dilaporkan',
            'diproses'   => 'Diproses',
            'selesai'    => 'Selesai',
            'ditolak'    => 'Ditolak',
            default      => ucfirst($this->status_perbaikan),
        };
    }

    // ──────────────────────────────────────────────────────────────────────────
    // GLOBAL SCOPE — isolasi data per instansi
    // ──────────────────────────────────────────────────────────────────────────

    protected static function booted()
    {
        static::addGlobalScope('instansi', function ($query) {
            if (auth()->check() && auth()->user()->role !== 'super_admin') {
                $query->where('kerusakans.InstansiID', auth()->user()->InstansiID);
            }
        });
    }
}