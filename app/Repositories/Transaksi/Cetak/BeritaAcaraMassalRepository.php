<?php

namespace App\Repositories\Transaksi\Cetak;

use App\Repositories\PdfRepository;
use App\Support\Facades\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BeritaAcaraMassalRepository
{
     protected $pdf;
     public function __construct(PdfRepository $pdf)
     {
          $this->pdf = $pdf;
     }
     public function cetak($dataList, $satuanKerja, $pegawai, $output = 'I', $isUTF8 = false)
     {
          Carbon::setLocale('id');

          $this->pdf->SetAuthor(config('app.name'));
          $this->pdf->SetCreator(config('app.name'));
          $this->pdf->SetTitle('BERITA ACARA MASSAL');
          $this->pdf->SetAutoPageBreak(true, 0);
          $this->pdf->AliasNbPages();

          foreach ($dataList as $data) {
               $nop = $data->kd_propinsi . '.' . $data->kd_dati2 . '.' . $data->kd_kecamatan . '.' . $data->kd_kelurahan . '.' . $data->kd_blok . '-' . $data->no_urut . '.' . $data->kd_jns_op;

               $this->main();
               $this->header();
               $this->title($pegawai['petugas'], $data);
               $this->objek($nop, $data);
               $this->ttdPetugas($satuanKerja, $pegawai['petugas']);
               $this->ttdKepala($satuanKerja, $pegawai['kepala']);
          }

          $filename = 'BA-Massal-' . now()->format('YmdHis') . '.pdf';

          // Tangkap output PDF ke buffer, supaya tidak langsung echo
          ob_start();
          $this->pdf->Output($output, $filename, $isUTF8);
          $content = ob_get_clean();

          return response($content)->header('Content-Type', 'application/pdf');
     }


     function main()
     {
          $this->pdf->SetAutoPageBreak(true, 5);
          $this->pdf->SetMargins(10, 7, 10);
          $this->pdf->AddPage('P', PdfRepository::PAGE_F4);
     }

     protected function header()
     {
          $this->pdf->SetFont('TIMES', '', 14);
          $this->pdf->Cell(30, 6, '', 0, 0, 'C');
          $this->pdf->Image(resource_path('images/logo.png'), $this->pdf->GetX() - 25, $this->pdf->GetY() - 1, 18.5, 25);
          $this->pdf->Cell(165, 6, 'PEMERINTAH KOTA PEKALONGAN', 0, 1, 'C');
          $this->pdf->SetFont('TIMES', 'B', 16);
          $this->pdf->Cell(30, 6, '', 0, 0, 'C');
          $this->pdf->Cell(165, 6, 'BADAN PENDATAPAN KEUANGAN DAN ASET DAERAH', 0, 1, 'C');
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(30, 5, '', 0, 0, 'C');
          $this->pdf->Cell(165, 5, 'Jl. Sriwijaya no.44 Kota Pekalongan, 51111', 0, 1, 'C');
          $this->pdf->Cell(30, 5, '', 0, 0, 'C');
          $this->pdf->Cell(165, 5, 'Telpon : (0285) 429451, bkd.kotapkl@gmail.com, https://bkpad.pekalongankota.go.id', 0, 1, 'C');
          $this->pdf->Cell(195, 2, '', 0, 1, 'C');
          $this->pdf->Cell(195, 2, '', 0, 1, 'C');
          $this->pdf->SetFillColor(0, 0, 0);
          $this->pdf->Cell(195, 1, '', 0, 1, 'C', true);
          $this->pdf->Ln();
     }
     protected function title($pegawai, $data)
     {
          $this->pdf->SetFont('TIMES', 'B', 11);
          $this->pdf->Cell(195, 5, '', 0, 1, 'C');
          $this->pdf->Cell(195, 5, 'BERITA ACARA ' . ($data->tahun == date('Y') ? 'PENGEMBALIAN' : 'PEMUTAKHIRAN') . ' SPPT PBB-P2', 0, 1, 'C');
          $this->pdf->Cell(195, 2, '', 0, 1, 'L');
          $this->pdf->Ln(5);
          $this->pdf->SetFont('TIMES', '', 11);
          $hari = Carbon::now()->translatedFormat('l');
          $tgl = Carbon::now()->translatedFormat('d');
          $bulan = Carbon::now()->translatedFormat('F');
          $tahun = Carbon::now()->translatedFormat('Y');
          $tgl_format = Carbon::now()->format('d/m/Y');
          $this->pdf->Cell(195, 4, "Pada hari $hari tanggal $tgl bulan $bulan Tahun $tahun ($tgl_format) kami yang bertanda tangan di bawah ini :", 0, 1, 'L');

          $this->pdf->SetFont('TIMES', '', 11);
          for ($i = 0; $i <= 1; $i++) {
               $this->pdf->Ln(5);

               $this->pdf->SetFont('TIMES', 'B', 11);
               $this->pdf->Cell(55, 6, 'NIP', 0, 0, 'L');
               $this->pdf->SetFont('TIMES', '', 11);
               $this->pdf->Cell(5, 6, ':', 0, 0, 'C');
               $this->pdf->Cell(135, 6, str_replace('NIP : ', '', $pegawai['nip'][$i]), 0, 1, 'L');

               $this->pdf->SetFont('TIMES', 'B', 11);
               $this->pdf->Cell(55, 6, 'NAMA', 0, 0, 'L');
               $this->pdf->SetFont('TIMES', '', 11);
               $this->pdf->Cell(5, 6, ':', 0, 0, 'C');
               $this->pdf->Cell(135, 6, $pegawai['nama'][$i], 0, 1, 'L');
          }
          $this->pdf->Ln(3);
     }

     protected function objek($nop, $data)
     {
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(195, 6, "Dengan ini menyatakan bahwa objek pajak PBB-P2 :", 0, 1, 'L');
          $this->pdf->SetFont('TIMES', 'B', 11);
          $this->pdf->Cell(55, 6, 'NOP', 0, 0, 'L');
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(5, 6, ':', 0, 0, 'C');
          $this->pdf->Cell(135, 6, $nop, 0, 1, 'L');

          $this->pdf->SetFont('TIMES', 'B', 11);
          $this->pdf->Cell(55, 6, 'NAMA WAJIB PAJAK', 0, 0, 'L');
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(5, 6, ':', 0, 0, 'C');
          $this->pdf->Cell(135, 6, Str::upper($data->nama_wp), 0, 1, 'L');

          $this->pdf->SetFont('TIMES', 'B', 11);
          $this->pdf->Cell(55, 6, 'ALAMAT OBJEK PAJAK', 0, 0, 'L');
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(5, 6, ':', 0, 0, 'C');
          $this->pdf->Cell(135, 6, Str::upper($data->datObjekPajak->alamat), 0, 1, 'L');

          $this->pdf->SetFont('TIMES', 'B', 11);
          $this->pdf->Cell(55, 6, 'TAHUN PAJAK', 0, 0, 'L');
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(5, 6, ':', 0, 0, 'C');
          $this->pdf->Cell(135, 6, $data->tahun, 0, 1, 'L');

          $this->pdf->SetFont('TIMES', 'B', 11);
          $this->pdf->Cell(55, 6, 'NOMINAL PAJAK', 0, 0, 'L');
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(5, 6, ':', 0, 0, 'C');
          $this->pdf->Cell(135, 6, 'Rp ' . Helpers::ribuan($data->nominal), 0, 1, 'L');

          $this->pdf->SetFont('TIMES', '', 11);

          $this->pdf->Ln(3);
          $this->pdf->TableSetWidth([195]);
          $this->pdf->TableSetAlign(['L']);
          $this->pdf->TableSetHeight(6);
          $this->pdf->TableRow(['Tidak dapat disampaikan dikarenakan ' . $data->keterangan . '. Adapun hasil dari verifikasi dilapangan adalah sebagai berikut : ................................................................................................................................................................']);

          $this->pdf->Cell(195, 10, "Demikian berita acara ini kami sampaikan untuk dipergunakan sebagaimana mestinya.", 0, 1, 'L');
          $this->pdf->Ln();
     }

     protected function ttdPetugas($satuanKerja, $petugas)
     {
          $wHeader = array(97.5, 97.5);
          $keterangan = array(
               'PETUGAS KELURAHAN',
               'PETUGAS KELURAHAN',
          );
          $kelurahan = array(
               $satuanKerja['kelurahan'],
               $satuanKerja['kelurahan'],
          );
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableSetHeight(5);
          $this->pdf->TableSetFontsName('TIMES');
          $this->pdf->TableSetFontsSize(11);
          $this->pdf->TableSetFontsStyle('');
          $this->pdf->Ln(10);

          $this->pdf->TableSetBorder('');
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableRow(['', "Pekalongan, " . Carbon::now()->locale('id')->translatedFormat('d F Y')]);
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableRow($keterangan);
          $this->pdf->TableRow($kelurahan);
          $this->pdf->Ln(20);

          $this->pdf->TableSetFontsStyle('B');
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetBorder('');
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableRow($petugas['nama']);

          $this->pdf->TableSetFontsStyle('');
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetBorder('');
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableRow($petugas['nip']);
     }
     protected function ttdKepala($satuanKerja, $kepala)
     {
          $this->pdf->Ln(5);
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(195, 6, 'Mengetahui', 0, 1, 'C');
          $this->pdf->Cell(97.5, 5, $kepala['jabatan_status'][0] !== 'DEFINITIF' ? $kepala['jabatan_status'][0] . ' LURAH ' . $satuanKerja['kelurahan'] : 'LURAH ' . $satuanKerja['kelurahan'], 0, 0, 'C');
          $this->pdf->Cell(97.5, 5, 'RT', 0, 1, 'C');

          $this->pdf->Ln(20);

          $this->pdf->SetFont('TIMES', 'B', 11);
          $this->pdf->Cell(97.5, 5, $kepala['nama'][0], 0, 0, 'C');
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(97.5, 5, '( ................................. )', 0, 1, 'C');
          $this->pdf->Cell(97.5, 5, $kepala['nip'][0], 0, 0, 'C');
          $this->pdf->Cell(97.5, 8, 'RW .......... / RT ..........', 0, 1, 'C');
     }
}
