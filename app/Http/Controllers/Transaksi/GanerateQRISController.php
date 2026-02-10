<?php

namespace App\Http\Controllers\Transaksi;

use App\Enums\PenyampaianTipe;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\Penyampaian\DataPengembalianRequest;
use App\Http\Requests\Transaksi\Penyampaian\DataRequest;
use App\Http\Requests\Transaksi\Penyampaian\DeleteRequest;
use App\Http\Requests\Transaksi\Penyampaian\TersampaikanRequest;
use App\Http\Requests\Transaksi\Penyampaian\TidakTersampaikanRequest;
use App\Models\JenisLapor;
use App\Models\Penyampaian;
use App\Repositories\Master\Pegawai\PegawaiRepository;
use App\Repositories\Transaksi\Cetak\BeritaAcaraMassalRepository;
use App\Repositories\Transaksi\Cetak\BeritaAcaraRepository;
use App\Repositories\Transaksi\PenyampaianRepository;
use App\Support\Facades\Memo;
use App\Traits\Pdf\PdfResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class GanerateQRISController extends Controller implements HasMiddleware
{
    use PdfResponse;
    public function __construct(
        protected PenyampaianRepository $repository,
    ) {}
    public static function middleware(): array
    {
        return [
            new Middleware('can:ganerate-qris', only: ['index', 'form', 'data'])
        ];
    }

    public function index()
    {
        return inertia('transaksi/ganerate-qris/index');
    }
}
