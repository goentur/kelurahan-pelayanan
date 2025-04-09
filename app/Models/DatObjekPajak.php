<?php

namespace App\Models;

use App\Casts\LeadingZero;
use App\Casts\Uppercase;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class DatObjekPajak extends Model
{
    use Compoships;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dat_objek_pajak';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = [
        'kd_propinsi',
        'kd_dati2',
        'kd_kecamatan',
        'kd_kelurahan',
        'kd_blok',
        'no_urut',
        'kd_jns_op'
    ];

    /**
     * The "type" of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'kd_propinsi',
        'kd_dati2',
        'kd_kecamatan',
        'kd_kelurahan',
        'kd_blok',
        'no_urut',
        'kd_jns_op',
        'subjek_pajak_id',
        'no_formulir_spop',
        'no_persil',
        'jalan_op',
        'blok_kav_no_op',
        'rw_op',
        'rt_op',
        'kd_status_cabang',
        'kd_status_wp',
        'total_luas_bumi',
        'total_luas_bng',
        'njop_bumi',
        'njop_bng',
        'status_peta_op',
        'jns_transaksi_op',
        'tgl_pendataan_op',
        'nip_pendata',
        'tgl_pemeriksaan_op',
        'nip_pemeriksa_op',
        'tgl_perekaman_op',
        'nip_perekam_op',
    ];
}
