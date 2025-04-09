<?php

namespace App\Repositories\Master\Jabatan;

use App\Http\Resources\Common\LabelValueResource;
use App\Http\Resources\Jabatan\JabatanResource;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;

class JabatanRepository
{
     public function __construct(protected Jabatan $model) {}
     public function list()
     {
          return LabelValueResource::collection($this->model::select('id', 'nama')->get());
     }
     public function data($request)
     {
          $query = $this->model::select('id', 'nama', 'jenis');
          if ($request->search) {
               $query->where('nama', 'like', "%{$request->search}%")->orWhere('jenis', 'like', "%{$request->search}%");
          }
          $result = JabatanResource::collection($query->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
     public function store($request)
     {
          try {
               DB::beginTransaction();
               $this->model::create([
                    'nama' => $request->nama,
                    'jenis' => $request->jenis,
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
                    'jenis' => $request->jenis,
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
