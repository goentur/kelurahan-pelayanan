<?php

namespace App\Repositories\Master\PenyampaianKeterangan;

use App\Http\Resources\Common\LabelValueResource;
use App\Http\Resources\PenyampaianKeterangan\PenyampaianKeteranganResource;
use App\Models\Ref\RefPenyampaianKeterangan;
use Illuminate\Support\Facades\DB;

class PenyampaianKeteranganRepository
{
     public function __construct(protected RefPenyampaianKeterangan $model) {}
     public function list()
     {
          return LabelValueResource::collection($this->model::select('id', 'nama')->get());
     }
     public function data($request)
     {
          $query = $this->model::select('id', 'nama', 'keterangan');
          if ($request->search) {
               $query->where('nama', 'like', "%{$request->search}%")
                    ->orWhere('keterangan', 'like', "%{$request->search}%");
          }
          $result = PenyampaianKeteranganResource::collection($query->latest()->paginate($request->perPage ?? 25))->response()->getData(true);
          return $result['meta'] + ['data' => $result['data']];
     }
     public function store($request)
     {
          try {
               DB::beginTransaction();
               $this->model::create([
                    'nama' => $request->nama,
                    'keterangan' => $request->keterangan,
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
                    'keterangan' => $request->keterangan,
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
