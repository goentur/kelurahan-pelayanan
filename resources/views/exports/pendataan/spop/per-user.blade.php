<table>
    <thead>
        <tr>
            <th colspan="39" style="font-weight: bold; text-align: center;">{{ $title }}</th>
        </tr>
        <tr>
            <th colspan="28" style="font-weight: bold; text-align: center;">OBJEK PAJAK</th>
            <th colspan="11" style="font-weight: bold; text-align: center;">SUBJEK PAJAK</th>
        </tr>
        <tr>
            @php
                $headers = [
                    'NO','NAMA PENDATA','KD PROPINSI','KD DATI2','KD KECAMATAN','KD KELURAHAN','KD BLOK','NO URUT','KD JNS OP',
                    'BLOK KAV NO','JALAN','RW','RT','KETERANGAN','JENIS TANAH','NO SERTIPIKAT','LUAS TANAH',
                    'LUAS BANGUNAN','JUMLAH LANTAI','TAHUN DIBANGUN','TAHUN RENOVASI','DAYA LISTRIK','JUMLAH AC','KONDISI',
                    'KONSTRUKSI','DINDING','LANGIT','LANTAI',
                    'NIK','NPWP','NAMA SUBJEK PAJAK','PEKERJAAN','JALAN','BLOK KAV NO','RW','RT','KELURAHAN','KECAMATAN','KOTA'
                ];
            @endphp
            @foreach($headers as $head)
                <th style="border-bottom: thin; font-weight: bold; text-align: center;">{{ $head }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    @foreach($data as $i => $r)
        @php $bangunans = $r->bangunan; @endphp

        @if($bangunans->count())
            @foreach($bangunans as $bIndex => $rb)
                <tr>
                    @if($bIndex === 0)
                        {{-- Data Objek Pajak --}}
                        <td style="border: thin;">{{ ++$i }}</td>
                        <td style="border-bottom: thin;">{{ $r->user->name }}</td>
                        <td style="border-bottom: thin;">{{ $r->kd_propinsi }}</td>
                        <td style="border-bottom: thin;">{{ $r->kd_dati2 }}</td>
                        <td style="border-bottom: thin;">{{ $r->kd_kecamatan }}</td>
                        <td style="border-bottom: thin;">{{ $r->kd_kelurahan }}</td>
                        <td style="border-bottom: thin;">{{ $r->kd_blok }}</td>
                        <td style="border-bottom: thin;">{{ $r->no_urut }}</td>
                        <td style="border-bottom: thin;">{{ $r->kd_jns_op }}</td>
                        <td style="border-bottom: thin;">{{ $r->blok_kav_no }}</td>
                        <td style="border-bottom: thin;">{{ $r->jalan }}</td>
                        <td style="border-bottom: thin;">{{ $r->rw }}</td>
                        <td style="border-bottom: thin;">{{ $r->rt }}</td>
                        <td style="border-bottom: thin;">{{ $r->keterangan }}</td>
                        <td style="border-bottom: thin;">{{ $r->tanah->refJenisTanah->nama }}</td>
                        <td style="border-bottom: thin;">{{ $r->tanah->no_sertipikat ? "'".$r->tanah->no_sertipikat : '' }}</td>
                        <td style="border-bottom: thin;">{{ $r->tanah->luas_tanah }}</td>
                    @else
                        <td style="border-bottom: thin; text-align: centar" colspan="17">BANGUNAN LEBIH DARI 1</td>
                    @endif

                    {{-- Data Bangunan --}}
                    <td style="border-bottom: thin;">{{ $rb->luas_bangunan }}</td>
                    <td style="border-bottom: thin;">{{ $rb->jumlah_lantai }}</td>
                    <td style="border-bottom: thin;">{{ $rb->tahun_dibangun }}</td>
                    <td style="border-bottom: thin;">{{ $rb->tahun_renovasi }}</td>
                    <td style="border-bottom: thin;">{{ $rb->daya_listrik }}</td>
                    <td style="border-bottom: thin;">{{ $rb->jumlah_ac }}</td>
                    <td style="border-bottom: thin;">{{ $rb->refKondisi->nama }}</td>
                    <td style="border-bottom: thin;">{{ $rb->refKonstruksi->nama }}</td>
                    <td style="border-bottom: thin;">{{ $rb->refDinding->nama }}</td>
                    <td style="border-bottom: thin;">{{ $rb->refLangit->nama }}</td>
                    <td style="border-bottom: thin;">{{ $rb->refLantai->nama }}</td>

                    @if($bIndex === 0)
                        {{-- Data Subjek Pajak --}}
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->nik ? "'".$r->subjekPajak->nik : '' }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->npwp ? "'".$r->subjekPajak->npwp : '' }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->nama }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->pekerjaan?->nama }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->jalan }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->blok_kav_no }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->rw }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->rt }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->kelurahan }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->kecamatan }}</td>
                        <td style="border-bottom: thin;">{{ $r->subjekPajak->kota }}</td>
                    @else
                        <td style="border-bottom: thin; text-align: centar" colspan="11">BANGUNAN LEBIH DARI 1</td>
                    @endif
                </tr>
            @endforeach
        @else
            {{-- Jika tidak ada bangunan --}}
            <tr>
                <td style="border: thin;">{{ ++$i }}</td>
                <td style="border-bottom: thin;">{{ $r->user->name }}</td>
                <td style="border-bottom: thin;">{{ $r->kd_propinsi }}</td>
                <td style="border-bottom: thin;">{{ $r->kd_dati2 }}</td>
                <td style="border-bottom: thin;">{{ $r->kd_kecamatan }}</td>
                <td style="border-bottom: thin;">{{ $r->kd_kelurahan }}</td>
                <td style="border-bottom: thin;">{{ $r->kd_blok }}</td>
                <td style="border-bottom: thin;">{{ $r->no_urut }}</td>
                <td style="border-bottom: thin;">{{ $r->kd_jns_op }}</td>
                <td style="border-bottom: thin;">{{ $r->blok_kav_no }}</td>
                <td style="border-bottom: thin;">{{ $r->jalan }}</td>
                <td style="border-bottom: thin;">{{ $r->rw }}</td>
                <td style="border-bottom: thin;">{{ $r->rt }}</td>
                <td style="border-bottom: thin;">{{ $r->keterangan }}</td>
                <td style="border-bottom: thin;">{{ $r->tanah->refJenisTanah->nama }}</td>
                <td style="border-bottom: thin;">{{ $r->tanah->no_sertipikat ? "'".$r->tanah->no_sertipikat : '' }}</td>
                <td style="border-bottom: thin;">{{ $r->tanah->luas_tanah }}</td>
                <td style="border-bottom: thin;" colspan="11">TIDAK ADA BANGUNAN</td> {{-- Kolom bangunan kosong --}}
                <td style="border-bottom: thin;">{{ $r->subjekPajak->nik ? "'".$r->subjekPajak->nik : '' }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->npwp ? "'".$r->subjekPajak->npwp : '' }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->nama }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->pekerjaan?->nama }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->jalan }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->blok_kav_no }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->rw }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->rt }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->kelurahan }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->kecamatan }}</td>
                <td style="border-bottom: thin;">{{ $r->subjekPajak->kota }}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
