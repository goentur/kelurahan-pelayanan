<?php

namespace App\Models\Pendataan;

use App\Casts\LeadingZero;
use App\Casts\Uppercase;
use Illuminate\Database\Eloquent\Model;

class PendataanSpopSubjekPajak extends Model
{
    protected $fillable = ['pendataan_spop_id', 'ref_status_subjek_pajak_id', 'ref_pekerjaan_subjek_pajak_id', 'nik', 'npwp', 'nama', 'jalan', 'blok_kav_no', 'rw', 'rt', 'kelurahan', 'kecamatan', 'kota', 'kode_pos', 'no_telp', 'email'];

    protected $casts = [
        'nik' => LeadingZero::class . ':16',
        'nama' => Uppercase::class,
        'jalan' => Uppercase::class,
        'blok_kav_no' => Uppercase::class,
        'rw' => LeadingZero::class . ':2',
        'rt' => LeadingZero::class . ':3',
        'kelurahan' => Uppercase::class,
        'kecamatan' => Uppercase::class,
        'kota' => Uppercase::class,
    ];
}
