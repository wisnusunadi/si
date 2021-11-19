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
use Carbon\Doctrine\CarbonType;
use Hamcrest\Core\IsNot;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;
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
    public function get_lacak_penjualan($parameter, $value)
    {

        if ($parameter == 'no_po') {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) use ($value) {
                $q->where('no_po', 'LIKE', '%' . $value . '%');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) use ($value) {
                $q->where('no_po', 'LIKE', '%' . $value . '%');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) use ($value) {
                $q->where('no_po', 'LIKE', '%' . $value . '%');
            })->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_customer', function ($data) {
                    return $data->Customer->nama;
                })
                ->addColumn('no_po', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tgl_po', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->tgl_po;
                    } else {
                        return '';
                    }
                })
                ->addColumn('noseri', function () {
                    return '';
                })
                ->addColumn('log', function ($data) {
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
                ->rawColumns(['log'])
                ->make(true);
        } elseif ($parameter == 'no_akn') {
            $data = Ekatalog::where('no_paket', 'LIKE', '%' . $value . '%')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    if ($data->status == "draft") {
                        $status = '<span class="badge blue-text">Draft</span>';
                    } else if ($data->status == "sepakat") {
                        $status = '<span class="green-text badge">Sepakat</span>';
                    } else if ($data->status == "negosiasi") {
                        $status =  '<span class="yellow-text badge">Negosiasi</span>';
                    } else {
                        $status =  '<span class="red-text badge">Batal</span>';
                    }
                    return $status;
                })
                ->addColumn('log', function ($data) {
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
                ->rawColumns(['status', 'log'])
                ->make(true);
        } elseif ($parameter == 'no_seri') {
        } elseif ($parameter == 'no_so') {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) use ($value) {
                $q->where('so', 'LIKE', '%' . $value . '%');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) use ($value) {
                $q->where('so', 'LIKE', '%' . $value . '%');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) use ($value) {
                $q->where('so', 'LIKE', '%' . $value . '%');
            })->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_customer', function ($data) {
                    return $data->Customer->nama;
                })
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->so;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tgl_po', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->tgl_po;
                    } else {
                        return '';
                    }
                })
                ->addColumn('noseri', function () {
                    return '';
                })
                ->addColumn('log', function ($data) {
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
                ->rawColumns(['log'])
                ->make(true);
        } elseif ($parameter == 'no_sj') {
        }
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
        $data  = DetailEkatalog::with('gudangbarangjadi')->where('ekatalog_id', $id)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('variasi', function ($data) {
                return implode(',', $data->gudangbarangjadi->pluck('nama')->toArray());

                //return implode(',', $data->detailekatalogproduk->gudangbarangjadi->nama);
            })
            ->addColumn('total', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<i class="fas fa-search"></i>';
            })
            ->rawColumns(['button', 'variasi'])
            ->make(true);
    }
    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '14';
        } else {
            $days = '21';
        }
        return Carbon::parse($value)->subDays($days);
    }
    public function get_data_ekatalog_pengiriman()
    {
        $data  = Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->orderBy('tgl_kontrak', 'ASC')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->so;
                } else {
                    return '';
                }
            })
            ->addColumn('no_po', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
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
            ->addColumn('batas_kontrak', function ($data) {

                $tgl_sekarang = Carbon::now()->format('Y-m-d');
                $tgl_parameter = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status)->format('Y-m-d');

                if ($tgl_sekarang < $tgl_parameter) {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                    $hari = $to->diffInDays($from);
                    if ($hari > 7) {
                        return  '<div> ' . $tgl_parameter . '</div>
                        <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>
                        ';
                    } else if ($hari > 0 && $hari <= 7) {
                        return  '<div>' . $tgl_parameter . '</div>
                        <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>
                        ';
                    } else {
                        return  '<div>' . $tgl_parameter . '</div>
                        <div class="invalid-feedback d-block"><small><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</small></div>
                        ';
                    }
                } elseif ($tgl_sekarang == $tgl_parameter) {
                    return  '<div>' . $tgl_parameter . '</div>
                    <div class="invalid-feedback d-block"><small><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</small></div>
                    ';
                } else {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                    $hari = $to->diffInDays($from);
                    return '<div>' . $tgl_parameter . '</div>
                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>
                    ';
                }
            })
            ->addColumn('button', function ($data) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Details
                    </button>
                </a>
                </div>';
            })
            ->rawColumns(['batas_kontrak', 'button', 'status'])
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
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->so;
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->status == "draft") {
                    $status = '<span class="blue-text badge">Draft</span>';
                } else if ($data->status == "sepakat") {
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
            ->rawColumns(['button', 'status'])
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
            ->rawColumns(['button', 'status'])
            ->make(true);
    }
    public function get_data_so()
    {
    }


    // Create
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
                'ket' => $request->keterangan,
                'log' => 'penjualan'
            ]);
            $bool = true;
            if ($Ekatalog) {
                if ($request->status != 'draft') {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $dekat = DetailEkatalog::create([
                            'ekatalog_id' => $Ekatalog->id,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);
                        for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                            $dekat->GudangBarangJadi()->attach($request->variasi[$i][$j], ['jumlah' => 1]);
                        }
                        if (!$dekat) {
                            $bool = false;
                        }
                    }
                } else {
                    $bool = true;
                }
            } else {
                $bool = false;
            }
            if ($bool == true) {
                return redirect()->back()->with('success', 'Berhasil menambahkan Ekatalog');
            } else if ($bool == false) {
                return redirect()->back()->with('error', 'Gagal menambahkan Ekatalog');
            }
        } elseif ($request->jenis_penjualan == 'spa') {
            if (!empty($request->input('no_po'))) {
                $pesanan = Pesanan::create([
                    'so' => $this->createSO('SPA'),
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
                'ket' => $request->keterangan,
                'log' => 'penjualan'
            ]);
            $bool = true;
            if ($Spa) {
                for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                    $dspa = DetailSpa::create([
                        'spa_id' => $Spa->id,
                        'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                        'jumlah' => $request->produk_jumlah[$i],
                        'harga' => str_replace('.', "", $request->produk_harga[$i]),
                        'ongkir' => 0,
                    ]);
                    if (!$dspa) {
                        $bool = false;
                    }
                }
            } else {
                $bool = false;
            }

            if ($bool == true) {
                return redirect()->back()->with('success', 'Berhasil menambahkan SPA');
            } else if ($bool == false) {
                return redirect()->back()->with('error', 'Gagal menambahkan SPA');
            }
        } else {

            if (!empty($request->input('no_po'))) {
                $pesanan = Pesanan::create([
                    'so' => $this->createSO('SPB'),
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
                'ket' => $request->keterangan,
                'log' => 'penjualan'
            ]);
            $bool = true;
            if ($Spb) {
                for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                    $dspb = DetailSpb::create([
                        'spb_id' => $Spb->id,
                        'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                        'jumlah' => $request->produk_jumlah[$i],
                        'harga' => str_replace('.', "", $request->produk_harga[$i]),
                        'ongkir' => 0,
                    ]);
                    if (!$dspb) {
                        $bool = false;
                    }
                }
            } else {
                $bool = false;
            }

            if ($bool == true) {
                return redirect()->back()->with('success', 'Berhasil menambahkan SPB');
            } else if ($bool == false) {
                return redirect()->back()->with('error', 'Gagal menambahkan SPB');
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
        $v = "";
        // Validator::make(
        //     $request->all(),
        //     [
        //         'customer_id' => 'required',
        //         'status' => 'required',
        //     ],
        //     [
        //         'customer_id.required' => 'Customer harus di isi',
        //         'status.required' => 'Status harus di pilih',
        //     ]
        // );

        // if ($v->fails()) {
        //     return redirect()->back()->withErrors($v);
        // } else {
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
        $bool = true;
        $pesanan =  Pesanan::create([
            'so' => $this->createSO('EKAT'),
            'no_po' => $request->no_po,
            'tgl_po' => $request->tanggal_po,
            'no_do' => $request->no_do,
            'tgl_do' => $request->tanggal_do,
            'ket' => $request->keterangan
        ]);
        if (!$pesanan) {
            $bool = false;
        } else {
            $ekatalog = Ekatalog::find($id);
            $ekatalog->pesanan_id = $pesanan->id;
            $ekat = $ekatalog->save();
            if (!$ekat) {
                $bool = false;
            }
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'Berhasil menambahkan PO');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Gagal menambahkan PO');
        }
        // }
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
        $ekat = $ekatalog->save();
        $bool = true;
        if ($ekat) {
            $dekat = DetailEkatalog::where('ekatalog_id', $id)->delete();
            if ($dekat) {
                if ($request->status != "draft") {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $cdekat = DetailEkatalog::create([
                            'ekatalog_id' => $id,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace(".", "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);
                        if (!$cdekat) {
                            $bool = false;
                        }
                    }
                }
            } else {
                $bool = false;
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'Berhasil mengubah Ekatalog');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Gagal mengubah Ekatalog');
        }
    }
    public function update_spa(Request $request, $id)
    {
        $spa = Spa::find($id);
        $spa->customer_id = $request->customer_id;
        $uspa = $spa->save();
        $bool = true;
        if ($uspa) {
            $pesanan = Pesanan::find($spa->pesanan_id);
            $pesanan->no_do = $request->no_do;
            $pesanan->tgl_do = $request->tanggal_do;
            $pesanan->ket = $request->keterangan;
            $po = $pesanan->save();

            if ($po) {
                $dspa = DetailSpa::where('spa_id', $id)->delete();
                if ($dspa) {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $cdspa = DetailSpa::create([
                            'spa_id' => $id,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace(".", "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);
                        if (!$cdspa) {
                            $bool = false;
                        }
                    }
                } else {
                    $bool = false;
                }
            } else {
                $bool = false;
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'Berhasil mengubah SPA');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Gagal mengubah SPA');
        }
    }
    public function update_spb(Request $request, $id)
    {
        $spb = Spb::find($id);
        $spb->customer_id = $request->customer_id;
        $uspb = $spb->save();
        $bool = true;
        if ($uspb) {
            $pesanan = Pesanan::find($spb->pesanan_id);
            $pesanan->no_do = $request->no_do;
            $pesanan->tgl_do = $request->tanggal_do;
            $pesanan->ket = $request->keterangan;
            $po = $pesanan->save();

            if ($po) {
                $dspb = DetailSpb::where('spb_id', $id)->delete();
                if ($dspb) {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $cdspb = DetailSpb::create([
                            'spb_id' => $id,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace(".", "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);

                        if (!$cdspb) {
                            $bool = true;
                        }
                    }
                } else {
                    $bool = false;
                }
            } else {
                $bool = false;
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'Berhasil mengubah SPB');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Gagal mengubah SPB');
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
    public function  get_data_laporan_penjualan($penjualan, $distributor, $tanggal_awal, $tanggal_akhir)
    {
        if ($penjualan == 'ekatalog') {

            if ($distributor == 'semua') {
                $data  = DetailEkatalog::whereHas('Ekatalog.Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else {
                $data  = DetailEkatalog::whereHas('Ekatalog.Pesanan', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);;
                })->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Ekatalog->Pesanan->so;
                })
                ->addColumn('no_paket', function ($data) {
                    return $data->Ekatalog->no_paket;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->Ekatalog->Pesanan->no_po;
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Ekatalog->Customer->nama;
                })
                ->addColumn('tgl_kontrak', function ($data) {
                    return $data->Ekatalog->tgl_kontrak;
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    return $data->Ekatalog->Pesanan->tgl_po;
                })
                ->addColumn('instansi', function ($data) {
                    return $data->Ekatalog->instansi;
                })
                ->addColumn('satuan', function ($data) {
                    return $data->Ekatalog->satuan;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->penjualanproduk->nama;
                })
                ->addColumn('no_seri', function () {
                    return '-';
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->jumlah;
                })
                ->addColumn('harga', function ($data) {
                    return $data->harga;
                })
                ->addColumn('subtotal', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('total', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('log', function () {
                    return '-';
                })
                ->addColumn('kosong', function () {
                    return '';
                })
                ->make(true);
        } elseif ($penjualan == 'spa') {
            if ($distributor == 'semua') {
                $data  = DetailSpa::whereHas('Spa.Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else {
                $data  = DetailSpa::whereHas('Spa.Pesanan', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Spa->Pesanan->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->Spa->Pesanan->no_po;
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Spa->Customer->nama;
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    return $data->Spa->Pesanan->tgl_po;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->penjualanproduk->nama;
                })
                ->addColumn('no_seri', function () {
                    return '-';
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->jumlah;
                })
                ->addColumn('harga', function ($data) {
                    return $data->harga;
                })
                ->addColumn('subtotal', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('total', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('log', function ($data) {
                    return '-';
                })
                ->addColumn('kosong', function () {
                    return '';
                })
                ->make(true);
        } elseif ($penjualan == 'spb') {
            if ($distributor == 'semua') {
                $data  = DetailSpb::whereHas('Spb.Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else {
                $data  = DetailSpb::whereHas('Spb.Pesanan', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Spb->Pesanan->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->Spb->Pesanan->no_po;
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Spb->Customer->nama;
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    return $data->Spb->Pesanan->tgl_po;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->penjualanproduk->nama;
                })
                ->addColumn('no_seri', function () {
                    return '-';
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->jumlah;
                })
                ->addColumn('harga', function ($data) {
                    return $data->harga;
                })
                ->addColumn('subtotal', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('total', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('log', function ($data) {
                    return '-';
                })
                ->addColumn('kosong', function () {
                    return '';
                })
                ->make(true);
        } else {
            if ($distributor == 'semua') {
                $Ekatalog = collect(DetailEkatalog::whereHas('Ekatalog.Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spa = collect(DetailSpa::whereHas('Spa.Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spb = collect(DetailSpb::whereHas('Spb.Pesanan', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else {
                $Ekatalog = collect(DetailEkatalog::whereHas('Ekatalog.Pesanan', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spa = collect(DetailSpa::whereHas('Spa.Pesanan', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spb = collect(DetailSpb::whereHas('Spb.Pesanan', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->Pesanan->so;
                    } elseif ($name == 'detail_spa') {
                        return $data->Spa->Pesanan->so;
                    } else {
                        return $data->Spb->Pesanan->so;
                    }
                })
                ->addColumn('no_paket', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->no_paket;
                    } else {
                        return '';
                    }
                })
                ->addColumn('no_po', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->Pesanan->no_po;
                    } elseif ($name == 'detail_spa') {
                        return $data->Spa->Pesanan->no_po;
                    } else {
                        return $data->Spb->Pesanan->no_po;
                    }
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->Customer->nama;
                    } elseif ($name == 'detail_spa') {
                        return $data->Spa->Customer->nama;
                    } else {
                        return $data->Spb->Customer->nama;
                    }
                })
                ->addColumn('tgl_kontrak', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->tgl_kontrak;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->Pesanan->tgl_po;
                    } elseif ($name == 'detail_spa') {
                        return $data->Spa->Pesanan->tgl_po;
                    } else {
                        return $data->Spb->Pesanan->tgl_po;
                    }
                })
                ->addColumn('instansi', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->instansi;
                    } else {
                        return '';
                    }
                })
                ->addColumn('satuan', function ($data) {
                    $name =  $data->getTable();
                    if ($name == 'detail_ekatalog') {
                        return $data->Ekatalog->Satuan;
                    } else {
                        return '';
                    }
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->penjualanproduk->nama;
                })
                ->addColumn('no_seri', function () {
                    return '-';
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->jumlah;
                })
                ->addColumn('harga', function ($data) {
                    return $data->harga;
                })
                ->addColumn('subtotal', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('total', function ($data) {
                    return $data->jumlah * $data->harga;
                })
                ->addColumn('log', function () {
                    return '-';
                })
                ->addColumn('kosong', function () {
                    return '';
                })
                ->make(true);
        }
    }
    // public function laporan(Request $request)
    // {
    //     return Excel::download(new LaporanPenjualan($request->customer_id ?? '', $request->penjualan ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_penjualan.xlsx');
    // }

    //Chart
    public function chart_penjualan()
    {
        //EKAT
        $ekatalog = Pesanan::Has('Ekatalog')
            ->select('Pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });

        $ekatalog_count = [];
        $ekatalog_graph = [];

        foreach ($ekatalog as $key => $value) {
            $ekatalog_count[(int)$key] = count($value);
        }


        for ($i = 1; $i <= 12; $i++) {
            if (!empty($ekatalog_count[$i])) {
                $ekatalog_graph[$i]['count'] = $ekatalog_count[$i];
            } else {
                $ekatalog_graph[$i]['count'] = 0;
            }
            $ekatalog_graph[$i];
        }

        //SPA
        $spa = Pesanan::Has('Spa')
            ->select('Pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });


        $spa_count = [];
        $spa_graph = [];
        foreach ($spa as $key => $value) {
            $spa_count[(int)$key] = count($value);
        }


        for ($i = 1; $i <= 12; $i++) {
            if (!empty($spa_count[$i])) {
                $spa_graph[$i]['count'] = $spa_count[$i];
            } else {
                $spa_graph[$i]['count'] = 0;
            }
            $spa_graph[$i];
        }


        //SPB
        $spb = Pesanan::Has('Spb')
            ->select('Pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });


        $spb_count = [];
        $spb_graph = [];

        foreach ($spb as $key => $value) {
            $spb_count[(int)$key] = count($value);
        }


        for ($i = 1; $i <= 12; $i++) {
            if (!empty($spb_count[$i])) {
                $spb_graph[$i]['count'] = $spb_count[$i];
            } else {
                $spb_graph[$i]['count'] = 0;
            }
            $spb_graph[$i];
        }

        return response()->json(compact('ekatalog_graph', 'spa_graph', 'spb_graph'));
    }
    //Another 
    function toRomawi($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    function getMonth()
    {
        $m = Carbon::now()->format('m');
        return $this->toRomawi($m);
    }
    function getYear()
    {
        return  Carbon::now()->format('Y');
    }

    function createSO($value)
    {
        $check = Pesanan::all('so');
        $max_number = 0;
        foreach ($check as $c) {
            if ($c->so == NULL) {
                $no = 'SO/' . $value . '/' . $this->getMonth() . '/' . $this->getYear() . '/1';
            } else {
                $get = explode('/', $c->so);
                if ($get[1] == $value) {
                    if ($get[4] > $max_number)
                        $max_number = $get[4];
                }
            }
        }
        $no = 'SO/' . $value . '/' . $this->getMonth() . '/' . $this->getYear() . '/' . ($max_number + 1) . '';
        return $no;
    }
}
