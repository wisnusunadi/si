<?php

namespace App\Http\Controllers;

use App\Exports\LaporanQcOutgoing;
use App\Models\DetailEkatalog;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\Spa;
use App\Models\Spb;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class QcController extends Controller
{
    //Get Data
    public function get_data_detail_so($id)
    {
        $data = DetailEkatalog::where('ekatalog_id', $id)->with('GudangBarangJadi', 'GudangBarangJadi.Produk')->get();
        // $q->whereNotNull('no_po');
        // echo json_encode($data);
        $l = [];
        $v = 0;
        foreach ($data as $s) {
            foreach ($s->GudangBarangJadi as $k) {
                $l[$v]['id'] = $k->pivot->gudang_barang_jadi_id;
                $l[$v]['nama_produk'] = $k->produk->nama;
                $l[$v]['jumlah'] = $k->pivot->jumlah;
                $v++;
            }
        }
        return datatables()->of($l)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($l) {
                return $l['nama_produk'];
            })
            ->addColumn('jumlah', function ($l) {
                return $l['jumlah'];
            })
            ->addColumn('button', function ($l) {
                return '<a type="button" class="noserishow" data-id="' . $l['id'] . '"><i class="fas fa-search"></i></a>';
            })
            ->rawColumns(['button'])
            ->make(true);
        //echo json_encode($data);
    }

    public function get_data_so_qc()
    {
        $data = Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po');
        })->get();
        return datatables()->of($data)
            ->make(true);
    }
    public function get_data_so($value)
    {
        $x = explode(',', $value);
        if ($value == 'semua') {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else if ($x == ['ekatalog', 'spa']) {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Ekatalog->merge($Spa);
        } else if ($x == ['ekatalog', 'spb']) {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Ekatalog->merge($Spb);
        } else if ($x == ['spa', 'spb']) {
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $data = $Spa->merge($Spb);
        } else if ($value == 'ekatalog') {
            $data = Ekatalog::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get();
        } else if ($value == 'spa') {
            $data = Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get();
        } else if ($value == 'spb') {
            $data = Spb::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get();
        } else {
            $data = Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
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
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('status', function () {
                return '<span class="badge yellow-text">Sedang Berlangsung</span>';
            })
            ->addColumn('button', function ($data) {
                $name =  $data->getTable();
                return '    <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a href="' . route('qc.so.detail', [$data->id, $name]) . '">
                <button class="dropdown-item" type="button">
                    <i class="fas fa-search"></i>
                    Detail
                </button>
            </a>
        </div>';
            })
            ->rawColumns(['button', 'status'])
            ->make(true);
    }


    //Detail
    public function update_modal_so()
    {
        return view('page.qc.so.edit');
    }

    public function detail_so($id, $value)
    {
        if ($value == 'ekatalog') {
            $data = Ekatalog::where('id', $id)->get();
            foreach ($data as $d) {
                $tgl_sekarang = Carbon::now()->format('Y-m-d');
                $tgl_parameter = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status)->format('Y-m-d');

                if ($tgl_sekarang < $tgl_parameter) {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);

                    if ($hari > 7) {
                        $x = ' <div class="info">' . $tgl_parameter . '</div> <small><i class="fas fa-clock"></i> Batas sisa ' . $hari . ' Hari</small>';
                    } else if ($hari > 0 && $hari <= 7) {
                        $x = ' <div class="warning">' . $tgl_parameter . '</div><small><i class="fa fa-exclamation-circle warning"></i>Batas Sisa ' . $hari . ' Hari</small>';
                    } else {
                        $x = '' . $tgl_parameter . '<br><span class="badge bg-danger">Batas Kontrak Habis</span>';
                    }
                } elseif ($tgl_sekarang == $tgl_parameter) {
                    $x =  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas Pengujian</small>';
                } else {
                    $to = Carbon::now();
                    $from = $this->getHariBatasKontrak($d->tgl_kontrak, $d->provinsi->status);
                    $hari = $to->diffInDays($from);
                    $x =  '<div>' . $tgl_parameter . '</div><small class="invalid-feedback d-block"><i class="fa fa-exclamation-circle"></i> Lewat Batas ' . $hari . ' Hari</small>';
                }
            }
            return view('page.qc.so.detail_ekatalog', ['data' => $data, 'x' => $x]);
        } elseif ($value == 'spa') {
            $data = Spa::where('id', $id)->get();
            return view('page.qc.so.detail_spa', ['data' => $data]);
        } else {
            $data = Spb::where('id', $id)->get();
            return view('page.qc.so.detail_spb', ['data' => $data]);
        }
    }

    public function detail_modal_riwayat_so()
    {
        return view('page.qc.so.riwayat.detail');
    }

    //Laporan
    public function laporan_outgoing(Request $request)
    {
        return Excel::download(new LaporanQcOutgoing($request->produk_id ?? '', $request->no_so ?? '', $request->hasil_uji  ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_qc_outgoing.xlsx');
    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '21';
        } else {
            $days = '28';
        }
        return Carbon::parse($value)->subDays($days);
    }
}
