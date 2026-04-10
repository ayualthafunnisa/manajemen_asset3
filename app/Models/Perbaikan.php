<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\NotificationHelper;

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

        // Kirim notifikasi ke admin sekolah saat perbaikan selesai
        static::updated(function ($perbaikan) {
            // Cek jika status berubah menjadi 'selesai' atau 'tidak_bisa_diperbaiki'
            if ($perbaikan->wasChanged('status') && 
                in_array($perbaikan->status, ['selesai', 'tidak_bisa_diperbaiki'])) {
                
                // Cari admin sekolah di instansi yang sama
                $adminSekolah = User::where('InstansiID', $perbaikan->InstansiID)
                    ->where('role', 'admin_sekolah')
                    ->where('status', 'active')
                    ->get();
                
                $kerusakan = $perbaikan->kerusakan;
                $assetName = $kerusakan->asset->nama_asset ?? 'Asset';
                $teknisiName = $perbaikan->teknisi->name ?? 'Teknisi';
                
                $isSelesai = $perbaikan->status === 'selesai';
                
                $title = $isSelesai 
                    ? "✅ Perbaikan Selesai: {$assetName}"
                    : "⚠️ Perbaikan Tidak Bisa: {$assetName}";
                
                $message = $isSelesai
                    ? "Perbaikan oleh {$teknisiName} telah selesai. " . ($perbaikan->biaya_aktual ? "Biaya: Rp " . number_format($perbaikan->biaya_aktual, 0, ',', '.') : "")
                    : "Perbaikan oleh {$teknisiName} tidak dapat diselesaikan. Alasan: " . substr($perbaikan->alasan_tidak_bisa, 0, 100);
                
                $data = [
                    'perbaikan_id' => $perbaikan->perbaikanID,
                    'kerusakan_id' => $kerusakan->kerusakanID,
                    'kode_perbaikan' => $perbaikan->kode_perbaikan,
                    'kode_laporan' => $kerusakan->kode_laporan,
                    'asset_name' => $assetName,
                    'status' => $perbaikan->status,
                    'teknisi_name' => $teknisiName,
                    'biaya_aktual' => $perbaikan->biaya_aktual,
                    'alasan_tidak_bisa' => $perbaikan->alasan_tidak_bisa
                ];
                
                // Kirim notifikasi ke setiap admin sekolah
                foreach ($adminSekolah as $admin) {
                    NotificationHelper::send(
                        $admin->id,
                        $isSelesai ? 'perbaikan_selesai' : 'perbaikan_tidak_bisa',
                        $title,
                        $message,
                        $data,
                        $isSelesai ? '✅' : '⚠️'
                    );
                }
            }
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