<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Penyampaian extends Model
{
    use HasUuids;
    use Compoships;
    protected $fillable = ['user_id', 'penyampaian_keterangan_id', 'jenis_lapor_id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun', 'nama_wp', 'alamat_objek', 'nominal', 'tipe', 'status', 'keterangan', 'catatan'];

    public function bakuAwal()
    {
        return $this->hasOne(
            BakuAwal::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun']
        );
    }
}
