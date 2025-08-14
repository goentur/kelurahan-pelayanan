<?php

namespace App\Models;

use App\Casts\LeadingZero;
use App\Casts\Uppercase;
use App\Models\Ref\RefStatusSubjekPajak;
use App\Models\Sig\SigBngInfo;
use App\Models\Sig\SigOpBumi;
use App\Models\Sig\SigOpZnt;
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'kd_propinsi' => LeadingZero::class . ':2',
        'kd_dati2' => LeadingZero::class . ':2',
        'kd_kecamatan' => LeadingZero::class . ':3',
        'kd_kelurahan' => LeadingZero::class . ':3',
        'kd_blok' => LeadingZero::class . ':3',
        'no_urut' => LeadingZero::class . ':4',
        'no_persil' => Uppercase::class,
        'jalan_op' => Uppercase::class,
        'blok_kav_no_op' => Uppercase::class,
        'rw_op' => LeadingZero::class . ':2',
        'rt_op' => LeadingZero::class . ':3',
        'tgl_pendataan_op' => 'date',
        'tgl_pemeriksaan_op' => 'date',
        'tgl_perekaman_op' => 'datetime',
    ];

    public function datSubjekPajak()
    {
        return $this->belongsTo(DatSubjekPajak::class, 'subjek_pajak_id', 'subjek_pajak_id');
    }

    public function sppt()
    {
        return $this->hasMany(Sppt::class, $this->primaryKey, $this->primaryKey)->where('thn_pajak_sppt', '>=', 2008)->orderBy('thn_pajak_sppt', 'desc');
    }

    public function spptWithBayar()
    {
        return $this->sppt()->with('pembayaranSppt');
    }

    public function bangunan()
    {
        return $this->hasMany(SigBngInfo::class, $this->primaryKey, $this->primaryKey);
    }

    public function bumi()
    {
        return $this->hasOne(SigOpBumi::class, $this->primaryKey, $this->primaryKey);
    }

    public function znt()
    {
        return $this->hasOne(SigOpZnt::class, $this->primaryKey, $this->primaryKey)->where('thn_nir_znt', date('Y'));
    }

    public function statusWajibPajak()
    {
        return $this->belongsTo(RefStatusSubjekPajak::class, 'kd_status_wp', 'id');
    }

    public function getAlamatAttribute()
    {
        $alamat = [
            $this->jalan_op,
            $this->blok_kav_no_op
        ];
        if (!blank($this->rt_op) && !blank($this->rw_op)) {
            $alamat[] = "RW.{$this->rw_op} RT.{$this->rt_op}";
        }
        return trim(implode(' ', array_filter($alamat)));
    }
}
