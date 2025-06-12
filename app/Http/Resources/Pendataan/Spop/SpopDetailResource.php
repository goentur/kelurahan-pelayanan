<?php

namespace App\Http\Resources\Pendataan\Spop;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpopDetailResource extends JsonResource
{
     /**
      * Transform the resource into an array.
      *
      * @return array<string, mixed>
      */
     public function toArray(Request $request): array
     {
          return [
               'id' => $this->id,
               'jenis_bangunan' => (int) $this->ref_jenis_bangunan_id,
               'luas_bangunan' => (int) $this->luas_bangunan,
               'jumlah_lantai' => (int) $this->jumlah_lantai,
               'tahun_dibangun' => (int) $this->tahun_dibangun,
               'tahun_renovasi' => (int) $this->tahun_renovasi,
               'daya_listrik' => (int) $this->daya_listrik,
               'jumlah_ac' => (int) $this->jumlah_ac,
               'kondisi' => (int) $this->ref_kondisi_id,
               'konstruksi' => (int) $this->ref_konstruksi_id,
               'atap' => (int) $this->ref_atap_id,
               'dinding' => (int) $this->ref_dinding_id,
               'lantai' => (int) $this->ref_lantai_id,
               'langit' => (int) $this->ref_langit_id,
          ];
     }
}
