<?php

namespace App\Models\Pendataan;

use App\Casts\LeadingZero;
use App\Casts\Uppercase;
use App\Models\Ref\RefJenisPendataanSpop;
use Illuminate\Database\Eloquent\Model;

class PendataanSpop extends Model
{
    protected $fillable = ['uuid', 'user_id', 'ref_jenis_pendataan_spop_id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun', 'jalan', 'blok_kav_no', 'rw', 'rt', 'koordinat', 'keterangan'];

    protected $casts = [
        'kd_propinsi' => LeadingZero::class . ':2',
        'kd_dati2' => LeadingZero::class . ':2',
        'kd_kecamatan' => LeadingZero::class . ':3',
        'kd_kelurahan' => LeadingZero::class . ':3',
        'kd_blok' => LeadingZero::class . ':3',
        'no_urut' => LeadingZero::class . ':4',
        'jalan' => Uppercase::class,
        'blok_kav_no' => Uppercase::class,
        'rw' => LeadingZero::class . ':2',
        'rt' => LeadingZero::class . ':3',
    ];

    public function getnopAttribute()
    {
        return $this->kd_propinsi . '.' . $this->kd_dati2 . '.' . $this->kd_kecamatan . '.' . $this->kd_kelurahan . '.' . $this->kd_blok . '.' . $this->no_urut . '.' . $this->kd_jns_op;
    }
    public function getAlamatAttribute()
    {
        return $this->jalan . ' ' . ($this->blok_kav_no ? 'BLOK ' . $this->blok_kav_no : null) . ' RW.' . $this->rt . ' RT.' . $this->rt;
    }

    public function subjekPajak()
    {
        return $this->hasOne(PendataanSpopSubjekPajak::class);
    }

    public function tanah()
    {
        return $this->hasOne(PendataanSpopTanah::class);
    }

    public function bangunan()
    {
        return $this->hasOne(PendataanSpopBangunan::class);
    }

    public function jenisPendataanSpop()
    {
        return $this->belongsTo(RefJenisPendataanSpop::class);
    }
}
