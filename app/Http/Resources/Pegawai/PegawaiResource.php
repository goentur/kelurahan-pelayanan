<?php

namespace App\Http\Resources\Pegawai;

use App\Enums\PegawaiStatus;
use App\Support\Facades\Memo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PegawaiResource extends JsonResource
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
               'jabatan' => $this->when(
                    !blank($this->jabatan),
                    function () {
                         return Memo::for10min('jabatan-' . $this->jabatan_id, function () {
                              return [
                                   'id' => $this->jabatan_id,
                                   'nama' => $this->jabatan->nama,
                              ];
                         });
                    }
               ),
               'nik' => $this->nik,
               'nip' => $this->nip,
               'nama' => $this->nama,
               'no_rekening' => $this->no_rekening,
               'status' => $this->status == PegawaiStatus::AKTIF->value ? true : false,
          ];
     }
}
