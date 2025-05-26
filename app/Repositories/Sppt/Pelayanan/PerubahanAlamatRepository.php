<?php

namespace App\Repositories\Sppt\Pelayanan;

use App\Enums\Pelayanan\PerubahanAlamatStatus;
use App\Http\Resources\Sppt\Pelayanan\PerubahanAlamatResource;
use App\Models\DatObjekPajak;
use App\Models\PelayananPerubahanAlamat;
use Illuminate\Support\Facades\DB;

class PerubahanAlamatRepository
{

     protected $user;

     public function __construct()
     {
          $this->user = auth()->id();
     }
     protected function generateNomorPelayanan()
     {
          $last = PelayananPerubahanAlamat::withTrashed()->orderByDesc('tahun_permohonan')->orderByDesc('bundel_permohonan')->orderByDesc('no_urut_permohonan')->first();
          if ($last) {
               $tahun = $last->tahun_permohonan;
               $bundel = (int) $last->bundel_permohonan;
               $noUrut = $last->no_urut_permohonan;
               if ($noUrut < 999) {
                    $noUrut += 1;
               } else {
                    $noUrut = 1;
                    $bundel += 1;
                    if ($bundel > 12) {
                         $bundel = 1;
                         $tahun += 1;
                    }
               }
          } else {
               $tahun = date('Y');
               $bundel = date('n');
               $noUrut = 1;
          }

          return [
               'tahun' => $tahun,
               'bundel' => sprintf('%04d', $bundel),
               'no_urut' => sprintf('%03d', $noUrut),
          ];
     }
     public function data($request)
     {
          $userRole = auth()->user()->getRoleNames()->first();
          $query = PelayananPerubahanAlamat::select('*');
          if ($request->status !== 'SEMUA') {
               $query->where('status_pelayanan', $request->status);
          }
          if ($request->berdasarkan && $request->search) {
               $query->where(function ($q) use ($request) {
                    if ($request->berdasarkan === 'NOP') {
                         $nop = explode('.', $request->search);
                         if (count($nop) === 7) {
                              $q->where([
                                   ['kd_propinsi', $nop[0]],
                                   ['kd_dati2', $nop[1]],
                                   ['kd_kecamatan', $nop[2]],
                                   ['kd_kelurahan', $nop[3]],
                                   ['kd_blok', $nop[4]],
                                   ['no_urut', $nop[5]],
                                   ['kd_jns_op', $nop[6]],
                              ]);
                         } else {
                              return abort(404, "Data tidak ditemukan");
                         }
                    }
                    if ($request->berdasarkan === 'Nomor') {
                         $nomor = explode('.', $request->search);
                         if (count($nomor) === 3) {
                              $q->where([
                                   ['tahun_permohonan', $nomor[0]],
                                   ['bundel_permohonan', $nomor[1]],
                                   ['no_urut_permohonan', $nomor[2]],
                              ]);
                         } else {
                              return abort(404, "Data tidak ditemukan");
                         }
                    }
                    if ($request->berdasarkan === 'Nama') {
                         $q->whereLike('nama_wajib_pajak', "%$request->search%", caseSensitive: false);
                    }
               });
          }
          if ($userRole == "KELURAHAN") {
               $query->where('pemohon_id', $this->user);
          }
          $result = PerubahanAlamatResource::collection($query->orderByRaw("CASE WHEN status_pelayanan = 'MENUNGGU' THEN 1 WHEN status_pelayanan = 'TOLAK' THEN 2 WHEN status_pelayanan = 'TERIMA' THEN 3 ELSE 4 END ")->oldest()->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
     public function dataObjekPajak($request)
     {
          $kelurahan = explode('.', $request->kelurahan ?? '');
          $objekPajak = DatObjekPajak::with('datSubjekPajak')->select('subjek_pajak_id', 'jalan_op', 'blok_kav_no_op', 'rw_op', 'rt_op')->where([
               'kd_propinsi' => $kelurahan[0],
               'kd_dati2' => $kelurahan[1],
               'kd_kecamatan' => $kelurahan[2],
               'kd_kelurahan' => $kelurahan[3],
               'kd_blok' => $request->kd_blok,
               'no_urut' => $request->no_urut,
               'kd_jns_op' => $request->kd_jns_op,
          ])->first();
          if ($objekPajak) {
               return [
                    'nama_wajib_pajak' => $objekPajak->datSubjekPajak->nm_wp,
                    'jalan_lama' => $objekPajak->jalan_op,
                    'blok_kav_no_lama' => $objekPajak->blok_kav_no_op,
                    'rw_lama' => $objekPajak->rw_op,
                    'rt_lama' => $objekPajak->rt_op
               ];
          } else {
               return abort(404, "Data objek pajak tidak ditemukan");
          }
     }
     public function simpanAtauUpdate($request)
     {
          $kelurahan = explode('.', $request->kelurahan ?? '');
          $data = PelayananPerubahanAlamat::select('id', 'tahun_permohonan', 'bundel_permohonan', 'no_urut_permohonan')
               ->where([
                    'tahun_permohonan' => date('Y'),
                    'kd_propinsi' => $kelurahan[0],
                    'kd_dati2' => $kelurahan[1],
                    'kd_kecamatan' => $kelurahan[2],
                    'kd_kelurahan' => $kelurahan[3],
                    'kd_blok' => $request->kd_blok,
                    'no_urut' => $request->no_urut,
                    'kd_jns_op' => $request->kd_jns_op,
               ])
               ->where(function ($query) {
                    $query->where('status_pelayanan', PerubahanAlamatStatus::MENUNGGU->value)
                         ->orWhere('status_pelayanan', PerubahanAlamatStatus::TOLAK->value);
               })
               ->first();
          if ($data) {
               if ($request->tipe == "ubah") {
               }
               return abort(400, 'Data sudah ada pada nomor pelayanan : ' . $data->nomor);
          } else {
               try {
                    DB::beginTransaction();
                    $noPelayanan = $this->generateNomorPelayanan();
                    PelayananPerubahanAlamat::create([
                         'tahun_permohonan' => $noPelayanan['tahun'],
                         'bundel_permohonan' => $noPelayanan['bundel'],
                         'no_urut_permohonan' => $noPelayanan['no_urut'],
                         'pemohon_id' => $this->user,
                         'tanggal_permohonan' => now(),
                         'status_pelayanan' => PerubahanAlamatStatus::MENUNGGU,
                         'kd_propinsi' => $kelurahan[0],
                         'kd_dati2' => $kelurahan[1],
                         'kd_kecamatan' => $kelurahan[2],
                         'kd_kelurahan' => $kelurahan[3],
                         'kd_blok' => $request->kd_blok,
                         'no_urut' => $request->no_urut,
                         'kd_jns_op' => $request->kd_jns_op,
                         'nama_wajib_pajak' => $request->nama_wajib_pajak,
                         'jalan_op_lama' => $request->jalan_lama,
                         'blok_kav_no_op_lama' => $request->blok_kav_no_lama,
                         'rw_op_lama' => $request->rw_lama,
                         'rt_op_lama' => $request->rt_lama,
                         'jalan_op_baru' => $request->jalan_baru,
                         'blok_kav_no_op_baru' => $request->blok_kav_no_baru,
                         'rw_op_baru' => $request->rw_baru,
                         'rt_op_baru' => $request->rt_baru,
                    ]);
                    DB::commit();
                    return [
                         'status' => true,
                         'message' => 'Data berhasil disimpan',
                    ];
               } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
               }
          }
     }

     public function hapus($pelayananPerubahanAlamat)
     {
          $cek = PelayananPerubahanAlamat::findOrFail($pelayananPerubahanAlamat->id);
          if ($cek) {
               $cek->delete();
               return [
                    'status' => true,
                    'message' => "Data berhasil dihapus"
               ];
          }
          return [
               'status' => false,
               'message' => "Data tidak ditemukan"
          ];
     }
     public function terima($pelayananPerubahanAlamat)
     {
          try {
               DB::beginTransaction();
               $pelayananPerubahanAlamat->update([
                    'validasi_id' => $this->user,
                    'tanggal_validasi' => now(),
                    'status_pelayanan' => PerubahanAlamatStatus::SELESAI,
               ]);
               $datObjekPajak = DatObjekPajak::where([
                    'kd_propinsi' => $pelayananPerubahanAlamat->kd_propinsi,
                    'kd_dati2' => $pelayananPerubahanAlamat->kd_dati2,
                    'kd_kecamatan' => $pelayananPerubahanAlamat->kd_kecamatan,
                    'kd_kelurahan' => $pelayananPerubahanAlamat->kd_kelurahan,
                    'kd_blok' => $pelayananPerubahanAlamat->kd_blok,
                    'no_urut' => $pelayananPerubahanAlamat->no_urut,
                    'kd_jns_op' => $pelayananPerubahanAlamat->kd_jns_op,
               ])->first();
               $datObjekPajak->update([
                    'jalan_op' => $pelayananPerubahanAlamat->jalan_op_baru,
                    'blok_kav_no_op' => $pelayananPerubahanAlamat->blok_kav_no_op_baru,
                    'rw_op' => $pelayananPerubahanAlamat->rw_op_baru,
                    'rt_op' => $pelayananPerubahanAlamat->rt_op_baru,
               ]);
               DB::commit();
               return [
                    'status' => true,
                    'message' => "Data berhasil diterima"
               ];
          } catch (\Exception $e) {
               DB::rollBack();
               return [
                    'status' => false,
                    'message' => "Terjadi kesalahan pada saat penerimaan data"
               ];
          }
     }
}
