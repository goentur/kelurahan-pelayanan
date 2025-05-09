<?php

namespace App\Repositories\Dashboard;

use App\Enums\PenyampaianTipe;
use App\Models\BakuAwal;
use App\Models\JenisLapor;
use App\Models\Penyampaian;
use App\Models\SatuanKerja;
use App\Support\Facades\Helpers;
use App\Support\Facades\Memo;
use Illuminate\Support\Facades\DB;

class PenyampianSPPTRepository
{
     public function __construct() {}

     public function data()
     {
          $user = auth()->user();
          return Memo::for3min('tabel-dashboard-penyampaian-sppt-' . $user->id, function () {
               $jenisLapor = JenisLapor::select('id', 'nama')
                    ->where('jenis', PenyampaianTipe::TERSAMPAIKAN)
                    ->orderBy('no_urut')
                    ->get();

               $laporIds = $jenisLapor->pluck('id')->toArray();
               $laporInit = array_fill_keys($laporIds, 0);

               $dataAtasan = [];
               $grandTotal = ['baku' => 0, 'total' => 0, 'sisa' => 0] + $laporInit;

               foreach (SatuanKerja::with('bawahan')->whereNull('atasan_satuan_kerja_id')->orderBy('kode_ref')->get() as $atasan) {
                    $baku = 0;
                    $rekapAtasan = $laporInit;
                    $bawahanData = [];
                    $kode = 1;

                    foreach ($atasan->bawahan as $bawahan) {
                         $bakuBawahan = $this->baku($bawahan->kelurahan);
                         $penyampaian = $this->penyampaian($laporIds, $bawahan->user_id);
                         $rekapBawahan = array_replace($laporInit, $penyampaian);

                         $total = array_sum($rekapBawahan);
                         $sisa = $bakuBawahan - $total;
                         $persen = $bakuBawahan > 0 ? round(($total / $bakuBawahan) * 100, 2) : 0;

                         $bawahanData[] = [
                              'kode' => $kode++,
                              'nama' => $bawahan->nama,
                              'baku' => Helpers::ribuan($bakuBawahan),
                              'total' => Helpers::ribuan($total),
                              'sisa' => Helpers::ribuan($sisa),
                              'persen' => $persen,
                         ] + $rekapBawahan;

                         foreach ($laporIds as $id) {
                              $rekapAtasan[$id] += $rekapBawahan[$id];
                         }

                         $baku += $bakuBawahan;
                    }

                    $totalAtasan = array_sum($rekapAtasan);
                    $sisaAtasan = $baku - $totalAtasan;
                    $persenAtasan = $baku > 0 ? round(($totalAtasan / $baku) * 100, 2) : 0;

                    $dataAtasan[] = [
                         'kode' => $atasan->kode_ref,
                         'nama' => $atasan->nama,
                         'baku' => Helpers::ribuan($baku),
                         'total' => Helpers::ribuan($totalAtasan),
                         'sisa' => Helpers::ribuan($sisaAtasan),
                         'persen' => $persenAtasan,
                         'bawahan' => $bawahanData,
                    ] + $rekapAtasan;

                    // Akumulasi grand total
                    $grandTotal['baku'] += $baku;
                    $grandTotal['total'] += $totalAtasan;
                    $grandTotal['sisa'] += $sisaAtasan;
                    foreach ($laporIds as $id) {
                         $grandTotal[$id] += $rekapAtasan[$id];
                    }
               }

               $grandTotal['persen'] = $grandTotal['baku'] > 0
                    ? round(($grandTotal['total'] / $grandTotal['baku']) * 100, 2)
                    : 0;

               $dataAtasan[] = [
                    'kode' => '',
                    'nama' => 'TOTAL',
                    'baku' => Helpers::ribuan($grandTotal['baku']),
                    'total' => Helpers::ribuan($grandTotal['total']),
                    'sisa' => Helpers::ribuan($grandTotal['sisa']),
                    'persen' => $grandTotal['persen'],
                    'bawahan' => [],
               ] + array_intersect_key($grandTotal, $laporInit);

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
