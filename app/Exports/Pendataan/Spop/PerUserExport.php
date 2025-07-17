<?php

namespace App\Exports\Pendataan\Spop;

use App\Models\Pendataan\PendataanSpop;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PerUserExport implements FromView, ShouldAutoSize
{
    use Exportable;

    public function query()
    {
        return PendataanSpop::query()
            ->with('user', 'subjekPajak', 'tanah', 'pendataanSpopBangunan', 'jenisPendataanSpop')
            ->where(['tahun' => date('Y'), 'user_id' => auth()->id()]);
    }

    public function collection()
    {
        $cols = $this->query()->cursor();

        foreach ($cols as $col) {
            yield $col;
        }
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [];
    }

    public function view(): View
    {
        return view('exports.pendataan.spop.per-user', [
            'data' => $this->collection(),
            'title' => "EXPORT HASIL PENDATAAN SPOP TAHUN " . date('Y'),
        ]);
    }
}
