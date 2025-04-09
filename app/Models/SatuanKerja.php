<?php

namespace App\Models;

use App\Models\Ref\RefKelurahan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatuanKerja extends Model
{
    use HasUuids;
    use SoftDeletes;
    protected $fillable = ['user_id', 'atasan_satuan_kerja_id', 'kode_ref_kelurahan', 'nama'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function atasan()
    {
        return $this->belongsTo(SatuanKerja::class, 'atasan_satuan_kerja_id');
    }

    public function bawahan()
    {
        return $this->hasMany(SatuanKerja::class, 'atasan_satuan_kerja_id');
    }

    public function kelurahan()
    {
        return $this->hasMany(RefKelurahan::class, 'kd_kel_br', 'kode_ref_kelurahan')->where('kd_kecamatan', $this->atasan->kode_ref_kelurahan);
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class)->where('kd_kecamatan', $this->atasan->kode_ref_kelurahan);
    }
}
