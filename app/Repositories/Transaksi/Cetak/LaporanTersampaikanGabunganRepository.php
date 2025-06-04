<?php

namespace App\Repositories\Transaksi\Cetak;

use App\Repositories\PdfRepository;
use App\Support\Facades\Helpers;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LaporanTersampaikanGabunganRepository
{
     protected $pdf;
     public function __construct(PdfRepository $pdf)
     {
          $this->pdf = $pdf;
     }
     public function cetak($data, $satuanKerja, $pegawai, $jenisLapor, $output = 'I', $filename = null, $isUTF8 = false)
     {
          $filename = $filename ?: Str::slug($jenisLapor->nama) . '.pdf';
          $this->pdf->SetAuthor(config('app.name'));
          $this->pdf->SetCreator(config('app.name'));
          $this->pdf->SetTitle('LAPORAN GABUNGAN ' . strtoupper($jenisLapor->nama));
          $this->pdf->SetAutoPageBreak(true, 0);
          $this->pdf->AliasNbPages();
          $wHeader = array(8, 37, 15, 29, 15, 29, 15, 29, 15, 29, 15, 29, 15, 20);
          $this->main($wHeader);
          $this->header($wHeader, $satuanKerja, $jenisLapor);
          $this->data($wHeader, $data);
          $this->ttdPetugas($satuanKerja, $pegawai['petugas']);
          $this->ttdKepala($satuanKerja, $pegawai['kepala']);
          $this->pdf->Output($output, $filename, $isUTF8);
          exit;
     }

     function main($wHeader)
     {
          $this->pdf->SetAutoPageBreak(true, 5);
          $this->pdf->SetMargins(15, 7, 15);
          $this->pdf->AddPage('L', PdfRepository::PAGE_F4);
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetHeight(5);
          $this->pdf->TableSetFontsName('TIMES');
          $this->pdf->TableSetFontsSize(10);
          $this->pdf->TableSetFontsStyle('');
          $this->pdf->TableSetFillColor('255,255,255');
          $this->pdf->TableSetTextColor('0,0,0');
          $this->pdf->TableSetDrawColor('0,0,0');
          $this->pdf->TableSetLineWidth('0.2');
     }

     protected function header($wHeader, $satuanKerja, $jenisLapor)
     {
          $this->pdf->SetFont('TIMES', '', 14);
          $this->pdf->Cell(array_sum($wHeader), 4, 'LAPORAN HASIL PENYAMPAIAN SPPT PBB TAHUN ' . date('Y') . ' KE WAJIB PAJAK', 0, 1, 'C');
          $this->pdf->SetFont('TIMES', '', 12);
          $this->pdf->Cell(array_sum($wHeader), 4, strtoupper($jenisLapor->nama), 0, 1, 'C');
          $this->pdf->SetFont('TIMES', '', 10);
          $this->pdf->Cell(array_sum($wHeader), 4, Carbon::parse($jenisLapor->tanggal_awal)->translatedFormat('d-m-Y') . ' S/D ' . Carbon::parse($jenisLapor->tanggal_akhir)->translatedFormat('d-m-Y'), 0, 1, 'C');
          $this->pdf->Ln(4);
          $this->pdf->SetFont('TIMES', '', 11);
          $this->pdf->Cell(115, 5, '', 0);
          $this->pdf->Cell(30, 5, 'KECAMATAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(110, 5, $satuanKerja['kecamatan'], 0, 1);
          $this->pdf->Cell(115, 5, '', 0);
          $this->pdf->Cell(30, 5, 'KELURAHAN', 0);
          $this->pdf->Cell(5, 5, ':', 0);
          $this->pdf->Cell(120, 5, $satuanKerja['kelurahan'], 0, 1);
          $this->pdf->Ln();


          $this->pdf->SetFont('TIMES', 'B', 9);
          $this->pdf->Cell(8, 20, "NO", 1, 0, 'C');
          $this->pdf->Cell(37, 20, "KETETAPAN", 1, 1, 'C');

          $this->pdf->SetXY(60, 38);
          $this->pdf->Cell(44, 10, "BAKU PBB", 1, 1, 'C');
          $this->pdf->SetXY(60, 48);
          $this->pdf->Cell(15, 5, "SPPT", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "JUMLAH", 1, 1, 'C');
          $this->pdf->SetXY(60, 53);
          $this->pdf->Cell(15, 5, "Lembar", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "Rupiah", 1, 1, 'C');


          $this->pdf->SetXY(104, 38);
          $this->pdf->Cell(132, 5, 'REALISASI PENYAMPAIAN SPPT PBB TAHUN ' . date('Y'), 1, 1, 'C');
          $this->pdf->SetXY(104, 43);
          $this->pdf->Cell(44, 5, 'S/D BULAN LALU', 1, 1, 'C');
          $this->pdf->SetXY(104, 48);
          $this->pdf->Cell(15, 5, "SPPT", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "JUMLAH", 1, 1, 'C');
          $this->pdf->SetXY(104, 53);
          $this->pdf->Cell(15, 5, "Lembar", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "Rupiah", 1, 1, 'C');

          $this->pdf->SetXY(148, 43);
          $this->pdf->Cell(44, 5, 'BULAN INI', 1, 1, 'C');
          $this->pdf->SetXY(148, 48);
          $this->pdf->Cell(15, 5, "SPPT", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "JUMLAH", 1, 1, 'C');
          $this->pdf->SetXY(148, 53);
          $this->pdf->Cell(15, 5, "Lembar", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "Rupiah", 1, 1, 'C');

          $this->pdf->SetXY(192, 43);
          $this->pdf->Cell(44, 5, 'S/D BULAN INI', 1, 1, 'C');
          $this->pdf->SetXY(192, 48);
          $this->pdf->Cell(15, 5, "SPPT", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "JUMLAH", 1, 1, 'C');
          $this->pdf->SetXY(192, 53);
          $this->pdf->Cell(15, 5, "Lembar", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "Rupiah", 1, 1, 'C');


          $this->pdf->SetXY(236, 38);
          $this->pdf->Cell(44, 10, "JUMLAH SISA", 1, 1, 'C');
          $this->pdf->SetXY(236, 48);
          $this->pdf->Cell(15, 5, "SPPT", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "JUMLAH", 1, 1, 'C');
          $this->pdf->SetXY(236, 53);
          $this->pdf->Cell(15, 5, "Lembar", 1, 0, 'C');
          $this->pdf->Cell(29, 5, "Rupiah", 1, 1, 'C');

          $this->pdf->SetXY(280, 38);
          $this->pdf->Cell(35, 10, "PROSENTASE", 1, 1, 'C');
          $this->pdf->SetXY(280, 48);
          $this->pdf->Cell(15, 5, "SPPT", 1, 0, 'C');
          $this->pdf->Cell(20, 5, "JUMLAH", 1, 1, 'C');
          $this->pdf->SetXY(280, 53);
          $this->pdf->Cell(15, 5, "%", 1, 0, 'C');
          $this->pdf->Cell(20, 5, "%", 1, 1, 'C');
     }
     protected function data($wHeader, $data)
     {
          $dataTampil = [];
          $no = 1;

          // Inisialisasi array total untuk kolom 2 - 13
          $total = array_fill(2, 12, 0.0); // Pastikan array total menggunakan float

          foreach ($data as $key => $value) {
               $dataTampil = [
                    $no++ . ".",
                    $key,
                    Helpers::ribuan($value['baku']['sppt']),
                    Helpers::ribuan($value['baku']['jumlah']),
                    Helpers::ribuan($value['lalu']['sppt']),
                    Helpers::ribuan($value['lalu']['jumlah']),
                    Helpers::ribuan($value['ini']['sppt']),
                    Helpers::ribuan($value['ini']['jumlah']),
                    Helpers::ribuan($value['lalu']['sppt'] + $value['ini']['sppt']),
                    Helpers::ribuan($value['lalu']['jumlah'] + $value['ini']['jumlah']),
                    Helpers::ribuan($value['baku']['sppt'] - ($value['lalu']['sppt'] + $value['ini']['sppt'])),
                    Helpers::ribuan($value['baku']['jumlah'] - ($value['lalu']['jumlah'] + $value['ini']['jumlah'])),
                    round((1 - (($value['baku']['sppt'] - ($value['lalu']['sppt'] + $value['ini']['sppt'])) / max($value['baku']['sppt'], 1))) * 100, 2),
                    round((1 - (($value['baku']['jumlah'] - ($value['lalu']['jumlah'] + $value['ini']['jumlah'])) / max($value['baku']['jumlah'], 1))) * 100, 2),
               ];

               // Menambahkan nilai ke total hanya dari kolom 2 - 13
               for ($i = 2; $i <= 13; $i++) {
                    // Ambil nilai asli tanpa format ribuan
                    $numericValue = str_replace('.', '', $dataTampil[$i]);

                    // Pastikan nilai adalah angka, jika kosong atau tidak valid set ke 0
                    $total[$i] += is_numeric($numericValue) ? (float)$numericValue : 0;
               }

               $this->pdf->TableSetWidth($wHeader);
               $this->pdf->TableSetBorder('TLR');
               $this->pdf->TableSetAlign(array('C', 'L', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'C', 'C'));
               $this->pdf->TableSetHeight(6);
               $this->pdf->TableRow($dataTampil);
          }

          // Menampilkan total setelah foreach selesai
          $totalTampil = ["", "JUMLAH"];

          // Konversi total ke format ribuan untuk kolom 2 - 11, dan tetap angka biasa untuk kolom 12 & 13
          for ($i = 2; $i <= 11; $i++) {
               $totalTampil[$i] = Helpers::ribuan($total[$i]);
          }

          // Pastikan kolom 12 & 13 tetap angka biasa
          $totalTampil[12] = round(($total[12] / 100), 2);
          $totalTampil[13] = round(($total[13] / 100), 2);

          $this->pdf->TableSetAlign(array('C', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'R', 'C', 'C'));
          $this->pdf->TableSetFontsStyle('B');
          $this->pdf->TableSetBorder('TLBR');
          $this->pdf->TableSetHeight(7);
          $this->pdf->TableRow($totalTampil);
     }

     protected function ttdPetugas($satuanKerja, $petugas)
     {
          $wHeader = array(150, 150);
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
          $this->pdf->TableSetFontsSize(10);
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
          $wHeader = array(150, 150);
          $rHeader = array(
               'LURAH ' . $satuanKerja['kelurahan'],
               'CAMAT ' . $satuanKerja['kecamatan'],
          );
          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetAlign(array('C', 'C'));
          $this->pdf->TableSetHeight(5);
          $this->pdf->TableSetFontsName('TIMES');
          $this->pdf->TableSetFontsSize(10);
          $this->pdf->TableSetFontsStyle('');
          $this->pdf->TableSetFillColor('255,255,255');
          $this->pdf->TableSetTextColor('0,0,0');
          $this->pdf->TableSetDrawColor('0,0,0');
          $this->pdf->TableSetLineWidth('0.2');
          $this->pdf->Ln(15);

          $this->pdf->TableSetWidth($wHeader);
          $this->pdf->TableSetBorder('');
          $this->pdf->TableSetAlign(array('C', 'C'));
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
