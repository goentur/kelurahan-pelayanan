<?php

namespace App\Http\Resources\JenisLapor;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JenisLaporResource extends JsonResource
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
               'nama' => $this->nama,
               'jenis' => $this->jenis,
               'no_urut' => $this->no_urut,
               'keterangan' => $this->keterangan,
               'tanggal_awal' => [
                    'data' => $this->tanggal_awal,
                    'form' => date('Y-m-d', strtotime($this->tanggal_awal)),
               ],
               'tanggal_akhir' => [
                    'data' => $this->tanggal_akhir,
                    'form' => date('Y-m-d', strtotime($this->tanggal_akhir)),
               ],
          ];
     }
}
