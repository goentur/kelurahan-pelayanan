<?php

namespace App\Repositories\Dashboard\PenyampaianSPPT;

use App\Enums\PenyampaianTipe;
use App\Models\BakuAwal;
use App\Models\Penyampaian;
use App\Models\SatuanKerja;
use App\Support\Facades\Helpers;
use App\Support\Facades\Memo;
use Illuminate\Support\Facades\DB;

class RekapRepository
{
     public function __construct() {}

     public function data()
     {
          $user = auth()->user();

          return Memo::for3min('tabel-dashboard-rekap-penyampaian-sppt-' . $user->id, function () {
               $dataAtasan = [];
               $grandTotal = [
                    'baku' => 0,
                    'nominal' => 0,
                    'jumlah_tersampaikan' => 0,
                    'nominal_tersampaikan' => 0,
                    'jumlah_tidak' => 0,
                    'nominal_tidak' => 0,
                    'sisa_jumlah' => 0,
                    'sisa_nominal' => 0,
               ];

               foreach (SatuanKerja::with('bawahan')->whereNull('atasan_satuan_kerja_id')->orderBy('kode_ref')->get() as $atasan) {
                    $rekapAtasan = [
                         'baku' => 0,
                         'nominal' => 0,
                         'jumlah_tersampaikan' => 0,
                         'nominal_tersampaikan' => 0,
                         'jumlah_tidak' => 0,
                         'nominal_tidak' => 0,
                         'sisa_jumlah' => 0,
                         'sisa_nominal' => 0,
                    ];

                    $bawahanData = [];

                    foreach ($atasan->bawahan as $bawahan) {
                         $bakuJumlah = $this->baku($bawahan->kelurahan);
                         $penyampaian = $this->penyampaian($bawahan->user_id);

                         $jumlah_tersampaikan = $penyampaian['TERSAMPAIKAN_jumlah'] ?? 0;
                         $nominal_tersampaikan = $penyampaian['TERSAMPAIKAN_nominal'] ?? 0;
                         $jumlah_tidak = $penyampaian['TIDAK_jumlah'] ?? 0;
                         $nominal_tidak = $penyampaian['TIDAK_nominal'] ?? 0;

                         $jumlah_sisa = $bakuJumlah->jumlah - ($jumlah_tersampaikan + $jumlah_tidak);
                         $nominal_sisa = 0; // jika tidak ada nominal baku, biarkan 0

                         $bawahanData[] = [
                              'nama' => $bawahan->nama,
                              'jumlah_baku' => Helpers::ribuan($bakuJumlah->jumlah),
                              'jumlah_nominal' => Helpers::ribuan($bakuJumlah->nominal),
                              'jumlah_tersampaikan' => Helpers::ribuan($jumlah_tersampaikan),
                              'nominal_tersampaikan' => Helpers::ribuan($nominal_tersampaikan),
                              'persen_tersampaikan' => $bakuJumlah->jumlah > 0 ? round(($jumlah_tersampaikan / $bakuJumlah->jumlah) * 100, 2) : 0,
                              'jumlah_tidak' => Helpers::ribuan($jumlah_tidak),
                              'nominal_tidak' => Helpers::ribuan($nominal_tidak),
                              'persen_tidak' => $bakuJumlah->jumlah > 0 ? round(($jumlah_tidak / $bakuJumlah->jumlah) * 100, 2) : 0,
                              'jumlah_sisa' => Helpers::ribuan($jumlah_sisa),
                              'nominal_sisa' => Helpers::ribuan($nominal_sisa),
                              'persen_sisa' => $bakuJumlah->jumlah > 0 ? round(($jumlah_sisa / $bakuJumlah->jumlah) * 100, 2) : 0,
                         ];

                         // Akumulasi ke rekap atasan
                         $rekapAtasan['baku'] += $bakuJumlah->jumlah;
                         $rekapAtasan['nominal'] += $bakuJumlah->nominal;
                         $rekapAtasan['jumlah_tersampaikan'] += $jumlah_tersampaikan;
                         $rekapAtasan['nominal_tersampaikan'] += $nominal_tersampaikan;
                         $rekapAtasan['jumlah_tidak'] += $jumlah_tidak;
                         $rekapAtasan['nominal_tidak'] += $nominal_tidak;
                         $rekapAtasan['sisa_jumlah'] += $jumlah_sisa;
                         $rekapAtasan['sisa_nominal'] += $nominal_sisa;
                    }

                    $dataAtasan[] = [
                         'nama' => $atasan->nama,
                         'jumlah_baku' => Helpers::ribuan($rekapAtasan['baku']),
                         'jumlah_nominal' => Helpers::ribuan($rekapAtasan['nominal']),
                         'jumlah_tersampaikan' => Helpers::ribuan($rekapAtasan['jumlah_tersampaikan']),
                         'nominal_tersampaikan' => Helpers::ribuan($rekapAtasan['nominal_tersampaikan']),
                         'persen_tersampaikan' => $rekapAtasan['baku'] > 0 ? round(($rekapAtasan['jumlah_tersampaikan'] / $rekapAtasan['baku']) * 100, 2) : 0,
                         'jumlah_tidak' => Helpers::ribuan($rekapAtasan['jumlah_tidak']),
                         'nominal_tidak' => Helpers::ribuan($rekapAtasan['nominal_tidak']),
                         'persen_tidak' => $rekapAtasan['baku'] > 0 ? round(($rekapAtasan['jumlah_tidak'] / $rekapAtasan['baku']) * 100, 2) : 0,
                         'jumlah_sisa' => Helpers::ribuan($rekapAtasan['sisa_jumlah']),
                         'nominal_sisa' => Helpers::ribuan($rekapAtasan['sisa_nominal']),
                         'persen_sisa' => $rekapAtasan['baku'] > 0 ? round(($rekapAtasan['sisa_jumlah'] / $rekapAtasan['baku']) * 100, 2) : 0,
                         'bawahan' => $bawahanData,
                    ];

                    // Akumulasi ke grand total
                    foreach ($rekapAtasan as $key => $value) {
                         $grandTotal[$key] += $value;
                    }
               }

               $dataAtasan[] = [
                    'nama' => 'TOTAL',
                    'jumlah_baku' => Helpers::ribuan($grandTotal['baku']),
                    'jumlah_nominal' => Helpers::ribuan($grandTotal['nominal']),
                    'jumlah_tersampaikan' => Helpers::ribuan($grandTotal['jumlah_tersampaikan']),
                    'nominal_tersampaikan' => Helpers::ribuan($grandTotal['nominal_tersampaikan']),
                    'persen_tersampaikan' => $grandTotal['baku'] > 0 ? round(($grandTotal['jumlah_tersampaikan'] / $grandTotal['baku']) * 100, 2) : 0,
                    'jumlah_tidak' => Helpers::ribuan($grandTotal['jumlah_tidak']),
                    'nominal_tidak' => Helpers::ribuan($grandTotal['nominal_tidak']),
                    'persen_tidak' => $grandTotal['baku'] > 0 ? round(($grandTotal['jumlah_tidak'] / $grandTotal['baku']) * 100, 2) : 0,
                    'jumlah_sisa' => Helpers::ribuan($grandTotal['sisa_jumlah']),
                    'nominal_sisa' => Helpers::ribuan($grandTotal['sisa_nominal']),
                    'persen_sisa' => $grandTotal['baku'] > 0 ? round(($grandTotal['sisa_jumlah'] / $grandTotal['baku']) * 100, 2) : 0,
                    'bawahan' => [],
               ];

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

          return BakuAwal::select(
               DB::raw('COALESCE(SUM(pbb_yg_harus_dibayar_sppt), 0) as nominal'),
               DB::raw('COALESCE(COUNT(kd_propinsi), 0) as jumlah')
          )->where([
               'kd_propinsi' => $kelurahanPertama->kd_propinsi,
               'kd_dati2' => $kelurahanPertama->kd_dati2,
               'kd_kecamatan' => $kelurahanPertama->kd_kecamatan,
               'thn_pajak_sppt' => date('Y'),
          ])
               ->whereIn('kd_kelurahan', $kdKelurahans)
               ->first();
     }

     protected function penyampaian($userId)
     {
          $result = Penyampaian::select(
               'tipe',
               DB::raw('COUNT(*) as jumlah'),
               DB::raw('SUM(nominal) as nominal')
          )
               ->whereIn('tipe', [PenyampaianTipe::TERSAMPAIKAN, PenyampaianTipe::TIDAK])
               ->where('user_id', $userId)
               ->where('tahun', date('Y'))
               ->groupBy('tipe')
               ->get()
               ->keyBy('tipe');

          return [
               'TERSAMPAIKAN_jumlah' => $result['TERSAMPAIKAN']->jumlah ?? 0,
               'TERSAMPAIKAN_nominal' => $result['TERSAMPAIKAN']->nominal ?? 0,
               'TIDAK_jumlah' => $result['TIDAK']->jumlah ?? 0,
               'TIDAK_nominal' => $result['TIDAK']->nominal ?? 0,
          ];
     }
}
