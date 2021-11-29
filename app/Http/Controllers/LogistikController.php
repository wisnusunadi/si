<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Ekatalog;
use App\Models\Logistik;
use Illuminate\Http\Request;
use PDF;
use App\Models\Pesanan;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\NoseriTGbj;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\returnSelf;

class LogistikController extends Controller
{
    public function pdf_surat_jalan()
    {
        $customPaper = array(0, 0, 684.8094, 792.9372);
        $pdf = PDF::loadView('page.logistik.pengiriman.print_sj')->setPaper($customPaper);
        return $pdf->stream('');
    }


    public function get_data_select_produk($detail_produk, $pesanan_id)
    {
        $x = explode(',', $detail_produk);
        if ($detail_produk == '0') {
            $data = DetailPesanan::where('pesanan_id', $pesanan_id)->get();
        } else {
            $data = DetailPesanan::whereIN('id', $x)->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->make(true);
    }
    public function get_data_detail_so($id)
    {
        $data = DetailPesanan::where('pesanan_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                return '  <div class="form-check">
                <input class=" form-check-input yet detail_produk_id"  data-id="' . $data->id . '" type="checkbox" data-value="' . $data->pesanan->id . '" />
                </div>';
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('button', function () {
                return '<a type="button" class="noserishow" data-id="3"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['checkbox', 'button'])
            ->make(true);
    }



    //Get Data 
    public function get_data_so()
    {
        $data = TFProduksi::Has('Pesanan.DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->get();
        // $data = Ekatalog::Has('Pesanan.TFProduksi.detail.seri.NoseriDetailPesanan');
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
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
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->alamat;
                } else {
                    return $data->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('telp', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->telp;
                } else {
                    return $data->Pesanan->Spb->Customer->telp;
                }
            })
            ->addColumn('ket', function ($data) {
                return $data->pesanan->ket;
            })
            ->addColumn('status', function () {
                return '<span class="badge yellow-text">Sebagian dikirim</span>';
            })
            ->addColumn('batas', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    $x =  'ekatalog';
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status)->format('Y-m-d');
                    $param = "";

                    if ($tgl_sekarang < $tgl_parameter) {
                        $to = Carbon::now();
                        $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                        $hari = $to->diffInDays($from);

                        if ($hari > 7) {
                            $param = ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $hari . ' Hari</small>';
                        } else if ($hari > 0 && $hari <= 7) {
                            $param = ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                        } else {
                            $param = '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                        }
                    } elseif ($tgl_sekarang == $tgl_parameter) {
                        $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                    } else {
                        $to = Carbon::now();
                        $from = $this->getHariBatasKontrak($data->pesanan->ekatalog->tgl_kontrak, $data->pesanan->ekatalog->provinsi->status);
                        $hari = $to->diffInDays($from);
                        $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                    }
                    return $param;
                } else {
                    return '';
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->pesanan->so);
                $x = $name[1];
                if ($x == 'EKAT') {
                    $y = $data->Pesanan->ekatalog->id;
                } elseif ($x == 'SPA') {
                    $y = $data->Pesanan->spa->id;
                } else {
                    $y = $data->Pesanan->spb->id;
                }
                return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="' . route('logistik.so.detail', [$y, $x]) . '">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-search"></i>
                        Detail
                    </button>
                </a>
            </div>';
            })
            ->rawColumns(['status', 'button', 'batas'])
            ->make(true);
    }

    //Edit 
    public function update_modal_surat_jalan($id, $status)
    {
        return view('page.logistik.pengiriman.edit', ['id' => $id, 'status' => $status]);
    }
    public function update_so($id, $value)
    {
        if ($value == 'EKAT') {
            $data = Ekatalog::where('id', $id)->get();


            foreach ($data as $d) {
                $tgl_sekarang = Carbon::now()->format('Y-m-d');
                $tgl_parameter = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status)->format('Y-m-d');

                if ($tgl_sekarang < $tgl_parameter) {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);

                    if ($hari > 7) {
                        $param = ' <div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div> <small><i class="fas fa-clock info"></i> Batas Sisa ' . $hari . ' Hari</small>';
                    } else if ($hari > 0 && $hari <= 7) {
                        $param = ' <div class="warning">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small><i class="fa fa-exclamation-circle warning"></i> Batas Sisa ' . $hari . ' Hari</small>';
                    } else {
                        $param = '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Batas Kontrak Habis</small>';
                    }
                } elseif ($tgl_sekarang == $tgl_parameter) {
                    $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                } else {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);
                    $param =  '<div class="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                }
            }
            return view('page.logistik.so.detail_ekatalog', ['data' => $data, 'param' => $param]);
        } elseif ($value == 'SPA') {
            return view('page.logistik.so.detail_spa');
        } else {
            return view('page.logistik.so.detail_spb');
        }
    }

    public function create_logistik($detail_pesanan_id, $pesanan_id)
    {
        $value = [];
        $x = explode(',', $detail_pesanan_id);
        if ($detail_pesanan_id == '0') {
            $data = DetailPesanan::where('pesanan_id', $pesanan_id)->get();
            foreach ($data as $d) {
                $value[] = $d->id;
            }
            $id =  json_encode($value);
        } else {
            $data = DetailPesanan::whereIN('id', $x)->get();
            foreach ($data as $d) {
                $value[] = $d->id;
            }
            $id =  json_encode($value);
        }

        return view('page.logistik.so.create', ['id' => $id]);
    }
    //Other
    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '28';
        } else {
            $days = '35';
        }
        return Carbon::parse($value)->subDays($days);
    }
}
