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
                // if(count($data->DetailPesanan) <= 0){
                //     $datas .= ' <a id="produk_id" class="produk_id" data-pk="'.$data->id.'" data-url="/post"><i class="fas fa-pencil-alt"></i></a>';
                // }
            })
            ->addColumn('jumlah', function ($data) {
                // return $data->DetailRencanaPenjualan->jumlah;
                return $data->jumlah;
                //  return '<a  class="update_jumlah" data-name="jumlah" data-type="text" data-pk="' . $data->id . '" data-title="Masukkan Jumlah">' . $data->jumlah . '</a>';
            })
            ->addColumn('harga', function ($data) {
                // return $data->DetailRencanaPenjualan->harga;
                return '<a  class="update_harga" data-name="harga" data-type="text" data-pk="' . $data->id . '" data-title="Masukkan Harga">' . number_format($data->harga, 0, '.', '.') . '</a>';
                //return $data->harga;
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
                return '<a data-toggle="modal" class="realmodal" data-id="' . $data->id . '" data-target="#realmodal"><button type="button" class="btn btn-info btn-circle" alt="Tambah Realisasi">
                <i class="far fa-plus-square"></i>
               </button> </a>
               <a data-toggle="modal" class="hapusmodal" data-id="' . $data->id . '" data-target="#hapusmodal"><button type="button" class="btn btn-danger btn-circle">
                <i class="far fa-trash-alt"></i>
               </button> </a>
               ';
            })
            ->rawColumns(['hapus', 'harga'])
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

    public function update_rencana(Request $request)
    {
        if ($request->ajax()) {

            $harga_convert =  str_replace('.', "", $request->input('value'));
            DetailRencanaPenjualan::find($request->input('pk'))->update([$request->input('name') => $harga_convert]);
            return response()->json(['success' => true]);
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

    public function get_show_real($id)
    {
        $data = DetailRencanaPenjualan::find($id);
        return view('page.penjualan.rencana.real', ['id' => $id, 'data' => $data]);
    }

    public function get_show_data_real($id)
    {
        $data = DetailPesanan::where('detail_rencana_penjualan_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_so', function ($data) {
                // return $data->DetailRencanaPenjualan->RencanaPenjualan->instansi;
                return $data->Pesanan->so;
            })
            ->addColumn('no_akn', function ($data) {
                // return $data->DetailRencanaPenjualan->RencanaPenjualan->instansi;
                return $data->Pesanan->Ekatalog->no_paket;
            })
            ->addColumn('produk', function ($data) {
                $datas = "";
                if ($data->PenjualanProduk->nama_alias != '') {
                    $datas .= $data->PenjualanProduk->nama_alias;
                } else {
                    $datas .= $data->PenjualanProduk->nama;
                }
                return $datas;
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
            ->make(true);
    }

    public function get_data_real($id)
    {
        $datarencana = DetailRencanaPenjualan::find($id);

        $cust = $datarencana->RencanaPenjualan->customer_id;
        $ins = $datarencana->RencanaPenjualan->instansi;
        $prd = $datarencana->penjualan_produk_id;

        $datareal = DetailPesanan::where('detail_rencana_penjualan_id', $id)->get();
        $datapenj = DetailPesanan::whereNull('detail_rencana_penjualan_id')->where('penjualan_produk_id', $prd)->whereHas('Pesanan.Ekatalog', function ($q) use ($cust, $ins) {
            $q->where([['customer_id', '=', $cust], ['instansi', '=', $ins]]);
        })->get();

        $data = $datareal->merge($datapenj);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                // return $data->DetailRencanaPenjualan->RencanaPenjualan->instansi;
                if (!empty($data->detail_rencana_penjualan_id)) {
                    return '  <div class="form-check">
                    <input class="form-check-input so_id" name="detail_pesanan_id[]" value="' . $data->id . '" type="checkbox" checked/>
                    </div>';
                } else {
                    return '  <div class="form-check">
                    <input class="form-check-input so_id" name="detail_pesanan_id[]" value="' . $data->id . '" type="checkbox"/>
                    </div>';
                }
            })
            ->addColumn('no_so', function ($data) {
                // return $data->DetailRencanaPenjualan->RencanaPenjualan->instansi;
                return $data->Pesanan->so;
            })
            ->addColumn('no_akn', function ($data) {
                // return $data->DetailRencanaPenjualan->RencanaPenjualan->instansi;
                return $data->Pesanan->Ekatalog->no_paket;
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
            ->rawColumns(['checkbox'])
            ->make(true);
    }

    public function get_update_realisasi($id, Request $request)
    {
        $bool = true;
        $data = DetailPesanan::where('detail_rencana_penjualan_id', $id)->get();
        if (count($data) > 0) {
            foreach ($data as $i) {
                $update = DetailPesanan::find($i->id);
                $update->detail_rencana_penjualan_id = NULL;
                $u = $update->save();
                if (!$u) {
                    $bool = false;
                }
            }
        }


        if (isset($request->detail_pesanan_id)) {
            if ($bool == true) {
                for ($i = 0; $i < count($request->detail_pesanan_id); $i++) {
                    $update = DetailPesanan::find($request->detail_pesanan_id[$i]);
                    $update->detail_rencana_penjualan_id = $id;
                    $u = $update->save();
                    if (!$u) {
                        $bool = false;
                    }
                }
            }
        }

        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    //Select
    public function select_tahun_rencana(Request $request)
    {
        $data = RencanaPenjualan::where('tahun', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('tahun')->get();
        echo json_encode($data);
    }
}
