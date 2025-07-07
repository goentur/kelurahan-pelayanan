<?php

namespace App\Repositories\Master\Pegawai;

use App\Enums\JabatanJenis;
use App\Enums\PegawaiStatus;
use App\Http\Resources\Common\LabelValueResource;
use App\Http\Resources\Pegawai\PegawaiResource;
use App\Models\Pegawai;
use Illuminate\Support\Facades\DB;

class PegawaiRepository
{
     public function __construct(protected Pegawai $model) {}
     public function list()
     {
          return LabelValueResource::collection($this->model::select('id', 'nama')->get());
     }
     public function data($request)
     {
          $query = $this->model::select('id', 'jabatan_id', 'nik', 'nip', 'nama', 'no_rekening', 'status', 'jabatan_status')->where('satuan_kerja_id', $request->satuan_kerja);
          if ($request->search) {
               $query->where('nik', 'like', "%{$request->search}%")
                    ->orWhere('nip', 'like', "%{$request->search}%")
                    ->orWhere('nama', 'like', "%{$request->search}%")
                    ->orWhere('no_rekening', 'like', "%{$request->search}%")
                    ->orWhereHas('jabatan', fn($q) => $q->where('nama', 'like', "%{$request->search}%"));
          }
          $result = PegawaiResource::collection($query->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
     public function store($request)
     {
          try {
               DB::beginTransaction();
               $this->model::create([
                    'satuan_kerja_id' => $request->satuan_kerja,
                    'jabatan_id' => $request->jabatan,
                    'nik' => $request->nik,
                    'nip' => $request->nip,
                    'nama' => $request->nama,
                    'no_rekening' => $request->no_rekening,
                    'status' => PegawaiStatus::AKTIF,
                    'jabatan_status' => $request->jabatan_status,
               ]);
               DB::commit();
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
     public function update($id, $request)
     {
          try {
               DB::beginTransaction();
               $data = $this->model::find($id);
               $data->update([
                    'jabatan_id' => $request->jabatan,
                    'nik' => $request->nik,
                    'nip' => $request->nip,
                    'nama' => $request->nama,
                    'no_rekening' => $request->no_rekening,
                    'jabatan_status' => $request->jabatan_status,
               ]);
               DB::commit();
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
     public function delete($id)
     {
          return $this->model->findOrFail($id)?->delete();
     }
     public function status($request)
     {
          try {
               DB::beginTransaction();
               $data = $this->model::find($request->id);
               $data->update([
                    'status' => $request->status ? PegawaiStatus::AKTIF : PegawaiStatus::TIDAK,
               ]);
               DB::commit();
               return "Data berhasil diubah";
          } catch (\Exception $e) {
               DB::rollBack();
               throw $e;
          }
     }
     public function pegawaiPetugasCollection($user)
     {
          $petugas = $this->model::select('nip', 'nama')->where([
               'satuan_kerja_id' => $user?->satuanKerja->id,
               'status' => PegawaiStatus::AKTIF,
          ])->whereHas('jabatan', fn($q) => $q->where('jenis', JabatanJenis::PETUGAS))->limit(2);
          if ($petugas->count() < 2) {
               abort(400, 'Pegawai dengan jabatan Petugas tidak ada, silakan masukkan data pegawai dengan jabatan Petugas. Minimal 2 pegawai.');
          }
          $namaPetugas = [];
          $nipPetugas = [];
          foreach ($petugas->get() as $value) {
               $namaPetugas[] = $value->nama;
               $nipPetugas[] = $value->nip ? 'NIP : ' . $value->nip : '';
          }
          return [
               'nama' => $namaPetugas,
               'nip' => $nipPetugas,
          ];
     }
     public function pegawaiLurah($user)
     {
          return $this->model::select('nip', 'nama', 'jabatan_status')->where([
               'satuan_kerja_id' => $user?->satuanKerja->id,
               'status' => PegawaiStatus::AKTIF,
          ])->whereHas('jabatan', fn($q) => $q->where([
               'nama' => 'LURAH',
               'jenis' => JabatanJenis::KEPALA
          ]))->first();
     }
     public function pegawaiCamat($user)
     {
          return $this->model::select('nip', 'nama', 'jabatan_status')->where([
               'satuan_kerja_id' => $user?->satuanKerja->atasan?->id,
               'status' => PegawaiStatus::AKTIF,
          ])->whereHas('jabatan', fn($q) => $q->where([
               'nama' => 'CAMAT',
               'jenis' => JabatanJenis::KEPALA
          ]))->first();
     }
     public function pegawaiKepalaCollection($user)
     {
          $pegawaiLurah = $this->pegawaiLurah($user);
          if (!$pegawaiLurah) {
               abort(400, 'Pegawai dengan jabatan Lurah tidak ada, silakan masukkan data pegawai dengan jabatan Lurah.');
          }
          $pegawaiCamat = $this->pegawaiCamat($user);
          if (!$pegawaiCamat) {
               abort(400, 'Pegawai dengan jabatan camat tidak ada, silakan hubungi kecamatan untuk menambahkan data pegawai dengan jabatan Camat.');
          }
          return [
               'nama' => [
                    $pegawaiLurah->nama,
                    $pegawaiCamat->nama,
               ],
               'nip' => [
                    $pegawaiLurah->nip ? 'NIP : ' . $pegawaiLurah->nip : '',
                    $pegawaiCamat->nip ? 'NIP : ' . $pegawaiCamat->nip : '',
               ],
               'jabatan_status' => [
                    $pegawaiLurah->jabatan_status,
                    $pegawaiCamat->jabatan_status,
               ],
          ];
     }
}
