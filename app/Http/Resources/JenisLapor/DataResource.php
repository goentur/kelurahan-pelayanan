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
            'tanggal_awal' => $this->tanggal_awal,
            'tanggal_akhir' => $this->tanggal_akhir,
            'status' => now()->between(Carbon::parse($this->tanggal_awal), Carbon::parse($this->tanggal_akhir)),
        ];
    }
}
