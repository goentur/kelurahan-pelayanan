<?php

namespace App\Repositories\Pendataan;

use App\Http\Resources\Pendataan\SpopResource;
use App\Models\Pendataan\PendataanSpop;
use App\Models\Pendataan\PendataanSpopSubjekPajak;
use App\Models\Pendataan\PendataanSpopTanah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpopRepository
{
     public function data($request)
     {
          $query = PendataanSpop::with('subjekPajak', 'tanah')->select('id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'jalan', 'blok_kav_no', 'rw', 'rt');
          $result = SpopResource::collection($query->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
     public function store($request)
     {
          try {
               DB::beginTransaction();
               $spop = PendataanSpop::create([
                    'uuid' => Str::uuid()->toString(),
                    'user_id' => auth()->id(),
                    'kd_propinsi' => $request->kd_propinsi,
                    'kd_dati2' => $request->kd_dati2,
                    'kd_kecamatan' => $request->kd_kecamatan,
                    'kd_kelurahan' => $request->kd_kelurahan,
                    'kd_blok' => $request->kd_blok,
                    'no_urut' => $request->no_urut,
                    'kd_jns_op' => $request->kd_jns_op,
                    'tahun' => date('Y'),
                    'jalan' => $request->jalan,
                    'blok_kav_no' => $request->blok_kav_no,
                    'rw' => $request->rw,
                    'rt' => $request->rt,
                    'koordinat' => json_encode($request->koordinat),
               ]);
               PendataanSpopTanah::create([
                    'pendataan_spop_id' => $spop->id,
                    'luas_tanah' => $request->luas_tanah,
                    'no_sertipikat' => $request->no_sertipikat,
                    'ref_jenis_tanah_id' => $request->tanah,
               ]);
               PendataanSpopSubjekPajak::create([
                    'pendataan_spop_id' => $spop->id,
                    'ref_status_subjek_pajak_id' => $request->status,
                    'ref_pekerjaan_subjek_pajak_id' => $request->pekerjaan,
                    'nik' => $request->nik,
                    'npwp' => $request->npwp,
                    'nama' => $request->nama,
                    'jalan' => $request->jalan_sp,
                    'blok_kav_no' => $request->blok_kav_no_sp,
                    'rw' => $request->rw_sp,
                    'rt' => $request->rt_sp,
                    'kelurahan' => $request->kelurahan,
                    'kecamatan' => $request->kecamatan,
                    'kota' => $request->kota,
                    'kode_pos' => $request->kode_pos,
                    'no_telp' => $request->no_telp,
                    'email' => $request->email,
               ]);
               DB::commit();
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
     public function cekNop($request)
     {
          $spop = PendataanSpop::select("id")->where([
               'kd_propinsi' => $request->kd_propinsi,
               'kd_dati2' => $request->kd_dati2,
               'kd_kecamatan' => $request->kd_kecamatan,
               'kd_kelurahan' => $request->kd_kelurahan,
               'kd_blok' => $request->kd_blok,
               'no_urut' => $request->no_urut,
               'kd_jns_op' => $request->kd_jns_op,
               'tahun' => date('Y'),
          ])->first();
          if ($spop) {
               return [
                    'status' => false,
                    'message' => 'Data SPOP dengan NOP yang dimasukan sudah ada.',
               ];
          }
          return [
               'status' => true,
               'message' => 'Data dengan NOP yang dimasukan tidak ada',
          ];
     }
}
