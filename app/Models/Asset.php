<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'assets';
    protected $primaryKey = 'assetID';

    protected $fillable = [
        'InstansiID',
        'KategoriID',
        'LokasiID',
        'kode_asset',
        'nama_asset',
        'merk',
        'serial_number',
        'sumber_perolehan',
        'tanggal_perolehan',
        'nilai_perolehan',
        'nilai_residu',
        'umur_ekonomis',
        'kondisi',
        'status_asset',
        'jumlah',
        'satuan',
        'vendor',
        'gambar_asset',
        'dokumen_pendukung',
        'keterangan',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'KategoriID', 'KategoriID');
    }

    public function lokasi()
    {
        return $this->belongsTo(LokasiAsset::class, 'LokasiID', 'LokasiID');
    }

    public function penyusutans()
    {
        return $this->hasMany(Penyusutan::class, 'assetID', 'assetID');
    }

    public function kerusakans()
    {
        return $this->hasMany(Kerusakan::class, 'assetID', 'assetID');
    }

    public function penghapusans()
    {
        return $this->hasMany(Penghapusan::class, 'assetID', 'assetID');
    }

    /*
    |--------------------------------------------------------------------------
    | GLOBAL SCOPE INSTANSI
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::addGlobalScope('instansi', function ($query) {
            if (auth()->check() && auth()->user()->role !== 'super_admin') {
                $query->where('InstansiID', auth()->user()->InstansiID);
            }
        });
    }
}