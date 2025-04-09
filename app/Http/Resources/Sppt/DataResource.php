<?php

namespace App\Http\Resources\Sppt;

use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
     /**
      * Transform the resource into an array.
      *
      * @return array<string, mixed>
      */
     public function toArray(Request $request): array
     {
          return [
               'id' => $this->kd_propinsi . '.' . $this->kd_dati2 . '.' . $this->kd_kecamatan . '.' . $this->kd_kelurahan . '.' . $this->kd_blok . '.' . $this->no_urut . '.' . $this->kd_jns_op,
               'blok' => $this->kd_blok,
               'jenis' => $this->kd_jns_op,
               'no' => $this->no_urut,
               'nama' => $this->nm_wp_sppt,
               'alamat_wp' => trim(implode(' ', array_filter([
                    $this->jln_wp_sppt,
                    $this->blok_kav_no_wp_sppt,
                    $this->rw_wp_sppt && $this->rt_wp_sppt ? "RT.{$this->datObjekPajak->rt_op} RW.{$this->datObjekPajak->rw_op}" : null,
                    $this->kelurahan_wp_sppt,
                    $this->kota_wp_sppt,
               ]))),
               'alamat_op' => $this->when(!blank($this->datObjekPajak), function () {
                    $alamat = [
                         $this->datObjekPajak->jalan_op,
                         $this->datObjekPajak->blok_kav_no_op
                    ];
                    if (!blank($this->datObjekPajak->rt_op) && !blank($this->datObjekPajak->rw_op)) {
                         $alamat[] = "RT/RW {$this->datObjekPajak->rt_op}/{$this->datObjekPajak->rw_op}";
                    }

                    return trim(implode(' ', array_filter($alamat)));
               }),
               'pajak' => Helpers::ribuan($this->pbb_yg_harus_dibayar_sppt),
               'status' => [
                    'status' => $this->status_pembayaran_sppt == 1 ? true : false,
                    'text' => $this->status_pembayaran_sppt == 1 ? 'SUDAH' : 'BELUM'
               ],
          ];
     }
}
