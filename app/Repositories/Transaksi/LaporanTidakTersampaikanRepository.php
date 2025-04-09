<?php

namespace App\Repositories\Transaksi;

use App\Enums\PenyampaianStatus;
use App\Models\Penyampaian;
use App\Repositories\BakuAwal\BakuAwalRepository;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;

class LaporanTidakTersampaikanRepository
{
     public function __construct(
          protected Penyampaian $model,
          protected JenisBukuRepository $jenisBuku,
          protected SatuanKerjaRepository $satuanKerja,
          protected BakuAwalRepository $bakuAwal,
     ) {}

     public function data($jenis, $satuanKerja)
     {
          $query = $this->model::select('id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'nama_wp', 'alamat_objek', 'nominal', 'keterangan')
               ->where([
                    'kd_propinsi' => $satuanKerja['propinsi'],
                    'kd_dati2' => $satuanKerja['dati2'],
                    'kd_kecamatan' => $satuanKerja['kecamatan'],
                    'tahun' => date('Y'),
                    'tipe' => $jenis,
                    'status' => PenyampaianStatus::TERLAPOR,
               ])->whereIn('kd_kelurahan', $satuanKerja['kelurahan']);
          return $query->orderBy('kd_kelurahan')->orderBy('kd_blok')->orderBy('no_urut')->orderBy('kd_jns_op')->get();
     }
}
