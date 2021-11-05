<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualan;
use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailSpa;
use App\Models\DetailSpb;
use App\Models\Ekatalog;
use App\Models\Pesanan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Provinsi;
use Hamcrest\Core\IsNot;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use League\Fractal\Resource\Item;
use Maatwebsite\Excel\Facades\Excel;

use function PHPUnit\Framework\assertIsNotArray;

class PenjualanController extends Controller
{
    //Get Data Table
    public function penjualan_data()
    {
        // $data  = Ekatalog::all();

        $Ekatalog = collect(Ekatalog::with('Pesanan')->get());
        $Spa = collect(Spa::with('Pesanan')->get());
        $Spb = collect(Spb::with('Pesanan')->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

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
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('no_paket', function ($data) {
                if (isset($data->no_paket)) {
                    return $data->no_paket;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_order', function ($data) {
                if (isset($data->tgl_buat)) {
                    return $data->tgl_buat;
                } else {
                    return $data->tgl_po;;
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                if (isset($data->tgl_kontrak)) {
                    return $data->tgl_kontrak;
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
            ->addColumn('status', function ($data) {
                return '<span class="yellow-text badge">Gudang</span>';
            })
            ->addColumn('button', function ($data) {
                $name =  $data->getTable();
                if ($name == 'ekatalog') {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                          <i class="fas fa-search"></i>
                          Details
                        </button>
                    </a>
            <div>';
                } else if ($name == 'spa') {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                          <i class="fas fa-search"></i>
                          Details
                        </button>
                    </a>
                    </div>';
                } else {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                          <i class="fas fa-search"></i>
                          Details
                        </button>
                    </a>
                    </div>';
                }
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }
    public function get_data_detail_spa($value)
    {
        $data  = Spa::with('Pesanan')
            ->where('id', $value)
            ->get();
        return view('page.penjualan.penjualan.detail_spa', ['data' => $data]);
    }
    public function get_data_detail_ekatalog($value)
    {
        $data  = Ekatalog::find($value);
        return view('page.penjualan.penjualan.detail_ekatalog', ['data' => $data]);
    }

    public function get_data_detail_spb($value)
    {
        $data  = Spb::with('Pesanan')
            ->where('id', $value)
            ->get();
        return view('page.penjualan.penjualan.detail_spb', ['data' => $data]);
    }

    public function get_data_detail_paket_spa($id)
    {
        $data  = DetailSpa::where('spa_id', $id)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('total', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<i class="fas fa-search"></i>';
            })
            ->rawColumns(['button',])
            ->make(true);
    }
    public function get_data_detail_paket_spb($id)
    {
        $data  = DetailSpb::where('spb_id', $id)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('total', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<i class="fas fa-search"></i>';
            })
            ->rawColumns(['button',])
            ->make(true);
    }
    public function get_data_detail_paket_ekatalog($id)
    {
        $data  = DetailEkatalog::where('ekatalog_id', $id)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('total', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<i class="fas fa-search"></i>';
            })
            ->rawColumns(['button',])
            ->make(true);
    }

    public function get_data_ekatalog_pengiriman()
    {
        $data  = Ekatalog::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('batas_kontrak', function ($data) {
                $x = 'sd';

                return  ' <hgroup>' . $data->tgl_kontrak .  '<small id="warning">' . $x . '</small> </hgroup>';
            })
            ->rawColumns(['batas_kontrak',])
            ->make(true);
    }
    public function get_data_ekatalog($value)
    {
        $x = explode(',', $value);

        if ($value == 0 || $value == 'kosong') {
            $data  = Ekatalog::with('pesanan')->get();
        } else {
            $data  = Ekatalog::with('pesanan')->whereIN('status', $x);
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($data) {
                if ($data->status == "sepakat") {
                    $status = '<span class="green-text badge">Sepakat</span>';
                } else if ($data->status == "negosiasi") {
                    $status =  '<span class="yellow-text badge">Negosiasi</span>';
                } else {
                    $status =  '<span class="red-text badge">Batal</span>';
                }

                return $status;
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                if ($data->status == 'sepakat' && $data->pesanan == '') {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Details
                    </button>
                </a>
                <a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'ekatalog']) . '" data-id="' . $data->id . '">                      
                    <button class="dropdown-item" type="button" >
                      <i class="fas fa-pencil-alt"></i>
                      Edit
                    </button>
                </a>
                <a href="' . route('penjualan.so.create', [$data->id]) . '" data-id="' . $data->id . '">                      
                <button class="dropdown-item" type="button" >
                <i class="fas fa-plus"></i>
                  Tambah PO
                </button>
            </a>
                </div>';
                } else {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Details
                    </button>
                </a>
                <a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'ekatalog']) . '" data-id="' . $data->id . '">                      
                    <button class="dropdown-item" type="button" >
                      <i class="fas fa-pencil-alt"></i>
                      Edit
                    </button>
                </a>
                </div>';
                }
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }
    public function get_data_spa()
    {
        $data  = Spa::with('pesanan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('tglpo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->tgl_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                if ($data->Pesanan) {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                    <button class="dropdown-item" type="button">
                          <i class="fas fa-search"></i>
                          Details
                        </button>
                    </a>
                    <a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">                      
                        <button class="dropdown-item" type="button" >
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                        </button>
                    </a>
                    </div>';
                } else {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                          <i class="fas fa-search"></i>
                          Details
                        </button>
                    </a>
                    <a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">                      
                        <button class="dropdown-item" type="button" >
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                        </button>
                    </a>
                    <a href="' . route('penjualan.so.create', [$data->id]) . '" data-id="' . $data->id . '">                      
                    <button class="dropdown-item" type="button" >
                    <i class="fas fa-plus"></i>
                      Tambah PO
                    </button>
                </a>
                    </div>';
                }
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    public function get_data_spb()
    {
        $data  = Spb::with('pesanan')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->so;
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('tglpo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->tgl_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                if ($data->Pesanan) {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '" >
                    <button class="dropdown-item" type="button">
                          <i class="fas fa-search"></i>
                          Details
                        </button>
                    </a>
                    <a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">                      
                        <button class="dropdown-item" type="button" >
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                        </button>
                    </a>
                    </div>';
                } else {
                    return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                          <i class="fas fa-search"></i>
                          Details
                        </button>
                    </a>
                    <a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">                      
                        <button class="dropdown-item" type="button" >
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                        </button>
                    </a>
                    <a href="' . route('penjualan.so.create', [$data->id]) . '" data-id="' . $data->id . '">                      
                    <button class="dropdown-item" type="button" >
                    <i class="fas fa-plus"></i>
                      Tambah PO
                    </button>
                </a>
                    </div>';
                }
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    public function get_data_so()
    {
        $data  = Pesanan::select()->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_customer', function ($data) {
                return $data->Ekatalog->Customer->nama;
            })
            ->addColumn('jenis', function ($data) {
                return '   <span class="badge purple-text">E-Catalogue</span>';
            })
            ->addColumn('status', function ($data) {
                return 'sepakat';
            })
            ->rawColumns(['jenis'])
            ->make(true);
    }


    //Create


    public function create_penjualan(Request $request)
    {
        if ($request->jenis_penjualan == 'ekatalog') {
            // $this->validate(
            //     $request,
            //     [
            //         'no_paket' => 'required',
            //         'customer_id' => 'required',
            //         'status' => 'required',
            //         'tgl_kontrak' => 'required',
            //         'jumlah.*' => 'required',
            //         'penjualan_produk_id.*' => 'required'
            //     ],
            //     [
            //         'no_paket.required' => 'No Paket harus di isi',
            //         'customer_id.required' => 'Customer harus di isi',
            //         'status.required' => 'Status harus di pilih',
            //         'tgl_kontrak.required' => 'Tg; Kontrak harus di isi',
            //         'jumlah.required' => 'Jumlah Produk harus di isi',
            //         'penjualan_produk_id.required' => 'Produk harus di pilih',
            //     ]
            // );


            //Konversi No SO
            // $x = Ekatalog::max('id') + 1;
            // $y = Carbon::now()->format('Y');
            // $m = Carbon::now()->format('m');
            // $filter = new IntToRoman();

            $Ekatalog = Ekatalog::create([
                'customer_id' => $request->customer_id,
                'no_paket' => 'AK1-' . $request->no_paket,
                'deskripsi' => $request->deskripsi,
                'provinsi_id' => $request->provinsi,
                'instansi' => $request->instansi,
                'alamat' => $request->alamatinstansi,
                'satuan' => $request->satuan_kerja,
                'status' => $request->status,
                'tgl_kontrak' => $request->batas_kontrak,
                'tgl_buat' => $request->tanggal_pemesanan,
                'ket' => $request->keterangan
            ]);
            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                DetailEkatalog::create([
                    'ekatalog_id' => $Ekatalog->id,
                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                    'jumlah' => $request->produk_jumlah[$i],
                    'harga' => str_replace('.', "", $request->produk_harga[$i]),
                    'ongkir' => 0,
                ]);
            }
        } elseif ($request->jenis_penjualan == 'spa') {


            if (!empty($request->input('no_po'))) {
                $pesanan = Pesanan::create([
                    'no_po' => $request->no_po,
                    'tgl_po' => $request->tanggal_po,
                    'no_do' => $request->no_do,
                    'tgl_do' => $request->tanggal_do,
                    'ket' =>  $request->keterangan,
                ]);
                $x = $pesanan->id;
            }

            $Spa = Spa::create([
                'customer_id' => $request->customer_id,
                'pesanan_id' => $x,
                'ket' => $request->keterangan
            ]);

            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                DetailSpa::create([
                    'spa_id' => $Spa->id,
                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                    'jumlah' => $request->produk_jumlah[$i],
                    'harga' => str_replace('.', "", $request->produk_harga[$i]),
                    'ongkir' => 0,
                ]);
            }
        } else {

            if (!empty($request->input('no_po'))) {
                $pesanan = Pesanan::create([
                    'no_po' => $request->no_po,
                    'tgl_po' => $request->tanggal_po,
                    'no_do' => $request->no_do,
                    'tgl_do' => $request->tanggal_do,
                    'ket' =>  $request->keterangan,
                ]);
                $x = $pesanan->id;
            }

            $Spb = Spb::create([
                'customer_id' => $request->customer_id,
                'pesanan_id' => $x,
                'ket' => $request->keterangan
            ]);

            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                DetailSpb::create([
                    'spb_id' => $Spb->id,
                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                    'jumlah' => $request->produk_jumlah[$i],
                    'harga' => str_replace('.', "", $request->produk_harga[$i]),
                    'ongkir' => 0,
                ]);
            }
        }
    }
    public function create_ekatalog(Request $request)
    {
        $this->validate(
            $request,
            [
                'no_paket' => 'required',
                'customer_id' => 'required',
                'status' => 'required',
                'tgl_kontrak' => 'required',
                'jumlah.*' => 'required',
                'penjualan_produk_id.*' => 'required'
            ],
            [
                'no_paket.required' => 'No Paket harus di isi',
                'customer_id.required' => 'Customer harus di isi',
                'status.required' => 'Status harus di pilih',
                'tgl_kontrak.required' => 'Tg; Kontrak harus di isi',
                'jumlah.required' => 'Jumlah Produk harus di isi',
                'penjualan_produk_id.required' => 'Produk harus di pilih',
            ]
        );
        $Ekatalog = Ekatalog::create([
            'customer_id' => $request->customer_id,
            'no_paket' => 'AK1-' . $request->no_paket,
            'deskripsi' => $request->deskripsi,
            'instansi' => $request->instansi,
            'satuan' => $request->satuan,
            'status' => $request->status,
            'tgl_kontrak' => $request->tgl_kontrak,
            'tgl_buat' => $request->tgl_buat,
            'ket' => $request->ket
        ]);
        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
            DetailEkatalog::create([
                'ekatalog_id' => $Ekatalog->id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->jumlah[$i],
                'harga' => $request->harga[$i],
                'ongkir' => $request->ongkir[$i],
            ]);
        }
    }
    public function create_spa(Request $request)
    {
        $this->validate(
            $request,
            [
                'customer_id' => 'required',
                'status' => 'required',
                'jumlah.*' => 'required',
                'penjualan_produk_id.*' => 'required'
            ],
            [
                'customer_id.required' => 'Customer harus di isi',
                'status.required' => 'Status harus di pilih',
                'jumlah.required' => 'Jumlah Produk harus di isi',
                'penjualan_produk_id.required' => 'Produk harus di pilih',
            ]
        );
        $Spa = Spa::create([
            'customer_id' => $request->customer_id,
            'status' => $request->status,
            'ket' => $request->ket
        ]);

        foreach ($request->produk_harga as $k => $v) {
            $r =  $request->produk_harga[$k] = str_replace('.', '', $v);
        }

        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {

            DetailSpa::create([
                'spa_id' => $Spa->id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->produk_jumlah[$i],
                'harga' => $r[$i],
                'ongkir' => $request->ongkir[$i],
            ]);
        }
    }
    public function create_spb(Request $request)
    {
        $this->validate(
            $request,
            [
                'customer_id' => 'required',
                'status' => 'required',
                'jumlah.*' => 'required',
                'penjualan_produk_id.*' => 'required'
            ],
            [
                'customer_id.required' => 'Customer harus di isi',
                'status.required' => 'Status harus di pilih',
                'jumlah.required' => 'Jumlah Produk harus di isi',
                'penjualan_produk_id.required' => 'Produk harus di pilih',
            ]
        );
        $Spb = Spb::create([
            'customer_id' => $request->customer_id,
            'status' => $request->status,
            'ket' => $request->ket
        ]);
        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
            DetailSpb::create([
                'spb_id' => $Spb->id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->jumlah[$i],
                'harga' => $request->harga[$i],
                'ongkir' => $request->ongkir[$i],
            ]);
        }
    }

    public function view_so_ekatalog($value)
    {
        $ekatalog = Ekatalog::find($value);
        return view('page.penjualan.so.create', ['ekatalog' => $ekatalog]);
    }
    public function create_so_ekatalog(Request $request, $id)
    {
        // $this->validate(
        //     $request,
        //     [w
        //         'customer_id' => 'required',
        //         'status' => 'required',
        //         'jumlah.*' => 'required',
        //         'penjualan_produk_id.*' => 'required'
        //     ],
        //     [
        //         'customer_id.required' => 'Customer harus di isi',
        //         'status.required' => 'Status harus di pilih',
        //         'jumlah.required' => 'Jumlah Produk harus di isi',
        //         'penjualan_produk_id.required' => 'Produk harus di pilih',
        //     ]
        // );
        $pesanan =  Pesanan::create([
            'no_po' => $request->no_po,
            'tgl_po' => $request->tanggal_po,
            'no_do' => $request->no_do,
            'tgl_do' => $request->tanggal_do,
            'ket' => $request->keterangan
        ]);
        $ekatalog = Ekatalog::find($id);
        $ekatalog->pesanan_id = $pesanan->id;
        $ekatalog->save();
    }
    //Update
    public function update_penjualan($id, $jenis)
    {
        if ($jenis == 'ekatalog') {
            $ekatalog = Ekatalog::with('DetailEkatalog')->where('id', $id)->get();
            return view('page.penjualan.penjualan.edit_ekatalog', ['ekatalog' => $ekatalog]);
        } else if ($jenis == 'spa') {
            $spa = Spa::with('DetailSpa')->where('id', $id)->get();
            return view('page.penjualan.penjualan.edit_spa', ['spa' => $spa]);
        } else {
            $spb = Spb::with('DetailSpb')->where('id', $id)->get();
            return view('page.penjualan.penjualan.edit_spb', ['spb' => $spb]);
        }
    }
    public function update_ekatalog(Request $request, $id)
    {
        $ekatalog = Ekatalog::find($id);
        $ekatalog->customer_id = $request->customer_id;
        $ekatalog->pesanan_id = $request->pesanan_id;
        $ekatalog->provinsi_id = $request->provinsi;
        $ekatalog->deskripsi = $request->deskripsi;
        $ekatalog->instansi = $request->instansi;
        $ekatalog->alamat = $request->alamatinstansi;
        $ekatalog->satuan = $request->satuan_kerja;
        $ekatalog->status = $request->status_akn;
        $ekatalog->ket = $request->keterangan;
        $ekatalog->save();


        DetailEkatalog::where('ekatalog_id', $id)->delete();
        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {

            DetailEkatalog::create([
                'ekatalog_id' => $id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->produk_jumlah[$i],
                'harga' => str_replace(".", "", $request->produk_harga[$i]),
                'ongkir' => 0,
            ]);
        }
    }
    public function update_spa(Request $request, $id)
    {
        $spa = Spa::find($id);
        $spa->customer_id = $request->customer_id;
        $spa->save();


        $pesanan = Pesanan::find($spa->pesanan_id);
        $pesanan->no_do = $request->no_do;
        $pesanan->tgl_do = $request->tanggal_do;
        $pesanan->ket = $request->keterangan;
        $pesanan->save();


        DetailSpa::where('spa_id', $id)->delete();

        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {

            DetailSpa::create([
                'spa_id' => $id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->produk_jumlah[$i],
                'harga' => str_replace(".", "", $request->produk_harga[$i]),
                'ongkir' => 0,
            ]);
        }
    }
    public function update_spb(Request $request, $id)
    {
        $spb = Spb::find($id);
        $spb->customer_id = $request->customer_id;
        $spb->save();


        $pesanan = Pesanan::find($spb->pesanan_id);
        $pesanan->no_do = $request->no_do;
        $pesanan->tgl_do = $request->tanggal_do;
        $pesanan->ket = $request->keterangan;
        $pesanan->save();


        DetailSpb::where('spb_id', $id)->delete();

        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {

            DetailSpb::create([
                'spb_id' => $id,
                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                'jumlah' => $request->produk_jumlah[$i],
                'harga' => str_replace(".", "", $request->produk_harga[$i]),
                'ongkir' => 0,
            ]);
        }
    }

    //Delete
    public function delete_detail_ekatalog($id)
    {
        $detail_ekatalog = DetailEkatalog::findOrFail($id);
        $detail_ekatalog->delete();
    }
    public function delete_detail_spa($id)
    {
        $detail_spa = DetailSpa::findOrFail($id);
        $detail_spa->delete();
    }
    public function delete_detail_spb($id)
    {
        $detail_spb = DetailSpb::findOrFail($id);
        $detail_spb->delete();
    }
    public function delete_ekatalog($id)
    {
        $ekatalog = Ekatalog::findOrFail($id);
        $ekatalog->delete();
    }
    public function delete_spa($id)
    {
        $ekatalog = Spa::findOrFail($id);
        $ekatalog->delete();
    }
    public function delete_spb($id)
    {
        $ekatalog = Spb::findOrFail($id);
        $ekatalog->delete();
    }


    //Laporan
    public function laporan(Request $request)
    {
        return Excel::download(new LaporanPenjualan($request->customer_id ?? '', $request->penjualan ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_penjualan.xlsx');
    }
}
