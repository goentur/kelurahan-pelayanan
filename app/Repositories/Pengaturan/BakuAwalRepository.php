<?php

namespace App\Repositories\Pengaturan;

use App\Models\BakuAwal;
use App\Models\Sppt;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BakuAwalRepository
{
    public function __construct(protected BakuAwal $model) {}

    public function storeOrUpdate($request)
    {
        try {
            DB::beginTransaction();
            $query = BakuAwal::where('THN_PAJAK_SPPT', $request->tahun_pajak)
                ->where('KD_PROPINSI', $request->kd_propinsi)
                ->where('KD_DATI2', $request->kd_dati2)
                ->whereIn('STATUS_PEMBAYARAN_SPPT', ['0', '1'])
                ->when($request->kd_kecamatan, fn($q) => $q->where('KD_KECAMATAN', $request->kd_kecamatan))
                ->when($request->kd_kelurahan, fn($q) => $q->where('KD_KELURAHAN', $request->kd_kelurahan))
                ->when($request->kd_blok, fn($q) => $q->where('KD_BLOK', $request->kd_blok))
                ->when($request->no_urut, fn($q) => $q->where('NO_URUT', $request->no_urut));

            $data = $query->get();

            if ($data->isNotEmpty()) {
                $data->each(fn($value) => $value->update($value->sppt()->only(['SIKLUS_SPPT', 'KD_KANWIL_BANK', 'KD_KPPBB_BANK', 'KD_BANK_TUNGGAL', 'KD_BANK_PERSEPSI', 'KD_TP', 'NM_WP_SPPT', 'JLN_WP_SPPT', 'BLOK_KAV_NO_WP_SPPT', 'RW_WP_SPPT', 'RT_WP_SPPT', 'KELURAHAN_WP_SPPT', 'KOTA_WP_SPPT', 'KD_POS_WP_SPPT', 'NPWP_SPPT', 'NO_PERSIL_SPPT', 'KD_KLS_TANAH', 'THN_AWAL_KLS_TANAH', 'KD_KLS_BNG', 'THN_AWAL_KLS_BNG', 'TGL_JATUH_TEMPO_SPPT', 'LUAS_BUMI_SPPT', 'LUAS_BNG_SPPT', 'NJOP_BUMI_SPPT', 'NJOP_BNG_SPPT', 'NJOP_SPPT', 'NJOPTKP_SPPT', 'NJKP_SPPT', 'PBB_TERHUTANG_SPPT', 'FAKTOR_PENGURANG_SPPT', 'PBB_YG_HARUS_DIBAYAR_SPPT', 'STATUS_PEMBAYARAN_SPPT', 'STATUS_TAGIHAN_SPPT', 'STATUS_CETAK_SPPT', 'TGL_TERBIT_SPPT', 'TGL_CETAK_SPPT', 'NIP_PENCETAK_SPPT', 'TARIF_SPPT'])));
            } else {
                DB::table('baku_awal')->insertUsing(
                    $this->field(),
                    Sppt::where('THN_PAJAK_SPPT', $request->tahun_pajak)
                        ->where('KD_PROPINSI', $request->kd_propinsi)
                        ->where('KD_DATI2', $request->kd_dati2)
                        ->whereIn('STATUS_PEMBAYARAN_SPPT', ['0', '1'])
                        ->when($request->kd_kecamatan, fn($q) => $q->where('KD_KECAMATAN', $request->kd_kecamatan))
                        ->when($request->kd_kelurahan, fn($q) => $q->where('KD_KELURAHAN', $request->kd_kelurahan))
                        ->when($request->kd_blok, fn($q) => $q->where('KD_BLOK', $request->kd_blok))
                        ->when($request->no_urut, fn($q) => $q->where('NO_URUT', $request->no_urut))
                        ->select($this->field())
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'tahun_pajak' => 'Terjadi kesalahan saat penyimpanan data, silakan ulangi lagi.',
            ]);
        }
    }
    function field()
    {
        return ['kd_propinsi', 'kd_dati2', 'kd_kecamatan', 'kd_kelurahan', 'kd_blok', 'no_urut', 'kd_jns_op', 'thn_pajak_sppt', 'siklus_sppt', 'kd_kanwil_bank', 'kd_kppbb_bank', 'kd_bank_tunggal', 'kd_bank_persepsi', 'kd_tp', 'nm_wp_sppt', 'jln_wp_sppt', 'blok_kav_no_wp_sppt', 'rw_wp_sppt', 'rt_wp_sppt', 'kelurahan_wp_sppt', 'kota_wp_sppt', 'kd_pos_wp_sppt', 'npwp_sppt', 'no_persil_sppt', 'kd_kls_tanah', 'thn_awal_kls_tanah', 'kd_kls_bng', 'thn_awal_kls_bng', 'tgl_jatuh_tempo_sppt', 'luas_bumi_sppt', 'luas_bng_sppt', 'njop_bumi_sppt', 'njop_bng_sppt', 'njop_sppt', 'njoptkp_sppt', 'njkp_sppt', 'pbb_terhutang_sppt', 'faktor_pengurang_sppt', 'pbb_yg_harus_dibayar_sppt', 'status_pembayaran_sppt', 'status_tagihan_sppt', 'status_cetak_sppt', 'tgl_terbit_sppt', 'tgl_cetak_sppt', 'nip_pencetak_sppt', 'tarif_sppt'];
    }
}
