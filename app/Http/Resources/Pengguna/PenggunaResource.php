<?php

namespace App\Http\Resources\Pengguna;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PenggunaResource extends JsonResource
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
               'email' => $this->email,
               'nama' => $this->name,
               'role' => $this->getRoleNames()[0],
          ];
     }
}
