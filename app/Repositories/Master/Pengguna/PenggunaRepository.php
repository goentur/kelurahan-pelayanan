<?php

namespace App\Repositories\Master\Pengguna;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaRepository
{
    public function __construct(protected User $model) {}
    private function applySearchFilter($request)
    {
        return fn($q) => $q->where(function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%");
        });
    }
    public function data($request)
    {
        return $this->model::select('id', 'name', 'email')
            ->when($request->search, $this->applySearchFilter($request))
            ->whereHas('roles', fn($q) => $q->where('name', 'DEVELOPER'))
            ->latest()
            ->paginate($request->perPage ?? 25);
    }
    public function store($request)
    {
        try {
            DB::beginTransaction();
            $user = $this->model->create([
                'email' => $request->email,
                'name' => $request->nama,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole("DEVELOPER");
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
            $user = $this->model->findOrFail($id);
            $user->update([
                'email' => $request->email,
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
        return $this->model->find($id)?->delete();
    }
}
