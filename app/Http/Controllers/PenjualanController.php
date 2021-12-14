<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualan;
use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\DetailSpa;
use App\Models\DetailSpb;
use App\Models\Ekatalog;
use App\Models\Logistik;
use App\Models\Pesanan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Provinsi;
use App\Models\TFProduksi;
use Carbon\Doctrine\CarbonType;
use Hamcrest\Core\IsNot;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Validator;
use League\Fractal\Resource\Item;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\assertIsNotArray;

class PenjualanController extends Controller
{
    //Get Data Table
    public function penjualan_data()
    {
        $Ekatalog = collect(Ekatalog::with('Pesanan')->orderBy('id', 'DESC')->get());
        $Spa = collect(Spa::with('Pesanan')->orderBy('id', 'DESC')->get());
        $Spb = collect(Spb::with('Pesanan')->orderBy('id', 'DESC')->get());
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
                return $data->Customer['nama'];
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
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
                    // return $data->tgl_buat;
                } else {
                    if (isset($data->tgl_po)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
                    } else {
                        return "";
                    }
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                if (isset($data->tgl_kontrak)) {
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status)->format('Y-m-d');

                    if (isset($data->Pesanan->so)) {
                        if ($data->Pesanan->getJumlahPesanan() == $data->Pesanan->getJumlahKirim()) {
                            return $tgl_parameter;
                        } else {
                            if ($tgl_sekarang < $tgl_parameter) {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else if ($tgl_sekarang == $tgl_parameter) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            } else {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                            }
                        }
                    } else {
                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else if ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                        }
                    }
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
                }
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
            ->rawColumns(['button', 'status', 'tgl_order', 'tgl_kontrak'])
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
                    $status = "";
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
            $data = Logistik::where('nosurat', 'LIKE', '%' . $value . '%');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('po', function ($data) {
                    return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->no_po;
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if ($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->state->nama == "penjualan") {
                        $datas .= '<span class="red-text badge">';
                    } else if ($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->state->nama == "po") {
                        $datas .= '<span class="purple-text badge">';
                    } else if ($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->state->nama == "gudang") {
                        $datas .= '<span class="orange-text badge">';
                    } else if ($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->state->nama == "qc") {
                        $datas .= '<span class="yellow-text badge">';
                    } else if ($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->state->nama == "logistik") {
                        $datas .= '<span class="blue-text badge">';
                    } else if ($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->state->nama == "selesai") {
                        $datas .= '<span class="green-text badge">';
                    }
                    $datas .= ucfirst($data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->state->nama) . '</span>';
                    return $datas;
                })
                ->make(true);
        }
    }
    public function get_data_detail_spa($value)
    {
        $data  = Spa::find($value);
        return view('page.penjualan.penjualan.detail_spa', ['data' => $data]);
    }
    public function get_data_detail_ekatalog($value)
    {
        $data  = Ekatalog::find($value);
        return view('page.penjualan.penjualan.detail_ekatalog', ['data' => $data]);
    }

    public function get_data_detail_spb($value)
    {
        $data  = Spb::find($value);
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
        })->orderBy('tgl_kontrak', 'ASC')->limit(20)->get();
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
                }
                return $datas;
            })
            ->addColumn('batas_kontrak', function ($data) {
                if (isset($data->tgl_kontrak)) {
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status)->format('Y-m-d');

                    if (isset($data->Pesanan->so)) {
                        if ($data->Pesanan->getJumlahPesanan() == $data->Pesanan->getJumlahKirim()) {
                            return $tgl_parameter;
                        } else {
                            if ($tgl_sekarang < $tgl_parameter) {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else if ($tgl_sekarang == $tgl_parameter) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            } else {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                            }
                        }
                    } else {
                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else if ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                        }
                    }
                } else {
                    return '';
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
        $divisi_id = Auth::user()->divisi->id;
        $x = explode(',', $value);
        $data = "";

        if ($value == 0 || $value == 'kosong') {
            $data  = Ekatalog::with('pesanan', 'customer')->orderBy('id', 'DESC')->get();
        } else {
            $data  = Ekatalog::with('pesanan', 'customer')->orderBy('id', 'DESC')->whereIN('status', $x);
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
                }
                return $datas;
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->editColumn('tgl_buat', function ($data) {
                if (!empty($data->tgl_buat)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
                }
            })->editColumn('tgl_kontrak', function ($data) {
                if (isset($data->tgl_kontrak)) {
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status)->format('Y-m-d');

                    if (isset($data->Pesanan->so)) {
                        if ($data->Pesanan->getJumlahPesanan() == $data->Pesanan->getJumlahKirim()) {
                            return $tgl_parameter;
                        } else {
                            if ($tgl_sekarang < $tgl_parameter) {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else if ($tgl_sekarang == $tgl_parameter) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            } else {
                                $to = Carbon::now();
                                $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                                $hari = $to->diffInDays($from);
                                return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                            }
                        }
                    } else {
                        if ($tgl_sekarang < $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else if ($tgl_sekarang == $tgl_parameter) {
                            return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->tgl_kontrak, $data->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                        }
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->Customer)) {
                    return $data->Customer['nama'];
                }
            })
            ->addColumn('button', function ($data) use ($divisi_id) {

                $return = "";
                $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Details
                    </button>
                </a>';
                if ($divisi_id == "26") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "Penjualan" || $data->Pesanan->State->nama == "PO") {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'ekatalog']) . '" data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button" >
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                        </button>
                    </a>';

                            if ($data->status == 'sepakat') {
                                if ($data->Pesanan == '') {
                                    $return .= '<a href="' . route('penjualan.so.create', [$data->id]) . '" data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="fas fa-plus"></i>
                            Tambah PO
                            </button>
                        </a>';
                                } else {
                                    if ($data->Pesanan->so == '') {
                                        $return .= '<a href="' . route('penjualan.so.create', [$data->id]) . '" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-plus"></i>
                                    Tambah PO
                                    </button>
                                </a>';
                                    }
                                }
                            }
                        }
                    }
                }
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['button', 'status', 'tgl_kontrak'])
            ->make(true);
    }
    public function get_data_spa()
    {
        $data  = Spa::with('pesanan')->orderBy('id', 'DESC')->get();
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
                }
                return $datas;
            })
            ->addColumn('tglpo', function ($data) {
                if ($data->Pesanan) {
                    if ($data->Pesanan->tgl_po == "0000-00-00" || empty($data->Pesanan->tgl_po)) {
                        return '-';
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                $divisi_id = Auth::user()->divisi->id;
                $return = "";
                $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Details
                    </button>
                </a>';
                if ($divisi_id == "26") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "Penjualan" || $data->Pesanan->State->nama == "PO") {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button" >
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                        </button>
                    </a>';
                        }
                    } else {

                        $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">
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
                    </a>';
                    }
                }
                $return .= '</div>';
                return $return;
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }
    public function get_data_spb()
    {
        $data  = Spb::with('pesanan')->orderBy('id', 'DESC')->get();
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
                }
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
                    if ($data->Pesanan->tgl_po == "0000-00-00") {
                        return '-';
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                $divisi_id = Auth::user()->divisi->id;
                $return = "";
                $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '" >
                <button class="dropdown-item" type="button">
                      <i class="fas fa-search"></i>
                      Details
                    </button>
                </a>';
                if ($divisi_id == "26") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "Penjualan" || $data->Pesanan->State->nama == "PO") {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button" >
                          <i class="fas fa-pencil-alt"></i>
                          Edit
                        </button>
                    </a>';
                        }
                    } else {

                        $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">
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
                    </a>';
                    }
                }
                $return .= '</div>';
                return $return;
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
            $x = "";
            $pesanan = Pesanan::create([
                'log_id' => '7',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            $x = $pesanan->id;

            $Ekatalog = Ekatalog::create([
                'customer_id' => $request->customer_id,
                'provinsi_id' => $request->provinsi,
                'pesanan_id' => $x,
                'no_paket' => 'AK1-' . $request->no_paket,
                'deskripsi' => $request->deskripsi,
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
                        $dekat = DetailPesanan::create([
                            'pesanan_id' => $x,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);

                        if (!$dekat) {
                            $bool = false;
                        } else {
                            for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                $dekatp = DetailPesananProduk::create([
                                    'detail_pesanan_id' => $dekat->id,
                                    'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                ]);
                                if (!$dekatp) {
                                    $bool = false;
                                }
                            }
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
        } else if ($request->jenis_penjualan == 'spa') {
            $pesanan = Pesanan::create([
                'so' => $this->createSO('SPA'),
                'no_po' => $request->no_po,
                'tgl_po' => $request->tanggal_po,
                'no_do' => $request->no_do,
                'tgl_do' => $request->tanggal_do,
                'ket' =>  $request->keterangan,
                'log_id' => '9'
            ]);
            $x = $pesanan->id;
            $Spa = Spa::create([
                'customer_id' => $request->customer_id,
                'pesanan_id' => $x,
                'ket' => $request->keterangan,
                'log' => 'po'
            ]);
            $bool = true;
            if ($Spa) {
                for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                    $dspa = DetailPesanan::create([
                        'pesanan_id' => $x,
                        'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                        'jumlah' => $request->produk_jumlah[$i],
                        'harga' => str_replace('.', "", $request->produk_harga[$i]),
                        'ongkir' => 0,
                    ]);
                    if (!$dspa) {
                        $bool = false;
                    } else {
                        for ($j = 0; $j < count(array($request->variasi[$i])); $j++) {
                            $dspap = DetailPesananProduk::create([
                                'detail_pesanan_id' => $dspa->id,
                                'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                            ]);
                            if (!$dspap) {
                                $bool = false;
                            }
                        }
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
            $pesanan = Pesanan::create([
                'so' => $this->createSO('SPB'),
                'no_po' => $request->no_po,
                'tgl_po' => $request->tanggal_po,
                'no_do' => $request->no_do,
                'tgl_do' => $request->tanggal_do,
                'ket' =>  $request->keterangan,
                'log_id' => '9'
            ]);
            $x = $pesanan->id;

            $Spb = Spb::create([
                'customer_id' => $request->customer_id,
                'pesanan_id' => $x,
                'ket' => $request->keterangan,
                'log' => 'po'
            ]);
            $bool = true;
            if ($Spb) {
                for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                    $dspb = DetailPesanan::create([
                        'pesanan_id' => $x,
                        'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                        'jumlah' => $request->produk_jumlah[$i],
                        'harga' => str_replace('.', "", $request->produk_harga[$i]),
                        'ongkir' => 0,
                    ]);
                    if (!$dspb) {
                        $bool = false;
                    } else {
                        for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                            $dspbp = DetailPesananProduk::create([
                                'detail_pesanan_id' => $dspb->id,
                                'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                            ]);
                            if (!$dspbp) {
                                $bool = false;
                            }
                        }
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

        $ekatalog = Ekatalog::find($id);
        $p = Pesanan::find($ekatalog->pesanan_id);


        if (isset($p)) {
            $p->so = $this->createSO('EKAT');
            $p->no_po = $request->no_po;
            $p->tgl_po = $request->tanggal_po;
            $p->no_do = $request->no_do;
            $p->tgl_do = $request->tanggal_do;
            $p->ket = $request->keterangan;
            $p->log_id = "9";
            $pes = $p->save();
            if (!$pes) {
                $bool = false;
            }
        } else {
            $po = Pesanan::create([
                'so' => $this->createSO('EKAT'),
                'no_po' => $request->no_po,
                'tgl_po' => $request->tanggal_po,
                'no_do' => $request->no_do,
                'tgl_do' => $request->tanggal_do,
                'ket' => $request->keterangan
            ]);

            if ($po) {
                $ekatalog->pesanan_id = $po->id;
                $eksave = $ekatalog->save();
                if (!$eksave) {
                    $bool = false;
                }
            }
        }

        $ekatalog->log = "po";
        $ekatalog->save();
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
            $ekatalog = Ekatalog::find($id);
            return view('page.penjualan.penjualan.edit_ekatalog', ['e' => $ekatalog]);
        } else if ($jenis == 'spa') {
            $spa = Spa::where('id', $id)->get();
            return view('page.penjualan.penjualan.edit_spa', ['spa' => $spa]);
        } else {
            $spb = Spb::where('id', $id)->get();
            return view('page.penjualan.penjualan.edit_spb', ['spb' => $spb]);
        }
    }
    public function update_ekatalog(Request $request, $id)
    {
        echo json_encode($request->all());
        $ekatalog = Ekatalog::find($id);
        $poid = $ekatalog->pesanan_id;
        $ekatalog->customer_id = $request->customer_id;
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
            $dekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->get();

            if (count($dekatp) > 0) {
                $deldekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->delete();
                if (!$deldekatp) {
                    $bool = false;
                }
            }
            $dekat = DetailPesanan::where('pesanan_id', $poid)->get();
            if (count($dekat) > 0) {
                $deldekat = DetailPesanan::where('pesanan_id', $poid)->delete();
                if (!$deldekat) {
                    $bool = false;
                }
            }
            if ($bool == true) {
                if ($request->status != "draft") {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $c = DetailPesanan::create([
                            'pesanan_id' => $poid,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);
                        if ($c) {
                            for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                $v = DetailPesananProduk::create([
                                    'detail_pesanan_id' => $c->id,
                                    'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                ]);
                                if (!$v) {
                                    $bool = false;
                                }
                            }
                        } else {
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
        $poid = $spa->pesanan_id;
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

                $dspap = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();
                if (count($dspap) > 0) {
                    $deldspap = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                    if (!$deldspap) {
                        $bool = false;
                    }
                }

                $dspa = DetailPesanan::where('pesanan_id', $poid)->get();
                if (count($dspa) > 0) {
                    $deldspa = DetailPesanan::where('pesanan_id', $poid)->delete();
                    if (!$deldspa) {
                        $bool = false;
                    }
                }

                if ($dspa) {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $c = DetailPesanan::create([
                            'pesanan_id' => $poid,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);
                        if (!$c) {
                            $bool = false;
                        } else {
                            for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                $cd = DetailPesananProduk::create([
                                    'detail_pesanan_id' => $c->id,
                                    'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                ]);
                                if (!$cd) {
                                    $bool = false;
                                }
                            }
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
        $poid = $spb->pesanan_id;
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
                $dspbp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();
                if (count($dspbp) > 0) {
                    $deldspbp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                    if (!$deldspbp) {
                        $bool = false;
                    }
                }

                $dspb = DetailPesanan::where('pesanan_id', $poid)->get();
                if (count($dspb) > 0) {
                    $deldspb = DetailPesanan::where('pesanan_id', $poid)->delete();
                    if (!$deldspb) {
                        $bool = false;
                    }
                }

                if ($dspb) {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $c = DetailPesanan::create([
                            'pesanan_id' => $spb->pesanan_id,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);
                        if (!$c) {
                            $bool = false;
                        } else {
                            for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                $dspbp = DetailPesananProduk::create([
                                    'detail_pesanan_id' => $c->id,
                                    'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                ]);
                                if (!$dspbp) {
                                    $bool = false;
                                }
                            }
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
                $data  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else {
                $data  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);;
                })->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Pesanan->so;
                })
                ->addColumn('no_paket', function ($data) {
                    return $data->Pesanan->Ekatalog->no_paket;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->Pesanan->no_po;
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Pesanan->Ekatalog->Customer->nama;
                })
                ->addColumn('tgl_kontrak', function ($data) {
                    return $data->Pesanan->Ekatalog->tgl_kontrak;
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    return $data->Pesanan->tgl_po;
                })
                ->addColumn('instansi', function ($data) {
                    return $data->Pesanan->Ekatalog->instansi;
                })
                ->addColumn('satuan', function ($data) {
                    return $data->Pesanan->Ekatalog->satuan;
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
                ->addColumn('ket', function ($data) {
                    return $data->Pesanan->Ekatalog->ket;
                })
                ->addColumn('kosong', function () {
                    return '';
                })
                ->make(true);
        } elseif ($penjualan == 'spa') {
            if ($distributor == 'semua') {
                $data  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else {
                $data  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Pesanan->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->Pesanan->no_po;
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Pesanan->Spa->Customer->nama;
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    return $data->Pesanan->tgl_po;
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
                ->addColumn('ket', function ($data) {
                    return $data->Pesanan->Spa->ket;
                })
                ->addColumn('kosong', function () {
                    return '';
                })
                ->make(true);
        } elseif ($penjualan == 'spb') {
            if ($distributor == 'semua') {
                $data  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else {
                $data  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Pesanan->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->Pesanan->no_po;
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Pesanan->Spa->Customer->nama;
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    return $data->Pesanan->tgl_po;
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
                ->addColumn('ket', function ($data) {
                    return $data->Pesanan->Spb->ket;
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
                $Ekatalog = collect(DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spa = collect(DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spb = collect(DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else {
                $Ekatalog = collect(DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spa = collect(DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $Spb = collect(DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->where('customer_id', $distributor)
                        ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->Pesanan->so;
                })
                ->addColumn('no_paket', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->no_paket;
                    } else {
                        return '';
                    }
                })
                ->addColumn('no_po', function ($data) {
                    return $data->Pesanan->no_po;
                })
                ->addColumn('no_sj', function () {
                    return '-';
                })
                ->addColumn('nama_customer', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Pesanan->Spa->Customer->nama;
                    } else {
                        return $data->Pesanan->Spb->Customer->nama;
                    }
                })
                ->addColumn('tgl_kontrak', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->tgl_kontrak;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tgl_kirim', function () {
                    return '-';
                })
                ->addColumn('tgl_po', function ($data) {
                    return $data->Pesanan->tgl_po;
                })
                ->addColumn('instansi', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->instansi;
                    } else {
                        return '';
                    }
                })
                ->addColumn('satuan', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->Satuan;
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
                ->addColumn('ket', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->ket;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Pesanan->Spa->ket;
                    } else {
                        return $data->Pesanan->Spb->ket;
                    }
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

    //Dashboard
    public function dashboard()
    {
        $belum_so = Pesanan::whereNull('so')->get()->count();
        $so_belum_gudang = Pesanan::DoesntHave('TFProduksi')->get()->count();
        $so_belum_qc = Pesanan::DoesntHave('DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->get()->count();
        $so_belum_logistik = Pesanan::DoesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik')->get()->count();
        return view('page.penjualan.dashboard', ['belum_so' => $belum_so, 'so_belum_gudang' => $so_belum_gudang, 'so_belum_qc' => $so_belum_qc, 'so_belum_logistik' => $so_belum_logistik]);
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

    public function check_no_paket($id, $val)
    {
        if ($id != "0") {
            $e = Ekatalog::where('no_paket', 'AK1-' . $val)->whereNotIn('id', [$id])->count();
            return response()->json(['data' => $e]);
        } else {
            $e = Ekatalog::where('no_paket', 'AK1-' . $val)->count();
            return response()->json(['data' => $e]);
        }
    }
}
