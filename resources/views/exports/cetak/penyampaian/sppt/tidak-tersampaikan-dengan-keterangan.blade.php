@php
use App\Support\Facades\Helpers;
@endphp
<table>
    <thead>
        <tr>
            <th colspan="7" style="font-weight: bold; text-align: center;">{{ $title }}</th>
        </tr>
        <tr>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NO</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NOP</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NAMA WAJIB PAJAK</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NOMINAL</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">ALAMAT</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">KETERANGAN</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">CATATAN</th>
            @for ($i = date('Y'); $i >= 2008; $i--)
                <th style="border-bottom: thin; font-weight: bold; text-align: center;">{{ $i }}</th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($data as $i => $r)
            <tr>
                <td style="border: thin;">{{ ++$i }}</td>
                <td style="border-bottom: thin;">{{ $r->nop }}</td>
                <td style="border-bottom: thin;">{{ $r->nama_wp }}</td>
                <td style="border-bottom: thin;">{{ $r->nominal }}</td>
                <td style="border-bottom: thin;">{{ $r->alamat_objek }}</td>
                <td style="border-bottom: thin;">{{ $r->keterangan }}</td>
                <td style="border-bottom: thin;">{{ $r->catatan }}</td>
                @for ($tahun = date('Y'); $tahun >= 2008; $tahun--)
                    @php
                        $spptTahunIni = $r->sppt->firstWhere('thn_pajak_sppt', $tahun);
                        if ($spptTahunIni) {
                            $totalBayar = $spptTahunIni->pembayaranSppt->sum('jml_sppt_yg_dibayar');
                            $tagihan = $spptTahunIni->pbb_yg_harus_dibayar_sppt;
                            $sisa = $tagihan - $totalBayar;
                            $tampil = $sisa;
                        } else {
                            $tampil = '-';
                        }
                    @endphp
                    <td style="border-bottom: thin; text-align: end;">
                        {{ $tampil }}
                    </td>
                @endfor
            </tr>
        @endforeach
    </tbody>
</table>
