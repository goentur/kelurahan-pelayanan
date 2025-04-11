<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Common\DataRequest;
use App\Http\Requests\Master\Pengguna\StoreRequest;
use App\Http\Requests\Master\Pengguna\UpdateRequest;
use App\Models\User;
use App\Repositories\Master\Pengguna\PenggunaRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PenggunaController extends Controller implements HasMiddleware
{
    protected PenggunaRepository $repository;

    public function __construct(PenggunaRepository $repository)
    {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:pengguna-index', only: ['index', 'data']),
            new Middleware('can:pengguna-create', only: ['store']),
            new Middleware('can:pengguna-update', only: ['update']),
            new Middleware('can:pengguna-delete', only: ['destroy'])
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('pengguna-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('pengguna-create'),
                'update' => $user->can('pengguna-update'),
                'delete' => $user->can('pengguna-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('master/pengguna/index', compact("gate"));
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

    public function update(UpdateRequest $request, User $pengguna)
    {
        $this->repository->update($pengguna->id, $request);
        back()->with('success', 'Data berhasil diubah');
    }

    public function destroy(User $pengguna)
    {
        $this->repository->delete($pengguna->id);
        back()->with('success', 'Data berhasil dihapus');
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request), 200);
    }
}
