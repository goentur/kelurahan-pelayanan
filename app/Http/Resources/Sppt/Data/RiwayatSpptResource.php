<?php

namespace App\Http\Resources\Sppt\Data;

use App\Support\Facades\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class RiwayatSpptResource extends JsonResource
{
     public function toArray($request)
     {
          $totalPembayaran = $this->pembayaranSppt->sum('jml_sppt_yg_dibayar');
          $tagihan = (int) $this->pbb_yg_harus_dibayar_sppt;
          $keterangan = 'BELUM';
          if ($totalPembayaran >= $tagihan) {
               $tagihan = 0;
               $keterangan = 'SUDAH';
          }
          return [
               'tahun' => $this->thn_pajak_sppt,
               'tagihan' => Helpers::ribuan($tagihan),
               'keterangan' => $keterangan,
          ];
     }
}
