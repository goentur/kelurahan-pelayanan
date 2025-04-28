<?php

namespace App\Repositories\Dashboard;

use App\Enums\PenyampaianTipe;
use App\Models\BakuAwal;
use App\Models\JenisLapor;
use App\Models\Penyampaian;
use App\Models\SatuanKerja;
use App\Support\Facades\Memo;
use Illuminate\Support\Facades\DB;

class PenyampianSPPTRepository
{
     public function __construct() {}

     public function data()
     {
          $user = auth()->user();
          return Memo::for3min('tabel-dashboard-penyampaian-sppt-' . $user->id, function () {
               $satuanKerjas = SatuanKerja::with('bawahan')->whereNull('atasan_satuan_kerja_id')->get();
               $jenisLapor = JenisLapor::select('id', 'nama')->where('jenis', PenyampaianTipe::TERSAMPAIKAN)->orderBy('no_urut')->get();

               $jenisLaporFields = [];
               foreach ($jenisLapor as $lapor) {
                    $jenisLaporFields[$lapor->id] = 0;
               }

               $dataAtasan = [];

               foreach ($satuanKerjas as $satuanKerja) {
                    $dataBawahan = [];
                    $baku = 0;

                    $rekapLaporAtasan = $jenisLaporFields;
                    $n = 1;
                    foreach ($satuanKerja->bawahan as $bawahan) {
                         $dataBawahanBaku = $this->baku($bawahan->kelurahan);

                         $rekapLaporBawahan = $jenisLaporFields;
                         $jenisLaporIds = $jenisLapor->pluck('id')->toArray();
                         $penyampaianData = $this->penyampaian($jenisLaporIds, $bawahan->user_id);

                         $rekapLaporBawahan = [];
                         foreach ($jenisLaporIds as $id) {
                              $rekapLaporBawahan[$id] = $penyampaianData[$id] ?? 0;
                         }

                         $totalBawahan = array_sum($rekapLaporBawahan);
                         $sisaBawahan = $dataBawahanBaku - $totalBawahan;
                         $persenBawahan = $dataBawahanBaku > 0 ? round(($totalBawahan / $dataBawahanBaku) * 100, 2) : 0;

                         $dataBawahan[] = array_merge([
                              'kode' => $n++,
                              'nama' => $bawahan->nama,
                              'baku' => $dataBawahanBaku,
                              'total' => $totalBawahan,
                              'sisa' => $sisaBawahan,
                              'persen' => $persenBawahan,
                         ], $rekapLaporBawahan);

                         foreach ($rekapLaporBawahan as $key => $val) {
                              $rekapLaporAtasan[$key] += $val;
                         }

                         $baku += $dataBawahanBaku;
                    }

                    $totalAtasan = array_sum($rekapLaporAtasan);
                    $sisaAtasan = $baku - $totalAtasan;
                    $persenAtasan = $baku > 0 ? round(($totalAtasan / $baku) * 100, 2) : 0;

                    $dataAtasan[] = array_merge([
                         'kode' => $satuanKerja->kode_ref,
                         'nama' => $satuanKerja->nama,
                         'baku' => $baku,
                         'total' => $totalAtasan,
                         'sisa' => $sisaAtasan,
                         'persen' => $persenAtasan,
                         'bawahan' => $dataBawahan,
                    ], $rekapLaporAtasan);
               }
               return $dataAtasan;
          });
     }
     protected function baku($kelurahans)
     {
          if (empty($kelurahans)) {
               return 0;
          }
          $kelurahanPertama = $kelurahans[0];
          $kdKelurahans = collect($kelurahans)->pluck('kd_kelurahan')->toArray();

          return BakuAwal::where([
               'kd_propinsi' => $kelurahanPertama->kd_propinsi,
               'kd_dati2' => $kelurahanPertama->kd_dati2,
               'kd_kecamatan' => $kelurahanPertama->kd_kecamatan,
               'thn_pajak_sppt' => date('Y'),
          ])
               ->whereIn('kd_kelurahan', $kdKelurahans)
               ->count();
     }

     protected function penyampaian(array $jenisLaporIds, $userId)
     {
          return Penyampaian::select('jenis_lapor_id', DB::raw('COUNT(id) as jumlah'))
               ->whereIn('jenis_lapor_id', $jenisLaporIds)
               ->where('user_id', $userId)
               ->where('tahun', date('Y'))
               ->groupBy('jenis_lapor_id')
               ->pluck('jumlah', 'jenis_lapor_id')
               ->toArray();
     }
}
