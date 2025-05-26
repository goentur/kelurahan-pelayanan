<?php

namespace App\Http\Resources\Sppt\Pelayanan;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerubahanAlamatResource extends JsonResource
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
               'nomor' => $this->nomor,
               'nama_wajib_pajak' => $this->nama_wajib_pajak,
               'tanggal_permohonan' => Carbon::parse($this->tanggal_permohonan)->translatedFormat('d M Y'),
               'tanggal_validasi' => $this->tanggal_validasi ? Carbon::parse($this->tanggal_validasi)->translatedFormat('d M Y') : null,
               'jalan_op_lama' => $this->jalan_op_lama,
               'blok_kav_no_op_lama' => $this->blok_kav_no_op_lama,
               'rw_op_lama' => $this->rw_op_lama,
               'rt_op_lama' => $this->rt_op_lama,
               'jalan_op_baru' => $this->jalan_op_baru,
               'blok_kav_no_op_baru' => $this->blok_kav_no_op_baru,
               'rw_op_baru' => $this->rw_op_baru,
               'rt_op_baru' => $this->rt_op_baru,
               'status' => $this->status,
               'gate' => $this->gate,
          ];
     }
}
