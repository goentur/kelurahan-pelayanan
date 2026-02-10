<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Repositories\Dashboard\Realisasi\RealisasiPerKelurahanRepository;
use App\Repositories\Dashboard\Realisasi\RealisasiRepository;
use App\Support\Facades\Memo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RealisasiController extends Controller implements HasMiddleware
{

    public function __construct(
        protected RealisasiRepository $repository,
        protected RealisasiPerKelurahanRepository $realisasiPerKelurahan,
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:dashboard-realisasi', only: ['index', 'data']),
            new Middleware('can:dashboard-realisasi-per-kelurahan', only: ['dataPerKelurahan']),
        ];
    }

    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('dashboard-realisasi-gate-' . $user->getKey(), function () use ($user) {
            return [
                'realisasi' => $user->can('dashboard-realisasi'),
                'per_kelurahan' => $user->can('dashboard-realisasi-per-kelurahan'),
            ];
        });
    }

    public function index()
    {
        $tahun = date('Y');
        $gate = $this->gate();
        return inertia('dashboard/realisasi/index', compact('tahun', 'gate'));
    }

    public function data(Request $request)
    {
        return response()->json($this->repository->data($request, [1, 2, 3, 4, 5]), 200);
    }

    public function dataPerKelurahan(Request $request)
    {
        return response()->json($this->realisasiPerKelurahan->data($request), 200);
    }
}
