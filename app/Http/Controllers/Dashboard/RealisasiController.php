<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\RealisasiRepository;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RealisasiController extends Controller implements HasMiddleware
{

    public function __construct(
        protected RealisasiRepository $repository,
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:dashboard-realisasi', only: ['index', 'data']),
        ];
    }

    public function index()
    {
        $tahun = date('Y');
        return inertia('dashboard/realisasi/index', compact('tahun'));
    }

    public function data()
    {
        return response()->json($this->repository->data([1, 2, 3, 4, 5]), 200);
    }
}
