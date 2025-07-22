<table>
    <thead>
        <tr>
            <th colspan="6" style="font-weight: bold; text-align: center;">{{ $title }}</th>
        </tr>
        <tr>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NO</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NOP</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NAMA WAJIB PAJAK</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">NOMINAL</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">ALAMAT</th>
            <th style="border-bottom: thin; font-weight: bold; text-align: center;">TANGGAL TERSAMPAIKAN</th>
        </tr>
    </thead>
    <tbody>
        @php
            $summary = [];
        @endphp

        @foreach($data as $i => $r)
            <tr>
                <td style="border: thin;">{{ ++$i }}</td>
                <td style="border-bottom: thin;">{{ $r->nop }}</td>
                <td style="border-bottom: thin;">{{ $r->nama_wp }}</td>
                <td style="border-bottom: thin;">{{ $r->nominal }}</td>
                <td style="border-bottom: thin;">{{ $r->alamat_objek }}</td>
                <td style="border-bottom: thin;">{{ $r->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
