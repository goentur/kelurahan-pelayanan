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

class PenyampaianController extends Controller implements HasMiddleware
{
    use PdfResponse;
    public function __construct(
        protected PenyampaianRepository $repository,
        protected BeritaAcaraRepository $ba,
        protected BeritaAcaraMassalRepository $bam,
        protected PegawaiRepository $pegawaiRepository,
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
        $today = Carbon::today();
        $tanggal = JenisLapor::select('id', 'no_urut', 'tanggal_awal', 'tanggal_akhir')
            ->where('jenis', PenyampaianTipe::TERSAMPAIKAN)
            ->whereDate('tanggal_input_awal', '<=', $today)
            ->whereDate('tanggal_input_akhir', '>=', $today)
            ->first();
        if (!$tanggal) {
            return abort(404);
        }
        $pesan = null;
        if ($tanggal->no_urut > 1) {
            $sebelumnya = JenisLapor::select('id', 'tanggal_awal', 'tanggal_akhir', 'tanggal_lapor_akhir')
                ->where([
                    'no_urut' => $tanggal->no_urut - 1,
                    'jenis' => PenyampaianTipe::TERSAMPAIKAN,
                ])
                ->first();
            if ($sebelumnya) {
                if ($sebelumnya->tanggal_lapor_akhir >= $today) {
                    $sudahLapor = Penyampaian::where([
                        'user_id' => auth()->id(),
                        'jenis_lapor_id' => $sebelumnya->id,
                        'tahun' => date('Y'),
                    ])->exists();
                    if (!$sudahLapor) {
                        $pesan = 'Apakah Anda tidak bisa menginput pada bulan ini? Jika ya, maka laporkan dulu progres penyampaian SPPT PBB-P2 bulan sebelumnya, sehingga Anda bisa melanjutkan pelaporan. Batas akhir pelaporan: ' . Carbon::parse($sebelumnya->tanggal_lapor_akhir)->translatedFormat('d F Y');
                        $tanggal = $sebelumnya;
                    }
                }
            }
        }
        $tanggal = tap($tanggal, function ($t) use ($today) {
            $t->tanggal_awal = Carbon::parse($t->tanggal_awal)->format('Y-m-d');
            $akhir = Carbon::parse($t->tanggal_akhir);
            $t->tanggal_akhir = $akhir->gt($today)
                ? $today->format('Y-m-d')
                : $akhir->format('Y-m-d');
        });
        // $tanggal = [
        //     'tanggal_awal' => date('Y-m-d'),
        //     'tanggal_akhir' => date('Y-m-d'),
        // ];
        return inertia('transaksi/penyampaian/index', compact('gate', 'tanggal', 'pesan'));
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
    public function lihat()
    {
        return inertia('transaksi/penyampaian/lihat');
    }

    public function lihatData(Request $request)
    {
        return response()->json($this->repository->lihatData($request), 200);
    }

    public function pengembalian()
    {
        return inertia('transaksi/penyampaian/pengembalian');
    }

    public function pengembalianData(DataPengembalianRequest $request)
    {
        return response()->json($this->repository->lihatDataPengembalian($request), 200);
    }
    public function pengembalianCetakBA($id)
    {
        $user = auth()->user();
        $data = $this->repository->getDataBeritaAcara($id);
        $pegawai = [
            'petugas' => $this->pegawaiRepository->pegawaiPetugasCollection($user),
            'kepala' => $this->pegawaiRepository->pegawaiKepalaCollection($user),
        ];
        $satuanKerja = [
            'kecamatan' => $user?->satuanKerja->atasan->nama,
            'kelurahan' => $user->name
        ];
        $this->pdfResponse($this->ba->cetak($data, $satuanKerja, $pegawai, 'I'));
    }
    public function pengembalianCetakMassalBA(Request $request)
    {
        $user = auth()->user();
        $data = $this->repository->getDataBeritaAcaraMassal($request);
        $pegawai = [
            'petugas' => $this->pegawaiRepository->pegawaiPetugasCollection($user),
            'kepala' => $this->pegawaiRepository->pegawaiKepalaCollection($user),
        ];
        $satuanKerja = [
            'kecamatan' => $user?->satuanKerja->atasan->nama,
            'kelurahan' => $user->name
        ];
        return $this->bam->cetak($data, $satuanKerja, $pegawai);
        // $this->pdfResponse($this->bam->cetak($data, $satuanKerja, $pegawai, 'I'));
    }
}
