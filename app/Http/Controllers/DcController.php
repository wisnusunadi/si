<?php

namespace App\Http\Controllers;

use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Ekatalog;
use App\Models\Logistik;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriCoo;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use App\Models\NoseriTGbj;
use App\Models\PackRw;
use Illuminate\Http\Request;
use PDF;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\SeriDetailRw;
use App\Models\Spa;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DcController extends Controller
{
    public function pdf_coo($id)
    {
        $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->get();
        $pdf = PDF::loadView('page.dc.coo.pdf_semua', ['data' => $data])->setPaper('A4');
        return $pdf->stream('');
    }

    public function pdf_coo_semua_rework(Request $request)
    {
        //  dd($request->all());
        //  $rw_produk = $request->produk;
        $jenis = $request->jenis;
        $stamp = $request->stamp;
        $penjualan = $request->penjualan;

        $rw_produk = 2;
        // $penjualan = 'ekatalog';
        // $jenis = 'kosong';
        // $stamp = 1;


        $series = explode(',', $request->id);
        if ($rw_produk == 1) {
            $pesanan = Pesanan::select('pesanan.id')
                ->leftJoin('detail_pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->whereIN('noseri_logistik.id', $series)
                ->pluck('id')->toArray();


            $ekat = Ekatalog::where('pesanan_id', $pesanan[0]);
            $spa = Spa::where('pesanan_id', $pesanan[0]);

            if ($ekat->count() > 0) {
                $no_paket = $ekat->first()->no_paket;
                $deskripsi = $ekat->first()->deskripsi;
            } else {
                $no_paket = '';
                $deskripsi = '';
            }

            $data = PackRw::select('noseri_coo.nama', 'noseri_coo.ket', 'noseri_coo.tgl_kirim as tgls', 'noseri_coo.tahun', 'noseri_coo.no_coo', 'pack_rw_head.prov', 'pack_rw_head.kota', 'pack_rw.noseri', 'seri_detail_rw.packer', 'seri_detail_rw.created_at', 'seri_detail_rw.isi')
                ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'pack_rw.noseri_id')
                ->leftjoin('noseri_barang_jadi', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
                ->leftjoin('pack_rw_head', 'pack_rw_head.id', '=', 'pack_rw.pack_rw_head_id')
                ->orderBy('noseri_coo.no_coo')
                ->whereIN('noseri_logistik.id', $series)->get();

            foreach ($data as $d) {
                $o = json_decode($d->isi);
                $seri[] = array(
                    'no_coo' => 'KIT10-' . str_pad($d->no_coo, 5, '0', STR_PAD_LEFT),
                    'kepada_prov' => 'Provinsi ' . $d->prov,
                    'kepada_kab' => $d->kota,
                    'seri' => $d->noseri,
                    'packer' => $d->packer,
                    'tahun' => $d->tahun,
                    'tgl' => $d->tgls != NULL ? $this->tgl_footer($d->tgls) : '-',
                    'item' => $o,
                    'no_paket' => $no_paket,
                    'deskripsi' => $deskripsi,
                    'nama' => $d->nama,
                    'ket' => $d->ket,
                    'romawi' => $d->tgls != NULL ? $this->bulan_romawi($d->tgls) : '-'
                );
            }

            $collection = collect($seri);

            $collection = $collection->map(function ($item) {
                $item['item'] = collect($item['item'])->sortBy('produk')->values()->all();
                return $item;
            });

            $data_urut_produk = $collection->toArray();

            //  return response()->json($data_urut_produk);

            $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat_rw', ['data' => $data_urut_produk, 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
            //return view('page.dc.coo.pdf_semua_ekat_rw', ['data' => $data_urut_produk]);

        } else {
            $data = NoseriCoo::leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                ->whereIN('noseri_logistik.id', $series)
                ->get();

            if ($penjualan == 'ekatalog') {
                $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat', ['data' => $data, 'count' => count($series), 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
            } else {
                $pdf = PDF::loadView('page.dc.coo.pdf_semua_spa', ['data' => $data, 'count' => count($series), 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
            }
        }
        return $pdf->stream('');
    }

    public function pdf_semua_coo($id, $value, $jenis, $stamp)
    {
        $data = NoseriCoo::whereHas('NoseriDetailLogistik', function ($q) use ($id) {
            $q->where('detail_logistik_id', $id);
        })->get();
        $count = $data->count();

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat', ['data' => $data, 'count' => $count, 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_spa', ['data' => $data, 'count' => $count, 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
        }
        return $pdf->stream('');
    }
    public function pdf_semua_so_coo($id, $value, $jenis, $stamp)
    {
        $data = NoseriCoo::whereHas('NoseriDetailLogistik.DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
            $q->where('pesanan.id', $id);
        })->get();
        $count = $data->count();

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_ekat_so', ['data' => $data, 'count' => $count, 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_semua_spa_so', ['data' => $data, 'count' => $count, 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
        }
        return $pdf->stream('');
    }
    public function pdf_seri_coo($id, $value, $jenis, $stamp)
    {
        $data = NoseriCoo::where('noseri_logistik_id', $id)->first();
        $tgl_sj = $data->NoseriDetailLogistik->DetailLogistik->logistik->tgl_kirim;
        $bulan =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('m');
        $tahun =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('Y');
        $romawi = $this->toRomawi($bulan);
        $footer = Carbon::createFromFormat('Y-m-d', $tgl_sj)->isoFormat('D MMMM Y');

        if ($value == 'ekatalog') {
            $pdf = PDF::loadView('page.dc.coo.pdf_ekat', ['data' => $data, 'romawi' => $romawi, 'tahun' => $tahun, 'footer' => $footer, 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
        } else {
            $pdf = PDF::loadView('page.dc.coo.pdf_spa', ['data' => $data, 'romawi' => $romawi, 'tahun' => $tahun, 'footer' => $footer, 'jenis' => $jenis, 'stamp' => $stamp])->setPaper('A4');
        }

        return $pdf->stream('');
    }
    public function get_data_coo($tahun)
    {
        // $data = NoseriCoo::with(['NoseriDetailLogistik.NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi','NoseriDetailLogistik.DetailLogistik.DetailPesananProduk.']);


        $data = DB::table('noseri_coo')
            ->select(
                DB::raw("MONTH(logistik.tgl_kirim) as bulan_coo"),
                DB::raw("DATE_FORMAT(logistik.tgl_kirim, '%d-%m-%Y') as tglsjcoo"),
                DB::raw("DATE_FORMAT(noseri_coo.tgl_kirim, '%d-%m-%Y') as tglkirim_coo"),
                'noseri_coo.catatan as coo_catatan',
                'noseri_coo.jenis as coo_jenis',
                'noseri',
                'noseri_logistik.id as noserilogistik_id',
                'produk.no_akd',
                'produk.nama_coo as nama',
                'produk.nama as tipe ',
                'produk.merk',
                'pesanan.no_po as nopo',
                'ekatalog.no_paket as no_akn',
                DB::raw("(CASE WHEN noseri_coo.ket = 'spa' THEN 'Kusmardiana Rahayu'
                WHEN noseri_coo.ket = 'emiindo' THEN 'Bambang Hendro M BE'
                ELSE noseri_coo.nama END ) as pic")
            )
            ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
            ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
            ->leftJoin('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'penjualan_produk.id')
            ->leftJoin('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
            ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->orderBy('noseri_coo.id', 'DESC')
            ->where(['produk.coo' => 1, 'penjualan_produk.status' => 'ekat'])
            ->whereYear('noseri_coo.created_at', $tahun)
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

                if ($data->coo_jenis == 'antro') {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a href="/dc/coo/rework/pdf?id=' . $data->noserilogistik_id . '&produk=' . $x . '&penjualan=ekatalog&jenis=kosong&stamp=0" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo
                      </button>
                  </a>
                      <a href="/dc/coo/rework/pdf?id=' . $data->noserilogistik_id . '&produk=' . $x . '&penjualan=ekatalog&jenis=back&stamp=0" target="_blank">
                          <button class="dropdown-item" type="button">
                              <i class="fas fa-file"></i>
                              Coo + Background
                          </button>
                      </a>
                      <a href="/dc/coo/rework/pdf?id=' . $data->noserilogistik_id . '&produk=' . $x . '&penjualan=ekatalog&jenis=ttd&stamp=0" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo + Background + Ttd
                      </button>
                  </a>
                      <a href="/dc/coo/rework/pdf?id=' . $data->noserilogistik_id . '&produk=' . $x . '&penjualan=ekatalog&jenis=ttd&stamp=1" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo + Background + Ttd + Stamp
                      </button>
                  </a>
                  </div>';
                } else {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a href="' . route('dc.seri.coo.pdf', [$data->noserilogistik_id, $x, "kosong", 0]) . '" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo
                      </button>
                  </a>
                      <a href="' . route('dc.seri.coo.pdf', [$data->noserilogistik_id, $x, "back", 0]) . '" target="_blank">
                          <button class="dropdown-item" type="button">
                              <i class="fas fa-file"></i>
                              Coo + Background
                          </button>
                      </a>
                      <a href="' . route('dc.seri.coo.pdf', [$data->noserilogistik_id, $x, "ttd", 0]) . '" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo + Background + Ttd
                      </button>
                  </a>
                      <a href="' . route('dc.seri.coo.pdf', [$data->noserilogistik_id, $x, "ttd", 1]) . '" target="_blank">
                      <button class="dropdown-item" type="button">
                          <i class="fas fa-file"></i>
                          Coo + Background + Ttd + Stamp
                      </button>
                  </a>
                  </div>';
                }
            })
            ->rawColumns(['laporan'])
            ->make(true);
    }
    public function get_data_so_in_process()
    {
        $data = Pesanan::whereNotNull('no_po')
            ->has('Ekatalog')
            ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi'])
            ->addSelect([
                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
                }
            ])->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function ($data) {
                if ($data->Ekatalog) {
                    return "E-Catalogue";
                } else if ($data->Spa) {
                    return "SPA";
                } else if ($data->Spb) {
                    return "SPB";
                }
            })
            ->addColumn('nama_customer', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->Customer->nama;
                } else if ($data->Spa) {
                    return $data->Spa->Customer->nama;
                } else if ($data->Spb) {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('no_paket', function ($data) {
                if ($data->Ekatalog) {
                    $datas = '';
                    $datas .= '<div>' . $data->Ekatalog->no_paket . '</div>';
                    if (!empty($data->Ekatalog->status)) {
                        if ($data->Ekatalog->status == "batal") {
                            $datas .= '<small class="badge-danger badge">';
                        } else if ($data->Ekatalog->status == "negosiasi") {
                            $datas .= '<small class="badge-warning badge">';
                        } else if ($data->Ekatalog->status == "draft") {
                            $datas .= '<small class="badge-info badge">';
                        } else if ($data->Ekatalog->status == "sepakat") {
                            $datas .= '<small class="badge-success badge">';
                        }
                        $datas .= ucfirst($data->Ekatalog->status) . '</small>';
                    }

                    return $datas;
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl_order', function ($data) {

                if (!empty($data->tgl_po)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
                } else {
                    return "-";
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                if ($data->Ekatalog) {
                    if ($data->tgl_kontrak_custom != "") {
                        if ($data->log_id != '10') {
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
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih dari ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                        }
                    }
                } else {
                    return "-";
                }
            })
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('nopo', function ($data) {
                return $data->no_po;
            })
            ->addColumn('status', function ($data) {
                $progress = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($hitung > 0) {
                        $progress = '<div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                    } else {
                        $progress = '<div class="progress">
                            <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                    }
                }

                if ($data->Ekatalog) {
                    if ($data->Ekatalog->status == "batal" && ($data->log_id != "7")) {
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->log_id == "7") {
                            return '<span class="badge red-text">' . $data->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                } else if ($data->Spa) {
                    if ($data->Spa->log == "batal") {
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->Spa->log_id == "7") {
                            return '<span class="badge red-text">' . $data->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                } else if ($data->Spb) {
                    if ($data->Spb->log == "batal") {
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->log_id == "7") {
                            return '<span class="badge red-text">' . $data->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                }
            })
            ->addColumn('button', function ($data) {
                if ($data->Ekatalog) {
                    return  '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->Ekatalog->id) . '"  data-id="' . $data->Ekatalog->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                } else if ($data->Spa) {
                    return  '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->Spa->id) . '"  data-id="' . $data->Spa->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                } else {
                    return  '<a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->Spb->id) . '"  data-id="' . $data->Spb->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                }
            })

            ->rawColumns(['button', 'status', 'tgl_order', 'tgl_kontrak', 'no_paket'])
            ->setRowClass(function ($data) {
                if ($data->Ekatalog) {
                    if ($data->Ekatalog->status == 'batal') {
                        return 'text-danger font-weight-bold line-through';
                    }
                } else if ($data->Spa) {
                    if ($data->Spa->log == 'batal') {
                        return 'text-danger font-weight-bold line-through';
                    }
                } else if ($data->Spb) {
                    if ($data->Spb->log == 'batal') {
                        return 'text-danger font-weight-bold line-through';
                    }
                }
            })
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



        $data = Pesanan::whereIn('id', function ($q) {
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
            ->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ccoo' => function ($q) {
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
                'cseri' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                },
                'cek_rw' => function ($q) {
                    $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                        ->from('seri_detail_rw')
                        ->leftjoin('noseri_barang_jadi', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                        ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                        ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
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
                if ($data->tgl_kontrak != "") {
                    if ($data->log_id) {
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
                        } else {
                            return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih dari ' . $hari . ' Hari</small></div>';
                        }
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->Customer->nama;
                } else if ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else if ($name[1] == 'SPB') {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->instansi;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $hitung = floor((($data->ccoo / $data->cseri) * 100));
                if ($hitung > 0) {
                    $datas = '<div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small>';
                } else {
                    $datas = '<div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
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

                if ($data->cek_rw > 0) {
                    if ($name[1] == 'EKAT') {

                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                     <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="kosong" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="back" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="ttd" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="ttd" data-stamp="1" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd + Stamp
                    </button>
                        <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times text-danger"></i>
                       <b class="text-danger">Batal</b>
                    </button>

                    </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="kosong" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="back" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="ttd" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="ttd" data-stamp="1" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd + Stamp
                    </button>
                    <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times "></i>
                        <b class="text-danger">Batal</b>
                    </button>
                    </div>';
                    }
                } else {
                    if ($name[1] == 'EKAT') {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back', 0]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd + Stamp
                    </button>
                            </a>

                        <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times text-danger"></i>
                       <b class="text-danger">Batal</b>
                    </button>

                    </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background + Ttd
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background + Ttd + Stamp
                                </button>
                            </a>
                                <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times "></i>
                                <b class="text-danger">Batal</b>
                                </button>
                    </div>';
                    }
                }
            })
            ->rawColumns(['button', 'status', 'batas_paket'])
            ->make(true);
    }
    public function get_data_so_selesai($years)
    {
        $data = Pesanan::whereIn('id', function ($q) {
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
        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi', 'Ekatalog.Provinsi'])
            ->addSelect(['ccoo' => function ($q) {
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
            }, 'cseri' => function ($q) {
                $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->where('produk.coo', '=', '1');
            }, 'cek_rw' => function ($q) {
                $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                    ->from('seri_detail_rw')
                    ->leftjoin('noseri_barang_jadi', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                    ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            }])->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
            ->whereYear('created_at', $years)
            ->whereNotIn('log_id', ['7'])->orderBy('id', 'desc')->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_paket', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('batas_paket', function ($data) {
                if (isset($data->Ekatalog->tgl_kontrak)) {
                    $tgl_sekarang = Carbon::now()->format('Y-m-d');
                    $tgl_parameter = $this->getHariBatasKontrak($data->Ekatalog->tgl_kontrak, $data->Ekatalog->Provinsi->status)->format('Y-m-d');

                    if (isset($data->so)) {
                        if ($tgl_sekarang <= $tgl_parameter) {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->Ekatalog->tgl_kontrak, $data->Ekatalog->Provinsi->status);
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
                        } else {
                            $to = Carbon::now();
                            $from = $this->getHariBatasKontrak($data->Ekatalog->tgl_kontrak, $data->Ekatalog->Provinsi->status);
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
                    return $data->Ekatalog->Customer->nama;
                } else if ($name[1] == 'SPA') {
                    return $data->Spa->Customer->nama;
                } else if ($name[1] == 'SPB') {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->Ekatalog->instansi;
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                if ($data->ccoo >= $data->cseri) {
                    return  '<span class="badge green-text">Selesai</span>';
                } else {
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

                if ($data->ccoo > 100 && $data->cek_rw > 0) {
                    if ($name[1] == 'EKAT') {

                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                     <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="kosong" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="back" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="ttd" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="ekatalog" data-jenis="ttd" data-stamp="1" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd + Stamp
                    </button>
                        <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times text-danger"></i>
                       <b class="text-danger">Batal</b>
                    </button>

                    </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="kosong" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="back" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="ttd" data-stamp="0" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                    <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="spa" data-jenis="ttd" data-stamp="1" class="' . $class . '">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd + Stamp
                    </button>
                    <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times "></i>
                        <b class="text-danger">Batal</b>
                    </button>
                    </div>';
                    }
                } else {
                    if ($name[1] == 'EKAT') {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back', 0]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background
                    </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                            </a>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                        <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd + Stamp
                    </button>
                            </a>
                            <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times "></i>
                            <b class="text-danger">Batal</b>
                            </button>
                    </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a  class="dropdown-item" href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                        <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background + Ttd
                                </button>
                            </a>
                        <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background + Ttd + Stamp
                                </button>
                            </a>
                            <button class="dropdown-item batalmodal ' . $class . ' " type="button" data-id="' . $data->id . '"><i class="fas fa-times "></i>
                            <b class="text-danger">Batal</b>
                            </button>

                    </div>';
                    }
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
        $data = DetailLogistik::addSelect([
            'cek_coo' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_coo.id),0)')
                    ->from('noseri_coo')
                    ->leftjoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->whereColumn('noseri_logistik.detail_logistik_id', 'detail_logistik.id');
            },
            'cek_tf' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->whereColumn('noseri_logistik.detail_logistik_id', 'detail_logistik.id');
            },
            'cek_rw' => function ($q) {
                $q->selectRaw('coalesce(count(seri_detail_rw.id), 0)')
                    ->from('seri_detail_rw')
                    ->leftjoin('noseri_barang_jadi', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                    ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                    ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                    ->whereColumn('noseri_logistik.detail_logistik_id', 'detail_logistik.id');
            }
        ])
            ->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) use ($id) {
                $q->where('pesanan.id', $id);
            })->with(['Logistik', 'DetailPesananProduk.GudangBarangJadi.Produk', 'DetailPesananProduk.DetailPesanan.Pesanan'])->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('tgl_surat', function ($data) {
                return $data->Logistik->tgl_kirim;
            })
            ->addColumn('nama_paket', function ($data) {
                if ($data->DetailPesananProduk->GudangBarangJadi->nama == ' ') {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                } else {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama . ' - ' . $data->DetailPesananProduk->GudangBarangJadi->nama;
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
                $bulan =  Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('m');
                $romawi = $this->toRomawi($bulan);
                return $romawi;
            })
            ->addColumn('status', function ($data) {
                // $value = array();
                // $get = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->get();
                // foreach ($get as $d) {
                //     $value[] = $d->id;
                // }
                // $coo = NoseriCoo::whereIN('noseri_logistik_Id', $value)->get()->count();

                if ($data->DetailPesananProduk->GudangBarangJadi->Produk->coo == '0') {
                    return '<span class="badge red-text">Bukan Produk Utama</span>';
                } else {
                    if ($data->cek_coo == 0) {
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

                // $value = array();
                // $get = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->get();
                // foreach ($get as $d) {
                //     $value[] = $d->id;
                // }
                // $coo = NoseriCoo::whereIN('noseri_logistik_Id', $value)->get()->count();
                // $count_trf = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();

                if ($data->cek_tf == $data->cek_coo) {
                    $c = 0;
                } else {
                    $c = 1;
                }

                if ($data->cek_coo == 0) {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '" data-count="' . $c . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                    </div>';
                } else {
                    if ($data->cek_coo > 1) {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '" data-count="' . $c . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="' . $x . '" data-jenis="kosong" data-stamp="0" data-produk="' . $data->cek_rw . '">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                        <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="' . $x . '" data-jenis="back" data-stamp="0" data-produk="' . $data->cek_rw . '">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                        <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="' . $x . '" data-jenis="ttd" data-stamp="0" data-produk="' . $data->cek_rw . '">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                        <button class="dropdown-item buttonShowModalCOO" type="button" data-id="' . $data->id . '" data-value="' . $x . '" data-jenis="ttd" data-stamp="1" data-produk="' . $data->cek_rw . '">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd + Stamp
                        </button>
                    </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '" data-count="' . $c . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "kosong", 0]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                    </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "back", 0]) . '" target="_blank">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background
                            </button>
                        </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "ttd", 0]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                    </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id, $x, "ttd", 1]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd + Stamp
                        </button>
                    </a>
                    </div>';
                    }
                }
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }
    public function get_data_detail_seri_po($id)
    {
        $data = NoseriCoo::select('noseri_logistik.id', 'noseri_barang_jadi.noseri')
            ->leftJoin('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->orderBy(
                'noseri_coo.no_coo'
            )
            ->where('pesanan.id', $id)
            ->get();


        return response()->json($data);
    }
    public function get_data_detail_seri_so($id, $jenis)
    {
        $data = "";
        if ($jenis == "belum") {
            $data = NoseriDetailLogistik::select('pesanan.so', 'noseri', 'detail_logistik_id', 'noseri_logistik.id', 'noseri_coo.tgl_kirim', 'noseri_coo.catatan')
                ->selectRaw('"" AS jenis')
                ->addSelect([
                    'coo' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_coo.id),0)')
                            ->from('noseri_coo')
                            ->whereColumn('noseri_coo.noseri_logistik_id', 'noseri_logistik.id');
                    },
                ])
                ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->where('detail_logistik_id', $id)
                ->doesntHave('NoseriCoo')->get();
        } else {
            $data = NoseriDetailLogistik::select('pesanan.so', 'noseri', 'detail_logistik_id', 'noseri_logistik.id', 'noseri_coo.tgl_kirim', 'noseri_coo.catatan', 'noseri_coo.jenis')
                ->addSelect([
                    'coo' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_coo.id),0)')
                            ->from('noseri_coo')
                            ->whereColumn('noseri_coo.noseri_logistik_id', 'noseri_logistik.id');
                    },
                ])
                ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->where('detail_logistik_id', $id)
                ->orderBy('noseri_coo.no_coo')
                ->has('NoseriCoo')
                ->get();
        }


        //return response()->json(['jumlah'=> count($data) , 'data' => $data]);

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
                return  $data->noseri;
            })
            ->addColumn('tgl', function ($data) {
                if ($data->coo > 0) {
                    return $data->tgl_kirim;
                } else {
                    return '';
                }
            })
            ->addColumn('ket', function ($data) {
                if ($data->coo > 0) {
                    return $data->catatan;
                } else {
                    return '';
                }
            })
            ->addColumn('laporan', function ($data) {
                $name = explode('/', $data->so);

                if ($name[1] == 'EKAT') {
                    $x = 'ekatalog';
                } else {
                    $x = 'spa';
                }

                if ($data->coo != 0) {
                    if ($data->jenis == 'antro') {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a data-target="#tglkirim_modal" class="tglkirim_modal"  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-pencil-alt"></i>
                                Edit
                            </button>
                        </a>
                            <a href="/dc/coo/rework/pdf?id=' . $data->id . '&produk=1&penjualan=ekatalog&jenis=kosong&stamp=0" target="_blank">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo
                            </button>
                        </a>
                            <a href="/dc/coo/rework/pdf?id=' . $data->id . '&produk=1&penjualan=ekatalog&jenis=back&stamp=0" target="_blank">
                                <button class="dropdown-item" type="button">
                                    <i class="fas fa-file"></i>
                                    Coo + Background
                                </button>
                            </a>
                            <a href="/dc/coo/rework/pdf?id=' . $data->id . '&produk=1&penjualan=ekatalog&jenis=ttd&stamp=0" target="_blank">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background + Ttd
                            </button>
                        </a>
                            <a href="/dc/coo/rework/pdf?id=' . $data->id . 'produk=1&penjualan=ekatalog&jenis=ttd&stamp=0" target="_blank">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background + Ttd + Stamp
                            </button>
                        </a>
                        </div>';
                    } else {
                        return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-target="#tglkirim_modal" class="tglkirim_modal"  data-id="' . $data->id . '">
                    <button class="dropdown-item" type="button">
                    <i class="fas fa-pencil-alt"></i>
                        Edit
                    </button>
                </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "kosong", 0]) . '" target="_blank">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo
                    </button>
                </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "back", 0]) . '" target="_blank">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                    </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "ttd", 0]) . '" target="_blank">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd
                    </button>
                </a>
                    <a href="' . route('dc.seri.coo.pdf', [$data->id, $x, "ttd", 1]) . '" target="_blank">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-file"></i>
                        Coo + Background + Ttd + Stamp
                    </button>
                </a>
                </div>';
                    }
                }
            })
            ->rawColumns(['checkbox', 'laporan'])
            ->make(true);
    }
    public function get_data_detail_select_seri_so($id, $value)
    {
        $array_seri = explode(',', $id);
        if ($id == 0) {
            //  $data =  NoseriDetailLogistik::with(['NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi'])->DoesntHave('NoseriCoo')->where('detail_logistik_id', $value)->get();

            $data = NoseriDetailLogistik::select('noseri')
                ->addSelect([
                    'coo' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_coo.id),0)')
                            ->from('noseri_coo')
                            ->whereColumn('noseri_coo.noseri_logistik_id', 'noseri_logistik.id');
                    },
                ])
                ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->where('detail_logistik_id', $value)
                ->doesntHave('NoseriCoo')->get();
        } else {
            //$data =  NoseriDetailLogistik::with(['NoseriDetailPesanan.NoseriTGbj.NoseriBarangJadi'])->whereIN('id', $array_seri)->get();
            $data = NoseriDetailLogistik::select('noseri')
                ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->whereIn('noseri_logistik.id', $array_seri)
                ->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('noseri', function ($data) {
                return  $data->noseri;
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
    public function create_coo(Request $request)
    {

        $obj = json_decode($request->input('noseri'));
        // if ($id == 0) {
        //     $data =  NoseriDetailLogistik::where('detail_logistik_id', $obj->detail_logistik_id)->first();
        //     $jumlah = count($array_seri);

        //     $seri_data = NoseriDetailLogistik::where('detail_logistik_id', $obj->detail_logistik_id)->get();
        //     foreach ($seri_data as $d) {
        //         $value2[] = $d->id;
        //     }
        //     $noseri_id =  json_encode($value2);
        // } else {
        //     $data =  NoseriDetailLogistik::whereIN('id', $array_seri)->first();
        //     $jumlah = count($array_seri);

        //     $seri_data = NoseriDetailLogistik::whereIN('id', $array_seri)->get();
        //     foreach ($seri_data as $d) {
        //         $value2[] = $d->id;
        //     }
        //     $noseri_id =  json_encode($value2);
        // }

        $series = NoseriDetailLogistik::select('noseri_barang_jadi.noseri')
            ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->whereIN('noseri_logistik.id', $obj->id)
            ->pluck('noseri')->toArray();

        $data =  NoseriDetailLogistik::select('produk.nama', 'produk.no_akd')
            ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->whereIN('noseri_logistik.id', $obj->id)
            ->first();
        $jumlah = count($obj->id);

        // $seri_data = NoseriDetailLogistik::whereIN('id',  $obj->id)->get();
        // foreach ($seri_data as $d) {
        //     $value2[] = $d->id;
        // }

        return view('page.dc.coo.create', ['series' => $series, 'data' => $data, 'id' => $obj->id, 'jumlah' => $jumlah, 'noseri_id' => json_encode($obj->id)]);
    }

    public function edit_coo(Request $request)
    {

        $obj = json_decode($request->input('noseri'));
        // $value2 = array();
        // $array_seri = explode(',', $id);
        // if ($id == 0) {
        //     $data =  NoseriDetailLogistik::where('detail_logistik_id', $value)->first();
        //     $jumlah = count($array_seri);

        //     $seri_data = NoseriDetailLogistik::where('detail_logistik_id', $value)->get();
        //     foreach ($seri_data as $d) {
        //         $value2[] = $d->id;
        //     }
        //     $noseri_id =  json_encode($value2);
        // } else {
        //     $data =  NoseriDetailLogistik::whereIN('id', $array_seri)->first();
        //     $jumlah = count($array_seri);

        //     $seri_data = NoseriDetailLogistik::whereIN('id', $array_seri)->get();
        //     foreach ($seri_data as $d) {
        //         $value2[] = $d->id;
        //     }
        //     $noseri_id =  json_encode($value2);
        // }

        $series = NoseriDetailLogistik::select('noseri_barang_jadi.noseri')
            ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->whereIN('noseri_logistik.id', $obj->id)
            ->pluck('noseri')->toArray();

        $data =  NoseriDetailLogistik::select('produk.nama', 'produk.no_akd')
            ->leftJoin('noseri_coo', 'noseri_coo.noseri_logistik_id', '=', 'noseri_logistik.id')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->whereIN('noseri_logistik.id', $obj->id)
            ->first();
        $jumlah = count($obj->id);
        return view('page.dc.coo.edit', ['series' => $series, 'data' => $data, 'id' => $obj->id, 'jumlah' => $jumlah, 'noseri_id' => json_encode($obj->id)]);
    }

    public function edit_tglkirim_coo($id)
    {
        $data =  NoseriDetailLogistik::find($id);
        return view('page.dc.coo.tglkirim_edit', ['data' => $data]);
    }

    function getYear($value)
    {
        $tahun = Carbon::createFromFormat('Y-m-d', $value)->format('Y');
        return  $tahun;
    }
    // $check = Pesanan::whereYear('created_at', $this->getYear())->where('so', 'like', '%' . $this->getYear() . '%')->get('so');
    public function store_coo(Request $request)
    {
        $array_seri = explode(',', $request->id);
        DB::beginTransaction();
        try {
            $array_seri = explode(',', $request->id);
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

            $l = NoseriDetailLogistik::find($array_seri[0]);
            $cek_rw = SeriDetailRw::selectRaw('coalesce(count(seri_detail_rw.id), 0) as cek')
                ->leftjoin('noseri_barang_jadi', 'seri_detail_rw.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->where('noseri_logistik.id', $array_seri[0])->get();

            if ($cek_rw[0]->cek > 0) {
                $max = NoseriCoo::where(['tahun' => $this->getYear($l->DetailLogistik->Logistik->tgl_kirim), 'jenis' => 'antro'])->latest('id')->value('no_coo') + 1;
            } else {
                $max = NoseriCoo::where(['tahun' => $this->getYear($l->DetailLogistik->Logistik->tgl_kirim), 'jenis' => 'default'])->latest('id')->value('no_coo') + 1;
            }

            $thn =  $this->getYear($l->DetailLogistik->Logistik->tgl_kirim);
            //code...
            for ($i = 0; $i < count($array_seri); $i++) {
                NoseriCoo::create([
                    'no_coo' => $max + $i,
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                    'noseri_logistik_id' => $array_seri[$i],
                    'ket' => $ket,
                    'tgl_kirim' => $request->tgl_kirim,
                    'catatan' =>  $request->keterangan,
                    'tahun' => $thn,
                    'jenis' => $cek_rw[0]->cek > 0 ? 'antro' : 'default'
                ]);
            }
            $series = NoseriBarangJadi::select('noseri_barang_jadi.noseri')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->whereIN('noseri_logistik.id', $array_seri)
                ->pluck('noseri_barang_jadi.noseri')
                ->toArray();

            $item = [
                'noseri' => $series,
                'diketahui' => $request->diketahui,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'tgl_kirim' => $request->tgl_kirim,
                'keterangan' => $request->keterangan
            ];

            SystemLog::create([
                'tipe' => 'DC',
                'subjek' => 'Penambahan COO',
                'response' => json_encode($item),
                'user_id' => $request->user_id,
            ]);

            DB::commit();
            return response()->json(['data' =>  'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['data' =>  'error'], 500);
        }
    }

    public function update_coo(Request $request)
    {
        DB::beginTransaction();
        try {
            $array_seri = explode(',', $request->id);
            //code...
            for ($i = 0; $i < count($array_seri); $i++) {
                NoseriCoo::where('noseri_logistik_id', $array_seri[$i])
                    ->update([
                        'tgl_kirim' => $request->edit_tgl_kirim,
                        'catatan' => $request->edit_keterangan
                    ]);
            }
            $series = NoseriBarangJadi::select('noseri_barang_jadi.noseri')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->whereIN('noseri_logistik.id', $array_seri)
                ->pluck('noseri_barang_jadi.noseri')
                ->toArray();
            $item = [
                'noseri' => $series,
                'diketahui' => $request->diketahui,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'tgl_kirim' => $request->edit_tgl_kirim,
                'keterangan' => $request->edit_keterangan,
            ];
            SystemLog::create([
                'tipe' => 'DC',
                'subjek' => 'Ubah COO',
                'response' => json_encode($item),
                'user_id' => $request->user_id,
            ]);
            DB::commit();
            return response()->json(['data' =>  'success'], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            //dd($th->getMessage());
            return response()->json(['data' =>  'error'], 500);
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
            $obj = [
                'noseri' => NoseriBarangJadi::whereIn(
                    'id',
                    NoseriTGbj::whereIn(
                        'id',
                        NoseriDetailPesanan::whereIn(
                            'id',
                            NoseriDetailLogistik::where('id', NoseriCoo::find($id)->noseri_logistik_id)
                                ->get()->pluck('noseri_detail_pesanan_id')
                        )
                            ->get()->pluck('t_tfbj_noseri_id')
                    )
                        ->get()->pluck('noseri_id')
                )
                    ->get()->pluck('noseri'),
                'tgl_kirim' => $request->tgl_kirim,
                'keterangan' => $request->keterangan,
            ];

            SystemLog::create([
                'tipe' => 'DC',
                'subjek' => 'Perubahan COO',
                'response' => json_encode($obj),
                'user_id' => $request->user_id,
            ]);
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function dashboard()
    {
        $daftar_so = Pesanan::whereIn('id', function ($q) {
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
            ->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ccoo' => function ($q) {
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
                'cseri' => function ($q) {
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

        $belum_coo = Pesanan::whereIn('id', function ($q) {
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
            ->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ccoo' => function ($q) {
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
                'cseri' => function ($q) {
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

        $penjualan = Pesanan::addSelect(['cjumlahprd' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'cjumlahpart' => function ($q) {
            $q->selectRaw('sum(detail_pesanan_part.jumlah)')
                ->from('detail_pesanan_part')
                ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }, 'clogprd' => function ($q) {
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'clogpart' => function ($q) {
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

        $lewat_batas = Pesanan::whereIn('id', function ($q) {
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
            ->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ccoo' => function ($q) {
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
                'cseri' => function ($q) {
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

        $penjualan = Pesanan::addSelect(['cjumlahprd' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'cjumlahpart' => function ($q) {
            $q->selectRaw('sum(detail_pesanan_part.jumlah)')
                ->from('detail_pesanan_part')
                ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }, 'clogprd' => function ($q) {
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'clogpart' => function ($q) {
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

        $gudang = Pesanan::addSelect(['jumlah_produk' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
        }, 'jumlah_gudang' => function ($q) {
            $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
        }])->whereNotIn('log_id', ['7'])->havingRaw('jumlah_produk > jumlah_gudang')->has('Ekatalog')->count();

        $qc = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([
            'tgl_kontrak' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                    ->from('ekatalog')
                    ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'ctfprd' => function ($q) {
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
            },
            'cqcprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                    ->from('noseri_detail_pesanan')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('noseri_detail_pesanan.status', 'ok')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }
        ])->with(['ekatalog.customer.provinsi'])
            ->havingRaw('(ctfprd > cqcprd AND ctfprd > 0)')
            ->orderBy('tgl_kontrak', 'asc')
            ->has('Ekatalog')
            ->count();

        $logistik = Pesanan::addSelect([
            'cqcprd' => function ($q) {
                $q->selectRaw('count(noseri_detail_pesanan.id)')
                    ->from('noseri_detail_pesanan')
                    ->join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->join('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->join('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->where('noseri_detail_pesanan.status', 'ok')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'clogprd' => function ($q) {
                $q->selectRaw('count(noseri_logistik.id)')
                    ->from('noseri_logistik')
                    ->join('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->join('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->join('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'ccoo' => function ($q) {
                $q->selectRaw('count(noseri_coo.id)')
                    ->from('noseri_coo')
                    ->join('noseri_logistik', 'noseri_logistik.id', '=', 'noseri_coo.noseri_logistik_id')
                    ->join('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                    ->join('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                    ->join('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('produk.coo', 1)
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
        ])
            ->havingRaw('cqcprd > 0 AND ((ccoo < clogprd OR clogprd <= 0))')
            ->has('Ekatalog')
            ->count();
        return view('page.dc.dashboard', ['daftar_so' => $daftar_so, 'belum_coo' => $belum_coo, 'lewat_batas' => $lewat_batas, 'penjualan' => $penjualan, 'gudang' => $gudang, 'qc' => $qc, 'logistik' => $logistik]);
    }

    public function dashboard_data($value)
    {
        if ($value == 'pengirimansotable') {
            $data = Pesanan::whereIn('id', function ($q) {
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
                ->addSelect([
                    'tgl_kontrak' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog')
                            ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                            ->limit(1);
                    },
                    'ccoo' => function ($q) {
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
                    'cseri' => function ($q) {
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
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id) {
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
                            } else {
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else {
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
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo
                            </button>
                                    </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background
                            </button>
                                    </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background + Ttd
                            </button>
                                    </a>
                                    </a>
                                <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Coo + Background + Ttd + Stamp
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
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd + Stamp
                        </button>
                                </a>
                        </div>';
                    }
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        } else if ($value == 'sotanpacootable') {
            $data = Pesanan::whereIn('id', function ($q) {
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
                ->addSelect([
                    'tgl_kontrak' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog')
                            ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                            ->limit(1);
                    },
                    'ccoo' => function ($q) {
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
                    'cseri' => function ($q) {
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
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id) {
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
                            } else {
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else {
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
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'back', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'ekatalog', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd + Stamp
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
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'kosong', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'back', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 0]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd
                        </button>
                                </a>
                            <a href="' . route('dc.coo.semua.so.pdf', [$data->id, 'spa', 'ttd', 1]) . '" target="_blank" class="' . $class . '">
                            <button class="dropdown-item" type="button">
                            <i class="fas fa-file"></i>
                            Coo + Background + Ttd + Stamp
                        </button>
                                </a>

                        </div>';
                    }
                })
                ->rawColumns(['batas_kontrak', 'status', 'button'])
                ->make(true);
        } else {
            $data = Pesanan::whereIn('id', function ($q) {
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
                ->addSelect([
                    'tgl_kontrak' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog')
                            ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                            ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                            ->limit(1);
                    },
                    'ccoo' => function ($q) {
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
                    'cseri' => function ($q) {
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
                    if ($data->tgl_kontrak != "") {
                        if ($data->log_id) {
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
                            } else {
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else {
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

    public function dashboard_so()
    {
        $data = Pesanan::whereIn('id', function ($q) {
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
                ->havingRaw('NOT EXISTS (select *
                    from noseri_coo
                    left join noseri_logistik on noseri_logistik.id = noseri_coo.noseri_logistik_id
                    left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
                    left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
                    left join gdg_barang_jadi on gdg_barang_jadi.id = detail_pesanan_produk.gudang_barang_jadi_id
                    left join produk on produk.id = gdg_barang_jadi.produk_id AND produk.coo = 1
                    left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
                    where detail_pesanan.pesanan_id = pesanan.id)');
        })->with(['Ekatalog.Customer.Provinsi'])
            ->addSelect([
                'tgl_kontrak' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                },
                'ccoo' => function ($q) {
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
                'cseri' => function ($q) {
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
                'cjumlah' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        // ->where('produk.coo', 1)
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
                }
            ])->whereNotIn('log_id', ['7', '20'])->orderBy('tgl_kontrak', 'desc')->has('Ekatalog')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->no_po;
            })
            ->addColumn('no_paket', function ($data) {
                if ($data->Ekatalog) {
                    return $data->Ekatalog->no_paket;
                } else {
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
                $hitung = floor((($data->cseri / $data->cjumlah) * 100));
                if ($data->log_id == "9") {
                    $datas = '<span class="badge purple-text">' . $data->State->nama . '</span>';
                } else {
                    if ($hitung > 0) {
                        $datas = '<div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                    } else {
                        $datas = '<div class="progress">
                                <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                    }
                }
                return $datas;
            })
            ->addColumn('aksi', function ($data) {
                $id = "";
                $jenis = "";
                if ($data->Ekatalog) {
                    $id = $data->Ekatalog->id;
                    $jenis = "ekatalog";
                } else if ($data->Spa) {
                    $id = $data->Spa->id;
                    $jenis = "spa";
                } else if ($data->Spb) {
                    $id = $data->Spb->id;
                    $jenis = "spb";
                }
                return  '<a data-toggle="modal" data-target="' . $jenis . '" class="somodal" data-attr="' . route('penjualan.penjualan.detail.' . $jenis,  $id) . '"  data-id="' . $id . '">
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

    public function cancel_so(Request $request)
    {
        $user = User::find($request->user);

        $cek =  str_word_count($request->alasan);
        if ($cek < 5) {
            return response()->json([
                'data' => 'alasan_salah',
                'message' => 'Alasan yang dimasukkan minimal 5 kata',
            ], 200);
        }

        $get = DB::select('
       select  p.no_po , group_concat(nc.id) as coo_id, group_concat(nc.tahun) as tahun , group_concat(nc.no_coo) as coo_no ,  group_concat(nbj.noseri) as seri  from noseri_coo nc
       left join  noseri_logistik nl on nc.noseri_logistik_id = nl.id
       left join noseri_detail_pesanan ndp on ndp.id = nl.noseri_detail_pesanan_id
       left join t_gbj_noseri tgn on tgn.id = ndp.t_tfbj_noseri_id
       left join noseri_barang_jadi nbj on nbj.id = tgn.noseri_id
       left join t_gbj_detail tgd on tgd.id = tgn.t_gbj_detail_id
       left join t_gbj tg on tg.id = tgd.t_gbj_id
       left join pesanan p on p.id = tg.pesanan_id
       where p.id = ?', [$request->id]);


        $id =  explode(',', $get[0]->coo_id);
        $seri =  explode(',', $get[0]->seri);
        $coo_no =  explode(',', $get[0]->coo_no);
        $tahun =  explode(',', $get[0]->tahun);

        if (count($seri) > 0) {

            try {
                NoseriCoo::whereIn('id', $id)->delete();
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Ada kesalahan, batal transaksi gagal',
                ], 500);
            }

            $save =   SystemLog::create([
                'tipe' => 'DC',
                'subjek' => 'Batalkan Transaksi',
                'user_id' => $user->id
            ]);

            foreach ($seri as $key_c => $coo) {
                $seri[$key_c] = array(
                    'coo_no' =>   $coo_no[$key_c],
                    'tahun' =>   $tahun[$key_c],
                    'noseri' =>   $seri[$key_c]

                );
            }

            $data = array(
                'po' => $get[0]->no_po,
                'alasan' => $request->alasan,
                'noseri' => $seri
            );

            $data = json_encode($data);
            $get_response = SystemLog::find($save->id);
            $get_response->response = $data;
            $get_response->save();


            return response()->json([
                'data' => 'berhasil',
                'message' => 'Data berhasil dibatalkan',
            ], 200);
        }

        return response()->json([
            'message' => 'Ada kesalahan, batal transaksi gagal',
        ], 500);


        return response()->json([
            'data' => 'berhasil',
            'message' => 'Data berhasil dibatalkan',
        ], 200);
    }

    public function cancel_so_view($id)
    {
        $data = Pesanan::find($id);
        return view('page.dc.so.cancel', ['id' => $id, 'data' => $data]);
    }
}
