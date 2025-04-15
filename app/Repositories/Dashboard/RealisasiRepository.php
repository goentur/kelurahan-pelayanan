<?php

namespace App\Repositories\Dashboard;

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
               $bakuAwaljumlah = 0;
               $bakuAwalsppt = 0;
               $spptjumlah = 0;
               $spptsppt = 0;
               $penyampaianjumlah = 0;
               $penyampaiansppt = 0;
               $pembayaranjumlah = 0;
               $pembayaransppt = 0;

               foreach ($jenisBuku as $value) {
                    $nominal = $this->jenisBuku->dataNominal($value);
                    $bakuAwal = $this->bakuAwal($nominal, $satuanKerja);
                    $sppt = $this->sppt($nominal, $satuanKerja);
                    $penyampaian = $this->penyampaian($nominal, $satuanKerja);
                    $pembayaran = $this->pembayaran($nominal, $satuanKerja);

                    $bakuAwaljumlah += $bakuAwal->jumlah;
                    $bakuAwalsppt += $bakuAwal->sppt;

                    $spptjumlah += $sppt->jumlah;
                    $spptsppt += $sppt->sppt;

                    $penyampaianjumlah += $penyampaian->jumlah;
                    $penyampaiansppt += $penyampaian->sppt;

                    $pembayaranjumlah += $pembayaran->jumlah;
                    $pembayaransppt += $pembayaran->sppt;

                    $data[$value] = [
                         'bakuAwal' => [
                              'jumlah' => Helpers::ribuan($bakuAwal->jumlah),
                              'sppt' => Helpers::ribuan($bakuAwal->sppt),
                         ],
                         'penyampaian' => [
                              'jumlah' => Helpers::ribuan($penyampaian->jumlah),
                              'sppt' => Helpers::ribuan($penyampaian->sppt),
                         ],
                         'sppt' => [
                              'jumlah' => Helpers::ribuan($sppt->jumlah),
                              'sppt' => Helpers::ribuan($sppt->sppt),
                         ],
                         'pembayaran' => [
                              'jumlah' => Helpers::ribuan($pembayaran->jumlah),
                              'sppt' => Helpers::ribuan($pembayaran->sppt),
                         ],
                    ];
               }

               $data['JUMLAH'] = [
                    'bakuAwal' => [
                         'jumlah' => Helpers::ribuan($bakuAwaljumlah),
                         'sppt' => Helpers::ribuan($bakuAwalsppt),
                    ],
                    'penyampaian' => [
                         'jumlah' => Helpers::ribuan($penyampaianjumlah),
                         'sppt' => Helpers::ribuan($penyampaiansppt),
                    ],
                    'sppt' => [
                         'jumlah' => Helpers::ribuan($spptjumlah),
                         'sppt' => Helpers::ribuan($spptsppt),
                    ],
                    'pembayaran' => [
                         'jumlah' => Helpers::ribuan($pembayaranjumlah),
                         'sppt' => Helpers::ribuan($pembayaransppt),
                    ],
               ];
               return $data;
          });
     }

     protected function whereNOP($query, $satuanKerja, $tahunColumn = 'thn_pajak_sppt')
     {
          return $query
               ->where([
                    'kd_propinsi' => $satuanKerja['propinsi'],
                    'kd_dati2' => $satuanKerja['dati2'],
                    'kd_kecamatan' => $satuanKerja['kecamatan'],
                    $tahunColumn => date('Y'),
               ])
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
