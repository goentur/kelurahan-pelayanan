<?php

namespace App\Models\Pendataan;

use App\Models\Ref\RefJenisTanah;
use Illuminate\Database\Eloquent\Model;

class PendataanSpopTanah extends Model
{
    protected $fillable = ['pendataan_spop_id', 'luas_tanah', 'no_sertipikat', 'ref_jenis_tanah_id'];

    public function refJenisTanah()
    {
        return $this->belongsTo(RefJenisTanah::class);
    }
}
