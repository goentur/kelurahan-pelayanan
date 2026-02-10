<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\PenyampaianTipe;
use App\Http\Controllers\Controller;
use App\Models\JenisLapor;
use App\Repositories\Dashboard\PenyampaianSPPT\PenyampianSPPTRepository;
use App\Repositories\Dashboard\PenyampaianSPPT\RekapRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PenyampaianSPPTController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('can:dashboard-penyampaian-sppt', only: ['index', 'data']),
        ];
    }

    public function index()
    {
        $jenisLapor = JenisLapor::select('id', 'nama')->where('jenis', PenyampaianTipe::TERSAMPAIKAN)->orderBy('no_urut')->get();
        $tahunPajak = date('Y');
        return inertia('dashboard/penyampaian-sppt/index', compact('jenisLapor', 'tahunPajak'));
    }

    public function data(Request $request, PenyampianSPPTRepository $penyampianSPPTRepository)
    {
        return response()->json($penyampianSPPTRepository->data($request));
    }
    public function rekapData(Request $request, RekapRepository $rekapRepository)
    {
        return response()->json($rekapRepository->data($request));
    }
}
