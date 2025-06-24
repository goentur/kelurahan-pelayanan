<?php

namespace App\Repositories\Pendataan;

use App\Http\Resources\Pendataan\Spop\SpopDetailResource;
use App\Http\Resources\Pendataan\Spop\SpopResource;
use App\Models\Pendataan\PendataanSpop;
use App\Models\Pendataan\PendataanSpopBangunan;
use App\Models\Pendataan\PendataanSpopSubjekPajak;
use App\Models\Pendataan\PendataanSpopTanah;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpopRepository
{
     public function data($request)
     {
          $query = PendataanSpop::with('subjekPajak', 'tanah')
               ->select('id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'jalan', 'blok_kav_no', 'rw', 'rt');
          if ($request->filled(['berdasarkan', 'search'])) {
               match ($request->berdasarkan) {
                    'NOP' => $query->where(function ($q) use ($request) {
                         $nop = explode('.', $request->search);
                         if (count($nop) !== 7) abort(404, 'Data tidak ditemukan');

                         $fields = ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op'];
                         foreach ($fields as $i => $field) {
                              $q->where($field, $nop[$i]);
                         }
                    }),
                    'Nama' => $query->whereHas('subjekPajak', function ($q) use ($request) {
                         $q->whereLike('nama', "%$request->search%", caseSensitive: false);
                    }),
                    default => null
               };
          }
          $result = SpopResource::collection(
               $query->where('user_id', auth()->id())->latest()->paginate($request->perPage ?? 25)
          )->response()->getData(true);

          return $result['meta'] + ['data' => $result['data']];
     }
     public function store($request)
     {
          try {
               DB::beginTransaction();
               $spop = PendataanSpop::create([
                    'uuid' => Str::uuid()->toString(),
                    'user_id' => auth()->id(),
                    'ref_jenis_pendataan_spop_id' => $request->jenis,
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
                    'keterangan' => $request->keterangan,
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
               PendataanSpopBangunan::create([
                    'pendataan_spop_id' => $spop->id,
                    'ref_jenis_bangunan_id' => $request->jenis_bangunan,
                    'luas_bangunan' => $request->luas_bangunan,
                    'jumlah_lantai' => $request->jumlah_lantai,
                    'tahun_dibangun' => $request->tahun_dibangun,
                    'tahun_renovasi' => $request->tahun_renovasi,
                    'daya_listrik' => $request->daya_listrik,
                    'jumlah_ac' => $request->jumlah_ac,
                    'ref_kondisi_id' => $request->kondisi,
                    'ref_konstruksi_id' => $request->konstruksi,
                    'ref_atap_id' => $request->atap,
                    'ref_dinding_id' => $request->dinding,
                    'ref_lantai_id' => $request->lantai,
                    'ref_langit_id' => $request->langit,
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
     public function dataDetail($request)
     {
          return SpopDetailResource::collection(PendataanSpopBangunan::where("pendataan_spop_id", $request->id)->orderBy('id')->get());
     }
     public function addBangunan($request)
     {
          try {
               DB::beginTransaction();
               PendataanSpopBangunan::create([
                    'pendataan_spop_id' => $request->pendataan_spop,
                    'ref_jenis_bangunan_id' => $request->jenis_bangunan,
                    'luas_bangunan' => $request->luas_bangunan,
                    'jumlah_lantai' => $request->jumlah_lantai,
                    'tahun_dibangun' => $request->tahun_dibangun,
                    'tahun_renovasi' => $request->tahun_renovasi,
                    'daya_listrik' => $request->daya_listrik,
                    'jumlah_ac' => $request->jumlah_ac,
                    'ref_kondisi_id' => $request->kondisi,
                    'ref_konstruksi_id' => $request->konstruksi,
                    'ref_atap_id' => $request->atap,
                    'ref_dinding_id' => $request->dinding,
                    'ref_lantai_id' => $request->lantai,
                    'ref_langit_id' => $request->langit,
               ]);
               DB::commit();
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
     public function updateBangunan($request)
     {
          try {
               DB::beginTransaction();
               PendataanSpopBangunan::find($request->id)->update([
                    'ref_jenis_bangunan_id' => $request->jenis_bangunan,
                    'luas_bangunan' => $request->luas_bangunan,
                    'jumlah_lantai' => $request->jumlah_lantai,
                    'tahun_dibangun' => $request->tahun_dibangun,
                    'tahun_renovasi' => $request->tahun_renovasi,
                    'daya_listrik' => $request->daya_listrik,
                    'jumlah_ac' => $request->jumlah_ac,
                    'ref_kondisi_id' => $request->kondisi,
                    'ref_konstruksi_id' => $request->konstruksi,
                    'ref_atap_id' => $request->atap,
                    'ref_dinding_id' => $request->dinding,
                    'ref_lantai_id' => $request->lantai,
                    'ref_langit_id' => $request->langit,
               ]);
               DB::commit();
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
     public function deleteBangunan($request)
     {
          try {
               DB::beginTransaction();
               PendataanSpopBangunan::find($request->id)->delete();
               DB::commit();
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
}
