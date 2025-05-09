<?php

namespace App\Repositories\Dashboard\Realisasi;

use App\Models\BakuAwal;
use App\Models\Penyampaian;
use App\Models\Sppt;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use App\Support\Facades\Helpers;
use App\Support\Facades\Memo;
use Illuminate\Support\Facades\DB;

class RealisasiRepository
{
     public function __construct(
          protected JenisBukuRepository $jenisBuku,
          protected SatuanKerjaRepository $satuanKerja,
     ) {}

     public function data(array $jenisBuku)
     {
          $satuanKerja = $this->satuanKerja->collectionData();
          $user = auth()->user();

          return Memo::for3min('tabel-dashboard-realisasi-' . $user->id, function () use ($jenisBuku, $satuanKerja) {
               $data = [];
               $totals = [
                    'bakuAwal' => ['jumlah' => 0, 'sppt' => 0],
                    'penyampaian' => ['jumlah' => 0, 'sppt' => 0],
                    'sppt' => ['jumlah' => 0, 'sppt' => 0],
                    'pembayaran' => ['jumlah' => 0, 'sppt' => 0],
               ];

               foreach ($jenisBuku as $value) {
                    $nominal = $this->jenisBuku->dataNominal($value);

                    $items = [
                         'bakuAwal' => $this->bakuAwal($nominal, $satuanKerja),
                         'sppt' => $this->sppt($nominal, $satuanKerja),
                         'penyampaian' => $this->penyampaian($nominal, $satuanKerja),
                         'pembayaran' => $this->pembayaran($nominal, $satuanKerja),
                    ];

                    foreach ($items as $key => $item) {
                         $totals[$key]['jumlah'] += $item->jumlah;
                         $totals[$key]['sppt'] += $item->sppt;
                    }

                    $data[$value] = [
                         'bakuAwal' => $this->formatData($items['bakuAwal']),
                         'penyampaian' => $this->formatData($items['penyampaian'], $items['bakuAwal']),
                         'sppt' => $this->formatData($items['sppt']),
                         'pembayaran' => $this->formatData($items['pembayaran'], $items['sppt']),
                    ];
               }

               $data['JUMLAH'] = [
                    'bakuAwal' => $this->formatData((object) $totals['bakuAwal']),
                    'penyampaian' => $this->formatData(
                         (object) $totals['penyampaian'],
                         (object) $totals['bakuAwal']
                    ),
                    'sppt' => $this->formatData((object) $totals['sppt']),
                    'pembayaran' => $this->formatData(
                         (object) $totals['pembayaran'],
                         (object) $totals['sppt'],
                         true
                    ),
               ];
               return $data;
          });
     }

     private function formatData($current, $reference = null, $sppt = false)
     {
          $persen = 0;
          if ($reference && $reference->jumlah > 0) {
               if ($sppt) {
                    $persen = round(($current->sppt / $reference->sppt) * 100, 2);
               } else {
                    $persen = round(($current->jumlah / $reference->jumlah) * 100, 2);
               }
          }

          return [
               'jumlah' => Helpers::ribuan($current->jumlah),
               'sppt' => Helpers::ribuan($current->sppt),
               'persen' => isset($reference) ? $persen : null,
          ];
     }

     protected function whereNOP($query, $satuanKerja, $tahunColumn = 'thn_pajak_sppt')
     {
          return $query
               ->where([
                    'kd_propinsi' => $satuanKerja['propinsi'],
                    'kd_dati2' => $satuanKerja['dati2'],
                    $tahunColumn => date('Y'),
               ])
               ->when(!empty($satuanKerja['kecamatan']), function ($q) use ($satuanKerja) {
                    $q->where('kd_kecamatan', $satuanKerja['kecamatan']);
               })
               ->when(!empty($satuanKerja['kelurahan']), function ($q) use ($satuanKerja) {
                    $q->whereIn('kd_kelurahan', $satuanKerja['kelurahan']);
               });
     }

     public function bakuAwal($nominal, $satuanKerja = null)
     {
          $query = BakuAwal::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          );

          $this->whereNOP($query, $satuanKerja);

          return $query
               ->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']])
               ->first();
     }

     public function sppt($nominal, $satuanKerja = null)
     {
          $query = Sppt::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          );

          $this->whereNOP($query, $satuanKerja);

          return $query
               ->whereIn('status_pembayaran_sppt', [0, 1])
               ->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']])
               ->first();
     }

     public function penyampaian($nominal, $satuanKerja = null)
     {
          $query = Penyampaian::select(
               DB::raw('COALESCE(SUM(nominal), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(id), 0) as sppt')
          );

          // Perhatikan: kolom tahun di sini adalah 'tahun'
          $this->whereNOP($query, $satuanKerja, 'tahun');

          return $query
               ->whereBetween('nominal', [$nominal['min'], $nominal['max']])
               ->first();
     }

     public function pembayaran($nominal, $satuanKerja = null)
     {
          $query = Sppt::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          );

          $this->whereNOP($query, $satuanKerja);

          return $query
               ->whereIn('status_pembayaran_sppt', [0, 1])
               ->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']])
               ->whereHas('pembayaranSppt', function ($q) use ($satuanKerja) {
                    $this->whereNOP($q, $satuanKerja);
               })
               ->first();
     }
}
