<?php

namespace App\Http\Controllers;

use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\Logistik;
use App\Models\NoseriCoo;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use Illuminate\Http\Request;
use PDF;
use App\Models\Pesanan;
use App\Models\Produk;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DcController extends Controller
{
    public function pdf_coo($id)
    {
        $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->get();
        $pdf = PDF::loadView('page.dc.coo.pdf_semua', ['data' => $data])->setPaper('A4');
        return $pdf->stream('');
    }
    public function pdf_semua_coo($id, $value, $jenis)
    {
        $data = NoseriCoo::whereHas('NoseriDetailLogistik', function ($q) use ($id) {
            $q->where('detail_logistik_id', $id);
        })->get();
        $count = $data->count();

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_spa', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        }
        return $pdf->stream('');
    }
    public function pdf_semua_so_coo($id, $value, $jenis)
    {
        $data = NoseriCoo::whereHas('NoseriDetailLogistik.DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
            $q->where('pesanan.id', $id);
        })->get();
        $count = $data->count();

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat_so', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_spa_so', ['data' => $data, 'count' => $count, 'jenis' => $jenis])->setPaper('A4');
        }
        return $pdf->stream('');
    }
    public function pdf_seri_coo($id, $value, $jenis)
    {
        $data = NoseriCoo::where('noseri_logistik_id', $id)->first();
        $tgl_sj = $data->NoseriDetailLogistik->DetailLogistik->logistik->tgl_kirim;
        $bulan =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('m');
        $tahun =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('Y');
        $romawi = $this->toRomawi($bulan);
        $footer = Carbon::createFromFormat('Y-m-d', $tgl_sj)->isoFormat('D MMMM Y');

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_ekat', ['data' => $data, 'romawi' => $romawi, 'tahun' => $tahun, 'footer' => $footer, 'jenis' => $jenis])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_spa', ['data' => $data, 'romawi' => $romawi, 'tahun' => $tahun, 'footer' => $footer, 'jenis' => $jenis])->setPaper('A4');
        }

        return $pdf->stream('');
    }
    public function get_data_coo()
    {
       // $data = NoseriCoo::with(['NoseriDetailLogistik.NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi','NoseriDetailLogistik.DetailLogistik.DetailPesananProduk.']);


       $data = DB::table('noseri_coo')
       ->select(DB::raw("MONTH(logistik.tgl_kirim) as bulan_coo"),
                DB::raw("DATE_FORMAT(logistik.tgl_kirim, '%d-%m-%Y') as tglsjcoo"),
                DB::raw("DATE_FORMAT(noseri_coo.tgl_kirim, '%d-%m-%Y') as tglkirim_coo"),
               'noseri_coo.catatan as coo_catatan',
                'noseri',
                'noseri_logistik.id as noserilogistik_id',
                'produk.no_akd',
                'produk.nama_coo as nama',
                'produk.nama as tipe ',
                'produk.merk',
                'pesanan.no_po as nopo',
                'ekatalog.no_paket as no_akn',  DB::raw("(CASE WHEN noseri_coo.ket = 'spa' THEN 'Kusmardiana Rahayu'
                WHEN noseri_coo.ket = 'emiindo' THEN 'Bambang Hendro M BE'
                ELSE noseri_coo.nama END ) as pic")
       )
       ->leftJoin('noseri_logistik','noseri_logistik.id','=','noseri_coo.noseri_logistik_id')
       ->leftJoin('detail_logistik','detail_logistik.id','=','noseri_logistik.detail_logistik_id')
       ->leftJoin('detail_pesanan_produk','detail_pesanan_produk.id','=','detail_logistik.detail_pesanan_produk_id')
       ->leftJoin('detail_pesanan','detail_pesanan.id','=','detail_pesanan_produk.detail_pesanan_id')
       ->leftJoin('pesanan','pesanan.id','=','detail_pesanan.pesanan_id')
       ->leftJoin('ekatalog','ekatalog.pesanan_id','=','pesanan.id')
       ->leftJoin('spa','spa.pesanan_id','=','pesanan.id')
       ->leftJoin('penjualan_produk','penjualan_produk.id','=','detail_pesanan.penjualan_produk_id')
       ->leftJoin('detail_penjualan_produk','detail_penjualan_produk.penjualan_produk_id','=','penjualan_produk.id')
       ->leftJoin('produk','produk.id','=','detail_penjualan_produk.produk_id')
       ->leftJoin('logistik','logistik.id','=','detail_logistik.logistik_id')
       ->leftJoin('noseri_detail_pesanan','noseri_detail_pesanan.id','=','noseri_logistik.noseri_detail_pesanan_id')
       ->leftJoin('t_gbj_noseri','t_gbj_noseri.id','=','noseri_detail_pesanan.t_tfbj_noseri_id')
       ->leftJoin('noseri_barang_jadi','noseri_barang_jadi.id','=','t_gbj_noseri.noseri_id')
       ->orderBy('noseri_coo.id','DESC')
       ->where(['produk.coo'=> 1,'penjualan_produk.status' =>'ekat'])
       ->where('pesanan.log_id','10')
       ->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kosong', function () {
                return '-';
            })
            ->addColumn('seri', function ($data) {
               return $data->noseri;
            })
            ->addColumn('po', function ($data) {
             return $data->nopo;
            })
            ->addColumn('no_paket', function ($data) {

                 if ($data->no_akn != '') {
                    return $data->no_akn;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                if ($data->nama != '') {
                    return $data->nama;
                } else {
                    return '-';
                }
            })
            ->addColumn('noakd', function ($data) {
                if ($data->no_akd != '') {
                    return $data->no_akd;
                } else {
                    return '-';
                }
            })
            ->addColumn('bulan', function ($data) {
                $romawi = $this->toRomawi($data->bulan_coo);
                return $romawi;
            })
            ->addColumn('tgl_sj', function ($data) {
                return  $data->tglsjcoo;
            })
            ->addColumn('tglkirimcoo', function ($data) {
                return $data->tglkirim_coo;
            })
            ->addColumn('pic', function ($data) {
                return $data->pic;
            })
            ->addColumn('catatan', function ($data) {
                return $data->coo_catatan;
            })
            ->addColumn('laporan', function ($data) {

                // $name = explode('/', $data->NoseriDetailLogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($data->no_akn != '') {
                    $x = 'ekatalog';
                } else {
                    $x = 'spa';
                }
                return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a href="' . route('dc.seri.coo.pdf', [$data->noserilogistik_id, $x, "kosong"]) . '" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo
                      </button>
                  </a>
                      <a href="' . route('dc.seri.coo.pdf', [$data->noserilogistik_id, $x, "back"]) . '" target="_blank">
                          <button class="dropdown-item" type="button">
                              <i class="fas fa-file"></i>
                              Coo + Background
                          </button>
                      </a>
                      <a href="' . route('dc.seri.coo.pdf', [$data->noserilogistik_id, $x, "ttd"]) . '" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo + Background + Ttd
                      </button>
                  </a>
                  </div>';
            })
            ->rawColumns(['laporan'])
            ->make(true);
    }
    public function get_data_so($value)
    {
        // $array_id = array();
        // $x = explode(',', $value);
        // $datas = Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->get();

        // foreach ($datas as $d) {
        //     if ($value == 'semua') {
        //         $array_id[] = $d->id;
        //     } else if ($value == 'belum_diproses') {
        //         if ($d->getJumlahCoo() == 0) {
        //             $array_id[] = $d->id;
        //         }
        //     } else {
        //         if ($d->getJumlahCoo() < $d->getJumlahPaketPesanan() && $d->getJumlahCoo() != 0) {
        //             $array_id[] = $d->id;
        //         }
        //     }
        // }



        $data = Pesanan::whereIn('id', function($q){
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->where('produk.coo', '=', '1')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_logistik.id) > (
                    select count(noseri_coo.id)
                    from noseri_coo
                    left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                    left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                    left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                    left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                    where detail_pesanan.pesanan_id = pesanan.id)');
                })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                    ->addSelect(['tgl_kontrak' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                      ->from('ekatalog')
                      ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                      ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                      ->limit(1);
                },
                'ccoo' => function($q){
                    $q->selectRaw('count(noseri_coo.id)')
                    ->from('noseri_coo')
                    ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cseri' => function($q){
                    $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }
                ])->orderBy('tgl_kontrak', 'desc')->doesntHave('SPB')->get();

        // $data = Pesanan::with('Ekatalog.Customer','Spa.Customer')->DoesntHave('Spb')->whereIn('id', $array_id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_paket', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('batas_paket', function ($data) {
                if($data->tgl_kontrak != ""){
                    if($data->log_id){
                        $tgl_sekarang = Carbon::now();
                        $tgl_parameter = $data->tgl_kontrak;
                        $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                        if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle warning"></i> ' . $hari . ' Hari Lagi</small></div>';
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
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->customer->nama;
                } else if ($name[1] == 'SPA'){
                    return $data->spa->customer->nama;
                } else if ($name[1] == 'SPB'){
                    return $data->spb->customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->instansi;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $hitung = round((($data->ccoo / $data->cseri) * 100), 0);
                if($hitung > 0){
                    $datas = '<div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }else{
                    $datas = '<div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                }
                return $datas;
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                // $x = array();

                // $jumlah = 0;
                // foreach ($data->detailpesanan as $d) {
                //     $x[] = $d->id;
                //     $jumlah += $d->jumlah;
                // }

                // $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

                // $y = array();

                // foreach ($detail_pesanan_produk as $d) {
                //     $y[] = $d->id;
                // }

                // $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


                // $r = array();
                // foreach ($noseri as $j) {

                //     $r[] = $j->id;
                // }
                // $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

                // $d = array();

                // foreach ($logistik as $l) {
                //     $d[] =  $l->id;
                // }

                // $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();



                if ($data->cseri == $data->ccoo) {
                    $class = '';
                } else {
                    if ($data->ccoo <= 0) {
                        $class = 'd-none';
                    } else {
                        $class = '';
                    }
                }

                if ($name[1] == 'EKAT') {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                            </a>
                    </div>';
                } else {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background + Ttd
                                </button>
                            </a>

                    </div>';
                }
            })
            ->rawColumns(['button', 'status', 'batas_paket'])
            ->make(true);
    }
    public function get_data_so_selesai($value){
        $data = Pesanan::whereIn('id', function($q){
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->where('produk.coo', '=', '1')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_logistik.id) <= (
                    select count(noseri_coo.id)
                    from noseri_coo
                    left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                    left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                    left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                    left join produk on produk.id = gdg_barang_jadi.produk_id
                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                    where detail_pesanan.pesanan_id = pesanan.id AND produk.coo = 1) AND EXISTS (
                        select *
                        from noseri_coo
                        left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                        left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                        left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                        left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                        left join produk on produk.id = gdg_barang_jadi.produk_id
                        left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                        where detail_pesanan.pesanan_id = pesanan.id AND produk.coo = 1) ');
                })->addSelect(['ccoo' => function($q){
                    $q->selectRaw('count(noseri_coo.id)')
                    ->from('noseri_coo')
                    ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->where('produk.coo', '=', '1');
                }, 'cseri' => function($q){
                    $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->where('produk.coo', '=', '1');
                }])->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7'])->orderBy('id', 'desc')->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_paket', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('batas_paket', function ($data) {

                if (isset($data->ekatalog->tgl_kontrak)) {
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status)->format('Y-m-d');

                    if (isset($data->so)) {
                        if ($tgl_sekarang <= $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                  <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        }
                        else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->ekatalog->tgl_kontrak, $data->ekatalog->provinsi->status);
                            $hari = $to->diffInDays($from);
                            return '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
                        }
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->customer->nama;
                } else if ($name[1] == 'SPA'){
                    return $data->spa->customer->nama;
                } else if ($name[1] == 'SPB'){
                    return $data->spb->customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->instansi;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                if($data->ccoo >= $data->cseri){
                    return  '<span class="badge green-text">Selesai</span>';
                } else
                {
                    return '<span class="badge yellow-text">Sebagian Diproses</span>';
                }
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);

                if ($data->cseri == $data->ccoo) {
                    $class = '';
                } else {
                    if ($data->ccoo == 0) {
                        $class = 'd-none';
                    } else {
                        $class = '';
                    }
                }

                if ($name[1] == 'EKAT') {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd']) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                            </a>
                    </div>';
                } else {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background + Ttd
                                </button>
                            </a>

                    </div>';
                }
            })
            ->rawColumns(['button', 'status', 'batas_paket'])
            ->make(true);
    }

    public function get_data_detail_so($id)
    {
        //pesanan_id
        // $data = Logistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
        //     $q->where('Pesanan.id', $id);
        // })->get();
        $data = DetailLogistik::whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
            $q->where('Pesanan.id', $id);
        })->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_surat', function ($data) {
                return $data->Logistik->tgl_kirim;
            })
            ->addColumn('nama_paket', function ($data) {
                if ($data->DetailPesananProduk->GudangBarangJadi->nama == '') {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                } else {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama.' - '.$data->DetailPesananProduk->GudangBarangJadi->nama;
                }
            })
            ->addColumn('no_akd', function ($data) {
                if ($data->DetailPesananProduk->GudangBarangJadi->Produk->no_akd == '') {
                    return '';
                } else {
                    return  $data->DetailPesananProduk->GudangBarangJadi->Produk->no_akd;
                }
            })
            ->addColumn('bulan', function ($data) {
                $bulan =  Carbon::createFromFormat('Y-m-d', $data->logistik->tgl_kirim)->format('m');
                $romawi = $this->toRomawi($bulan);
                return $romawi;
            })
            ->addColumn('status', function ($data) {
                $value = array();
                $get = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->get();
                foreach ($get as $d) {
                    $value[] = $d->id;
                }
                $coo = NoseriCoo::whereIN('noseri_logistik_Id', $value)->get()->count();

                if ($data->DetailPesananProduk->GudangBarangJadi->Produk->coo == '0') {
                    return '<span class="badge red-text">Bukan Produk Utama</span>';
                } else {
                    if ($coo == 0) {
                        return '<span class="badge red-text">Belum Tersedia</span>';
                    } else {
                        return ' <span class="badge green-text">Tersedia</span>';
                    }
                }
            })
            ->addColumn('button', function ($data) {

                $name = explode('/', $data->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    $x = 'ekatalog';
                } else {
                    $x = 'spa';
                }

                $value = array();
                $get = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->get();
                foreach ($get as $d) {
                    $value[] = $d->id;
                }
                $coo = NoseriCoo::whereIN('noseri_logistik_Id', $value)->get()->count();
                $count_trf = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();

                if ($count_trf == $coo) {
                    $c = 0;
                } else {
                    $c = 1;
                }

                if ($coo == 0) {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '" data-count="' . $c . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                    </div>';
                } else {

                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '" data-count="' . $c . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "kosong"]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                    </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "back"]) . '" target="_blank">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background
                            </button>
                        </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "ttd"]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                    </a>
                    </div>';
                }
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }
    public function get_data_detail_seri_so($id, $jenis)
    {
        $data = "";
        if($jenis == "belum"){
            $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->doesntHave('NoseriCoo')->get();
        }
        else{
            $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->has('NoseriCoo')->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                // if (isset($data->NoseriCoo)) {
                //     return '';
                // } else {
                    return '<div class="form-check">
                    <input class=" form-check-input  nosericheck" type="checkbox" data-value="' . $data->detail_logistik_id . '" data-id="' . $data->id . '" />
                    </div>';
                // }
            })
            ->addColumn('noseri', function ($data) {
                return  $data->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->addColumn('tgl', function ($data) {
                if (isset($data->NoseriCoo->tgl_kirim)) {
                    return $data->NoseriCoo->tgl_kirim;
                } else {
                    return '';
                }
            })
            ->addColumn('ket', function ($data) {
                if (isset($data->NoseriCoo->catatan)) {
                    return $data->NoseriCoo->catatan;
                } else {
                    return '';
                }
            })
            ->addColumn('laporan', function ($data) {
                $get = NoseriCoo::where('noseri_logistik_id', $data->id)->get()->count();
                $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    $x = 'ekatalog';
                } else {
                    $x = 'spa';
                }

                if ($get != 0) {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-target="#tglkirim_modal" class="tglkirim_modal"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                    <i class="fas fa-pencil-alt"></i>
                        Edit
                    </button>
                </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "kosong"]) . '" target="_blank">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "back"]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                    </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "ttd"]) . '" target="_blank">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                </a>
                </div>';
                }
            })
            ->rawColumns(['checkbox', 'laporan'])
            ->make(true);
    }
    public function get_data_detail_select_seri_so($id, $value)
    {
        $array_seri = explode(',', $id);
        if ($id == 0) {
            $data =  NoseriDetailLogistik::DoesntHave('NoseriCoo')->where('detail_logistik_id', $value)->get();
        } else {
            $data =  NoseriDetailLogistik::whereIN('id', $array_seri)->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noseri', function ($data) {
                return  $data->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->rawColumns(['checkbox'])
            ->make(true);
    }
    //Show
    public function detail_coo($id, $value)
    {
        if ($value == 'ekatalog') {
            $data = Pesanan::find($id);

            $x = array();

            $jumlah = 0;
            foreach ($data->detailpesanan as $d) {
                $x[] = $d->id;
                $jumlah += $d->jumlah;
            }

            $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

            $y = array();

            foreach ($detail_pesanan_produk as $d) {
                $y[] = $d->id;
            }

            $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


            $r = array();
            foreach ($noseri as $j) {

                $r[] = $j->id;
            }
            $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

            $d = array();

            foreach ($logistik as $l) {
                $d[] =  $l->id;
            }

            $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();

            if ($jumlah == $coo) {
                $status = ' <span class="badge green-text">Sudah Diproses</span>';
            } else {
                if ($coo == 0) {
                    $status =  '<span class="badge red-text">Belum Diproses</span>';
                } else {
                    $status = '<span class="badge yellow-text">Sebagian Diproses</span>';
                }
            }

            return view('page.dc.so.detail_ekatalog', ['data' => $data, 'status' => $status]);
        } else {
            $data = Pesanan::find($id);

            $x = array();

            $jumlah = 0;
            foreach ($data->detailpesanan as $d) {
                $x[] = $d->id;
                $jumlah += $d->jumlah;
            }

            $detail_pesanan_produk  = DetailPesananProduk::whereIN('detail_pesanan_id', $x)->get();

            $y = array();

            foreach ($detail_pesanan_produk as $d) {
                $y[] = $d->id;
            }

            $noseri = NoseriDetailPesanan::whereIN('detail_pesanan_produk_id', $y)->get();


            $r = array();
            foreach ($noseri as $j) {

                $r[] = $j->id;
            }
            $logistik = NoseriDetailLogistik::whereIN('noseri_detail_pesanan_id', $r)->get();

            $d = array();

            foreach ($logistik as $l) {
                $d[] =  $l->id;
            }

            $coo = NoseriCoo::whereIN('noseri_logistik_id', $d)->get()->count();

            if ($jumlah == $coo) {
                $status = ' <span class="badge green-text">Sudah Diproses</span>';
            } else {
                if ($coo == 0) {
                    $status =  '<span class="badge red-text">Belum Diproses</span>';
                } else {
                    $status = '<span class="badge yellow-text">Sebagian Diproses</span>';
                }
            }
            return view('page.dc.so.detail_spa', ['data' => $data, 'status' => $status]);
        }
    }
    public function create_coo($id, $value)
    {
        $value2 = array();
        $array_seri = explode(',', $id);
        if ($id == 0) {
            $data =  NoseriDetailLogistik::where('detail_logistik_id', $value)->first();
            $jumlah = count($array_seri);

            $seri_data = NoseriDetailLogistik::where('detail_logistik_id', $value)->get();
            foreach ($seri_data as $d) {
                $value2[] = $d->id;
            }
            $noseri_id =  json_encode($value2);
        } else {
            $data =  NoseriDetailLogistik::whereIN('id', $array_seri)->first();
            $jumlah = count($array_seri);

            $seri_data = NoseriDetailLogistik::whereIN('id', $array_seri)->get();
            foreach ($seri_data as $d) {
                $value2[] = $d->id;
            }
            $noseri_id =  json_encode($value2);
        }

        return view('page.dc.coo.create', ['data' => $data, 'id' => $id, 'jumlah' => $jumlah, 'noseri_id' => $noseri_id]);
    }

    public function edit_coo($id, $value)
    {
        $value2 = array();
        $array_seri = explode(',', $id);
        if ($id == 0) {
            $data =  NoseriDetailLogistik::where('detail_logistik_id', $value)->first();
            $jumlah = count($array_seri);

            $seri_data = NoseriDetailLogistik::where('detail_logistik_id', $value)->get();
            foreach ($seri_data as $d) {
                $value2[] = $d->id;
            }
            $noseri_id =  json_encode($value2);
        } else {
            $data =  NoseriDetailLogistik::whereIN('id', $array_seri)->first();
            $jumlah = count($array_seri);

            $seri_data = NoseriDetailLogistik::whereIN('id', $array_seri)->get();
            foreach ($seri_data as $d) {
                $value2[] = $d->id;
            }
            $noseri_id =  json_encode($value2);
        }

        return view('page.dc.coo.edit', ['data' => $data, 'id' => $id, 'jumlah' => $jumlah, 'noseri_id' => $noseri_id]);
    }

    public function edit_tglkirim_coo($id)
    {
        $data =  NoseriDetailLogistik::find($id);
        return view('page.dc.coo.tglkirim_edit', ['data' => $data]);
    }
    public function store_coo(Request $request, $value)
    {
        if ($request->diketahui == 'spa') {
            $nama = NULL;
            $jabatan = NULL;
            $ket = 'spa';
        } elseif ($request->diketahui == 'emiindo') {
            $nama = NULL;
            $jabatan = NULL;
            $ket = 'emiindo';
        } else {
            $nama = $request->nama;
            $jabatan = $request->jabatan;
            $ket = NULL;
        }
        $replace_array_seri = strtr($value, array('[' => '', ']' => ''));
        $array_seri = explode(',', $replace_array_seri);
        $bool = true;
        for ($i = 0; $i < count($array_seri); $i++) {
            $noseri = NoseriCoo::where('noseri_logistik_id', $array_seri[$i])->first();
            if($noseri){
                $l = NoseriCoo::find($noseri->id);
                $l->nama = $nama;
                $l->jabatan = $jabatan;
                $l->ket = $ket;
                $l->tgl_kirim = $request->tgl_kirim;
                $l->catatan = $request->keterangan;
                $l->save();
                if (!$l) {
                    $bool = false;
                }
            }else{
                $c = NoseriCoo::create([
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                    'ket' => $ket,
                    'noseri_logistik_id' => $array_seri[$i],
                    'tgl_kirim' => $request->tgl_kirim,
                    'catatan' => $request->keterangan,
                ]);
                if (!$c) {
                    $bool = false;
                }
            }
        }
        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function update_coo(Request $request, $value)
    {
        $replace_array_seri = strtr($value, array('[' => '', ']' => ''));
        $array_seri = explode(',', $replace_array_seri);
        $bool = true;
        for ($i = 0; $i < count($array_seri); $i++) {
            $noseri = NoseriCoo::where('noseri_logistik_id', $array_seri[$i])->first();
            if($noseri){
                $l = NoseriCoo::find($noseri->id);
                $l->tgl_kirim = $request->edit_tgl_kirim;
                $l->catatan = $request->edit_keterangan;
                $l->save();
                if (!$l) {
                    $bool = false;
                }
            }
        }
        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function update_tgl_kirim_coo(Request $request, $id)
    {
        //  return response($id);
        $l = NoseriCoo::find($id);
        $l->tgl_kirim = $request->tgl_kirim;
        $l->catatan = $request->keterangan;
        $l->save();
        if ($l) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function dashboard()
    {
        $daftar_so = Pesanan::whereIn('id', function($q){
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->where('produk.coo', '=', '1')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_logistik.id) > (
                    select count(noseri_coo.id)
                    from noseri_coo
                    left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                    left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                    left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                    left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                    where detail_pesanan.pesanan_id = pesanan.id)');
                })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                    ->addSelect(['tgl_kontrak' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                      ->from('ekatalog')
                      ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                      ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                      ->limit(1);
                },
                'ccoo' => function($q){
                    $q->selectRaw('count(noseri_coo.id)')
                    ->from('noseri_coo')
                    ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cseri' => function($q){
                    $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }
                ])->orderBy('tgl_kontrak', 'desc')->has('Ekatalog')->count();

        $belum_coo = Pesanan::whereIn('id', function($q){
                    $q->select('pesanan.id')
                        ->from('pesanan')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                        ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                        ->where('produk.coo', '=', '1')
                        ->groupBy('pesanan.id')
                        ->havingRaw('count(noseri_logistik.id) > (
                            select count(noseri_coo.id)
                            from noseri_coo
                            left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                            left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                            left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                            where detail_pesanan.pesanan_id = pesanan.id) AND NOT EXISTS(select * from noseri_coo
                            left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                            left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                            left join produk on produk.id = gdg_barang_jadi.produk_id
                            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                            where detail_pesanan.pesanan_id = pesanan.id)');
                        })->with(['Ekatalog.Customer.Provinsi'])
                            ->addSelect(['tgl_kontrak' => function($q){
                            $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                              ->from('ekatalog')
                              ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                              ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                              ->limit(1);
                        },
                        'ccoo' => function($q){
                            $q->selectRaw('count(noseri_coo.id)')
                            ->from('noseri_coo')
                            ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('produk.coo', 1)
                            ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                        },
                        'cseri' => function($q){
                            $q->selectRaw('count(noseri_logistik.id)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('produk.coo', 1)
                            ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                        }
                        ])->orderBy('tgl_kontrak', 'desc')->has('Ekatalog')->count();
        $lewat_batas = Pesanan::whereIn('id', function($q){
                    $q->select('pesanan.id')
                        ->from('pesanan')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                        ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                        ->where('produk.coo', '=', '1')
                        ->groupBy('pesanan.id')
                        ->havingRaw('count(noseri_logistik.id) > (
                            select count(noseri_coo.id)
                            from noseri_coo
                            left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                            left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                            left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                            where detail_pesanan.pesanan_id = pesanan.id)');
                        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                            ->addSelect(['tgl_kontrak' => function($q){
                            $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                              ->from('ekatalog')
                              ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                              ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                              ->limit(1);
                        },
                        'ccoo' => function($q){
                            $q->selectRaw('count(noseri_coo.id)')
                            ->from('noseri_coo')
                            ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('produk.coo', 1)
                            ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                        },
                        'cseri' => function($q){
                            $q->selectRaw('count(noseri_logistik.id)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('produk.coo', 1)
                            ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                        }
                        ])->orderBy('tgl_kontrak', 'desc')->havingRaw('tgl_kontrak < CURDATE()')->has('Ekatalog')->count();

                // $penjualan = Pesanan::whereIn('id', function($q){
                //     $q->select('pesanan.id')
                //         ->from('pesanan')
                //         ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                //         ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //         ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //         ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                //         ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                //         ->where('produk.coo', '=', '1')
                //         ->groupBy('pesanan.id')
                //         ->havingRaw('NOT EXISTS(select *
                //             from noseri_coo
                //             left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                //             left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                //             left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                //             left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                //             left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                //             left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                //             where detail_pesanan.pesanan_id = pesanan.id)');
                //         })->with(['Ekatalog.Customer.Provinsi'])
                //             ->addSelect(['tgl_kontrak' => function($q){
                //             $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                //               ->from('ekatalog')
                //               ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                //               ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                //               ->limit(1);
                //         },
                //         'ccoo' => function($q){
                //             $q->selectRaw('count(noseri_coo.id)')
                //             ->from('noseri_coo')
                //             ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cseri' => function($q){
                //             $q->selectRaw('count(noseri_logistik.id)')
                //             ->from('noseri_logistik')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cjumlah' => function($q){
                //             $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                //             ->from('detail_pesanan')
                //             ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                //             ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         }
                //         ])->whereIn('log_id', ['9'])->orderBy('tgl_kontrak', 'desc')->doesntHave('Spb')->count();
                // $gudang = Pesanan::whereIn('id', function($q){
                //     $q->select('pesanan.id')
                //         ->from('pesanan')
                //         ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                //         ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //         ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //         ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                //         ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                //         ->where('produk.coo', '=', '1')
                //         ->groupBy('pesanan.id')
                //         ->havingRaw('NOT EXISTS(select *
                //             from noseri_coo
                //             left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                //             left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                //             left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                //             left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                //             left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                //             left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                //             where detail_pesanan.pesanan_id = pesanan.id)');
                //         })->with(['Ekatalog.Customer.Provinsi'])
                //             ->addSelect(['tgl_kontrak' => function($q){
                //             $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                //               ->from('ekatalog')
                //               ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                //               ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                //               ->limit(1);
                //         },
                //         'ccoo' => function($q){
                //             $q->selectRaw('count(noseri_coo.id)')
                //             ->from('noseri_coo')
                //             ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cseri' => function($q){
                //             $q->selectRaw('count(noseri_logistik.id)')
                //             ->from('noseri_logistik')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             // ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cjumlah' => function($q){
                //             $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                //             ->from('detail_pesanan')
                //             ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                //             ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         }
                //         ])->whereIn('log_id', ['6'])->orderBy('tgl_kontrak', 'desc')->doesntHave('Spb')->count();
                // $qc = Pesanan::whereIn('id', function($q){
                //     $q->select('pesanan.id')
                //         ->from('pesanan')
                //         ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                //         ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //         ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //         ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                //         ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                //         ->where('produk.coo', '=', '1')
                //         ->groupBy('pesanan.id')
                //         ->havingRaw('NOT EXISTS(select *
                //             from noseri_coo
                //             left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                //             left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                //             left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                //             left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                //             left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                //             left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                //             where detail_pesanan.pesanan_id = pesanan.id)');
                //         })->with(['Ekatalog.Customer.Provinsi'])
                //             ->addSelect(['tgl_kontrak' => function($q){
                //             $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                //               ->from('ekatalog')
                //               ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                //               ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                //               ->limit(1);
                //         },
                //         'ccoo' => function($q){
                //             $q->selectRaw('count(noseri_coo.id)')
                //             ->from('noseri_coo')
                //             ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cseri' => function($q){
                //             $q->selectRaw('count(noseri_logistik.id)')
                //             ->from('noseri_logistik')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cjumlah' => function($q){
                //             $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                //             ->from('detail_pesanan')
                //             ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                //             ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         }
                //         ])->whereIn('log_id', ['8'])->orderBy('tgl_kontrak', 'desc')->doesntHave('Spb')->count();
                // $logistik = Pesanan::with(['Ekatalog.Customer.Provinsi'])
                //             ->addSelect(['tgl_kontrak' => function($q){
                //             $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                //               ->from('ekatalog')
                //               ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                //               ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                //               ->limit(1);
                //         },
                //         'ccoo' => function($q){
                //             $q->selectRaw('count(noseri_coo.id)')
                //             ->from('noseri_coo')
                //             ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cseri' => function($q){
                //             $q->selectRaw('count(noseri_logistik.id)')
                //             ->from('noseri_logistik')
                //             ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //             ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //             ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //             ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //             ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         },
                //         'cjumlah' => function($q){
                //             $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                //             ->from('detail_pesanan')
                //             ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                //             ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                //             ->where('produk.coo', 1)
                //             ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                //         }
                // ])->whereIn('log_id', ['11', '13'])->orderBy('tgl_kontrak', 'desc')->doesntHave('Spb')->count();

                $penjualan = Pesanan::addSelect(['cjumlahprd' => function($q){
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }, 'cjumlahpart' => function($q){
                    $q->selectRaw('sum(detail_pesanan_part.jumlah)')
                    ->from('detail_pesanan_part')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },'clogprd' => function($q){
                    $q->selectRaw('count(noseri_logistik.id)')
                       ->from('noseri_logistik')
                       ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                       ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                       ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                       ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }, 'clogpart' => function($q){
                    $q->selectRaw('sum(detail_logistik_part.jumlah)')
                       ->from('detail_logistik_part')
                       ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                       ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                       ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                       ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                }])
                ->whereIn('log_id', ['9'])
                ->havingRaw('clogprd < cjumlahprd OR clogpart < cjumlahpart')
                ->has('Ekatalog')
                ->count();

                $gudang = Pesanan::addSelect(['jumlah_produk' => function($q){
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }, 'jumlah_gudang' => function($q){
                    $q->selectRaw('count(t_gbj_noseri.id)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                }])->whereNotIn('log_id', ['7'])->havingRaw('jumlah_produk > jumlah_gudang')->has('Ekatalog')->count();

                $qc = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect(['tgl_kontrak' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                    ->from('ekatalog')
                    ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                    ->limit(1);
                },
                'ctfprd' => function($q){
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
                },
                'cqcprd' => function($q){
                    $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ctfpart' => function($q){
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                    ->from('detail_pesanan_part')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cqcpart' => function($q){
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                    ->from('outgoing_pesanan_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->where('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function($q){
                    $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                       ->from('noseri_logistik')
                       ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                       ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                       ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                       ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                       ->limit(1);
                },
                'clogpart' => function($q){
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                       ->from('detail_logistik_part')
                       ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                       ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                       ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                       ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                       ->limit(1);
                }])->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi'])
                ->havingRaw('(ctfprd > cqcprd AND ctfprd > 0) OR (ctfpart > cqcpart AND ctfpart > 0)')
                ->orderBy('tgl_kontrak', 'asc')
                ->has('Ekatalog')
                ->count();

                $logistik = Pesanan::addSelect(['cqcprd' => function($q){
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'clogprd' => function($q){
                    $q->selectRaw('count(noseri_logistik.id)')
                       ->from('noseri_logistik')
                       ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                       ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                       ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                       ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                       ->limit(1);
                },
                'ccoo' => function($q){
                    $q->selectRaw('count(noseri_coo.id)')
                    ->from('noseri_coo')
                    ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },])
                ->havingRaw('(cqcprd >= clogprd) AND (clogprd > ccoo)')
                ->has('Ekatalog')
                ->count();
        return view('page.dc.dashboard', ['daftar_so' => $daftar_so, 'belum_coo' => $belum_coo, 'lewat_batas' => $lewat_batas, 'penjualan' => $penjualan, 'gudang' => $gudang, 'qc' => $qc, 'logistik' => $logistik]);
    }

    public function dashboard_data($value)
    {
        if ($value == 'pengirimansotable') {
            $data = Pesanan::whereIn('id', function($q){
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                    ->where('produk.coo', '=', '1')
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(noseri_logistik.id) > (
                        select count(noseri_coo.id)
                        from noseri_coo
                        left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                        left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                        left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                        left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                        left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                        left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                        where detail_pesanan.pesanan_id = pesanan.id)');
                    })->with(['Ekatalog.Customer.Provinsi'])
                        ->addSelect(['tgl_kontrak' => function($q){
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                          ->from('ekatalog')
                          ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                          ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                          ->limit(1);
                    },
                    'ccoo' => function($q){
                        $q->selectRaw('count(noseri_coo.id)')
                        ->from('noseri_coo')
                        ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                    },
                    'cseri' => function($q){
                        $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                    }
                    ])->orderBy('tgl_kontrak', 'desc')->has('Ekatalog')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('status', function ($data) {
                    if ($data->ccoo <= 0) {
                        return  '<span class="badge red-text">Belum Diproses</span>';
                    } else {
                        return '<span class="badge yellow-text">Sebagian Diproses</span>';
                    }
                })
                ->addColumn('batas_kontrak', function ($data) {
                    if($data->tgl_kontrak != ""){
                        if($data->log_id){
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            }
                            else{
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else{
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);

                    if ($data->ccoo <= 0) {
                        $class = 'd-none';
                    } else {
                        $class = '';
                    }

                    if ($name[1] == 'EKAT') {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                            <i class="fas fa-eye"></i>
                                Detail
                            </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo
                            </button>
                                    </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background
                            </button>
                                    </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd']) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background + Ttd
                            </button>
                                    </a>

                        </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                            <i class="fas fa-eye"></i>
                                Detail
                            </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>
                        </div>';
                    }
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        } else if ($value == 'sotanpacootable') {
            $data = Pesanan::whereIn('id', function($q){
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                    ->where('produk.coo', '=', '1')
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(noseri_logistik.id) > (
                        select count(noseri_coo.id)
                        from noseri_coo
                        left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                        left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                        left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                        left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                        left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                        left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                        where detail_pesanan.pesanan_id = pesanan.id) AND NOT EXISTS(select * from noseri_coo
                        left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                        left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                        left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                        left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                        left join produk on produk.id = gdg_barang_jadi.produk_id
                        left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                        where detail_pesanan.pesanan_id = pesanan.id)');
                    })->with(['Ekatalog.Customer.Provinsi'])
                        ->addSelect(['tgl_kontrak' => function($q){
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                          ->from('ekatalog')
                          ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                          ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                          ->limit(1);
                    },
                    'ccoo' => function($q){
                        $q->selectRaw('count(noseri_coo.id)')
                        ->from('noseri_coo')
                        ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                    },
                    'cseri' => function($q){
                        $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                    }
                    ])->orderBy('tgl_kontrak', 'desc')->has('Ekatalog')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return  $data->so;
                })
                ->addColumn('status', function ($data) {
                    if ($data->ccoo <= 0) {
                        return  '<span class="badge red-text">Belum Diproses</span>';
                    } else {
                        return '<span class="badge yellow-text">Sebagian Diproses</span>';
                    }
                })
                ->addColumn('batas_kontrak', function ($data) {
                    if($data->tgl_kontrak != ""){
                        if($data->log_id){
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            }
                            else{
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else{
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);

                    if ($data->ccoo <= 0) {
                        $class = 'd-none';
                    } else {
                        $class = '';
                    }

                    if ($name[1] == 'EKAT') {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                            <i class="fas fa-eye"></i>
                                Detail
                            </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>

                        </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                            <i class="fas fa-eye"></i>
                                Detail
                            </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd']) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>

                        </div>';
                    }
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        } else {
            $data = Pesanan::whereIn('id', function($q){
                $q->select('pesanan.id')
                    ->from('pesanan')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                    ->where('produk.coo', '=', '1')
                    ->groupBy('pesanan.id')
                    ->havingRaw('count(noseri_logistik.id) > (
                        select count(noseri_coo.id)
                        from noseri_coo
                        left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                        left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                        left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                        left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                        left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                        left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                        where detail_pesanan.pesanan_id = pesanan.id)');
                    })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                        ->addSelect(['tgl_kontrak' => function($q){
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                          ->from('ekatalog')
                          ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                          ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                          ->limit(1);
                    },
                    'ccoo' => function($q){
                        $q->selectRaw('count(noseri_coo.id)')
                        ->from('noseri_coo')
                        ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                    },
                    'cseri' => function($q){
                        $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                    }
                    ])->orderBy('tgl_kontrak', 'desc')->havingRaw('tgl_kontrak < CURDATE()')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('batas_kontrak', function ($data) {
                    if($data->tgl_kontrak != ""){
                        if($data->log_id){
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            }
                            else{
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else{
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('status', function ($data) {
                    if ($data->ccoo <= 0) {
                        return  '<span class="badge red-text">Belum Diproses</span>';
                    } else {
                        return '<span class="badge yellow-text">Sebagian Diproses</span>';
                    }
                })
                ->addColumn('button', function ($data) {
                    $name = explode('/', $data->so);
                    $x = $name[1];
                    if ($x == 'EKAT') {
                        $y = $data->ekatalog->id;
                    } elseif ($x == 'SPA') {
                        $y = $data->spa->id;
                    } else {
                        $y = $data->spb->id;
                    }
                    return '<a href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                    <i class="fas fa-eye"></i>
                    </a>';
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        }
    }

    public function dashboard_so(){
        $data = Pesanan::whereIn('id', function($q){
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftjoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->leftjoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->where('produk.coo', '=', '1')
                ->groupBy('pesanan.id')
                ->havingRaw('NOT EXISTS(select *
                    from noseri_coo
                    left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                    left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                    left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                    left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                    where detail_pesanan.pesanan_id = pesanan.id)');
                })->with(['Ekatalog.Customer.Provinsi'])
                    ->addSelect(['tgl_kontrak' => function($q){
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                      ->from('ekatalog')
                      ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                      ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                      ->limit(1);
                },
                'ccoo' => function($q){
                    $q->selectRaw('count(noseri_coo.id)')
                    ->from('noseri_coo')
                    ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cseri' => function($q){
                    $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    // ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cjumlah' => function($q){
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    // ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }
                ])->whereNotIn('log_id', ['7', '20'])->orderBy('tgl_kontrak', 'desc')->doesntHave('Spb')->get();
        return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('no_paket', function ($data) {
                    if($data->Ekatalog){
                        return $data->Ekatalog->no_paket;
                    } else{
                        return '-';
                    }
                })
                ->addColumn('customer', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        return '<div>' . $data->Ekatalog->Customer->nama . '</div><small>' . $data->Ekatalog->instansi . '</small>';
                    } else if ($name[1] == 'SPA') {
                        return $data->Spa->Customer->nama;
                    } else if ($name[1] == 'SPB') {
                        return $data->Spb->Customer->nama;
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    $hitung = round((($data->cseri / $data->cjumlah) * 100), 0);
                    if ($data->log_id == "9") {
                        $datas = '<span class="badge purple-text">'.$data->State->nama . '</span>';
                    } else {
                        if($hitung > 0){
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        }else{
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        }
                    }
                    return $datas;
                })
                ->addColumn('aksi', function($data){
                    $id = "";
                    $jenis = "";
                    if($data->Ekatalog){
                        $id = $data->Ekatalog->id;
                        $jenis = "ekatalog";
                    }
                    else if($data->Spa){
                        $id = $data->Spa->id;
                        $jenis = "spa";
                    }
                    else if($data->Spb){
                        $id = $data->Spb->id;
                        $jenis = "spb";
                    }
                    return  '<a data-toggle="modal" data-target="'.$jenis.'" class="somodal" data-attr="' . route('penjualan.penjualan.detail.'.$jenis,  $id) . '"  data-id="' . $id . '">
                            <button class="btn btn-outline-primary btn-xs" type="button"><i class="fas fa-eye"></i> Detail</button>
                        </a>';
                })
                ->rawColumns(['customer', 'status', 'aksi'])
                ->make(true);
    }
    //Another
    static function bulan_romawi($value)
    {
        $bulan =  Carbon::createFromFormat('Y-m-d', $value)->format('m');
        $to = new DcController();
        $x = $to->toRomawi($bulan);
        return $x;
    }
    static function tahun($value)
    {
        $tahun =  Carbon::createFromFormat('Y-m-d', $value)->format('Y');
        return $tahun;
    }
    static function tgl_footer($value)
    {
        $footer = Carbon::createFromFormat('Y-m-d', $value)->isoFormat('D MMMM Y');
        return $footer;
    }
    public function toRomawi($number)
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
