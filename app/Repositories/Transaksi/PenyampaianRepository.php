<?php

namespace App\Repositories\Transaksi;

use App\Enums\PenyampaianStatus;
use App\Enums\PenyampaianTipe;
use App\Http\Resources\Common\LabelValueResource;
use App\Http\Resources\Transaksi\LaporanPenyampaian\DataResource as LaporanPenyampaianDataResource;
use App\Http\Resources\Transaksi\LaporanPenyampaian\DataSisaAtauKembaliResource;
use App\Http\Resources\Transaksi\Penyampaian\DataResource;
use App\Models\BakuAwal;
use App\Models\JenisLapor;
use App\Models\Penyampaian;
use App\Models\Ref\RefPenyampaianKeterangan;
use App\Repositories\Common\JenisBukuRepository;
use App\Repositories\Master\SatuanKerja\SatuanKerjaRepository;
use Illuminate\Support\Facades\DB;

class PenyampaianRepository
{
    public function __construct(
        protected Penyampaian $model,
        protected JenisBukuRepository $jenisBuku,
        protected SatuanKerjaRepository $satuanKerja,
    ) {}
    public function list()
    {
        return LabelValueResource::collection($this->model::select('uuid', 'name')->get());
    }
    public function data($request)
    {
        $nop = explode('.', $request->kelurahan);
        $query = BakuAwal::with('datObjekPajak', 'penyampaian')
            ->select('kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt', 'nm_wp_sppt', 'pbb_yg_harus_dibayar_sppt')
            ->where([
                'kd_propinsi' => $nop[0],
                'kd_dati2' => $nop[1],
                'kd_kecamatan' => $nop[2],
                'kd_kelurahan' => $nop[3],
                'thn_pajak_sppt' => date('Y'),
            ])
            ->when(!empty($request->nama), function ($query) use ($request) {
                $query->whereLike('nm_wp_sppt', "%$request->nama%", caseSensitive: false);
            })
            ->when(!empty($request->tipe), function ($query) use ($request) {
                if ($request->tipe == 'BELUM') {
                    $query->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('penyampaians')
                            ->whereRaw('penyampaians.kd_propinsi = baku_awal.kd_propinsi')
                            ->whereRaw('penyampaians.kd_dati2 = baku_awal.kd_dati2')
                            ->whereRaw('penyampaians.kd_kecamatan = baku_awal.kd_kecamatan')
                            ->whereRaw('penyampaians.kd_kelurahan = baku_awal.kd_kelurahan')
                            ->whereRaw('penyampaians.kd_blok = baku_awal.kd_blok')
                            ->whereRaw('penyampaians.no_urut = baku_awal.no_urut')
                            ->whereRaw('penyampaians.kd_jns_op = baku_awal.kd_jns_op')
                            ->whereRaw('penyampaians.tahun = baku_awal.thn_pajak_sppt');
                    });
                }
                if ($request->tipe == 'TERSAMPAIKAN' || $request->tipe == 'TIDAK') {
                    $query->whereHas('penyampaian', function ($query) use ($request) {
                        $query->where('tipe', $request->tipe);
                    });
                }
            })
            ->when(!empty($request->kd_blok), function ($query) use ($request) {
                $query->where('kd_blok', $request->kd_blok);
            })
            ->when(!empty($request->no_urut), function ($query) use ($request) {
                $query->where('no_urut', $request->no_urut);
            })->orderBy('kd_propinsi')->orderBy('kd_dati2')->orderBy('kd_kecamatan')->orderBy('kd_kelurahan')->orderBy('kd_blok')->orderBy('no_urut')->orderBy('kd_jns_op');
        $result = DataResource::collection($query->paginate($request->perPage ?? 25))->response()->getData(true);
        return $result['meta'] + ['data' => $result['data']];
    }
    public function tersampaikan($request)
    {
        $tahun = date('Y');
        $nop = explode('.', $request->id);
        $tipe = PenyampaianTipe::TERSAMPAIKAN;
        $penyampaian = $this->model::select('id')->where([
            'kd_propinsi' => $nop[0],
            'kd_dati2' => $nop[1],
            'kd_kecamatan' => $nop[2],
            'kd_kelurahan' => $nop[3],
            'kd_blok' => $nop[4],
            'no_urut' => $nop[5],
            'kd_jns_op' => $nop[6],
            'tahun' => $tahun,
            'tipe' => $tipe,
        ])->first();
        try {
            DB::beginTransaction();
            if (!$penyampaian) {
                $this->model::create([
                    'user_id' => auth()->id(),
                    'penyampaian_keterangan_id' => null,
                    'kd_propinsi' => $nop[0],
                    'kd_dati2' => $nop[1],
                    'kd_kecamatan' => $nop[2],
                    'kd_kelurahan' => $nop[3],
                    'kd_blok' => $nop[4],
                    'no_urut' => $nop[5],
                    'kd_jns_op' => $nop[6],
                    'tahun' => $tahun,
                    'nama_wp' => $request->nama_wp,
                    'alamat_objek' => $request->alamat_objek,
                    'nominal' => str_replace('.', '', $request->nominal),
                    'tipe' => $tipe,
                    'status' => PenyampaianStatus::SIMPAN,
                    'keterangan' => $request->value,
                    'catatan' => "Tersampaikan ke Wajib Pajak",
                ]);
            } else {
                $penyampaian->update([
                    'user_id' => auth()->id(),
                    'penyampaian_keterangan_id' => null,
                    'keterangan' => $request->value,
                    'catatan' => "Tersampaikan ke Wajib Pajak",
                ]);
            }
            DB::commit();
            return [
                'status' => true,
                'value' => $tipe,
                'message' => "SIMPAN",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'value' => null,
                'message' => "GAGAL",
            ];
        }
    }
    public function tidakTersampaikan($request)
    {
        $tahun = date('Y');
        $nop = explode('.', $request->id);
        $penyampaian = $this->model::select('id')->where([
            'kd_propinsi' => $nop[0],
            'kd_dati2' => $nop[1],
            'kd_kecamatan' => $nop[2],
            'kd_kelurahan' => $nop[3],
            'kd_blok' => $nop[4],
            'no_urut' => $nop[5],
            'kd_jns_op' => $nop[6],
            'tahun' => $tahun,
        ])->first();
        try {
            DB::beginTransaction();
            $tipe = PenyampaianTipe::TIDAK;
            $keterangan = RefPenyampaianKeterangan::find($request->value)?->nama;
            if (!$penyampaian) {
                $this->model::create([
                    'user_id' => auth()->id(),
                    'penyampaian_keterangan_id' => $request->value,
                    'kd_propinsi' => $nop[0],
                    'kd_dati2' => $nop[1],
                    'kd_kecamatan' => $nop[2],
                    'kd_kelurahan' => $nop[3],
                    'kd_blok' => $nop[4],
                    'no_urut' => $nop[5],
                    'kd_jns_op' => $nop[6],
                    'tahun' => $tahun,
                    'nama_wp' => $request->nama_wp,
                    'alamat_objek' => $request->alamat_objek,
                    'nominal' => str_replace('.', '', $request->nominal),
                    'tipe' => $tipe,
                    'status' => PenyampaianStatus::SIMPAN,
                    'keterangan' => $keterangan,
                    'catatan' => $request->keterangan,
                ]);
            } else {
                $penyampaian->update([
                    'user_id' => auth()->id(),
                    'penyampaian_keterangan_id' => $request->value,
                    'keterangan' => $keterangan,
                    'catatan' => $request->keterangan,
                ]);
            }
            DB::commit();
            return [
                'status' => false,
                'value' => $tipe,
                'message' => "SIMPAN",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'value' => null,
                'message' => "GAGAL",
            ];
        }
    }
    public function delete($request)
    {
        $tahun = date('Y');
        $nop = explode('.', $request->id);
        $this->model::select('id')->where([
            'kd_propinsi' => $nop[0],
            'kd_dati2' => $nop[1],
            'kd_kecamatan' => $nop[2],
            'kd_kelurahan' => $nop[3],
            'kd_blok' => $nop[4],
            'no_urut' => $nop[5],
            'kd_jns_op' => $nop[6],
            'tahun' => $tahun,
        ])?->delete();
    }
    public function queryLaporan($request)
    {
        $jenisLapor = JenisLapor::find($request->id);
        $dataSatuanKerja = $this->satuanKerja->collectionData();
        return $this->model::select('id', 'kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'tahun', 'nama_wp', 'alamat_objek', 'nominal', 'tipe', 'keterangan')
            ->where([
                'kd_propinsi' => $dataSatuanKerja['propinsi'],
                'kd_dati2' => $dataSatuanKerja['dati2'],
                'kd_kecamatan' => $dataSatuanKerja['kecamatan'],
                'tahun' => date('Y'),
                'tipe' => $jenisLapor->jenis,
                'status' => PenyampaianStatus::SIMPAN,
            ])
            ->whereIn('kd_kelurahan', $dataSatuanKerja['kelurahan'])
            ->when(
                $jenisLapor->jenis === PenyampaianTipe::TERSAMPAIKAN->value,
                fn($q) => $q->whereBetween('keterangan', [$jenisLapor->tanggal_awal, $jenisLapor->tanggal_akhir])
            )
            ->orderBy('kd_kelurahan')
            ->orderBy('kd_blok')
            ->orderBy('no_urut')
            ->orderBy('kd_jns_op');
    }
    public function queryLaporanSisa()
    {
        $dataSatuanKerja = $this->satuanKerja->collectionData();
        $query = DB::table('baku_awal')->select('baku_awal.kd_propinsi', 'baku_awal.kd_dati2', 'baku_awal.kd_kecamatan', 'baku_awal.kd_kelurahan', 'baku_awal.kd_blok', 'baku_awal.no_urut', 'baku_awal.kd_jns_op', 'baku_awal.thn_pajak_sppt', 'baku_awal.nm_wp_sppt', 'baku_awal.pbb_yg_harus_dibayar_sppt', 'dat_objek_pajak.jalan_op', 'dat_objek_pajak.blok_kav_no_op', 'dat_objek_pajak.rt_op', 'dat_objek_pajak.rw_op')
            ->join('dat_objek_pajak', function ($query) {
                $query->on('baku_awal.kd_propinsi', '=', 'dat_objek_pajak.kd_propinsi')
                    ->on('baku_awal.kd_dati2', '=', 'dat_objek_pajak.kd_dati2')
                    ->on('baku_awal.kd_kecamatan', '=', 'dat_objek_pajak.kd_kecamatan')
                    ->on('baku_awal.kd_kelurahan', '=', 'dat_objek_pajak.kd_kelurahan')
                    ->on('baku_awal.kd_blok', '=', 'dat_objek_pajak.kd_blok')
                    ->on('baku_awal.no_urut', '=', 'dat_objek_pajak.no_urut')
                    ->on('baku_awal.kd_jns_op', '=', 'dat_objek_pajak.kd_jns_op');
            })
            ->where([
                'baku_awal.kd_propinsi' => $dataSatuanKerja['propinsi'],
                'baku_awal.kd_dati2' => $dataSatuanKerja['dati2'],
                'baku_awal.kd_kecamatan' => $dataSatuanKerja['kecamatan'],
                'baku_awal.thn_pajak_sppt' => date('Y'),
            ])
            ->whereIn('baku_awal.kd_kelurahan', $dataSatuanKerja['kelurahan'])
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('penyampaians')
                    ->whereRaw('penyampaians.kd_propinsi = baku_awal.kd_propinsi')
                    ->whereRaw('penyampaians.kd_dati2 = baku_awal.kd_dati2')
                    ->whereRaw('penyampaians.kd_kecamatan = baku_awal.kd_kecamatan')
                    ->whereRaw('penyampaians.kd_kelurahan = baku_awal.kd_kelurahan')
                    ->whereRaw('penyampaians.kd_blok = baku_awal.kd_blok')
                    ->whereRaw('penyampaians.no_urut = baku_awal.no_urut')
                    ->whereRaw('penyampaians.kd_jns_op = baku_awal.kd_jns_op')
                    ->whereRaw('penyampaians.tahun = baku_awal.thn_pajak_sppt');
            })
            ->orderBy('baku_awal.kd_kelurahan')
            ->orderBy('baku_awal.kd_blok')
            ->orderBy('baku_awal.no_urut')
            ->orderBy('baku_awal.kd_jns_op');
        return $query;
    }
    public function dataLaporan($request)
    {
        if ($request->jenis == PenyampaianTipe::KEMBALI->value) {
            $query = $this->queryLaporanSisa();
            $result = DataSisaAtauKembaliResource::collection($query->paginate($request->perPage ?? 25))->response()->getData(true);
        } else {
            $query = $this->queryLaporan($request);
            $result = LaporanPenyampaianDataResource::collection($query->paginate($request->perPage ?? 25))->response()->getData(true);
        }
        return $result['meta'] + ['data' => $result['data']];
    }
    public function simpanLaporan($request)
    {
        try {
            DB::beginTransaction();
            if ($request->jenis == PenyampaianTipe::KEMBALI->value) {
                $userId = auth()->id();
                $tipe = PenyampaianTipe::KEMBALI;
                $status = PenyampaianStatus::TERLAPOR;
                $keterangan = 'SISA ATAU DIKEMBALIKAN';
                $this->queryLaporanSisa()->chunk(100, function ($batches) use ($userId, $tipe, $status, $keterangan, $request) {
                    foreach ($batches as $item) {
                        Penyampaian::create([
                            'user_id' => $userId,
                            'jenis_lapor_id' => $request->id,
                            'kd_propinsi' => $item->kd_propinsi,
                            'kd_dati2' => $item->kd_dati2,
                            'kd_kecamatan' => $item->kd_kecamatan,
                            'kd_kelurahan' => $item->kd_kelurahan,
                            'kd_blok' => $item->kd_blok,
                            'no_urut' => $item->no_urut,
                            'kd_jns_op' => $item->kd_jns_op,
                            'tahun' => $item->thn_pajak_sppt,
                            'nama_wp' => $item->nm_wp_sppt,
                            'alamat_objek' => trim(implode(' ', array_filter([
                                $item->jalan_op,
                                $item->blok_kav_no_op,
                                (!blank($item->rt_op) && !blank($item->rw_op)) ? "RT/RW {$item->rt_op}/{$item->rw_op}" : null
                            ]))),
                            'nominal' => $item->pbb_yg_harus_dibayar_sppt,
                            'tipe' => $tipe,
                            'status' => $status,
                            'keterangan' => $keterangan,
                        ]);
                    }
                });
            } else {
                $query = $this->queryLaporan($request)->get();
                foreach ($query as $value) {
                    $value->update([
                        'status' => PenyampaianStatus::TERLAPOR,
                        'jenis_lapor_id' => $request->id
                    ]);
                }
            }
            DB::commit();
            return [
                'status' => true,
                'message' => "Data berhasil dilaporkan",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'status' => false,
                'message' => "Gagal melaporkan data",
            ];
        }
    }
}
