<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class Sppt extends Model
{
    use Compoships;
    protected $table = 'sppt';
    public $incrementing = false;
    protected $primaryKey = [
        'kd_propinsi',
        'kd_dati2',
        'kd_kecamatan',
        'kd_kelurahan',
        'kd_blok',
        'no_urut',
        'kd_jns_op',
        'thn_pajak_sppt'
    ];
    protected $keyType = 'string';
    public $timestamps = false;

    public function datObjekPajak()
    {
        return $this->belongsTo(
            DatObjekPajak::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op']
        );
    }
}
