<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use PDF;
use App\Models\Pesanan;
use Ekatalog;

class DcController extends Controller
{
    public function pdf_coo()
    {
        $pdf = PDF::loadView('page.dc.coo.pdf')->setPaper('A4');
        return $pdf->stream('');
    }

    public function get_data_so()
    {

        $Ekatalog = collect(Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->Has('Ekatalog')->get());
        $Spa = collect(Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan')->Has('Spa')->get());
        $data = $Ekatalog->merge($Spa);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_paket', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->no_paket;
                } else {
                    return '';
                }
            })
            ->addColumn('batas_paket', function () {
                return '';
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->customer->nama;
                } else {
                    return $data->spa->customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->instansi;
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {
                return '';
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return '<a href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                    <i class="fas fa-search"></i>
                </a>';
                } else {
                    return '<a href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                    <i class="fas fa-search"></i>
                </a>';
                }
            })
            ->rawColumns(['button'])
            ->make(true);
    }


    public function get_data_detail_so($id)
    {
        $data = DetailPesanan::WhereHas('Pesanan', function ($q) use ($id) {
            $q->where('id', $id);
        })->Has('Ekatalog')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }
    //Show
    public function detail_coo($id, $value)
    {
        if ($value == 'ekatalog') {
            $data = Pesanan::find($id);
            return view('page.dc.so.detail_ekatalog', ['data' => $data]);
        } else {
            return view('page.dc.so.detail_spa');
        }
    }
}
