<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPerencanaan;
use App\Exports\LaporanPerencanaanDetail;
use App\Models\DetailPesanan;
use App\Models\DetailRencanaPenjualan;
use App\Models\RencanaPenjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RencanaPenjualanController extends Controller
{
    //Data
    public function get_data_rencana($customer, $tahun)
    {
        $data = DetailPesanan::WhereHas('DetailRencanaPenjualan.RencanaPenjualan', function ($q) use ($customer) {
            $q->where('customer_id', $customer);
        })->groupby('detail_rencana_penjualan_id')->get();

        // $data = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($customer, $tahun) {
        //     $q->where('customer_id', $customer)
        //         ->where('tahun', $tahun);
        // })->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('instansi', function ($data) {
                return $data->DetailRencanaPenjualan->RencanaPenjualan->instansi;
            })
            ->addColumn('produk', function ($data) {
                if ($data->PenjualanProduk->nama_alias != '') {
                    return $data->PenjualanProduk->nama_alias;
                } else {
                    return $data->PenjualanProduk->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                return $data->DetailRencanaPenjualan->jumlah;
            })
            ->addColumn('harga', function ($data) {
                return $data->DetailRencanaPenjualan->harga;
            })
            ->addColumn('sub', function ($data) {
                return $data->DetailRencanaPenjualan->harga * $data->DetailRencanaPenjualan->jumlah;
            })
            ->addColumn('jumlah_real', function ($data) {
                return $data->DetailRencanaPenjualan->sum_prd();
            })
            ->addColumn('harga_real', function ($data) {
                return $data->harga;
            })
            ->addColumn('sub_real', function ($data) {
                return $data->DetailRencanaPenjualan->sum_prd() * $data->harga;
            })
            ->make(true);
    }
    //Insert
    public function create_rencana(Request $request)
    {
        $bool = true;
        $rp = RencanaPenjualan::create([
            'customer_id' => $request->customer_id,
            'instansi' => $request->nama_instansi,
            'tahun' => $request->tahun,
        ]);

        if ($rp) {
            for ($i = 0; $i < count($request->id_produk); $i++) {
                $drp = DetailRencanaPenjualan::create([
                    'rencana_penjualan_id' => $rp->id,
                    'penjualan_produk_id' => $request->id_produk[$i],
                    'harga' =>  $request->harga[$i],
                    'jumlah' =>  $request->jumlah[$i],
                ]);
            }
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'success');
        } else {
            return redirect()->back()->with('error', 'error');
        }
    }

    //Laporan
    public function show_laporan($distributor, $tahun)
    {
        $row_rencana = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($distributor, $tahun) {
            $q->where('customer_id', $distributor)
                ->where('tahun', $tahun);
        })->count();

        $row_realisasi = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor) {
            $q->where('customer_id', $distributor);
        })->count();


        $waktu = Carbon::now();
        return Excel::download(new LaporanPerencanaan($distributor, $tahun, $row_rencana, $row_realisasi), 'Laporan Perencanaan ' . $waktu->toDateTimeString() . '.xlsx');
    }
    public function show_laporan_detail($distributor, $tahun)
    {
        $row = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor) {
            $q->where('customer_id', $distributor);
        })->count();

        $waktu = Carbon::now();
        return Excel::download(new LaporanPerencanaanDetail($distributor, $tahun, $row), 'Laporan Perencanaan Detail ' . $waktu->toDateTimeString() . '.xlsx');
    }

    public function show()
    {
        $data = DetailPesanan::WhereHas('DetailRencanaPenjualan.RencanaPenjualan', function ($q) {
            $q->where('customer_id', 213);
        })->get();

        return view('page.penjualan.rencana.result', ['data' => $data]);
    }
    //Select
    public function select_tahun_rencana(Request $request)
    {
        $data = RencanaPenjualan::where('tahun', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('tahun')->get();
        echo json_encode($data);
    }
}
