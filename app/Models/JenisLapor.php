<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisLapor extends Model
{
    use HasUuids;
    use SoftDeletes;
    protected $fillable = ['nama', 'no_urut', 'keterangan', 'jenis', 'tanggal_awal', 'tanggal_akhir', 'tanggal_lapor_awal', 'tanggal_lapor_akhir', 'tanggal_data_awal', 'tanggal_data_akhir'];
}
