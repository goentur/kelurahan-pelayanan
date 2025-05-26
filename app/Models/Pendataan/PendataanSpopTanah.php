<?php

namespace App\Models\Pendataan;

use Illuminate\Database\Eloquent\Model;

class PendataanSpopTanah extends Model
{
    protected $fillable = ['pendataan_spop_id', 'luas_tanah', 'no_sertipikat', 'ref_jenis_tanah_id'];
}
