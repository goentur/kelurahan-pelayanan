<?php

namespace App\Models\Pendataan;

use App\Models\Ref\RefAtap;
use App\Models\Ref\RefDinding;
use App\Models\Ref\RefJenisBangunan;
use App\Models\Ref\RefKondisi;
use App\Models\Ref\RefKonstruksi;
use App\Models\Ref\RefLangit;
use App\Models\Ref\RefLantai;
use Illuminate\Database\Eloquent\Model;

class PendataanSpopBangunan extends Model
{
    protected $fillable = ['pendataan_spop_id', 'ref_jenis_bangunan_id', 'luas_bangunan', 'jumlah_lantai', 'tahun_dibangun', 'tahun_renovasi', 'daya_listrik', 'jumlah_ac', 'ref_kondisi_id', 'ref_konstruksi_id', 'ref_atap_id', 'ref_dinding_id', 'ref_lantai_id', 'ref_langit_id'];

    public function jenisBangunan()
    {
        return $this->belongsTo(RefJenisBangunan::class);
    }

    public function refKondisi()
    {
        return $this->belongsTo(RefKondisi::class);
    }
    public function refKonstruksi()
    {
        return $this->belongsTo(RefKonstruksi::class);
    }
    public function refAtap()
    {
        return $this->belongsTo(RefAtap::class);
    }
    public function refDinding()
    {
        return $this->belongsTo(RefDinding::class);
    }
    public function refLantai()
    {
        return $this->belongsTo(RefLantai::class);
    }
    public function refLangit()
    {
        return $this->belongsTo(RefLangit::class);
    }
}
