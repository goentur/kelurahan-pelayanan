<?php

namespace App\Http\Resources\Transaksi\LaporanPenyampaian;

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
               'kelurahan' => $this->kd_kelurahan,
               'blok' => $this->kd_blok,
               'no' => $this->no_urut,
               'jenis' => $this->kd_jns_op,
               'nama' => $this->nama_wp,
               'alamat' => $this->alamat_objek,
               'pajak' => Helpers::ribuan($this->nominal),
               'keterangan' => $this->tipe == PenyampaianTipe::TERSAMPAIKAN->value ? Carbon::parse($this->keterangan)->format('d-m-Y') : $this->keterangan,
          ];
     }
}
