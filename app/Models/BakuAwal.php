<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class BakuAwal extends Model
{
    use Compoships;
    protected $table = 'baku_awal';
    public $incrementing = false;
    protected $primaryKey = [
        'kd_propinsi',
        'kd_dati2',
        'kd_kecamatan',
        'kd_kelurahan',
        'kd_blok',
        'no_urut',
        'kd_jns_op',
        'thn_pajak_sppt'
    ];
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt', 'siklus_sppt', 'kd_kanwil_bank', 'kd_kppbb_bank', 'kd_bank_tunggal', 'kd_bank_persepsi', 'kd_tp', 'nm_wp_sppt', 'jln_wp_sppt', 'blok_kav_no_wp_sppt', 'rw_wp_sppt', 'rt_wp_sppt', 'kelurahan_wp_sppt', 'kota_wp_sppt', 'kd_pos_wp_sppt', 'npwp_sppt', 'no_persil_sppt', 'kd_kls_tanah', 'thn_awal_kls_tanah', 'kd_kls_bng', 'thn_awal_kls_bng', 'tgl_jatuh_tempo_sppt', 'luas_bumi_sppt', 'luas_bng_sppt', 'njop_bumi_sppt', 'njop_bng_sppt', 'njop_sppt', 'njoptkp_sppt', 'njkp_sppt', 'pbb_terhutang_sppt', 'faktor_pengurang_sppt', 'pbb_yg_harus_dibayar_sppt', 'status_pembayaran_sppt', 'status_tagihan_sppt', 'status_cetak_sppt', 'tgl_terbit_sppt', 'tgl_cetak_sppt', 'nip_pencetak_sppt', 'tarif_sppt'];

    public function sppt()
    {
        return $this->hasOne(
            Sppt::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt']
        );
    }

    public function penyampaian()
    {
        return $this->hasOne(
            Penyampaian::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt']
        );
    }

    public function datObjekPajak()
    {
        return $this->belongsTo(
            DatObjekPajak::class,
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op'],
            ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op']
        );
    }
}
