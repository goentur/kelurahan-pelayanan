<?php

namespace App\Repositories\Sppt;

use App\Http\Resources\Sppt\DataResource;
use App\Models\Sppt;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use Illuminate\Support\Str;

class SpptRepository
{
     public function __construct(
          protected Sppt $model,
          protected JenisBukuRepository $jenisBuku,
          protected SatuanKerjaRepository $satuanKerja,
     ) {}
     public function data($request)
     {

          if ($request->kelurahan) {
               $nop = explode('.', $request->kelurahan ?? '');
               $kd_propinsi   = $nop[0] ?? null;
               $kd_dati2      = $nop[1] ?? null;
               $kd_kecamatan  = $nop[2] ?? null;
               $kd_kelurahan  = [$nop[3] ?? null];
          } else {
               $satuanKerja   = $this->satuanKerja->collectionData();
               $kd_propinsi   = $satuanKerja['propinsi'];
               $kd_dati2      = $satuanKerja['dati2'];
               $kd_kecamatan  = $satuanKerja['kecamatan'];
               $kd_kelurahan  = $satuanKerja['kelurahan'];
          }

          $query = $this->model::with('datObjekPajak')->select('kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt', 'nm_wp_sppt', 'jln_wp_sppt', 'blok_kav_no_wp_sppt', 'rw_wp_sppt', 'rt_wp_sppt', 'kelurahan_wp_sppt', 'kota_wp_sppt', 'pbb_yg_harus_dibayar_sppt', 'status_pembayaran_sppt')->where([
               'kd_propinsi'   => $kd_propinsi,
               'kd_dati2'      => $kd_dati2,
               'kd_kecamatan'  => $kd_kecamatan,
               'thn_pajak_sppt' => date('Y'),
          ])->whereIn('kd_kelurahan', $kd_kelurahan)->whereIn('status_pembayaran_sppt', [0, 1]);
          if (!empty($request->jenisBuku)) {
               $nominal = $this->jenisBuku->dataNominal($request->jenisBuku);
               $query->whereBetween('pbb_yg_harus_dibayar_sppt', [$nominal['min'], $nominal['max']]);
          }
          if (!empty($request->kd_blok)) {
               $query->where('kd_blok', $request->kd_blok);
          }
          if (!empty($request->no_urut)) {
               $query->where('no_urut', $request->no_urut);
          }
          if (!empty($request->nama_wp)) {
               $query->whereLike('nm_wp_sppt', "%$request->nama_wp%", caseSensitive: false);
          }
          $query->orderBy('kd_propinsi')->orderBy('kd_dati2')->orderBy('kd_kecamatan')->orderBy('kd_kelurahan')->orderBy('kd_blok')->orderBy('no_urut')->orderBy('kd_jns_op');
          $result = DataResource::collection($query->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
}
