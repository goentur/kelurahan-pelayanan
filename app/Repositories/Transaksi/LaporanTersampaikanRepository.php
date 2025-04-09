<?php

namespace App\Repositories\Transaksi;

use App\Enums\PenyampaianStatus;
use App\Models\JenisLapor;
use App\Models\Penyampaian;
use App\Repositories\BakuAwal\BakuAwalRepository;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use Illuminate\Support\Facades\DB;

class LaporanTersampaikanRepository
{
     public function __construct(
          protected Penyampaian $model,
          protected JenisBukuRepository $jenisBuku,
          protected SatuanKerjaRepository $satuanKerja,
          protected BakuAwalRepository $bakuAwal,
     ) {}

     public function dataLaporan(JenisLapor $jenisLapor, $jenisBukus, $kelurahan = null)
     {
          $data = [];
          foreach ($jenisBukus as $jenisBuku) {
               $dataBakuAwal = $this->bakuAwal->totalBerdasarkanJenisBuku($jenisBuku['value'], $kelurahan);
               $dataPenyampaianLalu = [
                    'sppt' => 0,
                    'jumlah' => 0
               ];
               if ($jenisLapor->no_urut > 1) {
                    $jenisLaporSebelumnya = JenisLapor::select('id')->where('no_urut', '<', $jenisLapor->no_urut)->get();
                    $idJenisLapor = [];
                    foreach ($jenisLaporSebelumnya as $j) {
                         $idJenisLapor[] = $j->id;
                    }
                    $dataPenyampaianLalu = $this->dataPenyampaian($idJenisLapor, $jenisBuku['value'], $kelurahan);
               }
               $dataPenyampaian = $this->dataPenyampaian([$jenisLapor->id], $jenisBuku['value'], $kelurahan);
               $data[$jenisBuku['label']] = [
                    'baku' => [
                         'sppt' => $dataBakuAwal->sppt,
                         'jumlah' => $dataBakuAwal->jumlah
                    ],
                    'lalu' => [
                         'sppt' => $dataPenyampaianLalu['sppt'],
                         'jumlah' => $dataPenyampaianLalu['jumlah']
                    ],
                    'ini' => [
                         'sppt' => $dataPenyampaian->sppt,
                         'jumlah' => $dataPenyampaian->jumlah
                    ],
               ];
          }
          return $data;
     }

     public function dataPenyampaian($jenisLapor, $jenisBuku, $kelurahan = null)
     {
          $nominal = $this->jenisBuku->dataNominal($jenisBuku);
          return $this->model::select(
               DB::raw('COALESCE(SUM(nominal), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(id), 0) as sppt')
          )->where([
               'kd_propinsi' => $kelurahan['propinsi'],
               'kd_dati2' => $kelurahan['dati2'],
               'kd_kecamatan' => $kelurahan['kecamatan'],
               'tahun' => date('Y'),
               'status' => PenyampaianStatus::TERLAPOR,
          ])->whereIn('kd_kelurahan', $kelurahan['kelurahan'])->whereIn('jenis_lapor_id', $jenisLapor)->whereBetween('nominal', [$nominal['min'], $nominal['max']])->first();
     }
}
