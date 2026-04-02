<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyusutan extends Model
{
    protected $table = 'penyusutans';
    protected $primaryKey = 'penyusutanID';

    protected $fillable = [
        'assetID',
        'InstansiID',
        'tahun',
        'bulan',
        'periode_awal',
        'periode_akhir',
        'nilai_awal',
        'nilai_penyusutan',
        'nilai_akhir',
        'akumulasi_penyusutan',
        'metode',
        'persentase_penyusutan',
        'catatan',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'periode_awal' => 'date',
        'periode_akhir' => 'date',
        'nilai_awal' => 'decimal:2',
        'nilai_penyusutan' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'akumulasi_penyusutan' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'assetID', 'assetID');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'InstansiID', 'InstansiID');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}