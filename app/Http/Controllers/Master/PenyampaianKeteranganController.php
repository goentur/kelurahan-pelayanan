<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DataRequest;
use App\Http\Requests\Master\PenyampaianKeterangan\StoreRequest;
use App\Http\Requests\Master\PenyampaianKeterangan\UpdateRequest;
use App\Models\Ref\RefPenyampaianKeterangan;
use App\Repositories\Master\PenyampaianKeterangan\PenyampaianKeteranganRepository;
use App\Support\Facades\Memo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PenyampaianKeteranganController extends Controller implements HasMiddleware
{
    protected PenyampaianKeteranganRepository $repository;

    public function __construct(PenyampaianKeteranganRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:penyampaian-keterangan-index', only: ['index', 'data']),
            new Middleware('can:penyampaian-keterangan-create', only: ['store']),
            new Middleware('can:penyampaian-keterangan-update', only: ['update']),
            new Middleware('can:penyampaian-keterangan-delete', only: ['destroy'])
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('penyampaian-keterangan-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('penyampaian-keterangan-create'),
                'update' => $user->can('penyampaian-keterangan-update'),
                'delete' => $user->can('penyampaian-keterangan-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('Master/PenyampaianKeterangan/Index', compact("gate"));
    }

    public function create()
    {
        abort(404);
    }

    public function store(StoreRequest $request)
    {
        $this->repository->store($request);
        back()->with('success', 'Data berhasil ditambahkan');
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(UpdateRequest $request, RefPenyampaianKeterangan $penyampaianKeterangan)
    {
        $this->repository->update($penyampaianKeterangan->id, $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(RefPenyampaianKeterangan $penyampaianKeterangan)
    {
        $this->repository->delete($penyampaianKeterangan->id);
        back()->with('success', 'Data berhasil dihapus');
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request), 200);
    }

    public function list(Request $request)
    {
        return response()->json($this->repository->list($request), 200);
    }
}
