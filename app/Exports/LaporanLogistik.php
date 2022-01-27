<?php

namespace App\Exports;

use App\Models\DetailLogistik;
use App\Models\DetailLogistikPart;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class LaporanLogistik implements FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */



    public function jenis_laporan()
    {
        return $this->jenis_laporan;
    }
    public function ekspedisi()
    {
        return $this->ekspedisi;
    }
    public function tgl_awal()
    {
        return $this->tgl_awal;
    }
    public function tgl_akhir()
    {
        return $this->tgl_akhir;
    }
    // public function distributor()
    // {
    //     return $this->distributor;
    // }
    // public function tgl_awal()
    // {
    //     return $this->tgl_awal;
    // }
    // public function tgl_akhir()
    // {
    //     return $this->tgl_akhir;
    // }
    public function __construct(string $jenis_laporan, string  $ekspedisi, string $tgl_awal, string $tgl_akhir)
    {
        $this->jenis_laporan = $jenis_laporan;
        $this->ekspedisi = $ekspedisi;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }


    public function columnFormats(): array
    {
        return [
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:T2')->getFont()->setBold(true);

        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('b2:c2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('d2:f2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('g2:j2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('89d0b4');
        $sheet->getStyle('k2:n2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
    }

    public function view(): View
    {
        $header = $this->jenis_laporan;
        $eks = $this->ekspedisi;
        $awal = $this->tgl_awal;
        $akhir = $this->tgl_akhir;




        if ($header == "ekspedisi") {
            if ($eks != '0') {
                $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($eks, $awal, $akhir) {
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
                })->get();
                $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($eks, $awal, $akhir) {
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
                })->get();
            } else {
                $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
                })->get();
                $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
                })->get();
            }
        } else if ($header == "nonekspedisi") {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
            })->get();
            $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
            })->get();
        } else {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
            })->get();
            $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereBetween('tgl_kirim', [$awal, $akhir])->orderBy('nosurat', 'ASC');
            })->get();
        }

        $data = $prd->merge($prt);




        return view('page.logistik.laporan.LaporanLogistikEx', ['header' => $header, 'data' => $data]);
    }
}
