<?php

namespace App\Repositories\Common;

use App\Models\Ref\RefBuku;
use App\Support\Facades\Memo;
use Illuminate\Support\Arr;

class JenisBukuRepository
{
     public function __construct(protected RefBuku $model) {}
     public function data()
     {
          return Memo::forDay('jenis-buku', function () {
               $opt = [];
               for ($i = 1; $i <= 5; $i++) {
                    for ($x = $i; $x <= 5; $x++) {
                         $v = implode(',', range($i, $x));
                         $opt[] = ['value' => $v, 'label' => $v];
                    }
               }
               return $opt;
          });
     }
     public function dataNominal($request)
     {
          $buku = explode(',', $request);
          if (empty($buku) || count($buku) === 0) {
               $buku = $request;
          }
          $rmin = Memo::forDay('ref-buku-min-' . Arr::first($buku), function () use ($buku) {
               return $this->model::firstWhere('kd_buku', Arr::first($buku));
          });
          $rmax = Memo::forDay('ref-buku-max-' . Arr::last($buku), function () use ($buku) {
               return $this->model::firstWhere('kd_buku', Arr::last($buku));
          });
          return [
               'min' => $rmin->nilai_min_buku,
               'max' => $rmax->nilai_max_buku,
          ];
     }
}
