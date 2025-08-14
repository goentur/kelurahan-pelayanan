<?php

namespace App\Http\Resources\Sppt\Data;

use App\Support\Facades\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DatObjekPajakResource extends JsonResource
{
     public function toArray($request)
     {
          return [
               'objek' => [
                    'jenis' => $this->bumi->jenis_tnh,
                    'alamat' => $this->alamat,
                    'tanah' => [
                         'znt' => $this->znt->kd_znt . ' ( Rp ' . Helpers::ribuan($this->znt->nir * 1000) . ' )',
                         'luas' => Helpers::ribuan($this->total_luas_bumi),
                         'njop' => 'Rp ' . Helpers::ribuan($this->njop_bumi),
                    ],
                    'bangunan' => [
                         'jumlah' => $this->bangunan->count(),
                         'luas' => Helpers::ribuan($this->total_luas_bng),
                         'njop' => 'Rp ' . Helpers::ribuan($this->njop_bng),
                    ],
                    'njop' => 'Rp ' . Helpers::ribuan($this->njop_bumi + $this->njop_bgn),
               ],
               'subjek' => [
                    'npwp' => $this->datSubjekPajak->npwp,
                    'nama' => $this->datSubjekPajak->nm_wp,
                    'status' => Str::upper($this->statusWajibPajak->nama),
                    'alamat' => $this->datSubjekPajak->alamat,
               ],
               'nop' => $this->kd_propinsi . '.' . $this->kd_dati2 . '.' . $this->kd_kecamatan . '.' . $this->kd_kelurahan . '.' . $this->kd_blok . '.' . $this->no_urut . '.' . $this->kd_jns_op,

               // SPPT (banyak record)
               'riwayat' => RiwayatSpptResource::collection($this->whenLoaded('spptWithBayar')),

               // Tambahan: bisa tambahkan tahun terakhir, atau filter jika perlu
          ];
     }
}
