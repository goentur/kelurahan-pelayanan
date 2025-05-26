<?php

namespace App\Models\Pendataan;

use Illuminate\Database\Eloquent\Model;

class PendataanSpopBangunan extends Model
{
    protected $fillable = ['uuid', 'user_id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun', 'jalan', 'blok_kav_no', 'rw', 'rt', 'koordinat'];
}
