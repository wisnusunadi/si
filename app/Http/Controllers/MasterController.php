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
use Illuminate\Support\Carbon;
use Alert;
use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekspedisi;
use App\Models\Logistik;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Arr;
use App\Models\GudangKarantinaDetail;
use App\Models\GudangKarantinaNoseri;
use App\Models\Sparepart;
use App\Models\SparepartGudang;
use App\Models\UserLog;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnValueMap;

class MasterController extends Controller
{
    //Get Data Table
    public function  get_data_detail_ekspedisi($id)
    {
        $data = Logistik::where('ekspedisi_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if (isset($data->detaillogistik)) {
                    return $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->so;
                } else {
                    return $data->detaillogistikpart->first()->DetailPesananPart->pesanan->so;
                }
            })
            ->addColumn('sj', function ($data) {
                return $data->nosurat;
            })
            ->addColumn('no_resi', function ($data) {
                if (!empty($data->no_resi)) {
                    return $data->no_resi;
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl', function ($data) {
                return $data->tgl_kirim;
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->detaillogistik)) {
                    $name = explode('/', $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return   $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->ekatalog->customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return  $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->spa->customer->nama;
                    }
                } else {
                    return  $data->detaillogistikpart->first()->detailpesananpart->pesanan->spb->customer->nama;
                }
                return;
            })
            ->addColumn('alamat', function ($data) {
                if (isset($data->detaillogistik)) {
                    $name = explode('/', $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return   $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->ekatalog->customer->alamat;
                    } elseif ($name[1] == 'SPA') {
                        return  $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->spa->customer->alamat;
                    }
                } else {
                    return  $data->detaillogistikpart->first()->DetailPesananPart->pesanan->spb->customer->alamat;
                }
                return;
            })
            ->addColumn('telp', function ($data) {
                if (isset($data->detaillogistik)) {
                    $name = explode('/', $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return   $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->ekatalog->customer->telp;
                    } elseif ($name[1] == 'SPA') {
                        return  $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->spa->customer->telp;
                    }
                } else {
                    return $data->detaillogistikpart->first()->DetailPesananPart->pesanan->spb->customer->telp;
                }
                return;
            })
            ->addColumn('status', function ($data) {
                if ($data->status_id == "10") {
                    return '<span class="badge blue-text">Selesai</span>';
                } else {
                    return '<span class="badge red-text">Belum Kirim</span>';
                }
                // $y = array();
                // $count = 0;
                // $x = DetailPesananProduk::where('pesanan_id', $data->detaillogistik->DetailPesananProduk->detailpesanan->pesanan->id)->get();
                // foreach ($x  as $d) {
                //     $y[] = $d->id;
                //     $count++;
                // }
                // $detail_logistik  = DetailLogistik::whereIN('detail_pesanan_id', $y)->get()->Count();

                // if ($count == $detail_logistik) {
                //     return  '<span class="badge green-text">Sudah Dikirim</span>';
                // } else {
                //     if ($detail_logistik == 0) {
                //         return ' <span class="badge red-text">Belum Dikirim</span>';
                //     } else {
                //         return  '<span class="badge yellow-text">Sebagian Dikirim</span>';
                //     }
                // }
            })
            ->addColumn('button', function ($data) {
                $return = "";
            })
            ->rawColumns(['status'])
            ->make(true);
    }
    public function get_data_ekspedisi($value1, $value2)
    {
        $x = explode(',', $value1);
        $divisi_id = auth()->user()->divisi->id;

        if ($value1 == 'semua' && $value2 == 'semua' || $value1 == 'kosong' && $value2 == 'semua') {
            $data = Ekspedisi::orderby('nama', 'ASC')->get();
        } else if ($value1 == 'kosong' && $value2 == '1') {
            $data = Ekspedisi::Has('JalurEkspedisi')->whereHas('Provinsi', function ($q) {
                $q->where('status', 1);
            })->orderby('nama', 'ASC')->get();
        } else if ($value1 == 'kosong' && $value2 == '2') {
            $data = Ekspedisi::Has('JalurEkspedisi')->whereHas('Provinsi', function ($q) {
                $q->where('status', 2);
            })->orderby('nama', 'ASC')->get();
        } else if ($value1 != 'kosong' && $value2 == 'kosong' ||  $value2 == 'semua') {
            $data = Ekspedisi::whereHas('JalurEkspedisi', function ($q) use ($x) {
                $q->whereIN('nama', $x);
            })->orderby('nama', 'ASC')->get();
        } else if ($value1 != 'kosong' && $value2 != 'kosong') {
            $data = Ekspedisi::whereHas('JalurEkspedisi', function ($q) use ($x) {
                $q->whereIN('nama', $x);
            })->whereHas('Provinsi', function ($q) use ($value2) {
                $q->where('status', $value2);
            })->orderby('nama', 'ASC')->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jurusan', function ($data) {
                return implode(', ', $data->provinsi->pluck('nama')->toArray());
            })
            ->addColumn('via', function ($data) {
                $list = array();
                foreach ($data->jalurekspedisi as $s) {
                    $list[] = '<div class="badge ' . $s->nama . '-text">' . ucfirst($s->nama) . '</div>';
                }
                return implode('<br>', $list);
            })
            ->addColumn('button', function ($data) use ($divisi_id) {
                $return = "";
                $return .= '
            <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="' . route('logistik.ekspedisi.detail', ['id' => $data->id]) . '">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-search"></i>
                        Detail
                    </button>
                </a>';
                if ($divisi_id == "15") {
                    $x = array();
                    $y = array();

                    $return .= '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-id="' . $data->id . '" data-value="';
                    foreach ($data->jalurekspedisi as $s) {
                        $x[] = $s->id;
                    }
                    $return .= implode(',', $x);
                    $return .= '" data-provinsi="';

                    foreach ($data->provinsi as $u) {
                        $y[] = $u->id;
                    }
                    $return .= implode(',', $y);

                    $return .= '">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                    </button>
                </a>';
                }
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['button', 'jurusan', 'via'])
            ->make(true);
    }
    public function get_data_produk()
    {
        return datatables()->of(Produk::with('KelompokProduk'))->toJson();
    }
    public function get_data_customer($divisi_id, $value)
    {
        $divisi = $divisi_id;
        $x = explode(',', $value);
        if ($value == 0 || $value == 'kosong') {
            $data = Customer::WhereNotIN('id', ['484'])->orderby('nama', 'ASC')->get();
        } else {
            $data = Customer::WhereNotIN('id', ['484'])->whereHas('Provinsi', function ($q) use ($x) {
                $q->whereIN('status', $x);
            })->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('prov', function ($data) {
                return $data->provinsi->nama;
            })
            ->addColumn('button', function ($data) use ($divisi) {

                $datas = "";
                $datas .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="' . route('penjualan.customer.detail', $data->id) . '">
                    <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Detail
                    </button>
                </a>';
                if ($divisi == "26") {
                    $datas .= '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button" >
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                        </button>
                    </a>';
                    $datas .= '<a data-toggle="modal" data-target="#hapusmodal" class="hapusmodal" data-attr=""  data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button" >
                        <i class="fas fa-trash-alt"></i>
                        Hapus
                        </button>
                    </a>';
                }
                $datas .= '</div>';
                return $datas;
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    public function get_data_penjualan_produk($value, $min, $max)
    {
        $x = explode(',', $value);
        if ($value == 'kosong' && $min == 'kosong' && $max == 'kosong') {
            $data = PenjualanProduk::select();
        } else if ($value == 'kosong' && $min != 'kosong'  && $max == 'kosong') {
            $data = PenjualanProduk::Has('Produk')->where('harga', '>=', $min)->orderby('harga', 'ASC')->get();
        } else if ($value == 'kosong' && $min != 'kosong'  && $max != 'kosong') {
            $data = PenjualanProduk::Has('Produk')->whereBetween('harga', array($min, $max))->orderby('harga', 'ASC')->get();
        } else if ($value != 'kosong' && $min != 'kosong'  && $max != 'kosong') {
            $data = PenjualanProduk::whereHas('Produk', function ($q) use ($x) {
                $q->whereIN('kelompok_produk_id', $x);
            })->whereBetween('harga', array($min, $max))->orderby('harga', 'ASC')->get();
        } else if ($value != 'kosong' && $min != 'kosong'  && $max == 'kosong') {
            $data = PenjualanProduk::whereHas('Produk', function ($q) use ($x) {
                $q->whereIN('kelompok_produk_id', $x);
            })->where('harga', '>=', $min)->orderby('harga', 'ASC')->get();
        } else if ($value != 'kosong' && $min == 'kosong') {
            $data = PenjualanProduk::whereHas('Produk', function ($q) use ($x) {
                $q->whereIN('kelompok_produk_id', $x);
            })->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('nama', function ($data) {
                return $data->nama;
            })
            ->addColumn('no_akd', function ($data) {
                $id = $data->id;
                $s = Produk::where('coo', '1')->whereHas('PenjualanProduk', function ($q) use ($id) {
                    $q->where('id', $id);
                })->first();
                return $s->no_akd;
            })
            ->addColumn('merk', function ($data) {
                $id = $data->id;
                $s = Produk::where('coo', '1')->whereHas('PenjualanProduk', function ($q) use ($id) {
                    $q->where('id', $id);
                })->first();
                return $s->merk;
            })
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
                    <a data-toggle="modal" data-target="#hapusmodal" class="hapusmodal" data-attr=""  data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button" >
                        <i class="fas fa-trash-alt"></i>
                        Hapus
                        </button>
                    </a>
                </div>';
            })
            ->rawColumns(['nama', 'button'])
            ->make(true);
    }
    public function get_nama_customer($id, $val)
    {
        if ($id != "0") {
            $c = Customer::where('nama', $val)->whereNotIn('id', [$id])->count();
            return $c;
        } else {
            $c = Customer::where('nama', $val)->count();
            return $c;
        }
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
        // $data = Produk::whereHas('PenjualanProduk', function ($q) use ($id) {
        //     $q->where('id', $id);
        // })->get();

        $data = array();
        $c = 0;
        $datas = PenjualanProduk::find($id);
        foreach ($datas->Produk as $i) {
            $data[$c]['id'] = $i->id;
            $data[$c]['nama'] = $i->nama;
            $data[$c]['kelompok_produk'] = $i->KelompokProduk->nama;
            $data[$c]['jumlah'] = $i->pivot->jumlah;
            $c++;
        }

        //$data = PenjualanProduk::with('produk')->where('id', $id)->get();
        // $data = PenjualanProduk::with('Produk')->selectRaw('distinct penjualan_produk.*')->where('id', '5')->get();
        return datatables()->of($data)
            ->addColumn('nama', function ($data) {
                return $data['nama'];
            })
            ->addColumn('kelompok', function ($data) {
                $return = "";
                if ($data['kelompok_produk'] == 'Alat Kesehatan') {
                    $return .= '<span class="badge blue-text">';
                } else if ($data['kelompok_produk'] == 'Water Treatment') {
                    $return .= '<span class="badge orange-text">';
                } else {
                    $return .= '<span class="badge purple-text">';
                }
                $return .= $data['kelompok_produk'];
                $return .= '</span>';

                return $return;
            })
            ->addColumn('jumlah', function ($data) {
                return $data['jumlah'];
            })
            ->addIndexColumn()
            ->rawColumns(['kelompok'])
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

        if ($data){
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
                        if (empty($data->Pesanan->tgl_po) || $data->Pesanan->tgl_po == "0000-00-00") {
                            return '-';
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->Pesanan->State->nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->Pesanan->State->nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    } else {
                        $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                    }

                    return $datas;
                })
                ->addColumn('button', function ($data) {
                    $name =  $data->getTable();

                    if ($name == 'ekatalog') {
                        return  '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                                  <i class="fas fa-search"></i>
                            </a>';
                    } else if ($name == 'spa') {
                        return  '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                                  <i class="fas fa-search"></i>
                            </a>';
                    } else {
                        return  '
                            <a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                                  <i class="fas fa-search"></i>
                            </a>';
                    }
                })
                ->rawColumns(['status', 'button'])
                ->make(true);
            }
    }

    public function get_data_master_produk(){
        $data = Produk::select();
        return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('kelompok_produk', function ($data) {
                    $res = "";
                    if($data->KelompokProduk->nama == 'Alat Kesehatan'){
                        $res .= '<span class="badge blue-text">';
                    } else if($data->KelompokProduk->nama == 'Water Treatment'){
                        $res .= '<span class="badge orange-text">';
                    } else if($data->KelompokProduk->nama == 'Aksesoris'){
                        $res .= '<span class="badge purple-text">';
                    } else if($data->KelompokProduk->nama == 'Lain Lain'){
                        $res .= '<span class="badge red-text">';
                    } else if($data->KelompokProduk->nama == 'Sparepart'){
                        $res .= '<span class="badge green-text">';
                    }
                    $res .= $data->KelompokProduk->nama.'</span>';
                    return $res;
                })
                ->addColumn('merk', function ($data) {
                    return $data->merk;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->nama_coo;
                })
                ->addColumn('tipe', function ($data) {
                    return $data->nama;
                })
                ->addColumn('no_akd', function ($data) {
                    return $data->no_akd;
                })
                ->addColumn('coo', function ($data) {
                    if($data->coo == "1"){
                        return '<span class="badge green-text">Produk Utama</span>';
                    } else {
                        return '<span class="badge red-text">Bukan Produk Utama</span>';
                    }
                })
                ->addColumn('aksi', function ($data) {
                    if(Auth::user()->divisi->id == '9'){
                        return '<a data-toggle="modal" class="editmodal" data-attr="' . route('master.produk.edit_coo',  $data->id) . '">
                            <i class="fas fa-edit info"></i>
                        </a>';
                    }
                })
                ->rawColumns(['kelompok_produk', 'coo', 'aksi'])
                ->make(true);
    }

    //Create
    public function create_ekspedisi(Request $request)
    {
        // $this->validate(
        //     $request,
        //     [
        //         'nama' => 'required',
        //         'jalur' => 'required',
        //         'tipe' => 'required|unique:produk',

        //     ],
        //     [
        //         'nama.required' => 'Kelompok Produk harus di isi',
        //         'jalur.required' => 'Merk Produk harus di isi',
        //         'tipe.required' => 'Tipe Produk harus di isi',
        //     ]
        // );
        $bool = true;
        $ekspedisi =  Ekspedisi::create([
            'nama' => $request->nama_ekspedisi,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'telp' => $request->telepon,
            'ket' => $request->keterangan,
        ]);
        if ($ekspedisi) {
            $ekspedisi->JalurEkspedisi()->attach($request->jalur);

            if ($request->jurusan == 'provinsi') {
                $ekspedisi->Provinsi()->attach($request->provinsi);
            } else {
                $ekspedisi->Provinsi()->attach(35);
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            // Alert::success('Berhasil', 'Berhasil menambahkan data');
            return redirect()->back()->with('success', 'success');
        } else {
            return redirect()->back()->with('error', 'error');
        }
    }
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
            'ktp' => $request->ktp,
            'npwp' => $request->npwp,
            'batas' => $request->batas,
            'pic' => $request->pic,
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


    //Edit
    public function edit_coo_data_produk($id){
        $data = Produk::find($id);
        return view('page.master.produk.edit_coo', ['id' => $id, 'data' => $data]);
    }

    //Update
    public function update_customer(Request $request, $id)
    {
        $customer = Customer::find($id);
        $customer->id_provinsi = $request->provinsi;
        $customer->nama = $request->nama_customer;
        $customer->npwp = $request->npwp;
        $customer->ktp = $request->ktp;
        $customer->pic = $request->pic;
        $customer->batas = $request->batas;
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

    public function update_coo_master_produk(Request $request, $id){
        $p = Produk::find($id);
        $p->no_akd = $request->no_akd;
        $p->nama_coo = $request->nama_coo;
        $p->coo = $request->coo;
        $u = $p->save();
        if ($u) {
            return response()->json(['data' => 'success']);
        } else if (!$u) {
            return response()->json(['data' => 'error']);
        }
    }

    //Delete
    public function delete_produk($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
    }
    public function delete_customer($id)
    {
        $customer = Customer::find($id);
        $customer->delete();

        if ($customer) {
            return response()->json(['data' => 'success']);
        } else {
            return response()->json(['data' => 'error']);
        }
    }
    public function delete_penjualan_produk($id)
    {
        $penjualanproduk = PenjualanProduk::find($id);


        $detail_pesanan = DetailPesanan::where('penjualan_produk_id', $penjualanproduk->id)->get();
        $bool = '';
        if (count($detail_pesanan) <= 0) {
            $bool = 1;
            $produk_id = [];
            foreach ($penjualanproduk->produk as $p) {
                $produk_id[] = $p->id;
            }

            $x =  $penjualanproduk->produk()->detach($produk_id);
            $y = $penjualanproduk->delete();

            if ($x && $y) {
                $bool = 1;
            } else {
                $bool = 2;
            }
        } else {
            $bool = 0;
        }

        if ($bool == 1) {
            return response()->json(['data' => 'success']);
        } else if ($bool == 2) {
            return response()->json(['data' => 'error']);
        } else {
            return response()->json(['data' => 'warning']);
        }
    }
    public function delete_detail_penjualan_produk($id)
    {
        // $produk = DetailPenjualanProduk::findOrFail($id);
        // $produk->delete();
    }

    public function update_penjualan_produk(Request $request, $id)
    {
        $harga_convert =  str_replace(['.', ','], "", $request->harga);
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
    public function update_ekspedisi(Request $request, $id)
    {
        $Ekspedisi = Ekspedisi::find($id);
        $Ekspedisi->nama = $request->nama_ekspedisi;
        $Ekspedisi->alamat = $request->alamat;
        $Ekspedisi->email = $request->email;
        $Ekspedisi->telp = $request->telepon;
        $Ekspedisi->ket = $request->keterangan;
        $Ekspedisi->save();


        if ($request->jurusan == 'indonesia') {
            $p = $Ekspedisi->Provinsi()->sync('35');
        } else {
            $provinsi_array = [];
            for ($i = 0; $i < count($request->provinsi_id); $i++) {
                $provinsi_array[] = $request->provinsi_id[$i];
            }
            $p = $Ekspedisi->Provinsi()->sync($provinsi_array);
        }


        if ($p) {
            $jalur_array = [];
            for ($i = 0; $i < count($request->jalur); $i++) {
                $jalur_array[] = $request->jalur[$i];
            }
            $q = $Ekspedisi->JalurEkspedisi()->sync($jalur_array);
        }

        if ($q) {
            return response()->json(['data' => 'success']);
        } else if (!$q) {
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
    public function check_penjualan_produk($id, $value)
    {
        if ($id != "0") {
            $data = PenjualanProduk::where('nama', $value)->whereNotIn('id', [$id])->count();
            return response()->json(['jumlah' => $data]);
        } else {
            $data = PenjualanProduk::where('nama', $value)->count();
            return response()->json(['jumlah' => $data]);
        }
    }

    //Show Modal
    public function update_customer_modal($id)
    {
        $customer = Customer::find($id);
        return view("page.penjualan.customer.edit", ['customer' => $customer]);
    }
    public function update_ekspedisi_modal($id)
    {
        $ekspedisi = Ekspedisi::where('id', $id)->get();
        return view("page.logistik.ekspedisi.edit", ['ekspedisi' => $ekspedisi]);
    }


    //Show Detail

    public function detail_customer($id)
    {
        $customer = Customer::find($id);
        return view('page.penjualan.customer.detail', ['customer' => $customer]);
    }
    public function detail_ekspedisi($id)
    {
        $ekspedisi = Ekspedisi::where('id', $id)->get();
        return view('page.logistik.ekspedisi.detail', ['ekspedisi' => $ekspedisi]);
    }

    //Select
    public function select_produk(Request $request)
    {
        $data = Produk::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->orderby('nama', 'ASC')->get();
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
            ->WhereNotIN('id', ['484'])
            ->orderby('nama', 'ASC')->get();
        echo json_encode($data);
    }
    public function select_provinsi(Request $request)
    {
        $data = Provinsi::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->whereNotin('id', ['35'])
            ->orderby('nama', 'ASC')->get();
        echo json_encode($data);
    }
    public function select_provinsi_edit(Request $request)
    {
        $data = Provinsi::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->whereNotin('id', ['35'])
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
        return response()->json($data);
    }
    public function select_penjualan_produk_id($id)
    {
        $data = PenjualanProduk::with('Produk.GudangBarangJadi')->where('id', $id)
            ->get();

        echo json_encode($data);
    }
    public function check_no_akd($id, $val){
        $data = Produk::where('no_akd', $val)->whereNotIn('id', $id)->count();
        return $data;
    }

    function select_m_sparepart(Request $request)
    {
        $data = Sparepart::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->orderby('nama', 'ASC')
            ->get();
        return response()->json($data);
    }

    function select_sparepart()
    {
        $data = SparepartGudang::all();
        return response()->json($data);
    }

    function select_gk_spr()
    {
        $data = GudangKarantinaDetail::select('t_gk_detail.sparepart_id', 'm_gs.nama')
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        return $data;
    }

    function select_gk_unit()
    {
        $data = GudangKarantinaDetail::select('t_gk_detail.gbj_id', DB::raw('CONCAT(produk.nama," ",gdg_barang_jadi.nama) as name'))
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
            ->get();
        // $data = GudangKarantinaDetail::with('units.produk')->groupBy('gbj_id')->where('is_draft',0)->where('is_keluar', 0)->whereNotNull('gbj_id')->get()->pluck('gbj_id', 'units.produk.nama');
        return $data;
    }
    public function select_ekspedisi(Request $request, $provinsi)
    {
        $data = "";
        if ($provinsi != "0") {
            $data = Ekspedisi::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
                ->orderby('nama', 'ASC')->whereHas('Provinsi', function ($q) use ($provinsi) {
                    $q->where('id', $provinsi);
                    $q->Orwhere('id', 35);
                })->get();
        } else {
            $data = Ekspedisi::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
                ->orderby('nama', 'ASC')->get();
        }
        echo json_encode($data);
    }

    function select_gk_layout()
    {
        $data = GudangKarantinaNoseri::with('layout')->groupBy('layout_id')->whereNotNull('layout_id')->get();
        return $data;
    }

    function create_user_log(Request $request)
    {
        $row = new UserLog();
        $row->user_id = $request->userid;
        $row->user_nama = $request->usernama;
        $row->subjek = $request->subjek;
        $row->table = $this->table();
        $row->keterangan = $request->keterangan;
        $row->aksi = $request->aksi;
        $row->created_at = Carbon::now();
        $row->save();
    }
}
