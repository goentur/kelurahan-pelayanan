<?php

namespace App\Models\Pendataan;

use Illuminate\Database\Eloquent\Model;

class PendataanSpopBangunan extends Model
{
    protected $fillable = ['pendataan_spop_id', 'ref_jenis_bangunan_id', 'luas_bangunan', 'jumlah_lantai', 'tahun_dibangun', 'tahun_renovasi', 'daya_listrik', 'jumlah_ac', 'ref_kondisi_id', 'ref_konstruksi_id', 'ref_atap_id', 'ref_dinding_id', 'ref_lantai_id', 'ref_langit_id'];
}
