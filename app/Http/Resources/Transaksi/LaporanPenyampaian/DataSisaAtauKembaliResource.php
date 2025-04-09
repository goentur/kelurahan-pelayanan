<?php

namespace App\Http\Resources\Transaksi\LaporanPenyampaian;

use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataSisaAtauKembaliResource extends JsonResource
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
               'kelurahan' => $this->kd_kelurahan,
               'blok' => $this->kd_blok,
               'no' => $this->no_urut,
               'jenis' => $this->kd_jns_op,
               'nama' => $this->nm_wp_sppt,
               'alamat' => trim(implode(' ', array_filter([
                    $this->jalan_op,
                    $this->blok_kav_no_op,
                    (!blank($this->rt_op) && !blank($this->rw_op)) ? "RT/RW {$this->rt_op}/{$this->rw_op}" : null
               ]))),
               'pajak' => Helpers::ribuan($this->pbb_yg_harus_dibayar_sppt),
               'keterangan' => 'SISA ATAU KEMBALI',
          ];
     }
}
