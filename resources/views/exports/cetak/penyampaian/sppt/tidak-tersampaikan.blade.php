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
        </tr>
    </thead>
    <tbody>
        @php
            $summary = [];
        @endphp

        @foreach($data as $i => $r)
            @php
                // Inisialisasi jika belum ada di summary
                if (!isset($summary[$r->keterangan])) {
                    $summary[$r->keterangan] = [
                        'total_nominal' => 0,
                        'jumlah' => 0
                    ];
                }
        
                // Tambahkan nominal dan jumlah
                $summary[$r->keterangan]['total_nominal'] += $r->nominal;
                $summary[$r->keterangan]['jumlah'] += 1;
            @endphp

            <tr>
                <td style="border: thin;">{{ ++$i }}</td>
                <td style="border-bottom: thin;">{{ $r->nop }}</td>
                <td style="border-bottom: thin;">{{ $r->nama_wp }}</td>
                <td style="border-bottom: thin;">{{ $r->nominal }}</td>
                <td style="border-bottom: thin;">{{ $r->alamat_objek }}</td>
                <td style="border-bottom: thin;">{{ $r->keterangan }}</td>
                <td style="border-bottom: thin;">{{ $r->catatan }}</td>
            </tr>
        @endforeach

        {{-- Tampilkan summary per keterangan --}}
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td colspan="7"></td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" style="font-weight: bold; text-align: center;">REKAP</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="2" style="font-weight: bold; text-align: center;">KETERANGAN</td>
            <td style="font-weight: bold; text-align: center;">JUMLAH</td>
            <td style="font-weight: bold; text-align: center;">NOMINAL</td>
        </tr>
        @foreach($summary as $keterangan => $rekap)
            <tr>
                <td></td>
                <td colspan="2">{{ $keterangan }}</td>
                <td>{{ $rekap['jumlah'] }}</td>
                <td>{{ number_format($rekap['total_nominal']) }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
