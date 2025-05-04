<?php

namespace App\Http\Resources\Transaksi\Penyampaian;

use App\Enums\PenyampaianStatus;
use App\Enums\PenyampaianTipe;
use App\Support\Facades\Helpers;
use Carbon\Carbon;
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
               'nama_wp' => $this->nm_wp_sppt,
               'alamat_objek' => $this->when(!blank($this->datObjekPajak), function () {
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
               'penyampaian' => $this->when(!blank($this->penyampaian), function () {
                    return [
                         'tipe' => [
                              'status' => $this->penyampaian->tipe == PenyampaianTipe::TERSAMPAIKAN->value ? true : false,
                              'label' => $this->penyampaian->tipe,
                              'value' => $this->penyampaian->status == PenyampaianStatus::SIMPAN->value ? ($this->penyampaian->tipe == PenyampaianTipe::TERSAMPAIKAN->value ? $this->penyampaian->keterangan : $this->penyampaian->penyampaian_keterangan_id) : ($this->penyampaian->tipe == PenyampaianTipe::TERSAMPAIKAN->value ? Carbon::parse($this->penyampaian->keterangan)->format('d-m-Y') : $this->penyampaian->keterangan),
                              'catatan' => $this->penyampaian->catatan,
                         ],
                         'status' => [
                              'status' => $this->penyampaian->status == PenyampaianStatus::SIMPAN->value ? true : false,
                              'value' => $this->penyampaian->status,
                         ],
                    ];
               }),
          ];
     }
}
