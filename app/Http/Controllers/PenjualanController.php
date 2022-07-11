<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualan;
use App\Exports\LaporanPenjualanAll;
use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\DetailPesananProduk;
use App\Models\DetailRencanaPenjualan;
use App\Models\DetailSpa;
use App\Models\DetailSpb;
use App\Models\NoseriTGbj;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\Logistik;
use App\Models\OutgoingPesananPart;
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
use Maatwebsite\Excel\Excel as ExcelExcel;
use Symfony\Component\Console\Input\Input;
use DB;

use function PHPUnit\Framework\assertIsNotArray;

class PenjualanController extends Controller
{
    public function in_array_all($needles, $haystack)
    {
        return empty(array_diff($needles, $haystack));
    }
    //Get Data Table
    public function penjualan_data($jenis, $status)
    {
        $x = explode(',', $jenis);
        $y = explode(',', $status);
        $data = "";
        if ($jenis == "semua" && $status == "semua") {
            $Ekatalog = collect(Ekatalog::with(['Pesanan.State','Customer'])->addSelect(['tgl_kontrak_custom' => function($q){
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                  ->from('ekatalog as e')
                  ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                  ->whereColumn('e.id', 'ekatalog.id')
                  ->limit(1);
                }])->orderBy('id', 'DESC')->get());
            $Spa = collect(Spa::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get());
            $Spb = collect(Spb::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get());
            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else if ($jenis != "semua" && $status == "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekatalog', $x)) {
                $Ekatalog = collect(Ekatalog::with(['Pesanan.State','Customer'])->addSelect(['tgl_kontrak_custom' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                      ->from('ekatalog as e')
                      ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                      ->whereColumn('e.id', 'ekatalog.id')
                      ->limit(1);
                    }])->orderBy('id', 'DESC')->get());
            }
            if (in_array('spa', $x)) {
                $Spa = collect(Spa::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get());
            }
            if (in_array('spb', $x)) {
                $Spb = collect(Spb::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get());
            }
            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($jenis == "semua" && $status != "semua") {
            $Ekatalog = collect(Ekatalog::with(['Pesanan.State','Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());
            $Spa = collect(Spa::with(['Pesanan.State','Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());
            $Spb = collect(Spb::with(['Pesanan.State','Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());
            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekatalog', $x)) {
                $Ekatalog = collect(Ekatalog::with(['Pesanan.State','Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if (in_array('spa', $x)) {
                $Spa = collect(Spa::with(['Pesanan.State','Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if (in_array('spb', $x)) {
                $Spb = collect(Spb::with(['Pesanan.State','Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function ($data) {
                $name = $data->getTable();
                if ($name == 'ekatalog') {
                    return "E-Catalogue";
                } else if ($name == 'spa') {
                    return "SPA";
                } else if ($name == 'spb') {
                    return "SPB";
                }
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->Customer)) {
                    return $data->Customer['nama'];
                } else {
                    return '-';
                }
            })
            ->addColumn('no_paket', function ($data) {
                if (isset($data->no_paket)) {
                    return $data->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl_order', function ($data) {

                if (!empty($data->Pesanan->tgl_po)) {
                    return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                } else {
                    return "-";
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                $name = $data->getTable();
                if($name == 'ekatalog'){
                    if($data->tgl_kontrak_custom != ""){
                        if($data->Pesanan->log_id != '10'){
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak_custom;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
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
                            }
                            else{
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> ' . $hari . ' Hari Lagi</small></div>';
                            }
                        } else{
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                        }
                    }
                }
                // if (isset($data->tgl_kontrak)) {
                //     $tgl_sekarang = Carbon::now()->format('Y-m-d');
                //     $tgl_parameter = $data->tgl_kontrak;

                //     if (isset($data->Pesanan->so)) {
                //         if ($data->Pesanan->getJumlahPesanan() == $data->Pesanan->getJumlahKirim()) {
                //             return Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y');
                //         } else {
                //             if ($tgl_sekarang < $tgl_parameter) {
                //                 $to = Carbon::now();
                //                 $from = $data->tgl_kontrak;
                //                 $hari = $to->diffInDays($from);
                //                 if ($hari > 7) {
                //                     return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                     <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                //                 } else if ($hari > 0 && $hari <= 7) {
                //                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                     <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                //                 } else {
                //                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                     <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                //                 }
                //             } else if ($tgl_sekarang == $tgl_parameter) {
                //                 return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                 <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                //             } else {
                //                 $to = Carbon::now();
                //                 $from = $data->tgl_kontrak;
                //                 $hari = $to->diffInDays($from);
                //                 return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                 <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                //             }
                //         }
                //     } else {
                //         if ($tgl_sekarang < $tgl_parameter) {
                //             $to = Carbon::now();
                //             $from = $data->tgl_kontrak;
                //             $hari = $to->diffInDays($from);
                //             if ($hari > 7) {
                //                 return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                 <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                //             } else if ($hari > 0 && $hari <= 7) {
                //                 return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                 <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                //             } else {
                //                 return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //                 <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                //             }
                //         } else if ($tgl_sekarang == $tgl_parameter) {
                //             return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //             <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                //         } else {
                //             $to = Carbon::now();
                //             $from = $data->tgl_kontrak;
                //             $hari = $to->diffInDays($from);
                //             return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                //             <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                //         }
                //     }
                // } else {
                //     return '-';
                // }
            })
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
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
                    } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                        $datas .= '<span class="red-text badge">';
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
                    if ($data->status != 'draft') {
                        return  '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                    }
                } else if ($name == 'spa') {
                    return  '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                } else {
                    return  '<a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
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
                    $datas = $data->Customer->nama;
                    if ($data->satuan) {
                        $datas .= "<div><small>" . $data->satuan . "</small></div>";
                    }
                    return $datas;
                })
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
                ->addColumn('tgl_po', function ($data) {
                    if ($data->Pesanan) {
                        if ($data->Pesanan->tgl_po != "0000-00-00" && !empty($data->Pesanan->tgl_po)) {
                            return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('noseri', function () {
                    return '';
                })
                ->addColumn('log', function ($data) {
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
                        } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    }
                    return $datas;
                })
                ->rawColumns(['log', 'nama_customer'])
                ->make(true);
        } else if ($parameter == 'no_akn') {
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
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_buat', function ($data) {
                    if (!empty($data->tgl_buat)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
                    }
                })
                ->addColumn('tgl_kontrak', function ($data) {
                    if (!empty($data->tgl_kontrak)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                    }
                })
                ->addColumn('customer', function ($data) {
                    return $data->Customer->nama;
                })
                ->addColumn('instansi', function ($data) {
                    $datas = $data->satuan;
                    $datas .= "<div><small>" . $data->instansi . "</small></div>";
                    return $datas;
                })
                ->addColumn('log', function ($data) {
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
                        } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    }
                    return $datas;
                })
                ->rawColumns(['status', 'log', 'instansi'])
                ->make(true);
        } else if ($parameter == 'customer') {
            $ekatalog = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) use ($value) {
                $q->where('satuan', 'LIKE', '%' . $value . '%');
            })->orwhereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) use ($value) {
                $q->where('instansi', 'LIKE', '%' . $value . '%');
            })->orwhereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Customer', function ($q) use ($value) {
                $q->where('nama', 'LIKE', '%' . $value . '%');
            })->get();
            $spa = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer', function ($q) use ($value) {
                $q->where('nama', 'LIKE', '%' . $value . '%');
            })->get();
            $spb = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer', function ($q) use ($value) {
                $q->where('nama', 'LIKE', '%' . $value . '%');
            })->get();
            $data = $ekatalog->merge($spa)->merge($spb);
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noseri', function ($data) {
                    return $data->NoseriBarangJadi->noseri;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->NoseriBarangJadi->Gudang->Produk->nama;
                })
                ->addColumn('no_so', function ($data) {
                    if ($data->detail->header->pesanan_id) {
                        return $data->detail->header->pesanan->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        $name = explode('/', $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->so);
                        if ($name[1] == 'EKAT') {
                            $cus = $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                            $cus .= "<div><small>" . $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->satuan . "</small></div>";
                            return $cus;
                        } else if ($name[1] == 'SPA') {
                            return $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                        } else if ($name[1] == 'SPB') {
                            return $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_uji', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        return Carbon::createFromFormat('Y-m-d', $data->NoseriDetailPesanan->tgl_uji)->format('d-m-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('no_sj', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->NoseriDetailLogistik)) {
                            return $data->NoseriDetailPesanan->NoseriDetailLogistik->DetailLogistik->Logistik->nosurat;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->NoseriDetailLogistik)) {
                            return Carbon::createFromFormat('Y-m-d', $data->NoseriDetailPesanan->NoseriDetailLogistik->DetailLogistik->Logistik->tgl_kirim)->format('d-m-Y');
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->log_id)) {
                            if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Penjualan") {
                                $datas .= '<span class="red-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "PO") {
                                $datas .= '<span class="purple-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Gudang") {
                                $datas .= '<span class="orange-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "QC") {
                                $datas .= '<span class="yellow-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Belum Terkirim") {
                                $datas .= '<span class="red-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Terkirim Sebagian") {
                                $datas .= '<span class="blue-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Kirim") {
                                $datas .= '<span class="green-text badge">';
                            }
                            $datas .= ucfirst($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama) . '</span>';
                        }
                    } else {
                        $datas = '-';
                    }
                    return $datas;
                })
                ->rawColumns(['divisi_id', 'status', 'nama_customer'])
                ->make(true);
        } else if ($parameter == 'produk') {
            $data = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.GudangBarangJadi.produk', function ($q) use ($value) {
                $q->where('nama', 'LIKE', '%' . $value . '%');
            })->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noseri', function ($data) {
                    return $data->NoseriBarangJadi->noseri;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->NoseriBarangJadi->Gudang->Produk->nama;
                })
                ->addColumn('no_so', function ($data) {
                    if ($data->detail->header->pesanan_id) {
                        return $data->detail->header->pesanan->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        $name = explode('/', $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->so);
                        if ($name[1] == 'EKAT') {
                            $cus = $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
                            $cus .= "<div><small>" . $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->satuan . "</small></div>";
                            return $cus;
                        } else if ($name[1] == 'SPA') {
                            return $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                        } else if ($name[1] == 'SPB') {
                            return $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_uji', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        return Carbon::createFromFormat('Y-m-d', $data->NoseriDetailPesanan->tgl_uji)->format('d-m-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('no_sj', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->NoseriDetailLogistik)) {
                            return $data->NoseriDetailPesanan->NoseriDetailLogistik->DetailLogistik->Logistik->nosurat;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->NoseriDetailLogistik)) {
                            return Carbon::createFromFormat('Y-m-d', $data->NoseriDetailPesanan->NoseriDetailLogistik->DetailLogistik->Logistik->tgl_kirim)->format('d-m-Y');
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->log_id)) {
                            if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Penjualan") {
                                $datas .= '<span class="red-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "PO") {
                                $datas .= '<span class="purple-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Gudang") {
                                $datas .= '<span class="orange-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "QC") {
                                $datas .= '<span class="yellow-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Belum Terkirim") {
                                $datas .= '<span class="red-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Terkirim Sebagian") {
                                $datas .= '<span class="blue-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Kirim") {
                                $datas .= '<span class="green-text badge">';
                            }
                            $datas .= ucfirst($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama) . '</span>';
                        }
                    } else {
                        $datas = '-';
                    }
                    return $datas;
                })
                ->rawColumns(['divisi_id', 'status', 'nama_customer'])
                ->make(true);
        } else if ($parameter == 'no_seri') {
            $data = NoseriTGbj::whereHas('NoseriBarangJadi', function ($q) use ($value) {
                $q->where('noseri', 'LIKE', '%' . $value . '%');
            })->Has('NoseriDetailPesanan')->orderBy('id', 'desc')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noseri', function ($data) {
                    return $data->NoseriBarangJadi->noseri;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->NoseriBarangJadi->Gudang->Produk->nama;
                })
                ->addColumn('no_so', function ($data) {
                    if ($data->detail->header->pesanan_id) {
                        return $data->detail->header->pesanan->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        $name = explode('/', $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->so);
                        if ($name[1] == 'EKAT') {
                            return $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                        } else if ($name[1] == 'SPA') {
                            return $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                        } else if ($name[1] == 'SPB') {
                            return $data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_uji', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        return Carbon::createFromFormat('Y-m-d', $data->NoseriDetailPesanan->tgl_uji)->format('d-m-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('no_sj', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->NoseriDetailLogistik)) {
                            return $data->NoseriDetailPesanan->NoseriDetailLogistik->DetailLogistik->Logistik->nosurat;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->NoseriDetailLogistik)) {
                            return Carbon::createFromFormat('Y-m-d', $data->NoseriDetailPesanan->NoseriDetailLogistik->DetailLogistik->Logistik->tgl_kirim)->format('d-m-Y');
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if (isset($data->NoseriDetailPesanan)) {
                        if (isset($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->log_id)) {
                            if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Penjualan") {
                                $datas .= '<span class="red-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "PO") {
                                $datas .= '<span class="purple-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Gudang") {
                                $datas .= '<span class="orange-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "QC") {
                                $datas .= '<span class="yellow-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Belum Terkirim") {
                                $datas .= '<span class="red-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Terkirim Sebagian") {
                                $datas .= '<span class="blue-text badge">';
                            } else if ($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama == "Kirim") {
                                $datas .= '<span class="green-text badge">';
                            }
                            $datas .= ucfirst($data->NoseriDetailPesanan->DetailPesananProduk->DetailPesanan->Pesanan->State->nama) . '</span>';
                        }
                    } else {
                        $datas = '-';
                    }
                    return $datas;
                })
                ->rawColumns(['divisi_id', 'status'])
                ->make(true);
        } else if ($parameter == 'no_so') {
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
                    $datas = $data->Customer->nama;
                    if ($data->satuan) {
                        $datas .= "<div><small>" . $data->instansi . "</small></div>";
                    }
                    return $datas;
                })
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('no_po', function ($data) {
                    if ($data->Pesanan) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_po', function ($data) {
                    if ($data->Pesanan) {
                        if ($data->Pesanan->tgl_po != "0000-00-00") {
                            return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->Pesanan->DetailPesanan)) {
                        if (isset($data->Pesanan->DetailPesanan->DetailPesananProduk)) {
                            return $data->Pesanan->DetailPesanan->DetailPesananProduk->DetailLogistik->Logistik;
                        }
                    }
                })
                ->addColumn('log', function ($data) {
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
                        } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    }
                    return $datas;
                })
                ->rawColumns(['log', 'nama_customer'])
                ->make(true);
        } elseif ($parameter == 'no_sj') {
            $data = Logistik::where('nosurat',  'LIKE', '%' . $value . '%')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('no_so', function ($data) {
                    if (isset($data->DetailLogistik[0])) {
                        return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so;
                    } else if (isset($data->DetailLogistikPart)) {
                        $list = array();
                        foreach ($data->DetailLogistikPart as $s) {
                            $list[] = $s->DetailPesananPart->Pesanan->so;
                        }
                        return implode('<br>', $list);
                    } else {
                        return 3;
                    }
                })
                ->addColumn('nosurat', function ($data) {
                    return $data->nosurat;
                })
                ->addColumn('customer', function ($data) {
                    // if (isset($data->DetailLogistik)) {
                    //     $name = explode('/', $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->so);
                    //     if ($name[1] == 'EKAT') {
                    //         return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->instansi;
                    //     } else if ($name[1] == 'SPA') {
                    //         return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
                    //     }
                    // } else if (!isset($data->DetailLogistik)) {
                    //     return $data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
                    // } else {
                    //     return '-';
                    // }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if ($data->tgl_kirim) {
                        return $data->tgl_kirim;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    if ($data->status_id == "10") {
                        return '<div class="badge blue-text">' . $data->State->nama . '</div>';
                    } else if ($data->status_id == "11") {
                        return '<div class="badge red-text">' . $data->State->nama . '</div>';
                    }
                })
                ->rawColumns(['status', 'no_so'])
                ->make(true);
        }
    }
    public function get_data_detail_spa($value)
    {
        $data  = Spa::find($value);
        $cek_1 = DetailPesanan::where('pesanan_id', $data->pesanan_id)->get()->count();
        $cek_2 = DetailPesananPart::where('pesanan_id', $data->pesanan_id)->get()->count();

        if ($cek_1 <= 0) {
            $param = 'part';
        } else if ($cek_2 <= 0) {
            $param = 'produk';
        } else {
            $param = 'semua';
        }

        return view('page.penjualan.penjualan.detail_spa', ['data' => $data, 'param' => $param]);
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
                return '<i class="fas fa-eye"></i>';
            })
            ->rawColumns(['button'])
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
                return '<i class="fas fa-eye"></i>';
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
                return '<i class="fas fa-eye"></i>';
            })
            ->rawColumns(['button', 'variasi'])
            ->make(true);
    }

    public function get_data_paket_pesanan_ekat($id)
    {
        $data = DetailPesananProduk::whereHas('DetailPesanan.Pesanan.Ekatalog', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('paket_produk', function ($data) {
                return $data->DetailPesanan->PenjualanProduk->nama_alias . ' (' . $data->DetailPesanan->jumlah . ' unit)';
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->GudangBarangJadi->Produk->nama . ' ' . $data->GudangBarangJadi->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->getJumlahPesanan();
            })
            ->rawColumns(['paket_produk', 'nama_produk'])
            ->make(true);
    }

    public function get_data_pesanan_detail($id)
    {
        $dp = DetailPesanan::where('pesanan_id', $id)->get();
        $dpp = DetailPesananPart::where('pesanan_id', $id)->get();

        $data = $dp->merge($dpp);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    return $data->PenjualanProduk->nama;
                } else {
                    return $data->Sparepart->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                if ($data->jumlah) {
                    return $data->jumlah;
                }
            })
            ->addColumn('harga', function ($data) {
                return $data->harga;
            })
            ->addColumn('sub', function ($data) {
                return $data->harga * $data->jumlah;
            })
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
            $q->whereNotNull('no_po')->whereNotIn('log_id', ['7', '10', '20']);
        })->addSelect(['tgl_kontrak_custom' => function($q){
            $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
              ->from('ekatalog as e')
              ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
              ->whereColumn('e.id', 'ekatalog.id')
              ->limit(1);
            }, 'cjumlahprd' => function($q){
                $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
            }, 'clogprd' => function($q){
                $q->selectRaw('count(noseri_logistik.id)')
                   ->from('noseri_logistik')
                   ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                   ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                   ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                   ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id')
                   ->limit(1);
            }])->orderBy('tgl_kontrak_custom', 'ASC')->limit(20)->get();
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
            ->addColumn('tgl_po', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if (!empty($data->Pesanan->log_id)) {
                    $hitung = round(((($data->clogprd + $data->clogpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100), 0);
                    if ($data->Pesanan->log_id == "9") {
                        $datas = '<span class="badge purple-text">'.$data->Pesanan->State->nama . '</span>';
                    } else {
                        if($hitung > 0){
                            $datas = '<div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                            </div>';
                        }else{
                            $datas = '<span class="text-secondary">0%</span>';
                        }
                    }
                }
                return $datas;
            })
            ->addColumn('batas_kontrak', function ($data) {
                if($data->tgl_kontrak_custom != ""){
                    if($data->Pesanan->log_id != "10"){
                        $tgl_sekarang = Carbon::now();
                        $tgl_parameter = $data->tgl_kontrak_custom;
                        $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                        if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
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
                        } else {
                            return  '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                        }
                    } else{
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('button', function ($data) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button"><i class="fas fa-eye"></i> Detail</button>
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

        if ($value == 'semua') {
            $data  = Ekatalog::with(['Pesanan.State','Customer', 'Provinsi'])->addSelect(['tgl_kontrak_custom' => function($q){
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                  ->from('ekatalog as e')
                  ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                  ->whereColumn('e.id', 'ekatalog.id')
                  ->limit(1);
                }])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get();
        } else {
            $data  = Ekatalog::with(['Pesanan.State','Customer'])->addSelect(['tgl_kontrak_custom' => function($q){
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                  ->from('ekatalog as e')
                  ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                  ->whereColumn('e.id', 'ekatalog.id')
                  ->limit(1);
                }])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_urut', function ($data) {
                return $data->no_urut;
            })
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if (!empty($data->status)) {
                    if ($data->status == "batal") {
                        $datas .= '<span class="red-text badge">';
                    } else if ($data->status == "negosiasi") {
                        $datas .= '<span class="yellow-text badge">';
                    } else if ($data->status == "draft") {
                        $datas .= '<span class="blue-text badge">';
                    } else if ($data->status == "sepakat") {
                        $datas .= '<span class="green-text badge">';
                    }
                    $datas .= ucfirst($data->status) . '</span>';
                }
                return $datas;
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->editColumn('tgl_buat', function ($data) {
                if (!empty($data->tgl_buat)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
                }
            })
            ->editColumn('tgl_edit', function ($data) {
                if (!empty($data->tgl_edit)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_edit)->format('d-m-Y');
                }
            })->editColumn('tgl_kontrak', function ($data) {

                if($data->tgl_kontrak_custom != ""){
                    if($data->Pesanan->log_id != "10"){
                        $tgl_sekarang = Carbon::now();
                        $tgl_parameter = $data->tgl_kontrak_custom;
                        $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                        if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
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
                        } else {
                            return  '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                        }
                    } else{
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->Customer)) {
                    return $data->Customer['nama'];
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) use ($divisi_id) {
                $return = "";
                if ($data->status != 'draft') {
                    if ($divisi_id == "26") {
                        $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    } else {
                        $return .= '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    }
                } else {
                    if ($divisi_id == "26") {
                        $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        if(isset($data->Pesanan->DetailPesanan)){
                            $return .= '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                                </button>
                            </a>';
                        }
                    } else {
                        return '';
                    }
                    // $return .= "-";
                }

                if ($divisi_id == "26") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "Penjualan") {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'ekatalog']) . '" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                                </button>
                            </a>
                            ';
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
                            $return .= '<a data-toggle="modal" data-target="ekatalog" class="deletemodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="far fa-trash-alt"></i>
                                    Hapus
                                    </button>
                                </a>
                                ';
                        } else {
                            $return .= '<a data-toggle="modal" data-jenis="ekatalog" class="editmodal" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit No Urut & DO
                                </button>
                            </a>
                            ';
                        }
                    } else if (empty($data->Pesanan->log_id)) {
                        $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'ekatalog']) . '" data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                            </button>
                        </a>
                        <a data-toggle="modal" data-target="ekatalog" class="deletemodal" data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="far fa-trash-alt"></i>
                            Hapus
                            </button>
                        </a>
                        ';
                    }
                    $return .= '</div>';
                }

                return $return;
            })


            ->rawColumns(['button', 'status', 'tgl_kontrak'])
            ->make(true);
    }
    public function get_data_spa($value)
    {
        $divisi_id = Auth::user()->divisi->id;
        $x = explode(',', $value);
        $data = "";
        if ($value == 'semua') {
            $data  = Spa::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get();
        } else {
            $data  = Spa::with(['Pesanan.State','Customer'])->whereHas('pesanan', function ($q) use ($x) {
                $q->whereIN('log_id', $x);
            })->orderBy('id', 'DESC')->get();
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if ($data->log != "batal") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->Pesanan->State->nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->Pesanan->State->nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    } else {
                        $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                    }
                } else {
                    $datas .= '<span class="red-text badge">Batal</span>';
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

                if ($divisi_id == "26") {
                    if ($data->log != "batal") {
                        $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                        if (!empty($data->Pesanan->log_id)) {
                            if ($data->Pesanan->State->nama == "PO") {
                                $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                    </button>
                                </a>';
                                $return .= '<a data-toggle="modal" data-target="spa" class="deletemodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="far fa-trash-alt"></i>
                                    Hapus
                                    </button>
                                </a>
                                ';
                            } else {
                                $return .= '<a data-toggle="modal" data-jenis="spa" class="editmodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit DO
                                    </button>
                                </a>
                                ';
                                if ($data->Pesanan->State->nama != "Terkirim Sebagian" && $data->Pesanan->State->nama != "Kirim") {
                                    $return .= '<hr class="separator">
                                    <a data-toggle="modal" data-jenis="spa" class="batalmodal" data-id="' . $data->id . '">
                                        <button class="dropdown-item" type="button" >
                                        <i class="fas fa-times"></i>
                                        Batal
                                        </button>
                                    </a>';
                                }
                            }
                        } else {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                                </button>
                            </a>';
                            $return .= '<a data-toggle="modal" data-target="spa" class="deletemodal" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="far fa-trash-alt"></i>
                                Hapus
                                </button>
                            </a>
                            ';
                        }
                        $return .= '</div>';
                    } else {
                        $return .= '<a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    }
                } else {
                    $return .= '<a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                }

                return $return;
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }
    public function get_data_spb($value)
    {
        $divisi_id = Auth::user()->divisi->id;
        $x = explode(',', $value);
        $data = "";
        if ($value == 'semua') {
            $data  = Spb::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get();
        } else {
            $data  = Spb::with(['Pesanan.State','Customer'])->whereHas('pesanan', function ($q) use ($x) {
                $q->whereIN('log_id', $x);
            })->orderBy('id', 'DESC')->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if ($data->log != "batal") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->Pesanan->State->nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->Pesanan->State->nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }

                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    } else {
                        $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                    }
                } else {
                    $datas .= '<span class="red-text badge">Batal</span>';
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

                if ($divisi_id == "26") {
                    $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '" >
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                    if ($data->log != "batal") {
                        if (!empty($data->Pesanan->log_id)) {
                            if ($data->Pesanan->State->nama == "PO") {
                                $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                    </button>
                                </a>';
                                if ($divisi_id == "26") {
                                    $return .= '<a data-toggle="modal" data-target="spb" class="deletemodal" data-id="' . $data->id . '">
                                        <button class="dropdown-item" type="button" >
                                        <i class="far fa-trash-alt"></i>
                                        Hapus
                                        </button>
                                    </a>
                                    ';
                                }
                            } else {
                                if ($divisi_id == "26") {
                                    $return .= '<a data-toggle="modal" data-jenis="spb" class="editmodal" data-id="' . $data->id . '">
                                        <button class="dropdown-item" type="button" >
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit DO
                                        </button>
                                    </a>
                                    ';
                                    if ($data->Pesanan->State->nama != "Terkirim Sebagian" && $data->Pesanan->State->nama != "Kirim") {
                                        $return .= '<hr class="separator">
                                        <a data-toggle="modal" data-jenis="spb" class="batalmodal" data-id="' . $data->id . '">
                                            <button class="dropdown-item" type="button" >
                                            <i class="fas fa-times"></i>
                                            Batal
                                            </button>
                                        </a>';
                                    }
                                }
                            }
                        } else {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                                </button>
                            </a>';
                            if ($divisi_id == "26") {
                                $return .= '<a data-toggle="modal" data-target="spb" class="deletemodal" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="far fa-trash-alt"></i>
                                Hapus
                                </button>
                            </a>
                            ';
                            }
                        }
                    }
                    $return .= '</div>';
                } else {
                    $return .= '<a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '" >
                    <button class="btn btn-outline-primary btn-sm" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                }

                return $return;
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }

    public function get_data_rencana_produk($customer_id, $instansi, $tahun)
    {
        $data = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($customer_id, $instansi, $tahun) {
            $q->where(['customer_id' => $customer_id, 'instansi' => $instansi, 'tahun' => $tahun]);
        })->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->PenjualanProduk->nama;
            })
            ->addColumn('qty', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('realisasi', function ($data) use ($customer_id, $instansi, $tahun) {
                $res = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($customer_id, $instansi, $tahun) {
                    $q->where(['customer_id' => $customer_id, 'instansi' => $instansi])->whereBetween('tgl_buat', [$tahun . '-01-01', $tahun . '-12-31']);
                })->where('penjualan_produk_id', $data->PenjualanProduk->id)->sum('jumlah');

                return $res;
            })
            ->addColumn('harga', function ($data) {
                return $data->harga;
            })
            ->addColumn('aksi', function ($data) {
                $res = '<button type="button" class="btn btn-outline-primary btn-circle" id="btntransfer" data-id="' . $data->id . '" data-nama_produk="' . $data->penjualanproduk->nama . '" data-produk="' . $data->penjualanproduk->id . '" data-jumlah="' . $data->jumlah . '" data-harga="' . $data->harga . '"><i class="fas fa-plus"></i></button>';
                return $res;
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
        if ($request->namadistributor == 'belum') {
            $c_id = '484';
        } else {
            $c_id = $request->customer_id;
        }

        if ($request->no_paket != "") {
            $nopaket = $request->jenis_paket . $request->no_paket;
        } else {
            $nopaket = "";
        }


        $Ekatalog = Ekatalog::create([
            'customer_id' => $c_id,
            'provinsi_id' => $request->provinsi,
            'pesanan_id' => $x,
            'no_paket' => $nopaket,
            'no_urut' => $request->no_urut,
            'deskripsi' => $request->deskripsi,
            'instansi' => $request->instansi,
            'alamat' => $request->alamatinstansi,
            'satuan' => $request->satuan_kerja,
            'status' => $request->status,
            'tgl_kontrak' => $request->batas_kontrak,
            'tgl_buat' => $request->tanggal_pemesanan,
            'tgl_edit' => $request->tanggal_edit,
            'ket' => $request->keterangan,
            'log' => 'penjualan'
        ]);

        $bool = true;
        if ($Ekatalog) {
            if ($request->status != 'draft') {
                if($request->status == 'batal' && !isset($request->penjualan_produk_id)){
                    $bool = true;
                } else {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        if (empty($request->produk_ongkir[$i])) {
                            $ongkir[$i] = 0;
                        } else {
                            $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                        }
                        $dekat = DetailPesanan::create([
                            'pesanan_id' => $x,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => $ongkir[$i],
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
                }
            } else {
                if($request->isi_produk == "isi"){
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        if (empty($request->produk_ongkir[$i])) {
                            $ongkir[$i] = 0;
                        } else {
                            $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                        }
                        $dekat = DetailPesanan::create([
                            'pesanan_id' => $x,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => $ongkir[$i],
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

            }
        } else {
            $bool = false;
        }
        if ($bool == true) {
            return redirect()->back()->with('success', 'Berhasil menambahkan Ekatalog');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Gagal menambahkan Ekatalog');
        }
    } else if ($request->jenis_penjualan == 'spa' || $request->jenis_penjualan == 'spb') {
        $count_array = count($request->jenis_pen);
        if (in_array("jasa", $request->jenis_pen) && $count_array == 1) {
            $k = '11';
        } else {
            $k = '9';
        }
        if ($request->jenis_penjualan == 'spa') {
            $var = 'SPA';
        } else if ($request->jenis_penjualan == 'spb') {
            $var = 'SPB';
        }
        $pesanan = Pesanan::create([
            'so' => $this->createSO($var),
            'no_po' => $request->no_po,
            'tgl_po' => $request->tanggal_po,
            'no_do' => $request->no_do,
            'tgl_do' => $request->tanggal_do,
            'ket' =>  $request->keterangan,
            'log_id' => $k
        ]);
        $x = $pesanan->id;
        if ($request->jenis_penjualan == 'spa') {
            $p = Spa::create([
                'customer_id' => $request->customer_id,
                'pesanan_id' => $x,
                'ket' => $request->keterangan,
                'log' => 'po'
            ]);
        } else if ($request->jenis_penjualan == 'spb') {
            $p = Spb::create([
                'customer_id' => $request->customer_id,
                'pesanan_id' => $x,
                'ket' => $request->keterangan,
                'log' => 'po'
            ]);
        }
        $bool = true;
        if ($p) {
            if (in_array("produk", $request->jenis_pen)) {
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
            }
            if (in_array("sparepart", $request->jenis_pen)) {
                for ($i = 0; $i < count($request->part_id); $i++) {
                    $dspb = DetailPesananPart::create([
                        'pesanan_id' => $x,
                        'm_sparepart_id' => $request->part_id[$i],
                        'jumlah' => $request->part_jumlah[$i],
                        'harga' => str_replace('.', "", $request->part_harga[$i]),
                        'ongkir' => 0,
                    ]);
                    if (!$dspb) {
                        $bool = false;
                    }
                }
            }
            if (in_array("jasa", $request->jenis_pen)) {
                for ($i = 0; $i < count($request->jasa_id); $i++) {
                    $dspb = DetailPesananPart::create([
                        'pesanan_id' => $x,
                        'm_sparepart_id' => $request->jasa_id[$i],
                        'jumlah' => 1,
                        'harga' => str_replace('.', "", $request->jasa_harga[$i]),
                        'ongkir' => 0,
                    ]);

                    $qcspb = OutgoingPesananPart::create([
                        'detail_pesanan_part_id' => $dspb->id,
                        'tanggal_uji' => $request->tanggal_po,
                        'jumlah_ok' => 1,
                        'jumlah_nok' => 0
                    ]);

                    if (!$dspb) {
                        $bool = false;
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
    public function edit_penjualan_pesanan($id, $jenis)
    {
        $data = "";
        if ($jenis == "ekatalog") {
            $data = Ekatalog::find($id);
        } else if ($jenis == "spa") {
            $data = Spa::find($id);
        } else if ($jenis == "spb") {
            $data = Spb::find($id);
        }
        return view('page.penjualan.penjualan.edit_pesanan', ['data' => $data, 'id' => $id, 'jenis' => $jenis]);
    }

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
        if ($request->namadistributor == 'belum') {
            $c_id = '484';
        } else {
            $c_id = $request->customer_id;
        }



        $ekatalog = Ekatalog::find($id);

        if ($request->status_akn == 'draft') {
            if ($request->no_paket == '') {
                $c_akn = NULL;
            } else {
                $c_akn = $request->has('isi_nopaket') ? $request->jenis_paket . $request->no_paket : NULL;
            }

            $akn = $c_akn;
        } else {
            $akn = $ekatalog->no_paket;
        }

        $poid = $ekatalog->pesanan_id;
        $ekatalog->customer_id = $c_id;
        $ekatalog->provinsi_id = $request->provinsi;
        $ekatalog->deskripsi = $request->deskripsi;
        $ekatalog->instansi = $request->instansi;
        $ekatalog->alamat = $request->alamatinstansi;
        $ekatalog->tgl_kontrak = $request->batas_kontrak;
        $ekatalog->tgl_buat = $request->tgl_buat;
        $ekatalog->tgl_edit = $request->tgl_edit;
        $ekatalog->no_urut = $request->no_urut;
        $ekatalog->satuan = $request->satuan_kerja;
        $ekatalog->status = $request->status_akn;
        $ekatalog->ket = $request->keterangan;
        $ekatalog->no_paket = $akn;
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
                if ($request->status_akn != "draft") {
                    if ($request->status_akn == "batal" && !isset($request->penjualan_produk_id)){
                        $bool = true;
                    }else{
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        if (empty($request->produk_ongkir[$i])) {
                            $ongkir[$i] = 0;
                        } else {
                            $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                        }
                        $c = DetailPesanan::create([
                            'pesanan_id' => $poid,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => $ongkir[$i],
                            'detail_rencana_penjualan_id' => $request->rencana_id[$i],
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
                        } else{
                                $bool = false;
                        }
                    }
                }
            } elseif ($request->status_akn == "draft"){
                if($request->isi_produk == "isi"){
                for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                    if (empty($request->produk_ongkir[$i])) {
                        $ongkir[$i] = 0;
                    } else {
                        $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                    }
                    $c = DetailPesanan::create([
                        'pesanan_id' => $poid,
                        'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                        'jumlah' => $request->produk_jumlah[$i],
                        'harga' => str_replace('.', "", $request->produk_harga[$i]),
                        'ongkir' => $ongkir[$i],
                        'detail_rencana_penjualan_id' => $request->rencana_id[$i],
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

                if ($request->jenis_pen) {
                    if (in_array("produk", $request->jenis_pen)) {
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
                        $dspa = DetailPesanan::where('pesanan_id', $poid)->get();
                        if (count($dspa) > 0) {
                            $deldspa = DetailPesanan::where('pesanan_id', $poid)->delete();
                            if (!$deldspa) {
                                $bool = false;
                            }
                        }
                    }

                    if (in_array("sparepart", $request->jenis_pen)) {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
                                $bool = false;
                            }
                        }
                        if ($dspb) {
                            for ($i = 0; $i < count($request->part_id); $i++) {
                                $dspb = DetailPesananPart::create([
                                    'pesanan_id' => $poid,
                                    'm_sparepart_id' => $request->part_id[$i],
                                    'jumlah' => $request->part_jumlah[$i],
                                    'harga' => str_replace('.', "", $request->part_harga[$i]),
                                    'ongkir' => 0,
                                ]);
                                if (!$dspb) {
                                    $bool = false;
                                }
                            }
                        } else {
                            $bool = false;
                        }
                    } else {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
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
        $spa = Spb::find($id);
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
                if ($request->jenis_pen) {
                    if (in_array("produk", $request->jenis_pen)) {
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
                        $dspa = DetailPesanan::where('pesanan_id', $poid)->get();
                        if (count($dspa) > 0) {
                            $deldspa = DetailPesanan::where('pesanan_id', $poid)->delete();
                            if (!$deldspa) {
                                $bool = false;
                            }
                        }
                    }

                    if (in_array("sparepart", $request->jenis_pen)) {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
                                $bool = false;
                            }
                        }
                        if ($dspb) {
                            for ($i = 0; $i < count($request->part_id); $i++) {
                                $dspb = DetailPesananPart::create([
                                    'pesanan_id' => $poid,
                                    'm_sparepart_id' => $request->part_id[$i],
                                    'jumlah' => $request->part_jumlah[$i],
                                    'harga' => str_replace('.', "", $request->part_harga[$i]),
                                    'ongkir' => 0,
                                ]);
                                if (!$dspb) {
                                    $bool = false;
                                }
                            }
                        } else {
                            $bool = false;
                        }
                    } else {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
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
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'Berhasil mengubah SPA');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Gagal mengubah SPA');
        }
    }

    public function update_penjualan_pesanan(Request $request, $id, $jenis)
    {
        $bool = true;
        if ($jenis == "ekatalog") {
            $po = Pesanan::find($id);
            $ekat = Ekatalog::find($po->Ekatalog->id);
            $ekat->no_urut = $request->no_urut;
            $u = $ekat->save();
            if ($u) {
                if (!empty($request->no_do) && !empty($request->tgl_do)) {
                    $po->no_do = $request->no_do;
                    $po->tgl_do = $request->tgl_do;
                    $pou = $po->save();
                    if (!$pou) {
                        $bool = false;
                    }
                } else if (empty($request->no_do) && empty($request->tgl_do)) {
                    $po->no_do = "";
                    $po->tgl_do = NULL;
                    $pou = $po->save();
                    $bool = true;
                } else {
                    $bool = false;
                }
            } else {
                $bool = false;
            }
        } else {
            $po = Pesanan::find($id);
            if (!empty($request->no_do) && !empty($request->tgl_do)) {
                $po->no_do = $request->no_do;
                $po->tgl_do = $request->tgl_do;
                $pou = $po->save();

                if (!$pou) {
                    $bool = false;
                }
            } else if (empty($request->no_do) && empty($request->tgl_do)) {
                $po->no_do = "";
                $po->tgl_do = NULL;
                $pou = $po->save();
                $bool = true;
            } else {
                $bool = false;
            }
        }

        if ($bool == true) {
            return response()->json(['data' => 'success']);
        } else if ($bool == false) {
            return response()->json(['data' => 'error']);
        }
    }

    //Delete
    public function delete_ekatalog($id)
    {
        $e = Ekatalog::find($id);
        $poid = $e->pesanan_id;

        $bool = true;
        $cdpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
            $q->where('pesanan_id', $poid);
        })->get();
        if (count($cdpp) > 0) {
            $dpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->delete();
            if (!$dpp) {
                $bool = false;
            }
        }

        $cdp = DetailPesanan::where('pesanan_id', $poid)->get();
        if (count($cdp) > 0) {
            $dp = DetailPesanan::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }
        if ($bool == true) {
            $d = $e->delete();
            if ($d) {
                if (!empty($poid)) {
                    $p = Pesanan::where('id', $poid)->delete();
                    if ($p) {
                        return response()->json(['data' => 'success']);
                    } else if (!$p) {
                        return response()->json(['data' => 'error']);
                    }
                } else {
                    return response()->json(['data' => 'success']);
                }
            } else {
                return response()->json(['data' => 'error']);
            }
        } else {
            return response()->json(['data' => 'error']);
        }
    }

    public function delete_spa($id)
    {
        $e = Spa::find($id);
        $poid = $e->pesanan_id;

        $bool = true;

        $cdpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
            $q->where('pesanan_id', $poid);
        })->get();
        if (count($cdpp) > 0) {
            $dpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->delete();
            if (!$dpp) {
                $bool = false;
            }
        }

        $cdp = DetailPesanan::where('pesanan_id', $poid)->get();
        if (count($cdp) > 0) {
            $dp = DetailPesanan::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }

        $cdppt = DetailPesananPart::where('pesanan_id', $poid)->get();
        if (count($cdppt) > 0) {
            $dp = DetailPesananPart::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }
        if ($bool == true) {
            $d = $e->delete();
            if ($d) {
                if (!empty($poid)) {
                    $p = Pesanan::where('id', $poid)->delete();
                    if ($p) {
                        return response()->json(['data' => 'success']);
                    } else if (!$p) {
                        return response()->json(['data' => 'error']);
                    }
                } else {
                    return response()->json(['data' => 'success']);
                }
            } else {
                return response()->json(['data' => 'error']);
            }
        } else {
            return response()->json(['data' => 'error']);
        }
    }

    public function delete_spb($id)
    {
        $e = Spb::find($id);
        $poid = $e->pesanan_id;

        $bool = true;

        $cdpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
            $q->where('pesanan_id', $poid);
        })->get();
        if (count($cdpp) > 0) {
            $dpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->delete();
            if (!$dpp) {
                $bool = false;
            }
        }

        $cdp = DetailPesanan::where('pesanan_id', $poid)->get();
        if (count($cdp) > 0) {
            $dp = DetailPesanan::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }

        $cdppt = DetailPesananPart::where('pesanan_id', $poid)->get();
        if (count($cdppt) > 0) {
            $dp = DetailPesananPart::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }
        if ($bool == true) {
            $d = $e->delete();
            if ($d) {
                if (!empty($poid)) {
                    $p = Pesanan::where('id', $poid)->delete();
                    if ($p) {
                        return response()->json(['data' => 'success']);
                    } else if (!$p) {
                        return response()->json(['data' => 'error']);
                    }
                } else {
                    return response()->json(['data' => 'success']);
                }
            } else {
                return response()->json(['data' => 'error']);
            }
        } else {
            return response()->json(['data' => 'error']);
        }
    }

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

    public function cancel_penjualan($id, $jenis)
    {
        $data = NULL;
        if ($jenis == "spa") {
            $data = Spa::find($id);
        } else if ($jenis == "spb") {
            $data = Spb::find($id);
        }

        return view('page.penjualan.penjualan.cancel', ['id' => $id, 'data' => $data]);
    }

    public function cancel_spa_spb(Request $r)
    {
        if ($r->jenis == "spa") {
            $spa = Spa::find($r->id);
            $spa->log = "batal";
            $u = $spa->save();
            if ($u) {
                return response()->json(['data' => 'success']);
            } else if (!$u) {
                return response()->json(['data' => 'error']);
            }
        } else if ($r->jenis == "spb") {
            $spb = Spb::find($r->id);
            $spb->log = "batal";
            $u = $spb->save();
            if ($u) {
                return response()->json(['data' => 'success']);
            } else if (!$u) {
                return response()->json(['data' => 'error']);
            }
        }
    }
    // public function delete_ekatalog($id)
    // {
    //     $ekatalog = Ekatalog::findOrFail($id);
    //     $ekatalog->delete();
    // }
    // public function delete_spa($id)
    // {
    //     $ekatalog = Spa::findOrFail($id);
    //     $ekatalog->delete();
    // }
    // public function delete_spb($id)
    // {
    //     $ekatalog = Spb::findOrFail($id);
    //     $ekatalog->delete();
    // }

    // Laporan
    public function  get_data_laporan_penjualan($penjualan, $distributor, $tanggal_awal, $tanggal_akhir)
    {
        $x = explode(',', $penjualan);
        if ($distributor == 'semua') {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spa)->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($x == ['ekatalog', 'spa']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['ekatalog', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['spa', 'spb']) {

                $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Spa->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($penjualan == 'ekatalog') {
                $data  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else if ($penjualan == 'spa') {
                $prd  = collect(DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $part =  collect(DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $prd->merge($part);
            } else if ($penjualan == 'spb') {
                $prd  = collect(DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $part =  collect(DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $prd->merge($part);
            }
        } else {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();

                $prd = $Ekatalog->merge($Spa)->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($x == ['ekatalog', 'spa']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $prd = $Ekatalog->merge($Spa);
                $data = $prd->merge($Part);
            } else if ($x == ['ekatalog', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['spa', 'spb']) {
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $part = $Part_Spa->merge($Part_Spb);
                $prd = $Spa->merge($Spb);
                $data = $part->merge($prd);
            } else if ($penjualan == 'ekatalog') {
                $data = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
            } else if ($penjualan == 'spa') {
                $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $data = $Spa->merge($Part);
            } else if ($penjualan == 'spb') {
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $data = $Spb->merge($Part);
            }
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
                    return '-';
                }
            })
            ->addColumn('satuan', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->satuan;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                if ($data->PenjualanProduk) {
                    return $data->penjualanproduk->nama;
                } else {
                    return $data->Sparepart->nama;
                }
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

    // public function get_data_laporan_penjualan($penjualan, $distributor, $tanggal_awal, $tanggal_akhir){
    //     $x = explode(',', $penjualan);
    //     $data = [];
    //     if($distributor == 'semua'){
    //         $ekat = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->Has('Ekatalog')->get();
    //         $ekatnopo = Pesanan::whereNull('no_po')->Has('Ekatalog')->get();
    //         $spa = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->Has('Spa')->get();
    //         $spanopo = Pesanan::whereNull('no_po')->Has('Spa')->get();
    //         $spb = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->Has('Spb')->get();
    //         $spbnopo = Pesanan::whereNull('no_po')->Has('Spb')->get();
    //         if($x == ['ekatalog']){
    //             $data = $ekat->merge($ekatnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa']){
    //             $data = $spa->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spb']){
    //             $data = $spb->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa']){
    //             $data = $ekat->merge($spa)->merge($ekatnopo)->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spb']){
    //             $data = $ekat->merge($spb)->merge($ekatnopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa', 'spb']){
    //             $data = $spa->merge($spb)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa', 'spb']){
    //             $data = $data = $ekat->merge($spa)->merge($spb)->merge($ekatnopo)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //     }
    //     else{
    //         $ekat = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->whereHas('Ekatalog', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $ekatnopo = Pesanan::whereNull('no_po')->whereHas('Ekatalog', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spa = $data = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->whereHas('Spa', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spanopo = Pesanan::whereNull('no_po')->whereHas('Spa', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spb = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->whereHas('Spb', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spbnopo = Pesanan::whereNull('no_po')->whereHas('Spb', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         if($x == ['ekatalog']){
    //             $data = $ekat->merge($ekatnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa']){
    //             $data = $spa->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spb']){
    //             $data = $spb->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa']){
    //             $data = $ekat->merge($spa)->merge($ekatnopo)->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spb']){
    //             $data = $ekat->merge($spb)->merge($ekatnopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa', 'spb']){
    //             $data = $spa->merge($spb)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa', 'spb']){
    //             $data = $data = $ekat->merge($spa)->merge($spb)->merge($ekatnopo)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //     }

    //     return datatables()->of($data)
    //         ->addIndexColumn()
    //         ->addColumn('so', function ($data) {
    //             return $data->so;
    //         })
    //         ->addColumn('no_paket', function ($data) {
    //             // if($data->so){
    //             //     $name = explode('/', $data->so);
    //                 if ($data->Ekatalog) {
    //                     return $data->Ekatalog->no_paket;
    //                 } else {
    //                     return '';
    //                 }
    //             // }
    //         })
    //         ->addColumn('no_po', function ($data) {
    //             if($data->no_po){
    //                 return '<div>'.$data->no_po.'</div><small>Tanggal PO '.Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y').'</small>';
    //             }
    //         })
    //         ->addColumn('no_sj', function () {
    //             return '-';
    //         })
    //         ->addColumn('nama_customer', function ($data) {
    //             // if($data->so){
    //                 // $name = explode('/', $data->so);
    //                 if ($data->Ekatalog) {
    //                     return $data->Ekatalog->Customer->nama;
    //                 } elseif ($data->Spa) {
    //                     return $data->Spa->Customer->nama;
    //                 } else {
    //                     return $data->Spb->Customer->nama;
    //                 }
    //             // }
    //         })
    //         ->addColumn('tgl_kontrak', function ($data) {
    //             if($data->so){
    //                 $name = explode('/', $data->so);
    //                 if ($name[1] == 'EKAT') {
    //                     if (isset($data->Ekatalog->tgl_kontrak)) {
    //                         $tgl_sekarang = Carbon::now()->format('Y-m-d');
    //                         $tgl_parameter = $data->Ekatalog->tgl_kontrak;

    //                         if (isset($data->Pesanan->so)) {
    //                             if ($data->Pesanan->getJumlahPesanan() == $data->Pesanan->getJumlahKirim()) {
    //                                 return Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y');
    //                             } else {
    //                                 if ($tgl_sekarang < $tgl_parameter) {
    //                                     $to = Carbon::now();
    //                                     $from = $data->Ekatalog->tgl_kontrak;
    //                                     $hari = $to->diffInDays($from);
    //                                     if ($hari > 7) {
    //                                         return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                         <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                     } else if ($hari > 0 && $hari <= 7) {
    //                                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                         <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                     } else {
    //                                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                         <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                                     }
    //                                 } else if ($tgl_sekarang == $tgl_parameter) {
    //                                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                                 } else {
    //                                     $to = Carbon::now();
    //                                     $from = $data->Ekatalog->tgl_kontrak;
    //                                     $hari = $to->diffInDays($from);
    //                                     return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
    //                                 }
    //                             }
    //                         } else {
    //                             if ($tgl_sekarang < $tgl_parameter) {
    //                                 $to = Carbon::now();
    //                                 $from = $data->Ekatalog->tgl_kontrak;
    //                                 $hari = $to->diffInDays($from);
    //                                 if ($hari > 7) {
    //                                     return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                 } else if ($hari > 0 && $hari <= 7) {
    //                                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                 } else {
    //                                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                                 }
    //                             } else if ($tgl_sekarang == $tgl_parameter) {
    //                                 return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                 <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                             } else {
    //                                 $to = Carbon::now();
    //                                 $from = $data->Ekatalog->tgl_kontrak;
    //                                 $hari = $to->diffInDays($from);
    //                                 return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                 <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
    //                             }
    //                         }
    //                     } else {
    //                         return '-';
    //                     }
    //                 } else {
    //                     return '';
    //                 }
    //             }
    //         })
    //         ->addColumn('tgl_kirim', function () {
    //             return '-';
    //         })
    //         ->addColumn('tgl_po', function ($data) {
    //             if($data->tgl_po){
    //                 return $data->tgl_po;
    //             }
    //         })
    //         ->addColumn('instansi', function ($data) {
    //             // if($data->so){
    //             //     $name = explode('/', $data->so);
    //                 if ($data->Ekatalog) {
    //                     return '<div>'.$data->Ekatalog->instansi.'</div><small>'.$data->Ekatalog->satuan.'</small>';
    //                 } else {
    //                     return '-';
    //                 }
    //             // }
    //         })
    //         ->addColumn('satuan', function ($data) {
    //             // $name = explode('/', $data->so);
    //             // if ($name[1] == 'EKAT') {
    //             //     return $data->Ekatalog->satuan;
    //             // } else {
    //             //     return '-';
    //             // }
    //         })
    //         ->addColumn('nama_produk', function ($data) {
    //         //     if ($data->PenjualanProduk) {
    //         //         return $data->penjualanproduk->nama;
    //         //     } else {
    //         //         return $data->Sparepart->nama;
    //         //     }
    //         })
    //         ->addColumn('no_seri', function () {
    //             return '-';
    //         })
    //         ->addColumn('jumlah', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('harga', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('subtotal', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('total', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('log', function ($data) {
    //             $datas = "";
    //             if ($data->log != "batal") {
    //                 if (!empty($data->log_id)) {
    //                     if ($data->State->nama == "Penjualan") {
    //                         $datas .= '<span class="red-text badge">';
    //                     } else if ($data->State->nama == "PO") {
    //                         $datas .= '<span class="purple-text badge">';
    //                     } else if ($data->State->nama == "Gudang") {
    //                         $datas .= '<span class="orange-text badge">';
    //                     } else if ($data->State->nama == "QC") {
    //                         $datas .= '<span class="yellow-text badge">';
    //                     } else if ($data->State->nama == "Belum Terkirim") {
    //                         $datas .= '<span class="red-text badge">';
    //                     } else if ($data->State->nama == "Terkirim Sebagian") {
    //                         $datas .= '<span class="blue-text badge">';
    //                     } else if ($data->State->nama == "Kirim") {
    //                         $datas .= '<span class="green-text badge">';
    //                     }

    //                     $datas .= ucfirst($data->State->nama) . '</span>';
    //                 } else {
    //                     $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
    //                 }
    //             } else {
    //                 $datas .= '<span class="red-text badge">Batal</span>';
    //             }
    //             return $datas;
    //         })
    //         ->addColumn('ket', function ($data) {
    //             if($data->so){
    //                 $name = explode('/', $data->so);
    //                 if ($name[1] == 'EKAT') {
    //                     return $data->Ekatalog->ket;
    //                 } elseif ($name[1] == 'SPA') {
    //                     return $data->Spa->ket;
    //                 } else {
    //                     return $data->Spb->ket;
    //                 }
    //             }
    //         })
    //         ->addColumn('kosong', function () {
    //             return '';
    //         })
    //         ->rawColumns(['tgl_kontrak', 'log', 'no_po', 'instansi'])
    //         ->make(true);
    // }

    // public function laporan(Request $request)
    // {
    //     return Excel::download(new LaporanPenjualan($request->customer_id ?? '', $request->penjualan ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_penjualan.xlsx');
    // }

    //Chart
    public function chart_penjualan()
    {
        $now = Carbon::now();
        $tgl_awal = $now->year . "-01-01";
        $tgl_akhir = $now->year . "-12-31";
        //EKAT
        $ekatalog = Pesanan::Has('Ekatalog')
            ->whereBetween('tgl_po', [$tgl_awal, $tgl_akhir])
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
            ->whereBetween('tgl_po', [$tgl_awal, $tgl_akhir])
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
            ->whereBetween('tgl_po', [$tgl_awal, $tgl_akhir])
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

    // //Dashboard
    // public function dashboard()
    // {
    //     $penj = Pesanan::whereNull('so')->where('log_id', '7')->count();
    //     $gudang = 0;
    //     $qc = 0;
    //     $log = 0;
    //     $dc = 0;
    //     $pes = Pesanan::whereNotIn('log_id', ['7', '10'])->get();
    //     foreach ($pes as $i) {
    //         if (isset($i->DetailPesanan)) {
    //             if ($i->getJumlahSeri() < $i->getJumlahPesanan()) {
    //                 $gudang = $gudang + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesanan)) {
    //             if ($i->getJumlahCek() < $i->getJumlahPesanan()) {
    //                 $qc = $qc + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesanan)) {
    //             if ($i->getJumlahKirim() < $i->getJumlahPesanan()) {
    //                 $log = $log + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesananPart)) {
    //             if ($i->getJumlahKirimPart() < $i->getJumlahPesananPart()) {
    //                 $log = $log + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesananPart)) {
    //             if ($i->getJumlahCoo() < $i->getJumlahPesanan()) {
    //                 $dc = $dc + 1;
    //             }
    //         }
    //     }


    //     // $belum_so = Pesanan::whereNull('so')->get()->count();
    //     // $so_belum_gudang = Pesanan::DoesntHave('TFProduksi')->get()->count();
    //     // $so_belum_qc = Pesanan::DoesntHave('DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->get()->count();
    //     // $so_belum_logistik = Pesanan::DoesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik')->get()->count();
    //     return view('page.penjualan.dashboard', ['belum_so' => $penj, 'so_belum_gudang' => $gudang, 'so_belum_qc' => $qc, 'so_belum_logistik' => $log]);
    // }

    public function dashboard()
    {
        $penj = Ekatalog::whereHas('Pesanan', function($q){ $q->whereNull('so')->where('log_id', '7')->whereNotIn('log_id', ['20', '10']);})->where('status', 'sepakat')->count();

        $gudang = Pesanan::whereIn('id', function($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->groupBy('pesanan.id')
                ->havingRaw('NOT EXISTS(select *
                    from t_gbj_noseri
                    left join t_gbj_detail on t_gbj_detail.id = t_gbj_noseri.t_gbj_detail_id
                    left join t_gbj on t_gbj.id = t_gbj_detail.t_gbj_id
                    where t_gbj.pesanan_id = pesanan.id)');
            })->whereNotIn('log_id', ['7', '20', '10'])->count();
        //QC
        $qcprd = Pesanan::whereIn('id', function($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('t_gbj', 't_gbj.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.t_gbj_id', '=', 't_gbj.id')
                    ->leftJoin('t_gbj_noseri', 't_gbj_noseri.t_gbj_detail_id', '=', 't_gbj_detail.id')
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(t_gbj_noseri.id) > (select count(noseri_detail_pesanan.id)
                        from noseri_detail_pesanan
                        left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                        left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                        where detail_pesanan.pesanan_id = pesanan.id)');
                })->whereNotIn('log_id', ['7', '20', '10'])
                ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi']);

        $qcpart = Pesanan::whereIn('id', function($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw("m_sparepart.kode NOT LIKE '%JASA%'")
                    ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                        select sum(outgoing_pesanan_part.jumlah_ok)
                        from outgoing_pesanan_part
                        left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS (select * from outgoing_pesanan_part
                        left join detail_pesanan_part on detail_pesanan_part.id = outgoing_pesanan_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)")
                    ->groupBy('pesanan.id');
                })->whereNotIn('log_id', ['7', '20', '10'])
                ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi'])
                ->union($qcprd)
                ->orderBy('id', 'desc')
                ->count();

        $qc = $qcpart;

        //LOGISTIK
        $logprd = Pesanan::whereIn('id', function($q) {
            $q->select('pesanan.id')
            ->from('pesanan')
            ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
            ->groupBy('pesanan.id')
            ->havingRaw('count(noseri_detail_pesanan.id) > (select count(noseri_logistik.id)
            from noseri_logistik
            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
            where detail_pesanan.pesanan_id = pesanan.id)');
        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '9', '20', '10']);

        $logpart = Pesanan::whereIn('id', function($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->groupBy('pesanan.id')
                ->havingRaw("(sum(outgoing_pesanan_part.jumlah_ok) > (
                    select sum(detail_logistik_part.jumlah)
                    from detail_logistik_part
                    left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                    left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                    where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS
                       (select * from detail_logistik_part
                        left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)) AND SUM(outgoing_pesanan_part.jumlah_ok) > 0")
                ;
            })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '20', '10']);

        $logjasa = Pesanan::whereIn('id', function($q) {
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                    ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                        select sum(detail_pesanan_part.jumlah)
                        from detail_logistik_part
                        left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS(
                            select * from detail_logistik_part
                            left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id)")
                    ->groupBy('pesanan.id');
                })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '20', '10'])->union($logprd)->union($logpart)->orderBy('id', 'desc')->count();

        $log = $logjasa;
        return view('page.penjualan.dashboard', ['belum_so' => $penj, 'so_belum_gudang' => $gudang, 'so_belum_qc' => $qc, 'so_belum_logistik' => $log ]);
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
            return $e;
        } else {
            $e = Ekatalog::where('no_paket', 'AK1-' . $val)->count();
            return $e;
        }
    }

    public function check_no_urut($id, $val)
    {
        if ($id != "0") {
            $e = Ekatalog::where('no_urut', $val)->whereNotIn('id', [$id])->count();
            return $e;
        } else {
            $e = Ekatalog::where('no_urut',$val)->count();
            return $e;
        }
    }

    public function check_variasi_jumlah($id)
    {
        $gbj = GudangBarangJadi::find($id);
        $jumlah_ekatalog = $this->get_count_ekatalog($id, $gbj->produk_id, "sepakat") + $this->get_count_ekatalog($id, $gbj->produk_id, "negosiasi");
        $jumlah_po = $this->get_count_spa_po($id, $gbj->produk_id);
        $jumlah = $gbj->stok - ($jumlah_ekatalog + $jumlah_po);
        return $jumlah;
    }

    public function check_alamat(Request $request)
    {
        $data = Ekatalog::where('alamat', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('alamat')->get();
        echo json_encode($data);
    }

    public function get_count_ekatalog($id, $produk_id, $status)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan.Ekatalog', function ($q) use ($status) {
            $q->where('status', '=', $status);
        })->whereHas('Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
        })->get();
        $jumlah = 0;
        foreach ($res as $a) {
            foreach ($a->PenjualanProduk->Produk as $b) {
                if ($b->id == $produk_id) {
                    $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                }
            }
        }
        return $jumlah;
    }

    public function get_count_spa_po($id, $produk_id)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
        })->doesntHave('Pesanan.Ekatalog')->get();
        $jumlah = 0;
        foreach ($res as $a) {
            foreach ($a->PenjualanProduk->Produk as $b) {
                if ($b->id == $produk_id) {
                    $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                }
            }
        }
        return $jumlah;
    }


    public function export_laporan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan)
    {
        $x = explode(',', $jenis);
        $waktu = Carbon::now();

        if ($x == ['ekatalog', 'spa', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan), 'Laporan Penjualan Semua ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['ekatalog', 'spa']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan), 'Laporan Penjualan Ekatalog dan SPA ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['ekatalog', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan), 'Laporan Penjualan Ekatalog dan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['spa', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan), 'Laporan Penjualan SPA dan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'ekatalog') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan), 'Laporan Penjualan Ekatalog ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'spa') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan), 'Laporan Penjualan SPA ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'spb') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $jenis_laporan), 'Laporan Penjualan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        }
    }

    public function manager_penjualan_show()
    {
        return view('manager.penjualan.so.show');
    }

    public function manager_penjualan_show_data($jenis, $value)
    {
        if($jenis == "ekatalog"){
            $x = explode(',', $value);
            $data = "";

            if ($value == 'semua') {
                $data  = Ekatalog::with(['Pesanan.State','Customer', 'Provinsi'])->addSelect(['tgl_kontrak_custom' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog as e')
                    ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                    ->whereColumn('e.id', 'ekatalog.id')
                    ->limit(1);
                    }])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get();
            } else {
                $data  = Ekatalog::with(['Pesanan.State','Customer'])->addSelect(['tgl_kontrak_custom' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog as e')
                    ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                    ->whereColumn('e.id', 'ekatalog.id')
                    ->limit(1);
                    }])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('no_urut', function ($data) {
                    return $data->no_urut;
                })
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->so)) {
                            return $data->Pesanan->so;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if (!empty($data->status)) {
                        if ($data->status == "batal") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->status == "negosiasi") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->status == "draft") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->status == "sepakat") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->status) . '</span>';
                    }
                    return $datas;
                })
                ->addColumn('nopo', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->no_po)) {
                            return $data->Pesanan->no_po;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->editColumn('tgl_buat', function ($data) {
                    if (!empty($data->tgl_buat)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
                    }
                })
                ->editColumn('tgl_edit', function ($data) {
                    if (!empty($data->tgl_edit)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_edit)->format('d-m-Y');
                    }
                })->editColumn('tgl_kontrak', function ($data) {

                    if($data->tgl_kontrak_custom != ""){
                        if($data->Pesanan->log_id != "10"){
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak_custom;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
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
                            } else {
                                return  '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                        <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else{
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    if (isset($data->Customer)) {
                        return $data->Customer['nama'];
                    } else {
                        return '-';
                    }
                })
                ->addColumn('button', function ($data) {
                    $return = "";
                    if($data->Pesanan->log_id != "20"){
                        $return = '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    }
                    else {
                        $return = '<a data-toggle="modal" data-jenis="ekatalog" class="batalmodal" data-id="' . $data->id . '" >
                            <button class="btn btn-outline-danger btn-sm" type="button">
                                <i class="fas fa-times"></i>
                                Batal
                                </button>
                            </a>';
                    }
                    return $return;
                })
                ->rawColumns(['button', 'status', 'tgl_kontrak'])
                ->make(true);
        }else if($jenis == "spa"){
            $x = explode(',', $value);
            if ($value == 'semua') {
                $data  = Spa::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get();
            } else {
                $data  = Spa::with(['Pesanan.State','Customer'])->whereHas('pesanan', function ($q) use ($x) {
                    $q->whereIN('log_id', $x);
                })->orderBy('id', 'DESC')->get();
            }
            return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if ($data->log != "batal") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->Pesanan->State->nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->Pesanan->State->nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    } else {
                        $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                    }
                } else {
                    $datas .= '<span class="red-text badge">Batal</span>';
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
                $return = "";
                if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                }
                else{
                    $return = '<a data-toggle="modal" data-jenis="spa" class="batalmodal" data-id="' . $data->id . '" >
                        <button class="btn btn-outline-danger btn-sm" type="button">
                            <i class="fas fa-times"></i>
                            Batal
                            </button>
                        </a>';
                }
                return $return;

            })
            ->rawColumns(['button', 'status'])
            ->make(true);
        }else if($jenis == "spb"){
            $x = explode(',', $value);
            if ($value == 'semua') {
                $data  = Spb::with(['Pesanan.State','Customer'])->orderBy('id', 'DESC')->get();
            } else {
                $data  = Spb::with(['Pesanan.State','Customer'])->whereHas('pesanan', function ($q) use ($x) {
                    $q->whereIN('log_id', $x);
                })->orderBy('id', 'DESC')->get();
            }
            return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if ($data->log != "batal") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->Pesanan->State->nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->Pesanan->State->nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->Pesanan->State->nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                    } else {
                        $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                    }
                } else {
                    $datas .= '<span class="red-text badge">Batal</span>';
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
                $return = "";
                if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                }
                else{
                    $return = '<a data-toggle="modal" data-jenis="spb" class="batalmodal" data-id="' . $data->id . '" >
                        <button class="btn btn-outline-danger btn-sm" type="button">
                            <i class="fas fa-times"></i>
                            Batal
                            </button>
                        </a>';
                }
                return $return;
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
        }
    }
}



