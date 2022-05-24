<?php

namespace App\Exports;

use App\Models\TFProduksiDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

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
        })->with('seri.seri', 'produk.produk', 'paket.detailpesanan.penjualanproduk')->groupBy('detail_pesanan_produk_id')->groupBy('gdg_brg_jadi_id')->get();
        return view('page.gbj.reports.spb',['data' => $data]);
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
            }
        ];
    }
}
