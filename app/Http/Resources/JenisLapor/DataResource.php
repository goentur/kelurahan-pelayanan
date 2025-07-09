<?php

namespace App\Http\Resources\JenisLapor;

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
            'id' => $this->id,
            'nama' => $this->nama,
            'keterangan' => $this->keterangan,
            'jenis' => $this->jenis,
            'tanggal_awal' => Carbon::parse($this->tanggal_lapor_awal)->translatedFormat('d F Y H:i:s'),
            'tanggal_akhir' => Carbon::parse($this->tanggal_lapor_akhir)->translatedFormat('d F Y H:i:s'),
            'status' => now()->greaterThan(Carbon::parse($this->tanggal_lapor_awal)),
        ];
    }
}
