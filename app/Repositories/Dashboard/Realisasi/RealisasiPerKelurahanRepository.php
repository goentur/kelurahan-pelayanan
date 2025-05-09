<?php

namespace App\Repositories\Dashboard\Realisasi;

use App\Models\BakuAwal;
use App\Models\Penyampaian;
use App\Models\SatuanKerja;
use App\Models\Sppt;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use App\Support\Facades\Helpers;
use App\Support\Facades\Memo;
use Illuminate\Support\Facades\DB;

class RealisasiPerKelurahanRepository
{
     public function __construct(
          protected JenisBukuRepository $jenisBuku,
          protected SatuanKerjaRepository $satuanKerja,
     ) {}

     public function data()
     {
          $user = auth()->user();
          return Memo::for3min('tabel-dashboard-realisasi-perkelurahan-' . $user->id, function () use ($user) {
               $satuanKerjas = SatuanKerja::with('bawahan')->when(
                    $user->satuanKerja,
                    fn($query) => $query->where('user_id', $user->id),
                    fn($query) => $query->whereNull('atasan_satuan_kerja_id')
               )->get();

               $dataAtasan = [];
               $finalTotals = $this->initTotals();

               foreach ($satuanKerjas as $satuanKerja) {
                    $dataBawahan = [];
                    $totalsAtasan = $this->initTotals();

                    foreach ($satuanKerja->bawahan as $bawahan) {
                         $dataKelurahan = [];
                         $totalsBawahan = $this->initTotals();

                         foreach ($bawahan->kelurahan as $kelurahan) {
                              $data = [
                                   'bakuAwal' => $this->bakuAwal($kelurahan),
                                   'sppt' => $this->sppt($kelurahan),
                                   'penyampaian' => $this->penyampaian($kelurahan),
                                   'pembayaran' => $this->pembayaran($kelurahan),
                              ];

                              foreach ($data as $key => $value) {
                                   $totalsBawahan[$key]['jumlah'] += $value->jumlah;
                                   $totalsBawahan[$key]['sppt'] += $value->sppt;
                              }

                              $dataKelurahan[$kelurahan->kd_kecamatan . '-' . $kelurahan->kd_kelurahan] = $this->formatData($kelurahan->nm_kelurahan, $data);
                         }

                         $this->sumTotals($totalsAtasan, $totalsBawahan);

                         $dataBawahan[$bawahan->id] = [
                              'nama' => $bawahan->nama,
                              ...$this->formatTotals($totalsBawahan),
                              'kelurahan' => $dataKelurahan,
                         ];
                    }

                    $this->sumTotals($finalTotals, $totalsAtasan);

                    $dataAtasan[$satuanKerja->id] = [
                         'nama' => $satuanKerja->nama,
                         ...$this->formatTotals($totalsAtasan),
                         'bawahan' => $dataBawahan,
                    ];
               }
               $dataAtasan['_total'] = [
                    'nama' => 'TOTAL KESELURUHAN',
                    ...$this->formatTotals($finalTotals),
               ];
               return $dataAtasan;
          });
     }

     protected function whereNOP($query, $satuanKerja, $tahunColumn = 'thn_pajak_sppt')
     {
          return $query->where([
               'kd_propinsi' => $satuanKerja->kd_propinsi,
               'kd_dati2' => $satuanKerja->kd_dati2,
               'kd_kecamatan' => $satuanKerja->kd_kecamatan,
               'kd_kelurahan' => $satuanKerja->kd_kelurahan,
               $tahunColumn => date('Y'),
          ]);
     }

     protected function querySummary($model, $satuanKerja, $tahunColumn = 'thn_pajak_sppt', $extraWhere = null, $extraQuery = null)
     {
          $query = $model::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          );
          $this->whereNOP($query, $satuanKerja, $tahunColumn);
          if ($extraWhere) {
               $query->where($extraWhere);
          }
          if ($extraQuery) {
               $extraQuery($query);
          }
          return $query->first();
     }

     public function bakuAwal($satuanKerja)
     {
          return $this->querySummary(BakuAwal::class, $satuanKerja);
     }

     public function sppt($satuanKerja)
     {
          $query = Sppt::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          );
          $this->whereNOP($query, $satuanKerja);
          return $query->whereIn('status_pembayaran_sppt', [0, 1])
               ->first();
     }

     public function penyampaian($satuanKerja)
     {
          $query = Penyampaian::select(
               DB::raw('COALESCE(SUM(nominal), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(id), 0) as sppt')
          );
          $this->whereNOP($query, $satuanKerja, 'tahun');
          return $query->first();
     }

     public function pembayaran($satuanKerja)
     {
          $query = Sppt::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          );
          $this->whereNOP($query, $satuanKerja);
          return $query->whereIn('status_pembayaran_sppt', [0, 1])
               ->whereHas('pembayaranSppt', function ($q) use ($satuanKerja) {
                    $this->whereNOP($q, $satuanKerja);
               })
               ->first();
     }

     // === Helper Functions ===
     function initTotals()
     {
          return [
               'bakuAwal' => ['jumlah' => 0, 'sppt' => 0],
               'penyampaian' => ['jumlah' => 0, 'sppt' => 0],
               'sppt' => ['jumlah' => 0, 'sppt' => 0],
               'pembayaran' => ['jumlah' => 0, 'sppt' => 0],
          ];
     }

     function sumTotals(&$target, $source)
     {
          foreach ($source as $key => $value) {
               $target[$key]['jumlah'] += $value['jumlah'];
               $target[$key]['sppt'] += $value['sppt'];
          }
     }

     function calculatePercentage($current, $total)
     {
          if ($total == 0) {
               return 0;
          }
          return round(($current / $total) * 100, 2);
     }

     function formatTotals($totals)
     {
          $penyampaianPersen = $totals['bakuAwal']['jumlah'] > 0
               ? round(($totals['penyampaian']['jumlah'] / $totals['bakuAwal']['jumlah']) * 100, 2)
               : 0;

          $pembayaranPersen = $totals['sppt']['sppt'] > 0
               ? round(($totals['pembayaran']['sppt'] / $totals['sppt']['sppt']) * 100, 2)
               : 0;

          return [
               'bakuAwal' => [
                    'jumlah' => Helpers::ribuan($totals['bakuAwal']['jumlah']),
                    'sppt' => Helpers::ribuan($totals['bakuAwal']['sppt']),
               ],
               'penyampaian' => [
                    'jumlah' => Helpers::ribuan($totals['penyampaian']['jumlah']),
                    'sppt' => Helpers::ribuan($totals['penyampaian']['sppt']),
                    'persen' => $penyampaianPersen,
               ],
               'sppt' => [
                    'jumlah' => Helpers::ribuan($totals['sppt']['jumlah']),
                    'sppt' => Helpers::ribuan($totals['sppt']['sppt']),
               ],
               'pembayaran' => [
                    'jumlah' => Helpers::ribuan($totals['pembayaran']['jumlah']),
                    'sppt' => Helpers::ribuan($totals['pembayaran']['sppt']),
                    'persen' => $pembayaranPersen,
               ],
          ];
     }

     function formatData($namaKelurahan, $data)
     {
          $penyampaianPersen = $data['bakuAwal']->jumlah > 0
               ? round(($data['penyampaian']->jumlah / $data['bakuAwal']->jumlah) * 100, 2)
               : 0;
          $pembayaranPersen = $data['sppt']->sppt > 0
               ? round(($data['pembayaran']->sppt / $data['sppt']->sppt) * 100, 2)
               : 0;
          return [
               'nama' => $namaKelurahan,
               'bakuAwal' => [
                    'jumlah' => Helpers::ribuan($data['bakuAwal']->jumlah),
                    'sppt' => Helpers::ribuan($data['bakuAwal']->sppt),
               ],
               'penyampaian' => [
                    'jumlah' => Helpers::ribuan($data['penyampaian']->jumlah),
                    'sppt' => Helpers::ribuan($data['penyampaian']->sppt),
                    'persen' => $penyampaianPersen,
               ],
               'sppt' => [
                    'jumlah' => Helpers::ribuan($data['sppt']->jumlah),
                    'sppt' => Helpers::ribuan($data['sppt']->sppt),
               ],
               'pembayaran' => [
                    'jumlah' => Helpers::ribuan($data['pembayaran']->jumlah),
                    'sppt' => Helpers::ribuan($data['pembayaran']->sppt),
                    'persen' => $pembayaranPersen,
               ],
          ];
     }
}
