<?php

namespace App\Models;

use App\Enums\Pelayanan\PerubahanAlamatStatus;
use App\Traits\HasUserstamps;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelayananPerubahanAlamat extends Model
{
    use HasUuids, SoftDeletes, HasUserstamps;

    protected $fillable = ['tahun_permohonan', 'bundel_permohonan', 'no_urut_permohonan', 'pemohon_id', 'validasi_id', 'tanggal_permohonan', 'tanggal_validasi', 'status_pelayanan', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'nama_wajib_pajak', 'jalan_op_lama', 'blok_kav_no_op_lama', 'rw_op_lama', 'rt_op_lama', 'jalan_op_baru', 'blok_kav_no_op_baru', 'rw_op_baru', 'rt_op_baru', 'created_by', 'updated_by', 'deleted_by'];

    public function getnopAttribute()
    {
        return $this->kd_propinsi . '.' . $this->kd_dati2 . '.' . $this->kd_kecamatan . '.' . $this->kd_kelurahan . '.' . $this->kd_blok . '.' . $this->no_urut . '.' . $this->kd_jns_op;
    }
    public function getShortNopAttribute()
    {
        return $this->kd_kecamatan . '.' . $this->kd_kelurahan . '.' . $this->kd_blok . '.' . $this->no_urut . '.' . $this->kd_jns_op;
    }
    public function getNomorAttribute()
    {
        return $this->tahun_permohonan . '.' . $this->bundel_permohonan . '.' . $this->no_urut_permohonan;
    }
    public function getStatusAttribute(): array
    {
        return [
            'color' => PerubahanAlamatStatus::tryFrom($this->status_pelayanan)?->color() ?? 'purple',
            'value' => $this->status_pelayanan,
        ];
    }
    public function getGateAttribute()
    {
        $userRole = auth()->user()->getRoleNames()->first();
        $status = $this->status_pelayanan;
        return !(
            ($userRole === 'KELURAHAN' && $status === PerubahanAlamatStatus::SELESAI->value) ||
            ($userRole !== 'KELURAHAN' && in_array($status, [PerubahanAlamatStatus::SELESAI->value, PerubahanAlamatStatus::TOLAK->value]))
        );
    }
}
