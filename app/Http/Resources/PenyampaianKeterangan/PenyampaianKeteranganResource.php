<?php

namespace App\Http\Resources\PenyampaianKeterangan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenyampaianKeteranganResource extends JsonResource
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
               'keterangan' => $this->keterangan,
          ];
     }
}
