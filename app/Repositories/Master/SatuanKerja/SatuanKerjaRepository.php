<?php

namespace App\Repositories\Master\SatuanKerja;

use App\Http\Resources\Common\LabelValueResource;
use App\Http\Resources\SatuanKerja\LabelValueResource as SatuanKerjaLabelValueResource;
use App\Http\Resources\SatuanKerja\SatuanKerjaResource;
use App\Models\Pegawai;
use App\Models\SatuanKerja;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SatuanKerjaRepository
{
     public function __construct(protected SatuanKerja $model) {}
     public function dataBerdasarkanUser($user)
     {
          $satuanKerja = $user?->satuanKerja[0];
          return SatuanKerjaLabelValueResource::collection($satuanKerja->kelurahan);
     }
     public function list()
     {
          return LabelValueResource::collection($this->model::select('id', 'nama')->get());
     }
     public function data($request)
     {
          $query = $this->model::select('id', 'user_id', 'atasan_satuan_kerja_id', 'nama', 'kode_ref_kelurahan');
          if ($request->search) {
               $query->where('nama', 'like', "%{$request->search}%")
                    ->orWhereHas('atasan', fn($q) => $q->where('nama', 'like', "%{$request->search}%"));
          }
          $result = SatuanKerjaResource::collection($query->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
     public function store($request)
     {
          try {
               DB::beginTransaction();
               $user = User::create([
                    'email' => $request->email,
                    'name' => $request->nama,
                    'password' => Hash::make($request->password),
               ]);
               $this->model::create([
                    'user_id' => $user->getKey(),
                    'atasan_satuan_kerja_id' => $request->atasan_satuan_kerja,
                    'kode_ref_kelurahan' => $request->kode_ref_kelurahan,
                    'nama' => $request->nama,
               ]);
               $user->assignRole("SATUAN-KERJA");
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
               $satuanKerja = $this->model::find($id);
               $satuanKerja->update([
                    'atasan_satuan_kerja_id' => $request->atasan_satuan_kerja,
                    'kode_ref_kelurahan' => $request->kode_ref_kelurahan,
                    'nama' => $request->nama,
               ]);
               $user = User::findOrFail($satuanKerja->user_id);
               $user->update([
                    'name' => $request->nama,
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
     public function collectionData()
     {
          $satuanKerja = $this->dataBerdasarkanUser(auth()->user());
          return [
               'propinsi' => $satuanKerja->pluck('kd_propinsi')->unique()->values(),
               'dati2' => $satuanKerja->pluck('kd_dati2')->unique()->values(),
               'kecamatan' => $satuanKerja->pluck('kd_kecamatan')->unique()->values(),
               'kelurahan' => $satuanKerja->pluck('kd_kelurahan')->unique()->values(),
          ];
     }
}
