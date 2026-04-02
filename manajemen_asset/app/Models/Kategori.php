<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori_assets';
    protected $primaryKey = 'KategoriID';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'KodeKategori',
        'NamaKategori',
        'Deskripsi',
        'InstansiID',
    ];

    /**
     * Relasi ke Instansi
     */
    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'KategoriID', 'KategoriID');
    }
}
