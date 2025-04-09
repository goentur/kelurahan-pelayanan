<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\Pegawai\DataRequest;
use App\Http\Requests\Master\Pegawai\StatusRequest;
use App\Http\Requests\Master\Pegawai\StoreRequest;
use App\Http\Requests\Master\Pegawai\UpdateRequest;
use App\Models\Pegawai;
use App\Repositories\Master\Pegawai\PegawaiRepository;
use App\Support\Facades\Memo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PegawaiController extends Controller implements HasMiddleware
{
    protected PegawaiRepository $repository;

    public function __construct(PegawaiRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:pegawai-index', only: ['index', 'data']),
            new Middleware('can:pegawai-create', only: ['store']),
            new Middleware('can:pegawai-update', only: ['update']),
            new Middleware('can:pegawai-delete', only: ['destroy'])
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('pegawai-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('pegawai-create'),
                'update' => $user->can('pegawai-update'),
                'delete' => $user->can('pegawai-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('Master/Pegawai/Index', compact("gate"));
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

    public function update(UpdateRequest $request, Pegawai $pegawai)
    {
        $this->repository->update($pegawai->id, $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(Pegawai $pegawai)
    {
        $this->repository->delete($pegawai->id);
        back()->with('success', 'Data berhasil dihapus');
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request), 200);
    }

    public function status(StatusRequest $request)
    {
        return response()->json($this->repository->status($request), 200);
    }

    public function list(Request $request)
    {
        return response()->json($this->repository->list($request), 200);
    }
}
