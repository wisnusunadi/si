<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailPenjualanProduk;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\KelompokProduk;
use App\Models\PenjualanProduk;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Provinsi;
use App\Models\Spa;
use App\Models\Spb;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Alert;

class MasterController extends Controller
{
    //Get Data Table
    public function get_data_produk()
    {
        return datatables()->of(Produk::with('KelompokProduk'))->toJson();
    }
    public function get_data_customer($value)
    {
        $x = explode(',', $value);
        if ($value == 0 || $value == 'kosong') {
            $data = Customer::select();
        } else {
            $data = Customer::whereHas('Provinsi', function ($q) use ($x) {
                $q->whereIN('status', $x);
            })->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prov', function ($data) {
                return $data->provinsi->nama;
            })
            ->addColumn('button', function ($data) {
                $datas = "";
                $datas .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="' . route('penjualan.customer.detail', $data->id) . '">
                    <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Detail
                    </button>
                </a>';
                $datas .= '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">                         
                        <button class="dropdown-item" type="button" >
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                        </button>
                    </a>';
                $datas .= '</div>';
                return $datas;
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    public function get_data_penjualan_produk($value)
    {
        $x = explode(',', $value);
        if ($value == 0 || $value == 'kosong') {
            $data = PenjualanProduk::select();
        } else {
            $data = PenjualanProduk::whereHas('Produk', function ($q) use ($value) {
                $q->where('kelompok_produk_id', $value);
            })->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('button', function ($data) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" type="button" id="showmodal">
                        <i class="fas fa-search"></i>
                        Detail
                    </button>
                    <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                    </button>
                    </a>
                </div>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    //public function get_data_detail_penjualan_produk($id)
    //{
    // $data = DetailPenjualanProduk::where('penjualan_produk_id', $id)->get();
    // $where = [49, 129];

    // $detail_data = GudangBarangJadi::whereIn('produk_id', $where)->get();
    // return datatables()->of($detail_data)
    //     ->addIndexColumn()
    //     ->addColumn('nama', function ($detail_data) {
    //         return $detail_data->produk->nama . ' - <b>' . $detail_data->variasi . '</b>';
    //     })
    //     ->addColumn('kelompok', function ($detail_data) {
    //         return $detail_data->produk->KelompokProduk->nama;
    //     })
    //     ->rawColumns(['nama'])
    //     ->make(true);
    //}
    public function get_data_detail_penjualan_produk($id)
    {
        $data = Produk::whereHas('PenjualanProduk', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();


        //$data = PenjualanProduk::with('produk')->where('id', $id)->get();
        // $data = PenjualanProduk::with('Produk')->selectRaw('distinct penjualan_produk.*')->where('id', '5')->get();
        return datatables()->of($data)
            // ->addColumn('produk_nama', function ($data) {
            //     return implode(',', $data->Produk->pluck('nama')->toArray());
            // })
            // ->rawColumns(['produk_nama'])
            ->addColumn('kelompok', function ($data) {
                return $data->KelompokProduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->PenjualanProduk->first()->pivot->jumlah;
            })
            ->addIndexColumn()
            ->make(true);
    }
    public function get_data_pesanan($id)
    {
        // $data  = Ekatalog::with('pesanan')
        //     ->where('customer_id', $id)
        //     ->get();

        $Ekatalog = collect(Ekatalog::with('Pesanan')->where('customer_id', $id)->get());
        $Spa = collect(Spa::with('Pesanan')->where('customer_id', $id)->get());
        $Spb = collect(Spb::with('Pesanan')->where('customer_id', $id)->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        if ($data)
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('jenis', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'ekatalog') {
                        return 'E-Catalogue';
                    } else if ($name == 'spa') {
                        return 'SPA';
                    } else {
                        return 'SPB';
                    }
                })
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->so;
                    } else {
                        return '';
                    }
                })
                ->addColumn('nopo', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tglpo', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->tgl_po;
                    } else {
                        return '';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if ($data->log == "penjualan") {
                        $datas .= '<span class="red-text badge">';
                    } else if ($data->log == "po") {
                        $datas .= '<span class="purple-text badge">';
                    } else if ($data->log == "gudang") {
                        $datas .= '<span class="orange-text badge">';
                    } else if ($data->log == "qc") {
                        $datas .= '<span class="yellow-text badge">';
                    } else if ($data->log == "logistik") {
                        $datas .= '<span class="blue-text badge">';
                    } else if ($data->log == "selesai") {
                        $datas .= '<span class="green-text badge">';
                    }
                    $datas .= ucfirst($data->log) . '</span>';
                    return $datas;
                })
                ->rawColumns(['status'])
                ->make(true);
    }
    //Create
    public function create_produk(Request $request)
    {
        $this->validate(
            $request,
            [
                'kelompok_produk_id' => 'required',
                'merk' => 'required',
                'tipe' => 'required|unique:produk',

            ],
            [
                'kelompok_produk_id.required' => 'Kelompok Produk harus di isi',
                'merk.required' => 'Merk Produk harus di isi',
                'tipe.required' => 'Tipe Produk harus di isi',
            ]
        );
        Produk::create([
            'kelompok_produk_id' => $request->kelompok_produk_id,
            'merk' => $request->merk,
            'tipe' => $request->tipe,
            'nama' => $request->nama,
            'nama_coo' => $request->nama_coo,
            'satuan' => $request->satuan,
            'no_akd' => $request->no_akd,
            'ket' => $request->ket,
            'status' => $request->kelompok_produk_id
        ]);
    }
    public function create_customer(Request $request)
    {
        // $this->validate(
        //     $request,
        //     [
        //         'nama' => 'required|unique:customer',

        //     ],
        //     [
        //         'nama.required|unique:customer' => 'Nama Customer harus di isi',
        //     ]
        // );
        $c = Customer::create([
            'nama' => $request->nama_customer,
            'telp' => $request->telepon,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'id_provinsi' => $request->provinsi,
            'npwp' => $request->npwp,
            'ket' => $request->keterangan,
        ]);

        if ($c) {
            // Alert::success('Berhasil', 'Berhasil menambahkan data');
            return redirect()->back()->with('success', 'success');
        } else {
            return redirect()->back()->with('error', 'error');
        }
    }
    public function create_penjualan_produk(Request $request)
    {
        // $this->validate(
        //     $request,
        //     [
        //         'nama_paket' => 'required|unique:penjualan_produk',
        //         'harga' => 'required',
        //         'produk_id.*' => 'required',
        //         'jumlah.*' => 'required'
        //     ],
        //     [
        //         'nama_paket.required' => 'Nama Produk harus di isi',
        //         'harga.required' => 'Harga Produk harus di isi',
        //         'produk_id.required' => 'Produk harus di pilih',
        //         'jumlah.required' => 'Jumlah Produk harus di isi',
        //     ]
        // );
        $harga_convert =  str_replace('.', "", $request->harga);
        $PenjualanProduk = PenjualanProduk::create([
            'nama' => $request->nama_paket,
            'harga' => $harga_convert
        ]);
        $bool = true;
        if ($PenjualanProduk) {
            for ($i = 0; $i < count($request->produk_id); $i++) {
                $PenjualanProduk->produk()->attach($request->produk_id[$i], ['jumlah' => $request->jumlah[$i]]);
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'success');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Detail Penjualan error');
        }


        // for ($i = 0; $i < count($request->produk_id); $i++) {
        //     DetailPenjualanProduk::create([
        //         'produk_id' => $request->produk_id[$i],
        //         'penjualan_produk_id' => $PenjualanProduk->id,
        //         'jumlah' => $request->jumlah[$i],
        //     ]);
        // }
    }

    public function update_penjualan_produk_modal($id)
    {
        $penjualanproduk = PenjualanProduk::with('produk')->where('id', $id)->get();
        // $produk = Produk::whereHas('PenjualanProduk', function ($q) use ($id) {
        //     $q->where('id', $id);
        // })->with('PenjualanProduk')->get();

        return view("page.penjualan.produk.edit", ['penjualanproduk' => $penjualanproduk,]);
    }


    //Update
    public function update_customer(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->id_provinsi = $request->provinsi;
        $customer->nama = $request->nama_customer;
        $customer->npwp = $request->npwp;
        $customer->email = $request->email;
        $customer->telp = $request->telepon;
        $customer->alamat = $request->alamat;
        $customer->ket = $request->keterangan;
        $c = $customer->save();
        if ($c) {
            return response()->json(['data' => 'success']);
        } else if (!$c) {
            return response()->json(['data' => 'error']);
        }
    }

    public function update_produk(Request $request)
    {
        $id = $request->id;
        $produk = Produk::find($id);
        $produk->kelompok_produk_id = $request->kelompok_produk_id;
        $produk->merk = $request->merk;
        $produk->tipe = $request->tipe;
        $produk->nama = $request->nama;
        $produk->nama_coo = $request->nama_coo;
        $produk->satuan = $request->satuan;
        $produk->no_akd = $request->no_akd;
        $produk->ket = $request->ket;
        $produk->status = $request->status;
        $p = $produk->save();
        if ($p) {
            return redirect()->back()->with('success', 'Berhasil mengubah data');
        } else {
            return redirect()->back()->with('error', 'Gagal mengubah data');
        }
    }
    public function delete_produk($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
    }
    public function delete_penjualan_produk($id)
    {
        $produk = PenjualanProduk::findOrFail($id);
        $produk->delete();
    }
    public function delete_detail_penjualan_produk($id)
    {
        // $produk = DetailPenjualanProduk::findOrFail($id);
        // $produk->delete();
    }

    public function update_penjualan_produk(Request $request, $id)
    {
        $harga_convert =  str_replace(',', "", $request->harga);
        $PenjualanProduk = PenjualanProduk::find($id);
        $PenjualanProduk->nama = $request->nama_paket;
        $PenjualanProduk->harga = $harga_convert;
        $PenjualanProduk->save();

        $produk_array = [];
        for ($i = 0; $i < count($request->produk_id); $i++) {
            $produk_array[$request->produk_id[$i]] = ['jumlah' => $request->jumlah[$i]];
        }
        $p = $PenjualanProduk->produk()->sync($produk_array);
        if ($p) {
            return response()->json(['data' => 'success']);
        } else if (!$p) {
            return response()->json(['data' => 'error']);
        }
    }
    //Other

    //Check
    public function check_produk($value)
    {
        $data = Produk::where('tipe', $value)->get();
        echo json_encode($data);
    }
    public function check_customer($value)
    {
        $data = Customer::where('nama', $value)->get();
        echo json_encode($data);
    }
    public function check_penjualan_produk($value)
    {
        $data = PenjualanProduk::where('nama', $value)->get();
        echo json_encode($data);
    }

    //Show Modal

    public function update_customer_modal($id)
    {
        $customer = Customer::find($id);
        return view("page.penjualan.customer.edit", ['customer' => $customer]);
    }


    //Show Detail

    public function detail_customer($id)
    {
        $customer = Customer::find($id);
        return view('page.penjualan.customer.detail', ['customer' => $customer]);
    }


    //Select
    public function select_produk(Request $request)
    {
        $data = Produk::where('tipe', 'LIKE', '%' . $request->input('term', '') . '%')
            ->orderby('tipe', 'ASC')->get();
        echo json_encode($data);
    }
    public function select_produk_id($id)
    {
        $data = Produk::with('KelompokProduk')
            ->where('id', $id)->get();
        echo json_encode($data);
    }
    public function select_customer(Request $request)
    {
        $data = Customer::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->orderby('nama', 'ASC')->get();
        echo json_encode($data);
    }
    public function select_provinsi(Request $request)
    {
        $data = Provinsi::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->orderby('nama', 'ASC')->get();
        echo json_encode($data);
    }
    public function select_customer_id($id)
    {
        $data = Customer::where('id', $id)->orderby('nama', 'ASC')->get();
        echo json_encode($data);
    }
    public function select_penjualan_produk(Request $request)
    {
        $data = PenjualanProduk::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->orderby('nama', 'ASC')
            ->get();
        echo json_encode($data);
    }
    public function select_penjualan_produk_id($id)
    {
        $data = PenjualanProduk::with('Produk.GudangBarangJadi')->where('id', $id)
            ->get();

        echo json_encode($data);
    }
}
