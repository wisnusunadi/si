<?php

namespace App\Exports;

use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\UjiLabDetail;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use Maatwebsite\Excel\Concerns\FromCollection;

class KontrolLabs implements   WithTitle, FromView,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
    public function view(): View
    {

        $data = UjiLabDetail::select('jenis_pemilik.nama as jp','pesanan.no_po','uji_lab.pesanan_id as p_id','karyawans.nama as pemeriksa','uji_lab.no_order','uji_lab.nama','uji_lab.alamat',
        'uji_lab_detail.no','uji_lab_detail.no_sertifikat','uji_lab_detail.tgl_masuk','metode_lab.metode',
        'produk.nama as produk','noseri_barang_jadi.noseri','uji_lab_detail.tgl_kalibrasi','uji_lab_detail.status'
        )
        ->leftJoin('uji_lab','uji_lab.id','=','uji_lab_detail.uji_lab_id')
        ->leftJoin('detail_pesanan_produk','detail_pesanan_produk.id','=','uji_lab_detail.detail_pesanan_produk_id')
        ->leftJoin('gdg_barang_jadi','gdg_barang_jadi.id','=','detail_pesanan_produk.gudang_barang_jadi_id')
        ->leftJoin('produk','produk.id','=','gdg_barang_jadi.produk_id')
        ->leftJoin('noseri_detail_pesanan','noseri_detail_pesanan.id','=','uji_lab_detail.noseri_id')
        ->leftJoin('t_gbj_noseri','t_gbj_noseri.id','=','noseri_detail_pesanan.t_tfbj_noseri_id')
        ->leftJoin('noseri_barang_jadi','noseri_barang_jadi.id','=','t_gbj_noseri.noseri_id')
        ->leftJoin('detail_metode_lab','detail_metode_lab.id','=','uji_lab_detail.metode_id')
        ->leftJoin('metode_lab','metode_lab.id','=','detail_metode_lab.metode_lab_id')
        ->leftJoin('erp_kesehatan.karyawans','karyawans.id','=','uji_lab_detail.pemeriksa_id')
        ->leftJoin('pesanan','pesanan.id','=','uji_lab.pesanan_id')
        ->leftJoin('jenis_pemilik','jenis_pemilik.id','=','uji_lab.jenis_pemilik_id')
        ->where('uji_lab_detail.status', '!=', 'belum');

        $spb = Spb::select('spb.pesanan_id as id', 'customer.nama','spb.ket')
        ->selectRaw('"" AS no_paket')
        ->selectRaw('"-" AS instansi')
        ->selectRaw('"-" AS alamat_instansi')
        ->selectRaw('"-" AS status')
        ->selectRaw('"-" AS satuan')
        ->selectRaw('"-" AS no_urut')
        ->selectRaw('"-" AS tgl_buat')
        ->selectRaw('"-" AS tgl_kontrak')
        ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
        ->whereIn('spb.pesanan_id', $data->pluck('p_id')->toArray())->get();

    $spa = Spa::select('spa.pesanan_id as id', 'customer.nama','spa.ket')
    ->selectRaw('"" AS no_paket')
    ->selectRaw('"-" AS instansi')
    ->selectRaw('"-" AS alamat_instansi')
    ->selectRaw('"-" AS status')
    ->selectRaw('"-" AS satuan')
    ->selectRaw('"-" AS no_urut')
    ->selectRaw('"-" AS tgl_buat')
    ->selectRaw('"-" AS tgl_kontrak')
    ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
    ->whereIn('spa.pesanan_id', $data->pluck('p_id')->toArray())->get();

    $ekatalog = Ekatalog::select('ekatalog.pesanan_id as id','ekatalog.ket','ekatalog.tgl_buat','ekatalog.tgl_kontrak','ekatalog.no_urut as no_urut','customer.nama' ,'ekatalog.no_paket','ekatalog.instansi','ekatalog.alamat as alamat_instansi','ekatalog.satuan','ekatalog.status')
        ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
        ->whereIn('ekatalog.pesanan_id', $data->pluck('p_id')->toArray())->get();

    $dataInfo =   $ekatalog->merge($spa)->merge($spb);

        foreach($data->get() as $d){
            $obj[] = array(
                'no' => str_pad($d->no, 4, '0', STR_PAD_LEFT),
                'p_id' =>$d->p_id,
                'no_po' =>$d->no_po,
                'no_order' => 'LAB-'.str_pad($d->no_order, 4, '0', STR_PAD_LEFT),
                'tgl_masuk' => $d->tgl_masuk,
                'nama_alat' => $d->metode,
                'type' => $d->produk,
                'noseri' => $d->noseri,
                'nama_pemilik' => $d->jp,
                'nama_pemilik_sert' => $d->nama,
                'alamat' => $d->alamat,
                'tgl_kalibrasi' => $d->tgl_kalibrasi,
                'no_sertifikat' => $d->no_sertifikat,
                'pemeriksa' => $d->pemeriksa,
                'status' => $d->status,
                'nosj' => str_pad($d->no_order, 4, '0', STR_PAD_LEFT).'/LAB/'.date('m', strtotime($d->tgl_kalibrasi)).'/'.date('y', strtotime($d->tgl_kalibrasi)),
            );
        }


    $merge = [];

    foreach ($obj as $labItem) {
        $mergedItem = $labItem;
        $p_id = $labItem['p_id'];

    foreach ($dataInfo as $poItem) {
        if ($poItem['id'] === $p_id) {
            $mergedItem['info'] = $poItem;
            break;
        }
    }

    $merge[] = $mergedItem;
    }

        return view('page.lab.kontrol_lab',['data' => $merge]);
    }
    public function title(): string
    {
        return 'Kontrol Lab';
    }
}
