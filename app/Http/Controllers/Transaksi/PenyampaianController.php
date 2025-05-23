<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\Penyampaian\DataRequest;
use App\Http\Requests\Transaksi\Penyampaian\DeleteRequest;
use App\Http\Requests\Transaksi\Penyampaian\StoreRequest;
use App\Http\Requests\Transaksi\Penyampaian\TersampaikanRequest;
use App\Http\Requests\Transaksi\Penyampaian\TidakTersampaikanRequest;
use App\Repositories\Transaksi\PenyampaianRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PenyampaianController extends Controller implements HasMiddleware
{
    public function __construct(
        protected PenyampaianRepository $repository,
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:penyampaian-index', only: ['index', 'data']),
            new Middleware('can:penyampaian-create', only: ['store']),
            new Middleware('can:penyampaian-update', only: ['store']),
            new Middleware('can:penyampaian-delete', only: ['delete'])
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('penyampaian-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('penyampaian-create'),
                'update' => $user->can('penyampaian-update'),
                'delete' => $user->can('penyampaian-delete'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('transaksi/penyampaian/index', compact("gate"));
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request), 200);
    }

    public function tersampaikan(TersampaikanRequest $request)
    {
        return response()->json($this->repository->tersampaikan($request));
    }

    public function tidakTersampaikan(TidakTersampaikanRequest $request)
    {
        return response()->json($this->repository->tidakTersampaikan($request));
    }

    public function delete(DeleteRequest $request)
    {
        try {
            $this->repository->delete($request);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data : ' . $e->getMessage(),
            ], 500);
        }
    }
}
