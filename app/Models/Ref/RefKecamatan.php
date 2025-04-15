<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;

class RefKecamatan extends Model
{
    protected $table = 'ref_kecamatan';
    protected $primaryKey = 'kd_kecamatan';
    public $timestamps = false;
}
