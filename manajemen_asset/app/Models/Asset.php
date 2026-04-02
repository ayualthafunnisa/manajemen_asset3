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
        'tipe_model',
        'serial_number',
        'nomor_registrasi',
        'sumber_perolehan',
        'tanggal_perolehan',
        'tanggal_penggunaan',
        'nilai_perolehan',
        'nilai_residu',
        'umur_ekonomis',
        'kondisi',
        'status_asset',
        'warna',
        'bahan',
        'dimensi',
        'berat',
        'jumlah',
        'satuan',
        'spesifikasi',
        'vendor',
        'no_faktur',
        'tanggal_faktur',
        'gambar_asset',
        'dokumen_pendukung',
        'keterangan',
    ];

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

    
}
