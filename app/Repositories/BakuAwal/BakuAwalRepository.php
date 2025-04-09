<?php

namespace App\Repositories\BakuAwal;

use App\Models\BakuAwal;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use Illuminate\Support\Facades\DB;

class BakuAwalRepository
{
     public function __construct(
          protected BakuAwal $model,
          protected JenisBukuRepository $jenisBuku,
          protected SatuanKerjaRepository $satuanKerja,
     ) {}
     public function totalBerdasarkanJenisBuku($jenisBuku, $kelurahan = null)
     {
          $nominal = $this->jenisBuku->dataNominal($jenisBuku);
          return BakuAwal::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          )->where([
               'kd_propinsi' => $kelurahan['propinsi'],
               'kd_dati2' => $kelurahan['dati2'],
               'kd_kecamatan' => $kelurahan['kecamatan'],
               'thn_pajak_sppt' => date('Y'),
          ])->whereIn('kd_kelurahan', $kelurahan['kelurahan'])
               ->whereIn('status_pembayaran_sppt', [0, 1])
               ->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']])
               ->first();
     }
}
