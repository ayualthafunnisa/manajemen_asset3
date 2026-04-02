<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiAsset extends Model
{
    protected $table = 'lokasi_assets';
    protected $primaryKey = 'LokasiID';

    protected $fillable = [
        'InstansiID',
        'KodeLokasi',
        'NamaLokasi',
        'PenanggungJawab',
        'TeleponPenanggungJawab',
        'JenisLokasi',
        'Status',
        'Keterangan',
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

    public function assets()
    {
        return $this->hasMany(Asset::class, 'LokasiID', 'LokasiID');
    }

    public function kerusakans()
    {
        return $this->hasMany(Kerusakan::class, 'LokasiID', 'LokasiID');
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