<?php

namespace App\Exports;

use App\Models\NoseriTGbj;
use App\Models\TFProduksiDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;

class NonsoExport implements FromView
{
    public function id()
    {
        return $this->id;
    }

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $id = $this->id;
        $data = TFProduksiDetail::
                    leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
                    ->leftJoin('divisi as p', 'p.id', '=', 'tg.ke')
                    ->leftJoin('gdg_barang_jadi as gbj', 'gbj.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
                    ->leftJoin('produk as pp', 'pp.id', '=', 'gbj.produk_id')
                    ->where([
                        ['tg.jenis', '=', 'keluar'],
                        // ['t_gbj.status_id', '=', 2],
                    ])->whereNull('tg.pesanan_id')
                    ->selectRaw('p.nama as nm_divisi, tg.tgl_keluar, tg.deskripsi, tg.id as tgdid, t_gbj_detail.id,
                                concat(pp.nama," ",gbj.nama) as produkk, t_gbj_detail.qty')
                    ->where("t_gbj_detail.gdg_brg_jadi_id", "=", $id)
                    ->with(['noseri.seri'])
                    ->get();
        return view('page.gbj.reports.nonso',['data' => $data]);
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
}
