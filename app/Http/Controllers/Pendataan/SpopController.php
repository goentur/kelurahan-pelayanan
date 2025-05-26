<?php

namespace App\Http\Controllers\Pendataan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\NopRequest;
use App\Http\Requests\Pendataan\Spop\DataRequest;
use App\Http\Requests\Pendataan\Spop\StoreRequest;
use App\Models\Ref\RefJenisTanah;
use App\Models\Ref\RefPekerjaanSubjekPajak;
use App\Models\Ref\RefStatusSubjekPajak;
use App\Repositories\Pendataan\SpopRepository;
use App\Support\Facades\Memo;

class SpopController extends Controller
{
    public function __construct(
        protected SpopRepository $repository,
    ) {}

    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('pendataan-spop-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('pendataan-spop-create'),
                'update' => $user->can('pendataan-spop-update'),
                'delete' => $user->can('pendataan-spop-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        $status = RefStatusSubjekPajak::select("id", "nama")->get();
        $pekerjaan = RefPekerjaanSubjekPajak::select("id", "nama")->get();
        $tanah = RefJenisTanah::select("id", "nama")->get();
        return inertia('pendataan/spop/index', compact("gate", "status", "pekerjaan", "tanah"));
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request));
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function cekNop(NopRequest $request)
    {
        return response()->json($this->repository->cekNop($request));
    }

    public function store(StoreRequest $request)
    {
        $this->repository->store($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }
}
