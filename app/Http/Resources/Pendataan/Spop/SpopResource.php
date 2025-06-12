<?php

namespace App\Http\Resources\Pendataan\Spop;

use App\Support\Facades\Helpers;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpopResource extends JsonResource
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
               'nop' => $this->nop,
               'alamat' => $this->alamat,
               'nama' => $this->subjekPajak->nama,
               'tanah' => Helpers::ribuan($this->tanah->luas_tanah),
          ];
     }
}
