<?php

namespace App\Models;

use App\Models\Ref\RefPenyampaianKeterangan;
use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Penyampaian extends Model
{
    use HasUuids;
    use Compoships;
    protected $fillable = ['user_id', 'penyampaian_keterangan_id', 'jenis_lapor_id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun', 'nama_wp', 'alamat_objek', 'nominal', 'tipe', 'status', 'keterangan', 'catatan'];

    public function getnopAttribute()
    {
        return $this->kd_propinsi . '.' . $this->kd_dati2 . '.' . $this->kd_kecamatan . '.' . $this->kd_kelurahan . '.' . $this->kd_blok . '.' . $this->no_urut . '.' . $this->kd_jns_op;
    }

    public function bakuAwal()
    {
        return $this->hasOne(
            BakuAwal::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun']
        );
    }

    public function sppt()
    {
        return $this->hasMany(
            Sppt::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op']
        )->where('thn_pajak_sppt', '>=', 2008)->with('pembayaranSppt');
    }

    public function datObjekPajak()
    {
        return $this->belongsTo(
            DatObjekPajak::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op']
        );
    }

    public function penyampaianKeterangan()
    {
        return $this->belongsTo(RefPenyampaianKeterangan::class);
    }
}
