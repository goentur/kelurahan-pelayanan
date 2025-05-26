<?php

namespace App\Http\Controllers\Sppt\Pelayanan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sppt\Pelayanan\PerubahanAlamat\CariObjekPajakRequest;
use App\Http\Requests\Sppt\Pelayanan\PerubahanAlamat\DataRequest;
use App\Http\Requests\Sppt\Pelayanan\PerubahanAlamat\StoreOrUpdateRequest;
use App\Models\PelayananPerubahanAlamat;
use App\Repositories\Sppt\Pelayanan\PerubahanAlamatRepository;
use App\Support\Facades\Memo;

class PerubahanAlamatController extends Controller
{
    public function __construct(
        protected PerubahanAlamatRepository $repository,
    ) {}

    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('pelayanan-perubahan-alamat-pemohon-gate-' . $user->getKey(), function () use ($user) {
            return [
                'validasi' => $user->can('pelayanan-perubahan-alamat-validasi-action'),
                'create' => $user->can('pelayanan-perubahan-alamat-pemohon-create'),
                'update' => $user->can('pelayanan-perubahan-alamat-pemohon-update'),
                'delete' => $user->can('pelayanan-perubahan-alamat-pemohon-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('sppt/pelayanan/perubahan-alamat/index', compact("gate"));
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request));
    }

    public function simpanAtauUpdate(StoreOrUpdateRequest $request)
    {
        return response()->json($this->repository->simpanAtauUpdate($request));
    }

    public function dataObjekPajak(CariObjekPajakRequest $request)
    {
        return response()->json($this->repository->dataObjekPajak($request));
    }

    public function hapus(PelayananPerubahanAlamat $pelayananPerubahanAlamat)
    {
        return response()->json($this->repository->hapus($pelayananPerubahanAlamat));
    }
    public function terima(PelayananPerubahanAlamat $pelayananPerubahanAlamat)
    {
        return response()->json($this->repository->terima($pelayananPerubahanAlamat));
    }
}
