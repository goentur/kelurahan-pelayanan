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
                    'bayar_jumlah' => 0,
                    'bayar_nominal' => 0,
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
                         'bayar_jumlah' => 0,
                         'bayar_nominal' => 0,
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
                         $nominal_sisa = $bakuJumlah->nominal - ($nominal_tersampaikan + $nominal_tidak);

                         $jumlah_bayar = $bakuJumlah->jumlah_pembayaran;
                         $nominal_bayar = $bakuJumlah->total_dibayar;

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
                              'jumlah_bayar' => Helpers::ribuan($jumlah_bayar),
                              'nominal_bayar' => Helpers::ribuan($nominal_bayar),
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
                         $rekapAtasan['bayar_jumlah'] += $jumlah_bayar;
                         $rekapAtasan['bayar_nominal'] += $nominal_bayar;
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
                         'jumlah_bayar' => Helpers::ribuan($rekapAtasan['bayar_jumlah']),
                         'nominal_bayar' => Helpers::ribuan($rekapAtasan['bayar_nominal']),
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
                    'jumlah_bayar' => Helpers::ribuan($grandTotal['bayar_jumlah']),
                    'nominal_bayar' => Helpers::ribuan($grandTotal['bayar_nominal']),
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

          $baku = BakuAwal::select(
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
          $pembayaran = DB::table('penyampaians as b')
               ->leftJoin('pembayaran_sppt as p', function ($join) {
                    $join->on('b.kd_propinsi', '=', 'p.kd_propinsi')
                         ->on('b.kd_dati2', '=', 'p.kd_dati2')
                         ->on('b.kd_kecamatan', '=', 'p.kd_kecamatan')
                         ->on('b.kd_kelurahan', '=', 'p.kd_kelurahan')
                         ->on('b.kd_blok', '=', 'p.kd_blok')
                         ->on('b.no_urut', '=', 'p.no_urut')
                         ->on('b.kd_jns_op', '=', 'p.kd_jns_op')
                         ->on('b.tahun', '=', 'p.thn_pajak_sppt');
               })
               ->select(
                    DB::raw("COUNT(DISTINCT 
                  p.kd_propinsi || '.' ||
                  p.kd_dati2 || '.' ||
                  p.kd_kecamatan || '.' ||
                  p.kd_kelurahan || '.' ||
                  p.kd_blok || '.' ||
                  p.no_urut || '.' ||
                  p.kd_jns_op || '.' ||
                  p.thn_pajak_sppt
              ) AS jumlah_pembayaran"),
                    DB::raw('COALESCE(SUM(p.jml_sppt_yg_dibayar), 0) AS total_dibayar')
               )
               ->where([
                    'b.kd_propinsi' => $kelurahanPertama->kd_propinsi,
                    'b.kd_dati2' => $kelurahanPertama->kd_dati2,
                    'b.kd_kecamatan' => $kelurahanPertama->kd_kecamatan,
                    'b.tahun' => date('Y'),
                    'b.tipe' => PenyampaianTipe::TERSAMPAIKAN,
               ])
               ->whereIn('b.kd_kelurahan', $kdKelurahans)
               ->whereBetween('p.tgl_pembayaran_sppt', ['2025-01-01', '2025-09-30'])
               ->first();
          return (object) [
               'nominal' => $baku->nominal,
               'jumlah' => $baku->jumlah,
               'jumlah_pembayaran' => $pembayaran->jumlah_pembayaran,
               'total_dibayar' => $pembayaran->total_dibayar,
          ];
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
