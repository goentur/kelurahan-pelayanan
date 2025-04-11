<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengaturan\BakuAwal\StoreOrUpdateRequest;
use App\Repositories\Pengaturan\BakuAwalRepository;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BakuAwalController extends Controller implements HasMiddleware
{
    public function __construct(
        protected BakuAwalRepository $repository
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:pengaturan-baku-awal', only: ['index', 'storeOrUpdate'])
        ];
    }

    public function index()
    {
        return inertia('pengaturan/baku-awal/index');
    }

    public function storeOrUpdate(StoreOrUpdateRequest $request)
    {
        return $this->repository->storeOrUpdate($request);
    }
}
