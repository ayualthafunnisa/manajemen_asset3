<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghapusan extends Model
{
    protected $table = 'penghapusans';
    protected $primaryKey = 'penghapusanID';

    protected $fillable = [
        'assetID',
        'InstansiID',
        'no_surat_penghapusan',
        'tanggal_pengajuan',
        'tanggal_penghapusan',
        'jenis_penghapusan',
        'alasan_penghapusan',
        'nilai_buku',
        'harga_jual',
        'kerugian_keuntungan',
        'deskripsi_penghapusan',
        'dokumen_pendukung',
        'diajukan_oleh',
        'disetujui_oleh',
        'tanggal_persetujuan',
        'status_penghapusan',
        'alasan_penolakan',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_penghapusan' => 'date',
        'tanggal_persetujuan' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI
    |--------------------------------------------------------------------------
    */

    // Relasi ke Asset
    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assetID', 'assetID');
    }

    // Relasi ke Instansi
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    // User yang mengajukan
    public function pengaju()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    // User yang menyetujui
    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSOR (opsional - lebih bagus tanpa simpan kerugian_keuntungan)
    |--------------------------------------------------------------------------
    */

    public function getSelisihAttribute()
    {
        if (!$this->harga_jual) {
            return null;
        }

        return $this->harga_jual - $this->nilai_buku;
    }

    protected static function booted()
    {
        static::addGlobalScope('instansi', function ($query) {
            if (auth()->check() && auth()->user()->role !== 'super_admin') {
                $query->where('InstansiID', auth()->user()->InstansiID);
            }
        });
    }
}