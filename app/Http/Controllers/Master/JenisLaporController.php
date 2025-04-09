<?php

namespace App\Http\Controllers\Master;

use App\Enums\PenyampaianTipe;
use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DataRequest;
use App\Http\Requests\Master\JenisLapor\StoreRequest;
use App\Http\Requests\Master\JenisLapor\UpdateRequest;
use App\Models\JenisLapor;
use App\Repositories\Master\JenisLapor\JenisLaporRepository;
use App\Support\Facades\Memo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class JenisLaporController extends Controller implements HasMiddleware
{
    protected JenisLaporRepository $repository;

    public function __construct(JenisLaporRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:jenis-lapor-index', only: ['index', 'data']),
            new Middleware('can:jenis-lapor-create', only: ['store']),
            new Middleware('can:jenis-lapor-update', only: ['update']),
            new Middleware('can:jenis-lapor-delete', only: ['destroy'])
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('jenis-lapor-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('jenis-lapor-create'),
                'update' => $user->can('jenis-lapor-update'),
                'delete' => $user->can('jenis-lapor-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        $tipePenyampaian = PenyampaianTipe::toArray();
        return inertia('Master/JenisLapor/Index', compact("gate", "tipePenyampaian"));
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

    public function update(UpdateRequest $request, JenisLapor $jenisLapor)
    {
        $this->repository->update($jenisLapor->id, $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(JenisLapor $jenisLapor)
    {
        $this->repository->delete($jenisLapor->id);
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
