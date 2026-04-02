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
        'Gedung',
        'Lantai',
        'Ruangan',
        'PenanggungJawab',
        'TeleponPenanggungJawab',
        'LuasRuangan',
        'JenisLokasi',
        'Status',
        'Keterangan',
    ];

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'LokasiID', 'LokasiID');
    }
}
