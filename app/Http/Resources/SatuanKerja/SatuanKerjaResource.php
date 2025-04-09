<?php

namespace App\Http\Resources\SatuanKerja;

use App\Support\Facades\Memo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SatuanKerjaResource extends JsonResource
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
               'email' => $this->when(!blank($this->user), Memo::for10min('satuan-kerja-user-' . $this->user_id, fn() => $this->user->email)),
               'atasan_satuan_kerja' => $this->when(
                    isset($this->atasan_satuan_kerja_id) && $this->atasan_satuan_kerja_id !== null,
                    function () {
                         return Memo::for10min('atasan-satuan-kerja-' . $this->atasan_satuan_kerja_id, function () {
                              return [
                                   'id' => $this->atasan_satuan_kerja_id,
                                   'nama' => $this->atasan->nama,
                              ];
                         });
                    }
               ),
               'kode_ref_kelurahan' => $this->kode_ref_kelurahan,
               'nama' => $this->nama,
          ];
     }
}
