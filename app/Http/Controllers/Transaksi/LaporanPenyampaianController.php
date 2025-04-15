<?php

namespace App\Http\Controllers\Transaksi;

use App\Enums\PenyampaianTipe;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaksi\LaporanPenyampaian\BerdasarkanKelurahanRequest;
use App\Http\Requests\Transaksi\LaporanPenyampaian\DataRequest;
use App\Http\Requests\Transaksi\LaporanPenyampaian\StoreRequest;
use App\Models\JenisLapor;
use App\Models\Ref\RefKelurahan;
use App\Repositories\Master\Pegawai\PegawaiRepository;
use App\Repositories\Master\PenyampaianKeterangan\PenyampaianKeteranganRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use App\Repositories\Transaksi\Cetak\LaporanTersampaikanGabunganRepository;
use App\Repositories\Transaksi\Cetak\LaporanTidakTersampaikanGabunganRepository;
use App\Repositories\Transaksi\LaporanTersampaikanRepository;
use App\Repositories\Transaksi\LaporanTidakTersampaikanRepository;
use App\Repositories\Transaksi\PenyampaianRepository;
use App\Support\Facades\Memo;
use App\Traits\Pdf\PdfResponse;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class LaporanPenyampaianController extends Controller implements HasMiddleware
{
    use PdfResponse;

    public function __construct(
        protected PenyampaianRepository $repository,
        protected SatuanKerjaRepository $satuanKerja,
        protected PegawaiRepository $pegawaiRepository,
        protected PenyampaianKeteranganRepository $penyampaianKeteranganRepository,
        protected LaporanTersampaikanRepository $laporanTersampaikanRepository,
        protected LaporanTersampaikanGabunganRepository $laporanTersampaikanGabunganRepository,
        protected LaporanTidakTersampaikanRepository $laporanTidakTersampaikanRepository,
        protected LaporanTidakTersampaikanGabunganRepository $laporanTidakTersampaikanGabunganRepository,
    ) {
        $this->repository = $repository;
    }
    public static function middleware(): array
    {
        return [
            new Middleware('can:laporan-penyampaian-index', only: ['index']),
            new Middleware('can:laporan-penyampaian-create', only: ['store']),
            new Middleware('can:laporan-penyampaian-update', only: ['update'])
        ];
    }
    private function gate(): array
    {
        $user = auth()->user();
        return Memo::forHour('laporan-penyampaian-gate-' . $user->getKey(), function () use ($user) {
            return [
                'create' => $user->can('laporan-penyampaian-create'),
                'update' => $user->can('laporan-penyampaian-update'),
            ];
        });
    }

    public function index()
    {
        $gate = $this->gate();
        return inertia('transaksi/laporan-penyampaian/index', compact("gate"));
    }

    public function form(JenisLapor $jenisLapor)
    {
        if (now()->between(Carbon::parse($jenisLapor->tanggal_awal), Carbon::parse($jenisLapor->tanggal_akhir))) {
            return inertia('transaksi/laporan-penyampaian/data', compact('jenisLapor'));
        } else {
            return back();
        }
    }

    public function data(DataRequest $request)
    {
        return response()->json($this->repository->dataLaporan($request), 200);
    }

    public function simpan(StoreRequest $request)
    {
        return response()->json($this->repository->simpanLaporan($request), 200);
    }

    public function berdasarkanKelurahan(BerdasarkanKelurahanRequest $request)
    {
        $user = auth()->user();
        $nop = explode('.', $request->kelurahan);
        $kelurahan = [
            'propinsi' => $nop[0],
            'dati2' => $nop[1],
            'kecamatan' => $nop[2],
            'kelurahan' => [$nop[3]],
        ];
        $refKelurahan = RefKelurahan::select('nm_kelurahan')->where([
            'kd_propinsi' => $nop[0],
            'kd_dati2' => $nop[1],
            'kd_kecamatan' => $nop[2],
            'kd_kelurahan' => $nop[3],
        ])->first();
        $satuanKerja = [
            'kecamatan' => $user?->satuanKerja->atasan->nama,
            'kelurahan' => $refKelurahan->nm_kelurahan
        ];
        $this->cetak($user, JenisLapor::find($request->jenisLapor), $satuanKerja, $kelurahan);
    }
    public function gabungan(JenisLapor $jenisLapor)
    {
        $user = auth()->user();
        $satuanKerja = [
            'kecamatan' => $user?->satuanKerja->atasan->nama,
            'kelurahan' => $user->name
        ];
        $this->cetak($user, $jenisLapor, $satuanKerja, $this->satuanKerja->collectionData());
    }
    public function cetak($user, $jenisLapor, $satuanKerja, $kelurahan)
    {
        $kepala = $this->pegawaiRepository->pegawaiKepalaCollection($user);
        if ($jenisLapor->jenis == PenyampaianTipe::TERSAMPAIKAN->value) {
            $petugas = $this->pegawaiRepository->pegawaiPetugasCollection($user);
            $pegawai = [
                'petugas' => $petugas,
                'kepala' => $kepala,
            ];
            $jenisBuku = [
                [
                    'label' => 'DIBAWA 2.000.000',
                    'value' => '1,2,3'
                ],
                [
                    'label' => '2.000.000 KEATAS',
                    'value' => '4,5'
                ],
            ];
            return $this->pdfResponse($this->laporanTersampaikanGabunganRepository->cetak($this->laporanTersampaikanRepository->dataLaporan($jenisLapor, $jenisBuku, $kelurahan), $satuanKerja, $pegawai, $jenisLapor, 'D'));
        }
        if ($jenisLapor->jenis == PenyampaianTipe::TIDAK->value) {
            $pegawai = [
                'kepala' => $kepala,
            ];
            $this->pdfResponse($this->laporanTidakTersampaikanGabunganRepository->cetak(
                $this->laporanTidakTersampaikanRepository->data($jenisLapor->jenis, $kelurahan),
                $satuanKerja,
                $pegawai,
                $jenisLapor,
                'LAPORAN KENDALA DALAM PENYAMPAIAN SPPT PBB-P2 KE WAJIB PAJAK',
                'D'
            ));
        }
        if ($jenisLapor->jenis == PenyampaianTipe::KEMBALI->value) {
            $pegawai = [
                'kepala' => $kepala,
            ];
            $this->pdfResponse($this->laporanTidakTersampaikanGabunganRepository->cetak(
                $this->laporanTidakTersampaikanRepository->data($jenisLapor->jenis, $kelurahan),
                $satuanKerja,
                $pegawai,
                $jenisLapor,
                'LAPORAN SISA ATAU KEMBALI DALAM PENYAMPAIAN SPPT PBB-P2',
                'D'
            ));
        }
    }
}
