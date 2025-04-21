<?php

namespace App\Models;

use App\Models\Ref\RefKecamatan;
use App\Models\Ref\RefKelurahan;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatuanKerja extends Model
{
    use HasUuids;
    use SoftDeletes;
    protected $fillable = ['user_id', 'atasan_satuan_kerja_id', 'kode_ref', 'nama'];

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
        return $this->hasMany(SatuanKerja::class, 'atasan_satuan_kerja_id')->orderBy('kode_ref');
    }

    public function kecamatan()
    {
        return $this->hasOne(RefKecamatan::class, 'kd_kecamatan', 'kode_ref');
    }

    public function kelurahan()
    {
        return $this->hasMany(RefKelurahan::class, 'kd_kel_br', 'kode_ref')->where('kd_kecamatan', $this->atasan?->kode_ref)->orderBy('kd_propinsi')->orderBy('kd_dati2')->orderBy('kd_kecamatan')->orderBy('kd_kelurahan');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class)->where('kd_kecamatan', $this->atasan?->kode_ref);
    }
}
