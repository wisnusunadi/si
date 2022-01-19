<?php

namespace App\Exports;

use App\Models\GudangKarantinaDetail;
use App\Models\GudangKarantinaNoseri;
use App\Models\NoseriKeluarGK;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransaksiGKExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $cek = GudangKarantinaDetail::with('sparepart.Spare', 'units.produk', 'header.from', 'header.to')->where('is_draft', 0)->get();
        $arr = [];
        foreach($cek as $c) {
            $cc = GudangKarantinaNoseri::with('detail', 'layout')->where('gk_detail_id', $c->id)->get();
            $ccc = NoseriKeluarGK::where('gk_detail_id', $c->id)->get();
            $data = $cc->merge($ccc);
            foreach($data as $d) {
                $arr[] = [
                    'Jenis' => $c->qty_unit == null ? 'Sparepart' : 'Unit',
                    'Produk' => $c->gbj_id == null ? $c->sparepart->nama : $c->units->produk->nama . ' ' . $c->units->nama,
                    'Masuk' => $c->is_keluar == 0 ? date('d-m-Y', strtotime($c->header->date_in)) : '-',
                    'Keluar' => $c->is_keluar == 1 ? date('d-m-Y', strtotime($c->header->date_out)) : '-',
                    'Status' => $c->is_keluar == 1 ? 'Keluar' : 'Masuk',
                    'Dari/Ke' => $c->is_keluar == 1 ? $c->header->to->nama : $c->header->from->nama,
                    'Jumlah' => $c->qty_unit == null ? $c->qty_spr.' Unit' : $c->qty_unit . ' ' . $c->units->satuan->nama,
                    'Keterangan' => $c->header->deskripsi == null ? '-' : $c->header->deskripsi,
                    'Noseri' => [
                        'seri' => $d->seri ? $d->seri->noseri : $d->noseri,
                        'remark' => $d->seri ? $d->seri->remark : $d->remark,
                        'perbaikan' => $d->seri ? $d->seri->perbaikan : $d->perbaikan,
                        'layout' => $d->seri ? $d->seri->layout->ruang : $d->layout_id == null ? '-' : $d->layout->ruang,
                        'tingkat' => $d->seri ? 'Level '.$d->seri->tk_kerusakan : 'Level '.$d->tk_kerusakan,
                    ]
                ];
            }
        }
        // return response()->json([
        //     'data' => $arr,
        // ]);
        return view('page.gk.transaksi.report', [
            'data' => $arr,
        ]);
    }


}
