<?php

namespace App\Http\Controllers\Pengaturan;

use App\Enums\PenyampaianTipe;
use App\Http\Controllers\Controller;
use App\Models\JenisLapor;
use App\Models\User;
use App\Repositories\Transaksi\PenyampaianRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PenyampaianSpptController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:pengaturan-penyampaian-sppt', only: ['index']),
        ];
    }

    public function index()
    {
        $jenisLapor = JenisLapor::select('id', 'nama')->where('jenis', PenyampaianTipe::TERSAMPAIKAN)->orderBy('no_urut')->get();
        $users = User::role('KELURAHAN')->get()->map(function ($user) {
            return [
                'value' => $user->id,
                'label' => $user->name,
            ];
        });
        return inertia('pengaturan/penyampaian-sppt/index', compact('jenisLapor', 'users'));
    }
    public function resetDataLaporanPenyampaianSppt(Request $request, PenyampaianRepository $repository)
    {
        return response()->json($repository->resetDataLaporanPenyampaianSppt($request), 200);
    }
}
