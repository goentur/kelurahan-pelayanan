<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\Penyampaian\DataRequest;
use App\Http\Requests\Transaksi\Penyampaian\StoreRequest;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use App\Repositories\Transaksi\PenyampaianRepository;
use App\Support\Facades\Memo;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PenyampaianController extends Controller implements HasMiddleware
{
    protected SatuanKerjaRepository $satKerjrepository;
    protected PenyampaianRepository $repository;
    protected JenisBukuRepository $jenisBukuRepository;

    public function __construct(
        SatuanKerjaRepository $satKerjrepository,
        PenyampaianRepository $repository,
        JenisBukuRepository $jenisBukuRepository,
    ) {
        $this->satKerjrepository = $satKerjrepository;
        $this->repository = $repository;
        $this->jenisBukuRepository = $jenisBukuRepository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:penyampaian-index', only: ['index', 'data']),
            new Middleware('can:penyampaian-create', only: ['store']),
            new Middleware('can:penyampaian-update', only: ['update'])
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('penyampaian-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('penyampaian-create'),
                'update' => $user->can('penyampaian-update'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        $jenisBuku = $this->jenisBukuRepository->data();
        return inertia('Transaksi/Penyampaian/Index', compact("gate", "jenisBuku"));
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request), 200);
    }

    public function store(StoreRequest $request)
    {
        return response()->json($this->repository->simpan($request));
    }
}
