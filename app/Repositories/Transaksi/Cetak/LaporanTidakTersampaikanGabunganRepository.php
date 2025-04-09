<?php

namespace App\Repositories\Transaksi\Cetak;

use App\Repositories\PdfRepository;
use App\Support\Facades\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LaporanTidakTersampaikanGabunganRepository
{
     protected $pdf;
     protected $totalSppt = 0;
     protected $totalPenyampaianKeterangan = [];
     public function __construct(PdfRepository $pdf)
     {
          $this->pdf = $pdf;
     }
     public function cetak($data, $satuanKerja, $pegawai, $jenisLapor, $judul, $output = 'I', $filename = null, $isUTF8 = false)
     {
          $filename = $filename ?: Str::slug($jenisLapor->nama) . '.pdf';
          $this->pdf->SetAuthor(config('app.name'));
          $this->pdf->SetCreator(config('app.name'));
          $this->pdf->SetTitle('LAPORAN ' . strtoupper($jenisLapor->nama));
          $this->pdf->SetAutoPageBreak(true, 0);
          $this->pdf->AliasNbPages();

          $this->awal($satuanKerja, $judul);
          $this->tengah($data, $satuanKerja, $judul);
          $this->bawah($satuanKerja, $pegawai, $judul);
          $this->pdf->Output($output, $filename, $isUTF8);
          exit;
     }

     protected function awal($satuanKerja, $judul)
     {
          $this->pdf->SetMargins(20, 20, 20);
          $this->pdf->AddPage('L', PdfRepository::PAGE_F4);

          $this->pdf->SetFont('Arial', '', 12);
          $this->pdf->Image(resource_path('images/logo.png'), 156, 50, 23, 32);
          $this->pdf->Ln(65);
          $this->pdf->Cell(0, 6, 'PEMERINTAH KOTA PEKALONGAN', 0, 1, 'C');
          $this->pdf->Cell(0, 6, 'BADAN PENDAPATAN KEUANGAN DAN ASET DAERAH', 0, 1, 'C');

          $this->pdf->SetFont('Arial', 'B', 20);
          $this->pdf->Ln(5);
          $this->pdf->Cell(0, 10, $judul, 0, 1, 'C');
          $this->pdf->Cell(0, 10, 'TAHUN ' . date('Y'), 0, 1, 'C');

          $this->pdf->Ln();
          $this->pdf->SetFont('Arial', '', 12);
          $this->pdf->Cell(115, 5, '', 0);
          $this->pdf->Cell(30, 5, 'KECAMATAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(110, 5, $satuanKerja['kecamatan'], 0, 1);
          $this->pdf->Cell(115, 5, '', 0);
          $this->pdf->Cell(30, 5, 'KELURAHAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(120, 5, $satuanKerja['kelurahan'], 0, 1);
          $this->pdf->Ln();
     }

     protected function header($wHeader, $satuanKerja, $judul)
     {
          $this->pdf->SetFont('Arial', '', 11);
          $this->pdf->Cell(array_sum($wHeader), 4, $judul, 0, 1, 'C');
          $this->pdf->Cell(array_sum($wHeader), 4, 'TAHUN ' . date('Y'), 0, 1, 'C');
          $this->pdf->Ln();
          $this->pdf->Cell(30, 5, 'KECAMATAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(150, 5, $satuanKerja['kecamatan'], 0, 0);
          $this->pdf->Cell(30, 5, 'KELURAHAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(80, 5, $satuanKerja['kelurahan'], 0, 1);
          $this->pdf->Ln(1);

          $this->pdf->SetFont('Arial', 'B', 9);
          $this->pdf->Cell(10, 9, "NO", 1, 0, 'C');
          $this->pdf->Cell(40, 9, "NOP", 1, 0, 'C');
          $this->pdf->Cell(75, 9, "NAMA WAJIB PAJAK", 1, 0, 'C');
          $this->pdf->Cell(75, 9, "ALAMAT OBJEK PAJAK", 1, 0, 'C');
          $this->pdf->Cell(35, 9, "PAJAK TERHUTANG", 1, 0, 'C');
          $this->pdf->Cell(65, 9, "KETERANGAN", 1, 1, 'C');
     }
     protected function tengah($data, $satuanKerja, $judul)
     {
          $wHeader = array(10, 8, 8, 19, 5, 75, 75, 35, 65);
          $this->pdf->SetAutoPageBreak(true, 10);
          $this->pdf->SetMargins(15, 7, 15);
          $this->pdf->AddPage('L', PdfRepository::PAGE_F4);

          $this->pdf->SetHeader(function () use ($wHeader, $satuanKerja, $judul) {
               $this->header($wHeader, $satuanKerja, $judul);
          });
          $this->pdf->SetFooter(function ($pdf) {
               if ($pdf->PageNo() > 1) {
                    if ($pdf->GetPageHeight() - $pdf->GetY() > 10) {
                         $pdf->SetXY(0, -10);
                    }

                    $pdf->Cell(15, 6, '', 0, 0, 'R');
                    $pdf->Cell(300, 6, 'Halaman ' . $pdf->PageNo(), 0, 1, 'R');
               }
          });
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetAlign('C');
          $this->pdf->TableSetHeight(5);
          $this->pdf->TableSetFontsName('Arial');
          $this->pdf->TableSetFontsSize(10);
          $this->pdf->TableSetFontsStyle('');
          $this->pdf->TableSetFillColor('255,255,255');
          $this->pdf->TableSetTextColor('0,0,0');
          $this->pdf->TableSetDrawColor('0,0,0');
          $this->pdf->TableSetLineWidth('0.2');
          $this->pdf->TableSetBorder('TBLR');
          $this->pdf->TableSetValign('M');

          $this->pdf->TableSetPadding(0);
          $this->header($wHeader, $satuanKerja, $judul);

          $dataTampil = [];
          $no = 1;

          foreach ($data as $value) {
               $this->totalPenyampaianKeterangan[$value->keterangan] = ($this->totalPenyampaianKeterangan[$value->keterangan] ?? 0) + 1;
               $dataTampil = [
                    $no++,
                    $value->kd_kelurahan,
                    $value->kd_blok,
                    $value->no_urut,
                    $value->kd_jns_op,
                    $value->nama_wp,
                    $value->alamat_objek,
                    Helpers::ribuan($value->nominal),
                    $value->keterangan,
               ];

               $this->pdf->TableSetWidth($wHeader);
               $this->pdf->TableSetBorder('TBLR');
               $this->pdf->TableSetAlign(array('C', 'C', 'C', 'C', 'C', 'L', 'L', 'R', 'L'));
               $this->pdf->TableSetHeight(8);
               $this->pdf->TableRow($dataTampil);
          }
          $this->totalSppt = $no - 1;
          $this->pdf->SetHeader(function () {});
     }
     protected function bawah($satuanKerja, $pegawai, $judul)
     {
          $this->pdf->SetAutoPageBreak(true, 10);
          $this->pdf->SetMargins(15, 7, 15);
          $this->pdf->AddPage('L', PdfRepository::PAGE_F4);
          $this->pdf->SetFont('Arial', '', 12);
          $this->pdf->Cell(0, 6, 'PEMERINTAH KOTA PEKALONGAN', 0, 1, 'C');
          $this->pdf->Cell(0, 6, 'BADAN PENDAPATAN KEUANGAN DAN ASET DAERAH', 0, 1, 'C');
          $this->pdf->Cell(0, 6, $judul, 0, 1, 'C');
          $this->pdf->Cell(0, 6, 'TAHUN ' . date('Y'), 0, 1, 'C');
          $this->pdf->Ln(2);
          $this->pdf->Cell(85, 5, '', 0);
          $this->pdf->Cell(60, 5, 'KECAMATAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(110, 5, $satuanKerja['kecamatan'], 0, 1);
          $this->pdf->Cell(85, 5, '', 0);
          $this->pdf->Cell(60, 5, 'KELURAHAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(120, 5, $satuanKerja['kelurahan'], 0, 1);
          $this->pdf->Cell(85, 5, '', 0);
          $this->pdf->Cell(60, 5, 'DAFTAR INI TERDIRI ATAS', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(120, 5, $this->pdf->PageNo() . ' HALAMAN', 0, 1);
          $this->pdf->Cell(85, 5, '', 0);
          $this->pdf->Cell(60, 5, 'JUMLAH SPPT SEBANYAK', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(120, 5, $this->totalSppt . ' LEMBAR', 0, 1);
          $this->pdf->Ln(2);
          $this->penyampaianKeterangan();
          $this->ttdKepala($satuanKerja, $pegawai['kepala']);
          $this->pdf->SetFooter(function () {});
     }
     protected function penyampaianKeterangan()
     {
          $this->pdf->SetFont('Arial', 'B', 12);
          $this->pdf->Cell(65, 7, '', 0, 0, 'C');
          $this->pdf->Cell(10, 7, 'NO', 1, 0, 'C');
          $this->pdf->Cell(125, 7, 'KETERANGAN', 1, 0, 'C');
          $this->pdf->Cell(35, 7, 'JUMLAH SPPT', 1, 1, 'C');
          $no = 1;
          $this->pdf->SetFont('Arial', '', 12);
          foreach (collect($this->totalPenyampaianKeterangan)->sortKeysDesc() as $key => $value) {
               $this->pdf->Cell(65, 7, '', 0, 0, 'C');
               $this->pdf->Cell(10, 7, $no++, 1, 0, 'C');
               $this->pdf->Cell(125, 7, $key, 1, 0);
               $this->pdf->Cell(35, 7, $value, 1, 1, 'C');
          }
     }
     protected function ttdKepala($satuanKerja, $kepala)
     {
          $wHeader = array(150, 150);
          $rHeader = array(
               'LURAH ' . $satuanKerja['kelurahan'],
               'CAMAT ' . $satuanKerja['kecamatan'],
          );
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableSetHeight(5);
          $this->pdf->TableSetFontsName('Arial');
          $this->pdf->TableSetFontsSize(10);
          $this->pdf->TableSetFontsStyle('');
          $this->pdf->TableSetFillColor('255,255,255');
          $this->pdf->TableSetTextColor('0,0,0');
          $this->pdf->TableSetDrawColor('0,0,0');
          $this->pdf->TableSetLineWidth('0.2');
          $this->pdf->Ln(5);

          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetBorder('');
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableRow(['', "Pekalongan, " . Carbon::now()->locale('id')->translatedFormat('d F Y')]);
          $this->pdf->TableRow($rHeader);
          $this->pdf->Ln(20);

          $this->pdf->TableSetFontsStyle('B');
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetBorder('');
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableRow($kepala['nama']);

          $this->pdf->TableSetFontsStyle('');
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetBorder('');
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableRow($kepala['nip']);
     }
}
