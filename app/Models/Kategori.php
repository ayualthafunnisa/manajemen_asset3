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

    protected static function booted()
    {
        static::addGlobalScope('instansi', function ($query) {
            if (auth()->check() && auth()->user()->role !== 'super_admin') {
                $query->where('InstansiID', auth()->user()->InstansiID);
            }
        });
    }
    
}
