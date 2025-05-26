<?php

namespace App\Models;

use App\Casts\LeadingZero;
use App\Casts\Uppercase;
use App\Models\Concerns\DataDatabaseConnection;
use App\Models\Concerns\RouteBinding;
use App\Traits\CompositeKey;
use Awobaz\Compoships\Compoships;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatSubjekPajak extends Model
{
    use Compoships;
    use CompositeKey;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dat_subjek_pajak';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'subjek_pajak_id';

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
     * The name of the "created at" column.
     *
     * @var string
     */
    // const CREATED_AT = 'created_at';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    // const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subjek_pajak_id',
        'nm_wp',
        'jalan_wp',
        'blok_kav_no_wp',
        'rw_wp',
        'rt_wp',
        'kelurahan_wp',
        'kota_wp',
        'kd_pos_wp',
        'telp_wp',
        'npwp',
        'status_pekerjaan_wp',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'nm_wp' => Uppercase::class,
        'jalan_wp' => Uppercase::class,
        'blok_kav_no_wp' => Uppercase::class,
        'kelurahan_wp' => Uppercase::class,
        'kota_wp' => Uppercase::class,
        'rw_wp' => LeadingZero::class . ':2',
        'rt_wp' => LeadingZero::class . ':3',
    ];

    public function datObjekPajak()
    {
        return $this->hasMany(DatObjekPajak::class, $this->primaryKey, $this->primaryKey);
    }
}
