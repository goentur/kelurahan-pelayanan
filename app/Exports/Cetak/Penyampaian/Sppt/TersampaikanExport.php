<?php

namespace App\Exports\Cetak\Penyampaian\Sppt;

use App\Enums\PenyampaianTipe;
use App\Models\Pendataan\PendataanSpop;
use App\Models\Penyampaian;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TersampaikanExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct(protected $request) {}
    public function query()
    {
        $jenis = $this->request->jenis;
        $kelurahan = $this->request->kelurahan;
        return Penyampaian::query()
            ->where(['tahun' => $this->request->tahun])
            ->when($this->request->jenis != 'SEMUA', function ($query) use ($jenis) {
                $query->where('jenis_lapor_id', $jenis);
            })
            ->when($this->request->kelurahan != 'SEMUA', function ($query) use ($kelurahan) {
                $query->where('user_id', $kelurahan);
            })
            ->orderBy('kd_kecamatan')->orderBy('kd_kelurahan')->orderBy('kd_blok')->orderBy('no_urut')->orderBy('kd_jns_op');
    }

    public function collection()
    {
        $cols = $this->query()->cursor();
        foreach ($cols as $col) {
            yield $col;
        }
    }

    public function view(): View
    {
        return view('exports.cetak.penyampaian.sppt.tersampaikan', [
            'data' => $this->collection(),
            'title' => "CETAK PENYAMPAIAN SPPT PBB TERSAMPAIKAN TAHUN " . $this->request->tahun,
        ]);
    }
}
