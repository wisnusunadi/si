<?php

namespace App\Exports;

use App\Models\GudangKarantinaDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProdukGKExport implements FromView, ShouldAutoSize
{
   function view(): View
   {
        $dataspr = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        $dataunit = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'))
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get();
        $data = $dataspr->merge($dataunit);
        $arr = [];
        foreach($data as $d) {
            $arr[] = [
                'kode' => $d->kode,
                'nama' => $d->nama,
                'jml' => $d->jml. ' Unit',
                'jenis' => $d->sparepart_id == null ? 'Unit' : 'Sparepart',
            ];
        }
    return view('page.gk.gudang.report', [
        'data' => $arr,
    ]);
   }
}
