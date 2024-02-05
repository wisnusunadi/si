<?php

namespace App\Exports;

use App\Models\LogSurat;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SpbExport implements FromView, WithEvents
{
    public function pesanan_id()
    {
        return $this->pesanan_id;
    }

    public function __construct(string $pesanan_id)
    {
        $this->pesanan_id = $pesanan_id;
    }
    public function view(): View
    {
        $id = $this->pesanan_id;
        $data = TFProduksiDetail::whereHas('header', function ($q) use ($id) {
            $q->where('pesanan_id', $id);
        })->with('noseri', 'produk.produk', 'paket.detailpesanan.penjualanproduk')->groupBy('detail_pesanan_produk_id')->groupBy('gdg_brg_jadi_id')->get();
        $header = TFProduksi::where('pesanan_id', $id)->with('pesanan')->get();
        $tfbyid = LogSurat::where('pesanan_id', $id)->get();
        if (count($tfbyid) > 0) {
            LogSurat::where('pesanan_id', $id)->update(['transfer_by' => Auth::user()->id]);
        } else {
            LogSurat::create([
                'pesanan_id' => $id,
                'transfer_by' => Auth::user()->id,
            ]);
        }
        $tfby = LogSurat::where('pesanan_id', $id)->get();
        return view('page.gbj.reports.spb',['data' => $data, 'header' => $header, 'tfby' => $tfby]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('F')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('G')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('H')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('I')->setAutoSize(true);
                $event->sheet->getDelegate()->getColumnDimension('J')->setAutoSize(true);

                // foreach($this->drawings() as $row) {
                //     $row->setWorksheet($event->sheet->getDelegate());
                // }
            }

        ];
    }

    // function drawings()
    // {
    //     $id = $this->pesanan_id;
    //     $drawings = [];
    //     $data = TFProduksiDetail::whereHas('header', function ($q) use ($id) {
    //         $q->where('pesanan_id', $id);
    //     })->with('seri.seri', 'produk.produk', 'paket.detailpesanan.penjualanproduk')->groupBy('detail_pesanan_produk_id')->groupBy('gdg_brg_jadi_id')->get();

    //     foreach($data as $d) {
    //         $i = 0;
    //         foreach($d->seri as $dd) {
    //             $i++;
    //             // if ($i % 5 != 0) {
    //             //     $i += 1;
    //             // } else {
    //             //     $i;
    //             // }
    //             // echo $i;

    //             $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    //             $drawing->setName('Logo');
    //             $drawing->setDescription('This is my logo');
    //             $drawing->setPath(public_path('assets/image/accepted.png'));
    //             $drawing->setHeight(55);
    //             $drawing->setCoordinates('A'.$i);
    //             // $drawings[] = $drawing;
    //         }
    //     }
    //     return $drawing;
    // }
}
