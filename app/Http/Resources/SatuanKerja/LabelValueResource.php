<?php

namespace App\Http\Resources\SatuanKerja;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabelValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->kd_propinsi . '.' . $this->kd_dati2 . '.' . $this->kd_kecamatan . '.' . $this->kd_kelurahan,
            'label' => $this->kd_propinsi . '.' . $this->kd_dati2 . '.' . $this->kd_kecamatan . '.' . $this->kd_kelurahan . ' - ' . $this->nm_kelurahan,
        ];
    }
}
