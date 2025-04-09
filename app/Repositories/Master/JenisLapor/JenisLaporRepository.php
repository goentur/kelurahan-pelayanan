<?php

namespace App\Repositories\Master\JenisLapor;

use App\Http\Resources\JenisLapor\DataResource;
use App\Http\Resources\JenisLapor\JenisLaporResource;
use App\Models\JenisLapor;
use Illuminate\Support\Facades\DB;

class JenisLaporRepository
{
     public function __construct(protected JenisLapor $model) {}
     public function list()
     {
          return DataResource::collection($this->model::select('id', 'nama', 'keterangan', 'jenis', 'tanggal_awal', 'tanggal_akhir')->orderBy('no_urut')->get());
     }
     public function data($request)
     {
          $query = $this->model::select('id', 'nama', 'no_urut', 'jenis', 'keterangan', 'tanggal_awal', 'tanggal_akhir');
          if ($request->search) {
               $query->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('keterangan', 'like', "%{$request->search}%");
          }
          $result = JenisLaporResource::collection($query->orderBy('no_urut')->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
     public function store($request)
     {
          try {
               DB::beginTransaction();
               $this->model::create([
                    'nama' => $request->nama,
                    'no_urut' => $request->no_urut,
                    'jenis' => $request->jenis,
                    'keterangan' => $request->keterangan,
                    'tanggal_awal' => date('Y-m-d', strtotime($request->tanggal_awal)) . ' 00:00:00',
                    'tanggal_akhir' => date('Y-m-d', strtotime($request->tanggal_akhir)) . ' 23:59:00',
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
                    'nama' => $request->nama,
                    'no_urut' => $request->no_urut,
                    'keterangan' => $request->keterangan,
                    'jenis' => $request->jenis,
                    'tanggal_awal' => date('Y-m-d', strtotime($request->tanggal_awal)) . ' 00:00:00',
                    'tanggal_akhir' => date('Y-m-d', strtotime($request->tanggal_akhir)) . ' 23:59:00',
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
}
