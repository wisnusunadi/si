<?php

namespace App\Http\Controllers;

use App\Models\Logistik;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AfterSalesController extends Controller
{
    public function get_data_so()
    {
        $data = Logistik::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
            })
            ->addColumn('tgl_kirim', function ($data) {
                return  Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
                } else {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('provinsi', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
                } else {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('telepon', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp;
                } else {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp;
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->status_id  == "10") {
                    return '<span class="badge blue-text">' . $data->State->nama . '</span>';
                } else if ($data->status_id  == "11") {
                    return '<span class="badge red-text">' . $data->State->nama . '</span>';
                }
            })
            ->addColumn('keterangan', function ($data) {
                return "-";
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name[1]]) . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-search"></i>
                            Detail
                        </button>
                    </a>
                </div>';
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }
}
