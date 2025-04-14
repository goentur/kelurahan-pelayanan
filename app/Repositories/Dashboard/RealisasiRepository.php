<?php

namespace App\Repositories\Dashboard;

use App\Models\BakuAwal;
use App\Models\PembayaranSppt;
use App\Models\Penyampaian;
use App\Models\Sppt;
use App\Repositories\Common\JenisBukuRepository;
use App\Support\Facades\Helpers;
use App\Support\Facades\Memo;
use Illuminate\Support\Facades\DB;

class RealisasiRepository
{
     public function __construct(
          protected JenisBukuRepository $jenisBuku,
     ) {}

     public function data(array $jenisBuku, $satuanKerja)
     {
          return Memo::for3min('tabel-dashboard-realisasi', function () use ($jenisBuku, $satuanKerja) {
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
                    $bakuAwal = $this->bakuAwal($value, $satuanKerja);
                    $sppt = $this->sppt($value, $satuanKerja);
                    $penyampaian = $this->penyampaian($value, $satuanKerja);
                    $pembayaran = $this->pembayaran($value, $satuanKerja);

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
                         'sppt' => [
                              'jumlah' => Helpers::ribuan($sppt->jumlah),
                              'sppt' => Helpers::ribuan($sppt->sppt),
                         ],
                         'penyampaian' => [
                              'jumlah' => Helpers::ribuan($penyampaian->jumlah),
                              'sppt' => Helpers::ribuan($penyampaian->sppt),
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
                    'sppt' => [
                         'jumlah' => Helpers::ribuan($spptjumlah),
                         'sppt' => Helpers::ribuan($spptsppt),
                    ],
                    'penyampaian' => [
                         'jumlah' => Helpers::ribuan($penyampaianjumlah),
                         'sppt' => Helpers::ribuan($penyampaiansppt),
                    ],
                    'pembayaran' => [
                         'jumlah' => Helpers::ribuan($pembayaranjumlah),
                         'sppt' => Helpers::ribuan($pembayaransppt),
                    ],
               ];
               return $data;
          });
     }

     public function bakuAwal($jenisBuku, $kelurahan = null)
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
          ])->whereIn('kd_kelurahan', $kelurahan['kelurahan'])->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']])->first();
     }

     public function sppt($jenisBuku, $kelurahan = null)
     {
          $nominal = $this->jenisBuku->dataNominal($jenisBuku);
          return Sppt::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          )->where([
               'kd_propinsi' => $kelurahan['propinsi'],
               'kd_dati2' => $kelurahan['dati2'],
               'kd_kecamatan' => $kelurahan['kecamatan'],
               'thn_pajak_sppt' => date('Y'),
          ])->whereIn('kd_kelurahan', $kelurahan['kelurahan'])->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']])->first();
     }

     public function penyampaian($jenisBuku, $kelurahan = null)
     {
          $nominal = $this->jenisBuku->dataNominal($jenisBuku);
          return Penyampaian::select(
               DB::raw('COALESCE(SUM(nominal), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(id), 0) as sppt')
          )->where([
               'kd_propinsi' => $kelurahan['propinsi'],
               'kd_dati2' => $kelurahan['dati2'],
               'kd_kecamatan' => $kelurahan['kecamatan'],
               'tahun' => date('Y'),
          ])->whereIn('kd_kelurahan', $kelurahan['kelurahan'])->whereBetween('nominal', [$nominal['min'], $nominal['max']])->first();
     }

     public function pembayaran($jenisBuku, $kelurahan = null)
     {
          $nominal = $this->jenisBuku->dataNominal($jenisBuku);
          return Sppt::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as jumlah'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as sppt')
          )->where([
               'kd_propinsi' => $kelurahan['propinsi'],
               'kd_dati2' => $kelurahan['dati2'],
               'kd_kecamatan' => $kelurahan['kecamatan'],
               'thn_pajak_sppt' => date('Y'),
          ])->whereIn('kd_kelurahan', $kelurahan['kelurahan'])
               ->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']])
               ->whereHas('pembayaranSppt', fn($q) => $q->where([
                    'kd_propinsi' => $kelurahan['propinsi'],
                    'kd_dati2' => $kelurahan['dati2'],
                    'kd_kecamatan' => $kelurahan['kecamatan'],
                    'thn_pajak_sppt' => date('Y'),
               ])->whereIn('kd_kelurahan', $kelurahan['kelurahan']))
               ->first();
     }
}
