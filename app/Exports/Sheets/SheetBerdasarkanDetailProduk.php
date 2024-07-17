<?php

namespace App\Exports\Sheets;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\DetailPesananDsb;
use App\Models\DetailPesananPart;
use App\Models\DetailPesananProduk;
use App\Models\DetailRencanaPenjualan;
use App\Models\Ekatalog;
use App\Models\Logistik;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriDsb;
use App\Models\RencanaPenjualan;
use App\Models\RiwayatBatalPoSeri;
use App\Models\RiwayatReturPoSeri;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\TblSiswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class SheetBerdasarkanDetailProduk implements WithTitle, FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function jenis_penjualan()
    {
        return $this->jenis_penjualan;
    }
    public function distributor()
    {
        return $this->distributor;
    }
    public function tgl_awal()
    {
        return $this->tgl_awal;
    }
    public function tgl_akhir()
    {
        return $this->tgl_akhir;
    }
    public function seri()
    {
        return $this->seri;
    }
    public function tampilan()
    {
        return $this->tampilan;
    }

    public function __construct(string $jenis_penjualan, string $distributor, string $tgl_awal,  string $tgl_akhir, string $seri, string $tampilan)
    {
        $this->jenis_penjualan = $jenis_penjualan;
        $this->distributor = $distributor;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->seri = $seri;
        $this->tampilan = $tampilan;
    }

    public function columnFormats(): array
    {
        return [
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'U' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'W' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'Y' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    public function styles(Worksheet $sheet)
    {

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:AA2')->getFont()->setBold(true);

        $sheet->getStyle('b2:f2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('f2:g2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');

        $sheet->getStyle('g2:k2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('l2:m2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00b359');

        $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('K')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('N')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('O')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('O')->getAlignment()->setWrapText(true);

        if ($this->seri != "kosong") {
            $sheet->getColumnDimension('P')->setAutoSize(false)->setWidth(70);
            $sheet->getStyle('P')->getAlignment()->setWrapText(true);
            $sheet->getStyle('n2:t2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('89d0b4');
            $sheet->getStyle('u2:AA2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('f99c83');
            $sheet->getStyle('U:AA')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
            $sheet->getStyle('n2:s2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('89d0b4');
            $sheet->getStyle('t2:AA2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('f99c83');
            $sheet->getStyle('T:AA')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getStyle('A:W')->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('A:D')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L:M')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function view(): View
    {
        $tampilan = $this->tampilan;
        $seri = $this->seri;
        $dsb = $this->distributor;
        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;
        $x = explode(',', $this->jenis_penjualan);

        $tanggal_awal = $tanggal_awal . ' 00:00:01';
        $tanggal_akhir = $tanggal_akhir . ' 23:59:00';

        $data = "";
        if ($dsb == 'semua') {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');

            } else if ($x == ['ekatalog', 'spa']) {
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spb = 0')
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');

            } else if ($x == ['ekatalog', 'spb']) {
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spa = 0')
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');

            } else if ($x == ['spa', 'spb']) {
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('ekat = 0')
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');

            } else if ($this->jenis_penjualan == 'ekatalog') {
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('ekat > 0')
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');

            } else if ($this->jenis_penjualan == 'spa') {
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spa > 0')
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');
            } else if ($this->jenis_penjualan == 'spb') {
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spb > 0')
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');

            }
        } else {

            if ($x == ['ekatalog', 'spa', 'spb']) {

                $ekt_id = Ekatalog::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();
                $spa_id = Spa::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();
                $spb_id = Spb::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();

                $collection1 = collect($ekt_id);
                $collection2 = collect($spa_id);
                $collection3 = collect($spb_id);

                $mergedCollection = $collection1->merge($collection2)->merge($collection3);

                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->whereIN('id', $mergedCollection)
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                // ->whereRaw('TRIM(no_po) <> ""')
                // ->wherenotnull('no_po');

            } else if ($x == ['ekatalog', 'spa']) {
                $ekt_id = Ekatalog::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();
                $spa_id = Spa::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();


                $collection1 = collect($ekt_id);
                $collection2 = collect($spa_id);


                $mergedCollection = $collection1->merge($collection2);
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spb = 0')
                    ->whereIN('id', $mergedCollection)
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
            } else if ($x == ['ekatalog', 'spb']) {
                $ekt_id = Ekatalog::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();
                $spb_id = Spb::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();

                $collection1 = collect($ekt_id);
                $collection2 = collect($spb_id);


                $mergedCollection = $collection1->merge($collection2);

                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spa = 0')
                    ->whereIN('id', $mergedCollection)
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
            } else if ($x == ['spa', 'spb']) {
                $spa_id = Spa::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();
                $spb_id = Spb::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();

                $collection1 = collect($spa_id);
                $collection2 = collect($spb_id);


                $mergedCollection = $collection1->merge($collection2);
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('ekat = 0')
                    ->whereIN('id', $mergedCollection)
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
            } else if ($this->jenis_penjualan == 'ekatalog') {

                $ekt_id = Ekatalog::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();

                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('ekat > 0')
                    ->whereIn('pesanan.id', $ekt_id)
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
            } else if ($this->jenis_penjualan == 'spa') {
                $spa_id = Spa::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spa > 0')
                    ->whereIn('pesanan.id', $spa_id)
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
            } else if ($this->jenis_penjualan == 'spb') {
                $spb_id = Spb::where('customer_id', $dsb)->pluck('pesanan_id')->toArray();
                //GET PESANAN
                $data = Pesanan::addSelect([
                    'spa' => function ($q) {
                        $q->selectRaw('coalesce(count(spa.id),0)')
                            ->from('spa')
                            ->whereColumn('spa.pesanan_id', 'pesanan.id');
                    },
                    'spb' => function ($q) {
                        $q->selectRaw('coalesce(count(spb.id),0)')
                            ->from('spb')
                            ->whereColumn('spb.pesanan_id', 'pesanan.id');
                    },
                    'ekat' => function ($q) {
                        $q->selectRaw('coalesce(count(ekatalog.id),0)')
                            ->from('ekatalog')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
                    }
                ])
                    ->havingRaw('spb > 0')
                    ->whereIn('pesanan.id', $spb_id)
                    ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
            }
        }
        $pesananIds = $data->pluck('id')->toArray();
        $data_dpp = DetailPesananProduk::leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->whereIN('detail_pesanan.pesanan_id', $pesananIds);

        $dppIds = $data_dpp->pluck('detail_pesanan_produk.id')->toArray();

        $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
            ->selectRaw('"-" AS no_paket')
            ->selectRaw('"-" AS instansi')
            ->selectRaw('"-" AS alamat_instansi')
            ->selectRaw('"-" AS status')
            ->selectRaw('"-" AS satuan')
            ->selectRaw('"-" AS no_urut')
            ->selectRaw('"-" AS tgl_buat')
            ->selectRaw('"-" AS tgl_kontrak')
            ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
            ->whereIn('spb.pesanan_id', $pesananIds)->get();

        $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
            ->selectRaw('"-" AS no_paket')
            ->selectRaw('"-" AS instansi')
            ->selectRaw('"-" AS alamat_instansi')
            ->selectRaw('"-" AS status')
            ->selectRaw('"-" AS satuan')
            ->selectRaw('"-" AS no_urut')
            ->selectRaw('"-" AS tgl_buat')
            ->selectRaw('"-" AS tgl_kontrak')
            ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
            ->whereIn('spa.pesanan_id', $pesananIds)->get();

        $ekatalog = Ekatalog::select('ekatalog.pesanan_id as id', 'ekatalog.ket', DB::raw("DATE_FORMAT(ekatalog.tgl_buat, '%d-%m-%Y') as tgl_buat"), DB::raw("DATE_FORMAT(ekatalog.tgl_kontrak, '%d-%m-%Y') as tgl_kontrak"), 'ekatalog.no_urut as no_urut', 'customer.nama', 'ekatalog.no_paket', 'ekatalog.instansi', 'ekatalog.alamat as alamat_instansi', 'ekatalog.satuan', 'ekatalog.status')
            ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
            ->whereIn('ekatalog.pesanan_id', $pesananIds)->get();

        $dataInfo =   $ekatalog->merge($spa)->merge($spb);


        //GET SURAT JALAN
        $surat_jalan = Logistik::select('detail_pesanan.pesanan_id as id', 'nosurat', DB::raw("DATE_FORMAT(tgl_kirim, '%d-%m-%Y') as tgl_kirim"))
            ->leftJoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->whereIN('detail_logistik.detail_pesanan_produk_id', $dppIds)
            ->groupBy('logistik.id')
            ->get();

        //GET SURAT JALAN PART
        $surat_jalan_part = Logistik::select('detail_pesanan_part.pesanan_id as id', 'nosurat', DB::raw("DATE_FORMAT(tgl_kirim, '%d-%m-%Y') as tgl_kirim"))
            ->leftJoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
            ->whereIN('detail_logistik_part.detail_pesanan_part_id', $dppIds)
            ->groupBy('logistik.id')
            ->get();


        //GET NOSERI
        $noseri = NoseriBarangJadi::select('detail_pesanan.id as id', 'detail_pesanan.penjualan_produk_id', 'noseri')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();

        $noseriDsb = NoseriDsb::select('detail_pesanan_dsb.id as id', 'detail_pesanan_dsb.penjualan_produk_id', 'noseri')
            ->leftJoin('detail_pesanan_dsb', 'detail_pesanan_dsb.id', '=', 'noseri_dsb.detail_pesanan_dsb')
            ->whereIN('detail_pesanan_dsb.pesanan_id', $data->pluck('id')->toArray())->get();

        $noseriBatal = RiwayatBatalPoSeri::select('detail_pesanan.id as id', 'detail_pesanan.penjualan_produk_id', 'noseri_barang_jadi.noseri')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'riwayat_batal_po_seri.noseri_id')
            ->leftJoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', '=', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
            ->leftJoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', '=', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'riwayat_batal_po_paket.detail_pesanan_id')
            ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();

        $noseriRetur = RiwayatReturPoSeri::select('detail_pesanan.id as id', 'detail_pesanan.penjualan_produk_id', 'noseri_barang_jadi.noseri')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'riwayat_retur_po_seri.noseri_id')
            ->leftJoin('riwayat_retur_po_prd', 'riwayat_retur_po_prd.id', '=', 'riwayat_retur_po_seri.detail_riwayat_retur_prd_id')
            ->leftJoin('riwayat_retur_po_paket', 'riwayat_retur_po_paket.id', '=', 'riwayat_retur_po_prd.detail_riwayat_retur_paket_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'riwayat_retur_po_paket.detail_pesanan_id')
            ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();


        //GET SPAREPART
        $detail_pesanan_part = DetailPesananPart::select(
            'detail_pesanan_part.id',
            'detail_pesanan_part.pesanan_id',
            'detail_pesanan_part.m_sparepart_id',
            'm_sparepart.nama',
            'm_sparepart.nama as item',
            'detail_pesanan_part.harga',
            'detail_pesanan_part.jumlah',
            'detail_pesanan_part.ongkir',
            DB::raw('(SELECT COALESCE(SUM(riwayat_batal_po_part.jumlah), 0)
            FROM riwayat_batal_po_part
            WHERE riwayat_batal_po_part.detail_pesanan_part_id = detail_pesanan_part.id) AS jumlah_batal'),
            // DB::raw('(SELECT COALESCE(SUM(riwayat_batal_po_retur.jumlah), 0)
            // FROM riwayat_batal_po_retur
            // WHERE riwayat_batal_po_part.detail_pesanan_part_id = detail_pesanan_part.id) AS jumlah_retur'),
        )
            // ->selectRaw('"0" AS jumlah_batal')
            ->selectRaw('"0" AS jumlah_retur')
            ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
            ->whereIN('detail_pesanan_part.pesanan_id', $data->pluck('id')->toArray())->get();


        //GET DETAIL PESANAN DSB
        $detail_pesanan_dsb = DetailPesananDsb::select(
            'detail_pesanan_dsb.id',
            'detail_pesanan_dsb.pesanan_id',
            'detail_pesanan_dsb.penjualan_produk_id',
            'penjualan_produk.nama as nama',
            'penjualan_produk.nama_alias as nama_alias',
            'detail_pesanan_dsb.harga',
            'detail_pesanan_dsb.jumlah',
            'detail_pesanan_dsb.ongkir',
            DB::raw('(SELECT GROUP_CONCAT(CONCAT(produk.nama," ", gdg_barang_jadi.nama))
    FROM detail_pesanan_produk_dsb AS dpp
    LEFT JOIN gdg_barang_jadi ON gdg_barang_jadi.id = dpp.gudang_barang_jadi_id
    LEFT JOIN produk ON gdg_barang_jadi.produk_id = produk.id
    WHERE dpp.detail_pesanan_dsb_id = detail_pesanan_dsb.id ) AS item')
        )
            ->selectRaw('"0" AS jumlah_batal')
            ->selectRaw('"0" AS jumlah_retur')
            ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan_dsb.penjualan_produk_id')
            ->whereIN('detail_pesanan_dsb.pesanan_id', $data->pluck('id')->toArray())->get();


        //GET DETAIL PESANAN
        $detail_pesanan = DetailPesanan::select(
            'detail_pesanan.id',
            'detail_pesanan.pesanan_id',
            'detail_pesanan.penjualan_produk_id',
            'penjualan_produk.nama as nama',
            'penjualan_produk.nama_alias as nama_alias',
            'detail_pesanan.harga',
            'detail_pesanan.jumlah',
            'detail_pesanan.ongkir',
            DB::raw('(SELECT GROUP_CONCAT(CONCAT(produk.nama," ", gdg_barang_jadi.nama))
    FROM detail_pesanan_produk AS dpp
    LEFT JOIN gdg_barang_jadi ON gdg_barang_jadi.id = dpp.gudang_barang_jadi_id
    LEFT JOIN produk ON gdg_barang_jadi.produk_id = produk.id
    WHERE dpp.detail_pesanan_id = detail_pesanan.id ) AS item'),
            DB::raw('(SELECT COALESCE(SUM(riwayat_batal_po_paket.jumlah), 0)
    FROM riwayat_batal_po_paket
    WHERE riwayat_batal_po_paket.detail_pesanan_id = detail_pesanan.id
    ) AS jumlah_batal'),
            DB::raw('(SELECT COALESCE(SUM(riwayat_retur_po_paket.jumlah), 0)
    FROM riwayat_retur_po_paket
    WHERE riwayat_retur_po_paket.detail_pesanan_id = detail_pesanan.id
    ) AS jumlah_retur')
        )
            ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
            ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();



        //GROUP DATA
        $groupedDataSeri = collect($noseri)->groupBy('id');
        $groupedDataSeriDsb = collect($noseriDsb)->groupBy('id');
        $groupedDataSeriBatal = collect($noseriBatal)->groupBy('id');
        $groupedDataSeriRetur = collect($noseriRetur)->groupBy('id');
        $groupedDataPrd = collect($detail_pesanan)->groupBy('pesanan_id');
        $groupedDataPrdDsb = collect($detail_pesanan_dsb)->groupBy('pesanan_id');
        $groupedDataPart = collect($detail_pesanan_part)->groupBy('pesanan_id');
        $groupedDataSj = collect($surat_jalan)->groupBy('id')->toArray();
        $groupedDataSjPart = collect($surat_jalan_part)->groupBy('id')->toArray();;
        $infoByID = [];
        foreach ($dataInfo as $infoItem) {
            $infoByID[$infoItem->id] = $infoItem;
        }


        //GROUP BY REF ID
        $noseri_group = $groupedDataSeri->map(function ($items, $key) {
            $uniqueItems = $items->unique('noseri')->values()->all();
            return [
                'id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();


        $noseri_groupDsb = $groupedDataSeriDsb->map(function ($items, $key) {
            $uniqueItems = $items->unique('noseri')->values()->all();
            return [
                'id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();


        $noseri_groupBatal = $groupedDataSeriBatal->map(function ($items, $key) {
            $uniqueItems = $items->unique('noseri')->values()->all();
            return [
                'id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();

        $noseri_groupRetur = $groupedDataSeriRetur->map(function ($items, $key) {
            $uniqueItems = $items->unique('noseri')->values()->all();
            return [
                'id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();

        //GROUP BY REF ID
        $detail_pesanan_part_group = $groupedDataPart->map(function ($items, $key) {
            //    $uniqueItems = $items->unique('m_sparepart_id')->values()->all();
            return [
                'pesanan_id' => $key,
                'data' => $items,
            ];
        })->values()->all();

        //GROUP BY REF ID
        $detail_pesanan_group = $groupedDataPrd->map(function ($items, $key) {
            // $uniqueItems = $items->unique('penjualan_produk_id')->values()->all();
            return [
                'pesanan_id' => $key,
                'data' => $items,
            ];
        })->values()->all();

        //GROUP BY REF ID DSB
        $detail_pesanan_dsb_group = $groupedDataPrdDsb->map(function ($items, $key) {
            // $uniqueItems = $items->unique('penjualan_produk_id')->values()->all();
            return [
                'pesanan_id' => $key,
                'data' => $items,
            ];
        })->values()->all();



        //SET NOSERI TO INDEX
        $seriByID = [];
        foreach ($noseri_group as $seriItem) {
            $seriByID[$seriItem['id']] = $seriItem['data'];
        }

        $seriDsbByID = [];
        foreach ($noseri_groupDsb as $seriItem) {
            $seriDsbByID[$seriItem['id']] = $seriItem['data'];
        }

        $seriBatalByID = [];
        foreach ($noseri_groupBatal as $seriItem) {
            $seriBatalByID[$seriItem['id']] = $seriItem['data'];
        }

        $seriReturByID = [];
        foreach ($noseri_groupRetur as $seriItem) {
            $seriReturByID[$seriItem['id']] = $seriItem['data'];
        }



        //SET INDEX NOSERI TO DETAIL PESANAN
        foreach ($detail_pesanan_group as $key => $pesananItem) {
            foreach ($pesananItem['data'] as $keys => $p) {
                $pesananID = $p['id'];
                if (isset($seriByID[$pesananID])) {
                    $detail_pesanan_group[$key]['data'][$keys]['seri'] = $seriByID[$pesananID];
                } else {
                    $detail_pesanan_group[$key]['data'][$keys]['seri'] = [];
                }

                if (isset($seriBatalByID[$pesananID])) {
                    $detail_pesanan_group[$key]['data'][$keys]['seri_batal'] = $seriBatalByID[$pesananID];
                } else {
                    $detail_pesanan_group[$key]['data'][$keys]['seri_batal'] = [];
                }

                if (isset($seriReturByID[$pesananID])) {
                    $detail_pesanan_group[$key]['data'][$keys]['seri_retur'] = $seriReturByID[$pesananID];
                } else {
                    $detail_pesanan_group[$key]['data'][$keys]['seri_retur'] = [];
                }
            }
        }

        foreach ($detail_pesanan_dsb_group as $key => $pesananItem) {
            foreach ($pesananItem['data'] as $keys => $p) {
                $pesananID = $p['id'];
                if (isset($seriDsbByID[$pesananID])) {
                    $detail_pesanan_dsb_group[$key]['data'][$keys]['seri'] = $seriDsbByID[$pesananID];
                } else {
                    $detail_pesanan_dsb_group[$key]['data'][$keys]['seri'] = [];
                }
            }
        }

        //SET PESANAN
        foreach ($data->get() as $d) {
            $pesanan[] = array(
                'id' => $d->id,
                'so' => $d->so,
                'nama' => '-',
                'no_paket' => '-',
                'instansi' => '-',
                'alamat_instansi' => '-',
                'satuan' => '-',
                'no_urut' => '-',
                'tgl_buat' => '-',
                'tgl_kontrak' => '-',
                'status' => '-',
                'po' => $d->no_po,
                'tgl_po' => $d->tgl_po != null ? date('d-m-Y', strtotime($d->tgl_po)) : '-',
                'ket' => $d->ket,
                'log_id' => $d->log_id,
                'nosurat' => [],
                'nosurat_part' => []
            );
        }

        $produkByPesananId = [];
        $produkDsbByPesananId = [];
        $partByPesananId = [];

        foreach ($pesanan as &$pesananItem) {
            $pesananID = $pesananItem['id'];
            if (array_key_exists($pesananID, $groupedDataSj)) {
                // $pesanan[$key]['nosurat'] = $groupedDataSj[$pesananID];
                $pesananItem['nosurat'] = $groupedDataSj[$pesananID];
            }
        }

        foreach ($pesanan as  &$pesananItem) {
            $pesananID = $pesananItem['id'];
            if (array_key_exists($pesananID, $groupedDataSjPart)) {
                $pesananItem['nosurat_part'] = $groupedDataSjPart[$pesananID];
            }
        }

        foreach ($pesanan as  &$pesananItem) {
            $pesananID = $pesananItem['id'];
            if (array_key_exists($pesananID, $infoByID)) {
                $pesananItem['nama'] = $infoByID[$pesananID]->nama;
                $pesananItem['no_paket'] = $infoByID[$pesananID]->no_paket;
                $pesananItem['instansi'] = $infoByID[$pesananID]->instansi;
                $pesananItem['alamat_instansi'] = $infoByID[$pesananID]->alamat_instansi;
                $pesananItem['satuan'] =  $infoByID[$pesananID]->satuan;
                $pesananItem['no_urut'] =  $infoByID[$pesananID]->no_urut;
                $pesananItem['tgl_buat'] =  $infoByID[$pesananID]->tgl_buat;
                $pesananItem['tgl_kontrak'] =  $infoByID[$pesananID]->tgl_kontrak;
                $pesananItem['status'] =  $infoByID[$pesananID]->status;
            }
        }

        // Group $produk array items by pesanan_id
        foreach ($detail_pesanan_part_group as $item) {
            $pesanansId = $item['pesanan_id'];

            // Check if the pesanan_id exists in $produkByPesananId array
            if (!array_key_exists($pesanansId, $partByPesananId)) {
                $partByPesananId[$pesanansId] = [];
            }

            // Add the produk item to the corresponding pesanan_id
            $partByPesananId[$pesanansId][] = $item;
        }


        foreach ($pesanan as &$pesananItem) {
            $pesananId = $pesananItem['id'];

            // Check if pesanan_id exists in $produkByPesananId array
            if (array_key_exists($pesananId, $partByPesananId)) {
                $pesananItem['part'] = $partByPesananId[$pesananId][0]['data'];
            } else {
                $pesananItem['part'] = [];
            }
        }

        //----------------------------------------

        // Group $produk array items by pesanan_id
        foreach ($detail_pesanan_group as $item) {
            $pesananId = $item['pesanan_id'];

            // Check if the pesanan_id exists in $produkByPesananId array
            if (!array_key_exists($pesananId, $produkByPesananId)) {
                $produkByPesananId[$pesananId] = [];
            }

            // Add the produk item to the corresponding pesanan_id
            $produkByPesananId[$pesananId][] = $item;
        }

        // Group $produk array items by pesanan_id
        foreach ($detail_pesanan_dsb_group as $item) {
            $pesananId = $item['pesanan_id'];

            // Check if the pesanan_id exists in $produkByPesananId array
            if (!array_key_exists($pesananId, $produkDsbByPesananId)) {
                $produkDsbByPesananId[$pesananId] = [];
            }

            // Add the produk item to the corresponding pesanan_id
            $produkDsbByPesananId[$pesananId][] = $item;
        }

        // Update $pesanan array with produk items based on pesanan_id
        foreach ($pesanan as &$pesananItem) {
            $pesananId = $pesananItem['id'];

            // Check if pesanan_id exists in $produkByPesananId array
            if (array_key_exists($pesananId, $produkDsbByPesananId)) {
                $pesananItem['produk_dsb'] = $produkDsbByPesananId[$pesananId][0]['data'];
            } else {
                $pesananItem['produk_dsb'] = [];
            }
        }
        foreach ($pesanan as &$pesananItem) {
            $pesananId = $pesananItem['id'];

            // Check if pesanan_id exists in $produkByPesananId array
            if (array_key_exists($pesananId, $produkByPesananId)) {
                $pesananItem['produk'] = $produkByPesananId[$pesananId][0]['data'];
            } else {
                $pesananItem['produk'] = [];
            }
        }
        // if ($tampilan == 'merge') {
        //     if ($dsb == 'semua') {
        //         $ekatalog = Pesanan::has('Ekatalog')->wherenotnull('no_po')
        //             ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //             ->orderby('so', 'ASC')
        //             ->get();
        //         $spa = Pesanan::has('Spa')->wherenotnull('no_po')
        //             ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //             ->orderby('so', 'ASC')
        //             ->get();
        //         $spb = Pesanan::has('Spb')->wherenotnull('no_po')
        //             ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //             ->orderby('so', 'ASC')
        //             ->get();
        //         if ($x == ['ekatalog', 'spa', 'spb']) {
        //             $data = $ekatalog->merge($spa)->merge($spb)->sortBy('created_at');
        //             $header = 'Laporan Penjualan Semua';
        //         } else if ($x == ['ekatalog', 'spa']) {
        //             $data = Pesanan::has('Spb', 'Ekatalog')->wherenotnull('no_po')
        //                 ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->orderby('so', 'ASC')
        //                 ->get();
        //             $header = 'Laporan Penjualan Ekatalog dan SPA';
        //         } else if ($x == ['ekatalog', 'spb']) {
        //             $data = $ekatalog->merge($spb)->sortBy('created_at');
        //             $header = 'Laporan Penjualan Ekatalog dan SPB';
        //         } else if ($x == ['spa', 'spb']) {
        //             $data = $spa->merge($spb)->sortBy('created_at');
        //             $header = 'Laporan Penjualan SPA dan SPB';
        //         } else if ($this->jenis_penjualan == 'ekatalog') {
        //             $data = $ekatalog;
        //             $header = 'Laporan Penjualan Ekatalog ';
        //         } else if ($this->jenis_penjualan == 'spa') {
        //             $data = $spa;
        //             $header = 'Laporan Penjualan SPA';
        //         } else if ($this->jenis_penjualan == 'spb') {
        //             $data = $spb;
        //             $header = 'Laporan Penjualan SPB';
        //         } else {
        //             $data = $ekatalog->merge($spa)->merge($spb)->sortBy('created_at');
        //             $header = 'Laporan Penjualan Semua';
        //         }
        //     } else {
        //         $Ekatalog = Pesanan::wherehas('Ekatalog', function ($q) use ($dsb) {
        //             $q->where('customer_id', $dsb);
        //         })->wherenotnull('no_po')
        //             ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //             ->orderby('so', 'ASC')
        //             ->get();
        //         $Spa = Pesanan::wherehas('Spa', function ($q) use ($dsb) {
        //             $q->where('customer_id', $dsb);
        //         })->wherenotnull('no_po')
        //             ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //             ->orderby('so', 'ASC')
        //             ->get();
        //         $Spb = Pesanan::wherehas('Spb', function ($q) use ($dsb) {
        //             $q->where('customer_id', $dsb);
        //         })->wherenotnull('no_po')
        //             ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //             ->orderby('so', 'ASC')
        //             ->get();

        //         if ($x == ['ekatalog', 'spa', 'spb']) {
        //             $data = $Ekatalog->merge($Spa)->merge($Spb);
        //             $header = 'Laporan Penjualan Semua';
        //         } else if ($x == ['ekatalog', 'spa']) {
        //             $data = $Ekatalog->merge($Spa);
        //             $header = 'Laporan Penjualan Ekatalog dan SPA';
        //         } else if ($x == ['ekatalog', 'spb']) {
        //             $data = $Ekatalog->merge($Spb);
        //             $header = 'Laporan Penjualan Ekatalog dan SPB';
        //         } else if ($x == ['spa', 'spb']) {
        //             $data = $Spa->merge($Spb);
        //             $header = 'Laporan Penjualan SPA dan SPB';
        //         } else if ($this->jenis_penjualan == 'ekatalog') {
        //             $data = $Ekatalog;
        //             $header = 'Laporan Penjualan Ekatalog ';
        //         } else if ($this->jenis_penjualan == 'spa') {
        //             $data = $Spa;
        //             $header = 'Laporan Penjualan SPA';
        //         } else if ($this->jenis_penjualan == 'spb') {
        //             $data = $Spb;
        //             $header = 'Laporan Penjualan SPB';
        //         } else {
        //             $data = $Ekatalog->merge($Spa)->merge($Spb);
        //             $header = 'Laporan Penjualan Semua';
        //         }
        //     }
        // } else {
        //     if ($dsb == 'semua') {
        //         $Ekatalog  = DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%EKAT%');
        //         })->get();
        //         $Spa  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%SPA%');
        //         })->get());

        //         $Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%SPB%');
        //         })->get());

        //         $Ekatalog_Spa  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%EKAT%')
        //                 ->orwhere('so', 'LIKE', '%SPA%');
        //         })->get());
        //         $Ekatalog_Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%EKAT%')
        //                 ->orwhere('so', 'LIKE', '%SPB%');
        //         })->get());
        //         $Spa_Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%SPA%')
        //                 ->orwhere('so', 'LIKE', '%SPB%');
        //         })->get());
        //         $Ekatalog_Spa_Spb  = collect(DetailPesanan::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%SPA%')
        //                 ->orwhere('so', 'LIKE', '%SPB%')
        //                 ->orwhere('so', 'LIKE', '%EKAT%');
        //         })->get());


        //         $Part_Spa  = collect(DetailPesananPart::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%SPA%');
        //         })->get());
        //         $Part_Spb  = collect(DetailPesananPart::whereHas('Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('so', 'LIKE', '%SPB%');
        //         })->get());
        //     } else {
        //         $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('customer_id', $dsb);
        //         })->get();
        //         $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('customer_id', $dsb);
        //         })->get();
        //         $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('customer_id', $dsb);
        //         })->get();
        //         $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('customer_id', $dsb);
        //         })->get();
        //         $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($dsb, $tanggal_awal, $tanggal_akhir) {
        //             $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
        //                 ->where('customer_id', $dsb);
        //         })->get();
        //     }

        //     if ($x == ['ekatalog', 'spa', 'spb']) {
        //         $part = $Part_Spa->merge($Part_Spb);
        //         $data = $Ekatalog_Spa_Spb->merge($part);
        //         $header = 'Laporan Penjualan Semua';
        //     } else if ($x == ['ekatalog', 'spa']) {
        //         $data = $Ekatalog_Spa->merge($Part_Spa);
        //         $header = 'Laporan Penjualan Ekatalog dan SPA';
        //     } else if ($x == ['ekatalog', 'spb']) {
        //         $data = $Ekatalog_Spb->merge($Part_Spb);
        //         $header = 'Laporan Penjualan Ekatalog dan SPB';
        //     } else if ($x == ['spa', 'spb']) {
        //         $part = $Part_Spa->merge($Part_Spb);
        //         $data = $Spa_Spb->merge($part);
        //         $header = 'Laporan Penjualan SPA dan SPB';
        //     } else if ($this->jenis_penjualan == 'ekatalog') {
        //         $data = $Ekatalog;
        //         $header = 'Laporan Penjualan Ekatalog ';
        //     } else if ($this->jenis_penjualan == 'spa') {
        //         $data = $Part_Spa->merge($Spa);
        //         $header = 'Laporan Penjualan SPA';
        //     } else if ($this->jenis_penjualan == 'spb') {
        //         $data = $Spb->merge($Part_Spb);
        //         $header = 'Laporan Penjualan SPB';
        //     }
        // }
        // return view('page.penjualan.penjualan.LaporanPenjualanEx', ['data' => $data, 'header' => $header, 'seri' => $seri, 'tampilan' => $tampilan]);

        return view('page.penjualan.penjualan.LaporanPenjualanExNew', ['data' => $pesanan, 'seri' => $seri]);
    }
    public function title(): string
    {
        return 'Detail Produk';
    }
}
