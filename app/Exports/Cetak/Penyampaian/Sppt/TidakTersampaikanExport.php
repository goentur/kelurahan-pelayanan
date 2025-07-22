<?php

namespace App\Exports\Cetak\Penyampaian\Sppt;

use App\Enums\PenyampaianTipe;
use App\Models\Pendataan\PendataanSpop;
use App\Models\Penyampaian;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TidakTersampaikanExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct(protected $tahun) {}
    public function query()
    {
        return Penyampaian::query()
            ->where(['tahun' => $this->tahun, 'tipe' => PenyampaianTipe::TIDAK])
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
        return view('exports.cetak.penyampaian.sppt.tidak-tersampaikan', [
            'data' => $this->collection(),
            'title' => "CETAK PENYAMPAIAN SPPT PBB TIDAK TERSAMPAIKAN TAHUN " . $this->tahun,
        ]);
    }
}
