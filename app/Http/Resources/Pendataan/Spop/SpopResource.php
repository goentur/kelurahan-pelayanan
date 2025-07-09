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
               'data' => [
                    'objek' => [
                         'jenis' => (int) $this->ref_jenis_pendataan_spop_id,
                         'kd_propinsi' => $this->kd_propinsi,
                         'kd_dati2' => $this->kd_dati2,
                         'kd_kecamatan' => $this->kd_kecamatan,
                         'kd_kelurahan' => $this->kd_kelurahan,
                         'kd_blok' => $this->kd_blok,
                         'no_urut' => $this->no_urut,
                         'kd_jns_op' => $this->kd_jns_op,
                         'tahun' => $this->tahun,
                         'jalan' => $this->jalan,
                         'blok_kav_no' => $this->blok_kav_no,
                         'rw' => $this->rw,
                         'rt' => $this->rt,
                         'koordinat' => json_decode($this->koordinat),
                         'keterangan' => $this->keterangan
                    ],
                    'tanah' => [
                         'luas_tanah' => $this->tanah->luas_tanah,
                         'no_sertipikat' => $this->tanah->no_sertipikat,
                         'tanah' => (int) $this->tanah->ref_jenis_tanah_id
                    ],
                    'subjek' => [
                         'status' => (int) $this->subjekPajak->ref_status_subjek_pajak_id,
                         'pekerjaan' => (int) $this->subjekPajak->ref_pekerjaan_subjek_pajak_id,
                         'nik' => $this->subjekPajak->nik,
                         'npwp' => $this->subjekPajak->npwp,
                         'nama' => $this->subjekPajak->nama,
                         'jalan' => $this->subjekPajak->jalan,
                         'blok_kav_no' => $this->subjekPajak->blok_kav_no,
                         'rw' => $this->subjekPajak->rw,
                         'rt' => $this->subjekPajak->rt,
                         'kelurahan' => $this->subjekPajak->kelurahan,
                         'kecamatan' => $this->subjekPajak->kecamatan,
                         'kota' => $this->subjekPajak->kota,
                         'kode_pos' => $this->subjekPajak->kode_pos,
                         'no_telp' => $this->subjekPajak->no_telp,
                         'email' => $this->subjekPajak->email
                    ]
               ]
          ];
     }
}
