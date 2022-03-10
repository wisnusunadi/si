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
        // $data = DetailPesanan::WhereHas('DetailRencanaPenjualan.RencanaPenjualan', function ($q) use ($customer) {
        //     $q->where('customer_id', $customer);
        // })->groupby('detail_rencana_penjualan_id')->get();

        $data = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($customer, $tahun) {
            $q->where('customer_id', $customer)
                ->where('tahun', $tahun);
        })->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('instansi', function ($data) {
                // return $data->DetailRencanaPenjualan->RencanaPenjualan->instansi;
                return $data->RencanaPenjualan->instansi;
            })
            ->addColumn('produk', function ($data) {
                if ($data->PenjualanProduk->nama_alias != '') {
                    return $data->PenjualanProduk->nama_alias;
                } else {
                    return $data->PenjualanProduk->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                // return $data->DetailRencanaPenjualan->jumlah;
                return $data->jumlah;
            })
            ->addColumn('harga', function ($data) {
                // return $data->DetailRencanaPenjualan->harga;
                return $data->harga;
            })
            ->addColumn('sub', function ($data) {
                // return $data->DetailRencanaPenjualan->harga * $data->DetailRencanaPenjualan->jumlah;
                return $data->harga * $data->jumlah;
            })
            ->addColumn('jumlah_real', function ($data) {
                // return $data->DetailRencanaPenjualan->sum_prd();
                return $data->sum_prd();
            })
            ->addColumn('harga_real', function ($data) {
                // return $data->harga;
                return $data->harga_prd();
            })
            ->addColumn('sub_real', function ($data) {
                // return $data->DetailRencanaPenjualan->sum_prd() * $data->harga;
                return $data->sum_prd() * $data->harga_prd();
            })
            ->addColumn('hapus', function ($data) {
                return '   <a data-toggle="modal" class="hapusmodal" data-id="' . $data->id . '" data-target="#hapusmodal"><button type="button" class="btn btn-danger">
                <i class="far fa-trash-alt"></i>
               </button> </a>';
            })
            ->rawColumns(['hapus'])
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
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
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
        $row = 0;
        $rows = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($distributor, $tahun) {
            $q->where(['customer_id' => $distributor, 'tahun' => $tahun]);
        })->get();

        foreach ($rows as $i) {
            if (count($i->DetailPesanan) > 1) {
                $row += count($i->DetailPesanan);
            } else {
                $row += 1;
            }
        }

        $row_rencana = RencanaPenjualan::where(['customer_id' => $distributor, 'tahun' => $tahun])->count();

        $waktu = Carbon::now();
        return Excel::download(new LaporanPerencanaanDetail($distributor, $tahun, $row, $row_rencana), 'Laporan Perencanaan Detail ' . $waktu->toDateTimeString() . '.xlsx');
    }

    public function show()
    {
        $data = DetailPesanan::WhereHas('DetailRencanaPenjualan.RencanaPenjualan', function ($q) {
            $q->where('customer_id', 213);
        })->get();

        return view('page.penjualan.rencana.result', ['data' => $data]);
    }
    //Hapus
    public function delete_detail_rencana($id)
    {
        $DetailRencanaPenjualan = DetailRencanaPenjualan::findOrFail($id);
        $Count = DetailRencanaPenjualan::where('rencana_penjualan_id', $DetailRencanaPenjualan->rencana_penjualan_id)->count();


        if ($Count > 1) {
            $d_detail_rencana = $DetailRencanaPenjualan->delete();

            if ($d_detail_rencana) {
                return response()->json(['data' => 'success']);
            } else {
                return response()->json(['data' => 'error']);
            }
        } else {
            $d_detail_rencana = $DetailRencanaPenjualan->delete();
            $d_rencana = RencanaPenjualan::findOrFail($DetailRencanaPenjualan->rencana_penjualan_id)->delete();

            if ($d_detail_rencana && $d_rencana) {
                return response()->json(['data' => 'success']);
            } else {
                return response()->json(['data' => 'error']);
            }
        }
    }


    //Select
    public function select_tahun_rencana(Request $request)
    {
        $data = RencanaPenjualan::where('tahun', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('tahun')->get();
        echo json_encode($data);
    }
}
