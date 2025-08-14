<?php

namespace App\Http\Controllers\Sppt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sppt\Data\DataRequest;
use App\Http\Requests\Sppt\Data\InfoPajakBumiBangunanRequest;
use App\Repositories\Sppt\SpptRepository;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DataController extends Controller implements HasMiddleware
{
    public function __construct(
        protected SpptRepository $repository
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:sppt-data-index', only: ['index', 'data']),
        ];
    }

    public function index()
    {
        return inertia('sppt/data/index');
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->data($request), 200);
    }

    public function infoPajakBumiBangunan(InfoPajakBumiBangunanRequest $request)
    {
        return response()->json($this->repository->infoPajakBumiBangunan($request), 200);
    }
}
