<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\NotificationHelper;

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
        'nama_pelapor',    
        'telepon_pelapor',
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

    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'kerusakanID', 'kerusakanID');
    }

    public function perbaikans()
    {
        return $this->hasMany(Perbaikan::class, 'kerusakanID', 'kerusakanID');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // EVENTS
    // ──────────────────────────────────────────────────────────────────────────
    
    protected static function booted()
    {
        static::addGlobalScope('instansi', function ($query) {
            if (auth()->check() && auth()->user()->role !== 'super_admin') {
                $query->where('kerusakans.InstansiID', auth()->user()->InstansiID);
            }
        });

        // Kirim notifikasi ke teknisi saat kerusakan baru dilaporkan
        static::created(function ($kerusakan) {
            // Cari semua teknisi di instansi yang sama
            $teknisi = User::where('InstansiID', $kerusakan->InstansiID)
                ->where('role', 'teknisi')
                ->where('status', 'active')
                ->get();
            
            // Data untuk notifikasi
            $assetName = $kerusakan->asset->nama_asset ?? 'Asset';
            $priorityLabels = [
                'kritis' => '🔴 KRITIS',
                'tinggi' => '🟠 Tinggi',
                'sedang' => '🟡 Sedang',
                'rendah' => '🟢 Rendah'
            ];
            $priorityLabel = $priorityLabels[$kerusakan->prioritas] ?? $kerusakan->prioritas;
            
            $title = "Keluhan Baru: {$assetName}";
            $message = "{$priorityLabel} - {$kerusakan->deskripsi_kerusakan}";
            
            $data = [
                'kerusakan_id' => $kerusakan->kerusakanID,
                'kode_laporan' => $kerusakan->kode_laporan,
                'asset_name' => $assetName,
                'prioritas' => $kerusakan->prioritas,
                'lokasi' => $kerusakan->lokasi->nama_lokasi ?? 'Tidak diketahui',
                'dilaporkan_oleh' => $kerusakan->pelapor->name ?? 'User'
            ];
            
            // Kirim notifikasi ke setiap teknisi
            foreach ($teknisi as $teknisiUser) {
                NotificationHelper::send(
                    $teknisiUser->id,
                    'keluhan_baru',
                    $title,
                    $message,
                    $data,
                    
                );
            }
        });
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

    // Helper — tampilkan nama pelapor apapun sumbernya
    public function getNamaPelaporDisplayAttribute(): string
    {
        // Kalau lapor via sistem (login)
        if ($this->pelapor) {
            return $this->pelapor->name;
        }

        // Kalau lapor via scan barcode (tanpa login)
        if ($this->nama_pelapor) {
            return $this->nama_pelapor . ' (Publik)';
        }

        return 'Tidak diketahui';
    }

    // Helper — tampilkan kontak pelapor
    public function getKontakPelaporDisplayAttribute(): string
    {
        if ($this->pelapor) {
            return $this->pelapor->phone ?? '-';
        }

        return $this->telepon_pelapor ?? '-';
    }

    // Helper — cek apakah laporan dari publik (scan barcode)
    public function getDariPublikAttribute(): bool
    {
        return is_null($this->dilaporkan_oleh);
    }

}