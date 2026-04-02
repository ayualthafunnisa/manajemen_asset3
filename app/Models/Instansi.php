<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instansi extends Model
{
    protected $table = 'instansis';
    protected $primaryKey = 'InstansiID';

    protected $fillable = [
        'KodeInstansi',
        'NPSN',
        'NamaSekolah',
        'JenjangSekolah',
        'provinsi_code',     
        'kota_code',         
        'kecamatan_code',     
        'kelurahan_code',
        'KodePos',
        'EmailSekolah',
        'Logo',
        'NamaKepalaSekolah',
        'NIPKepalaSekolah',
        'TanggalBerdiri',
        'Status',

    ];

    protected $casts = [
        'TanggalBerdiri' => 'datetime',
    ];

    public function kategoris()
    {
        return $this->hasMany(Kategori::class, 'InstansiID', 'InstansiID');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'InstansiID', 'InstansiID');
    }

    public function lokasi()
    {
        return $this->hasMany(LokasiAsset::class, 'InstansiID', 'InstansiID');
    }

    public function penyusutans()
    {
        return $this->hasMany(Penyusutan::class, 'InstansiID', 'InstansiID');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'InstansiID', 'InstansiID');
    }

    public function penghapusans()
    {
        return $this->hasMany(Penghapusan::class, 'InstansiID', 'InstansiID');
    }

    public function kerusakans()
    {
        return $this->hasMany(Kerusakan::class, 'InstansiID', 'InstansiID');
    }

    public function provinsi()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Province::class, 'provinsi_code', 'code');
    }

    public function kota()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\City::class, 'kota_code', 'code');
    }

    public function kecamatan()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\District::class, 'kecamatan_code', 'code');
    }

    public function kelurahan()
    {
        return $this->belongsTo(\Laravolt\Indonesia\Models\Village::class, 'kelurahan_code', 'code');
    }
}
