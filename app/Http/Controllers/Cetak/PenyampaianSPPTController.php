<?php

namespace App\Http\Controllers\Cetak;

use App\Enums\PenyampaianTipe;
use App\Exports\Cetak\Penyampaian\Sppt\TersampaikanExport;
use App\Exports\Cetak\Penyampaian\Sppt\TidakTersampaikanDenganKeteranganExport;
use App\Exports\Cetak\Penyampaian\Sppt\TidakTersampaikanExport;
use App\Http\Controllers\Controller;
use App\Models\JenisLapor;
use App\Models\User;
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
        $kelurahan = User::role('KELURAHAN')->get()->map(function ($user) {
            return [
                'value' => $user->id,
                'label' => $user->name,
            ];
        });
        return inertia('cetak/penyampaian-sppt/index', compact('jenisLapor', 'kelurahan'));
    }

    public function tidakTersampaikan(Request $request)
    {
        return (new TidakTersampaikanExport($request))->download('cetak-tidak-tersampaikan-sppt-pbb-tahun-' . $request->tahun . '.xlsx');
    }
    public function tidakTersampaikanDgKeterangan(Request $request)
    {
        return (new TidakTersampaikanDenganKeteranganExport($request))->download('cetak-tidak-tersampaikan-sppt-pbb-dengan-keterangan-tahun-' . $request->tahun . '.xlsx');
    }
    public function tersampaikan(Request $request)
    {
        return (new TersampaikanExport($request))->download('cetak-penyampaian-sppt-pbb-tahun-' . $request->tahun . '.xlsx');
    }
}
