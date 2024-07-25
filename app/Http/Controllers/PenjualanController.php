<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualan;
use App\Exports\LaporanPenjualanAll;
use App\Models\AktifPeriode;
use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailLogistik;
use App\Models\DetailLogistikPart;
use App\Models\DetailPesanan;
use App\Models\DetailPesananDsb;
use App\Models\DetailPesananPart;
use App\Models\DetailPesananProduk;
use App\Models\DetailPesananProdukDsb;
use App\Models\DetailRencanaPenjualan;
use App\Models\DetailSpa;
use App\Models\DetailSpb;
use App\Models\NoseriTGbj;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\Logistik;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use App\Models\NoseriDsb;
use App\Models\OutgoingPesananPart;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Provinsi;
use App\Models\RiwayatBatalPo;
use App\Models\RiwayatBatalPoPaket;
use App\Models\RiwayatBatalPoPart;
use App\Models\RiwayatBatalPoPrd;
use App\Models\RiwayatBatalPoSeri;
use App\Models\RiwayatReturPo;
use App\Models\RiwayatReturPoPaket;
use App\Models\RiwayatReturPoPrd;
use App\Models\RiwayatReturPoSeri;
use App\Models\SaveResponse;
use App\Models\SystemLog;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use PDF;
use Carbon\Doctrine\CarbonType;
use Google\Service\ContainerAnalysis\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

use League\Fractal\Resource\Item;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;
use Maatwebsite\Excel\Excel as ExcelExcel;
use stdClass;
use Symfony\Component\Console\Input\Input;
use TGbjHis;

use function PHPUnit\Framework\assertIsNotArray;

class PenjualanController extends Controller
{
    public function in_array_all($needles, $haystack)
    {
        return empty(array_diff($needles, $haystack));
    }
    public function getYearsPeriodePenjualan()
    {
        $data = AktifPeriode::first()->tahun;
        return response()->json($data);
    }
    public function get_items_penjualan($id)
    {
        $data = Pesanan::find($id);
        $item = array();
        $item_dsb = array();
        $item_nondsb = array();

        if ($data->DetailPesanan) {
            foreach ($data->DetailPesanan as $key_d => $d) {
                $item_nondsb[$key_d] = array(
                    'id' => $d->id,
                    'nama' => $d->PenjualanProduk->nama,
                    'jumlah' => $d->jumlah,
                    'jumlah_batal' =>  $d->RiwayatBatalPoPaket ? $d->RiwayatBatalPoPaket->jumlah : 0,
                    'jumlah_retur' =>   $d->RiwayatReturPoPaket ? $d->getJumlahRetur() : 0,
                    'ongkir' => $d->ongkir,
                    'harga' => $d->harga,
                    'jenis' => 'paket',
                    'stok' => 'non_dsb',
                    'produk' => array()
                );
                foreach ($d->DetailPesananProduk as $key_e => $e) {
                    $item_nondsb[$key_d]['produk'][$key_e] = array(
                        'id' => $e->id,
                        'nama' => $e->GudangBarangjadi->Produk->nama . ' ' . $e->GudangBarangjadi->nama,
                        'jenis' => 'variasi',
                        'gudang_barang_jadi_id' => $e->gudang_barang_jadi_id
                    );
                }
            }
        }
        if ($data->DetailPesananDsb) {
            foreach ($data->DetailPesananDsb as $key_d => $d) {
                $item_dsb[$key_d] = array(
                    'id' => $d->id,
                    'nama' => $d->PenjualanProduk->nama,
                    'jumlah' => $d->jumlah,
                    'jumlah_batal' =>   0,
                    'jumlah_retur' =>   0,
                    'ongkir' => $d->ongkir,
                    'harga' => $d->harga,
                    'jenis' => 'paket',
                    'stok' => 'dsb',
                    'produk' => array(),
                    'noseri' => $d->NoseriDsb ? $d->NoseriDsb->pluck('noseri')->toArray() : array()
                );


                foreach ($d->DetailPesananProdukDsb as $key_e => $e) {
                    $item_dsb[$key_d]['produk'][$key_e] = array(
                        'id' => $e->id,
                        'nama' => $e->GudangBarangjadi->Produk->nama . ' ' . $e->GudangBarangjadi->nama,
                        'jenis' => 'variasi',
                        'gudang_barang_jadi_id' => $e->gudang_barang_jadi_id,
                        'noseri' => array()
                    );
                }
            }
        }

        $item = array_merge($item_nondsb, $item_dsb);

        if ($data->DetailPesananPart) {
            foreach ($data->DetailPesananPart as $d) {
                $item[] = array(
                    'id' => $d->id,
                    'nama' => $d->Sparepart->nama,
                    'jumlah' => $d->jumlah,
                    'jumlah_batal' =>  $d->RiwayatBatalPoPart ? $d->RiwayatBatalPoPart->jumlah : 0,
                    'jumlah_retur' =>  0,
                    'ongkir' => $d->ongkir,
                    'harga' => $d->harga,
                    'jenis' => 'part'
                );
            }
        }
        return response()->json($item);
    }

    //Get Data Table
    public function penjualan_data($jenis, $status, $tahun)
    {
        $x = explode(',', $jenis);
        $y = explode(',', $status);
        $data = "";
        if ($jenis == "semua" && $status == "semua") {
            $Ekatalog = collect(Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                }, 'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                }
            ])->whereYear('tgl_buat',  $tahun)->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get());

            $Spa = collect(Spa::addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spa.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                }

            ])->with(['Pesanan.State',  'Customer'])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get());

            $Spb = collect(Spb::addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spb.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                }

            ])->with(['Pesanan.State',  'Customer'])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else if ($jenis != "semua" && $status == "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekatalog', $x)) {
                $Ekatalog = collect(Ekatalog::with(['Pesanan.State', 'Customer'])->addSelect([
                    'cterkirim' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                            ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('logistik.status_id', 10)
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'c_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                            ->from('riwayat_batal_po')
                            ->whereColumn('riwayat_batal_po.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'c_retur' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                            ->from('riwayat_retur_po')
                            ->whereColumn('riwayat_retur_po.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    }, 'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    }
                ])->orderBy('id', 'DESC')->get());
            }
            if (in_array('spa', $x)) {
                $Spa = collect(Spa::addSelect([
                    'cterkirim' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                            ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('logistik.status_id', 10)
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'c_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                            ->from('riwayat_batal_po')
                            ->whereColumn('riwayat_batal_po.pesanan_id', 'spa.pesanan_id');
                    },
                    'c_retur' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                            ->from('riwayat_retur_po')
                            ->whereColumn('riwayat_retur_po.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }
                ])->with(['Pesanan.State', 'Customer'])->orderBy('id', 'DESC')->get());
            }
            if (in_array('spb', $x)) {
                $Spb = collect(Spb::addSelect([

                    'cterkirim' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                            ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('logistik.status_id', 10)
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'c_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                            ->from('riwayat_batal_po')
                            ->whereColumn('riwayat_batal_po.pesanan_id', 'spb.pesanan_id');
                    },
                    'c_retur' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                            ->from('riwayat_retur_po')
                            ->whereColumn('riwayat_retur_po.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }
                ])->with(['Pesanan.State', 'Customer'])->orderBy('id', 'DESC')->get());
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
            $Ekatalog = collect(Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                }, 'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                }
            ])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());

            $Spa = collect(Spa::addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spa.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                }
            ])->with(['Pesanan.State', 'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());

            $Spb = collect(Spb::addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spb.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                }
            ])->with(['Pesanan.State', 'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekatalog', $x)) {
                $Ekatalog = collect(Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([
                    'cterkirim' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                            ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('logistik.status_id', 10)
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'c_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                            ->from('riwayat_batal_po')
                            ->whereColumn('riwayat_batal_po.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'c_retur' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                            ->from('riwayat_retur_po')
                            ->whereColumn('riwayat_retur_po.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    }, 'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    }
                ])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if (in_array('spa', $x)) {
                $Spa = collect(Spa::addSelect([
                    'cterkirim' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                            ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('logistik.status_id', 10)
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'c_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                            ->from('riwayat_batal_po')
                            ->whereColumn('riwayat_batal_po.pesanan_id', 'spa.pesanan_id');
                    },
                    'c_retur' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                            ->from('riwayat_retur_po')
                            ->whereColumn('riwayat_retur_po.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }
                ])->with(['Pesanan.State', 'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if (in_array('spb', $x)) {
                $Spb = collect(Spb::addSelect([
                    'cterkirim' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                            ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->where('logistik.status_id', 10)
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'c_batal' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                            ->from('riwayat_batal_po')
                            ->whereColumn('riwayat_batal_po.pesanan_id', 'spb.pesanan_id');
                    },
                    'c_retur' => function ($q) {
                        $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                            ->from('riwayat_retur_po')
                            ->whereColumn('riwayat_retur_po.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }

                ])->with(['Pesanan.State',  'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
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

        function persentase_and_status($data)
        {
            $name = $data->getTable();
            $progress = "";
            $tes = $data->cjumlahprd + $data->cjumlahpart;
            if ($tes > 0) {
                $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                if ($hitung > 0) {
                    $progress = $hitung;
                } else {
                    $progress = $hitung;
                }
            }

            if ($name == "ekatalog") {
                if ($data->status == "batal" && ($data->Pesanan->log_id != "7")) {
                    // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="EKAT" data-provinsi="">
                    //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                    //         <i class="fas fa-times"></i>
                    //         Batal
                    //     </button>
                    // </a>';
                    return 'Batal';
                } else {
                    if ($data->Pesanan->log_id == "7") {
                        return $data->Pesanan->State->nama;
                    } else {
                        return $progress;
                    }
                }
            } else if ($name == "spa") {
                if ($data->log == "batal") {
                    // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="SPA" data-provinsi="">
                    //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                    //         <i class="fas fa-times"></i>
                    //         Batal
                    //     </button>
                    // </a>';
                    return 'Batal';
                } else {
                    if ($data->Pesanan->log_id == "7") {
                        return $data->Pesanan->State->nama;
                    } else {
                        return $progress;
                    }
                }
            } else if ($name == "spb") {
                if ($data->log == "batal") {
                    // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="SPB" data-provinsi="">
                    //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                    //         <i class="fas fa-times"></i>
                    //         Batal
                    //     </button>
                    // </a>';
                    return 'Batal';
                } else {
                    if ($data->Pesanan->log_id == "7") {
                        return $data->Pesanan->State->nama;
                    } else {
                        return $progress;
                    }
                }
            }
        }

        $data = $data->map(function ($data) {
            $data->persentase = persentase_and_status($data);
            $data->jenis = strtolower($data->getTable());
            $data->is_batal = $this->cekBatal($data);
            $data->is_retur = $this->cekRetur($data);
            return $data;
        });

        return response()->json($data);


        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis_ppic', function ($data) {
                return strtolower($data->getTable());
            })
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
                    $datas = '';
                    $datas .= '<div>' . $data->no_paket . '</div>';
                    if (!empty($data->status)) {
                        if ($data->status == "batal") {
                            $datas .= '<small class="badge-danger badge">';
                        } else if ($data->status == "negosiasi") {
                            $datas .= '<small class="badge-warning badge">';
                        } else if ($data->status == "draft") {
                            $datas .= '<small class="badge-info badge">';
                        } else if ($data->status == "sepakat") {
                            $datas .= '<small class="badge-success badge">';
                        }
                        $datas .= ucfirst($data->status) . '</small>';
                    }

                    return $datas;
                    // return $data->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('no_paket_ppic', function ($data) {
                if (isset($data->no_paket)) {
                    $datas = '';
                    $datas .= $data->no_paket;
                    return $datas;
                    // return $data->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('status_paket', function ($data) {
                if (isset($data->status)) {
                    return strtolower($data->status);
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
            ->addColumn('tgl_kontrak_custom', function ($data) {
                $name = $data->getTable();
                if ($name == 'ekatalog') {
                    if ($data->tgl_kontrak_custom != "") {
                        if ($data->Pesanan->log_id != '10') {
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
                }
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
                $name = $data->getTable();
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

                if ($name == "ekatalog") {
                    if ($data->status == "batal" && ($data->Pesanan->log_id != "7")) {
                        // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="EKAT" data-provinsi="">
                        //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                        //         <i class="fas fa-times"></i>
                        //         Batal
                        //     </button>
                        // </a>';
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spa") {
                    if ($data->log == "batal") {
                        // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="SPA" data-provinsi="">
                        //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                        //         <i class="fas fa-times"></i>
                        //         Batal
                        //     </button>
                        // </a>';
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spb") {
                    if ($data->log == "batal") {
                        // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="SPB" data-provinsi="">
                        //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                        //         <i class="fas fa-times"></i>
                        //         Batal
                        //     </button>
                        // </a>';
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                }

                // $datas = "";
                // if (!empty($data->Pesanan->log_id)) {
                //     if ($data->Pesanan->State->nama == "Penjualan") {
                //         $datas .= '<span class="red-text badge">';
                //     } else if ($data->Pesanan->State->nama == "PO") {
                //         $datas .= '<span class="purple-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Gudang") {
                //         $datas .= '<span class="orange-text badge">';
                //     } else if ($data->Pesanan->State->nama == "QC") {
                //         $datas .= '<span class="yellow-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                //         $datas .= '<span class="red-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                //         $datas .= '<span class="blue-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Kirim") {
                //         $datas .= '<span class="green-text badge">';
                //     }
                //     $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                // } else {
                //     $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                // }
                // return $datas;
            })
            ->addColumn('status_ppic', function ($data) {
                $name = $data->getTable();
                $progress = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($hitung > 0) {
                        $progress = $hitung;
                    } else {
                        $progress = $hitung;
                    }
                }

                if ($name == "ekatalog") {
                    if ($data->status == "batal" && ($data->Pesanan->log_id != "7")) {
                        return 'batal';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return strtolower($data->Pesanan->State->nama);
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spa") {
                    if ($data->log == "batal") {

                        return 'batal';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return strtolower($data->Pesanan->State->nama);
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spb") {
                    if ($data->log == "batal") {

                        return 'batal';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return strtolower($data->Pesanan->State->nama);
                        } else {
                            return $progress;
                        }
                    }
                }
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
            ->rawColumns(['button', 'status', 'tgl_order', 'tgl_kontrak_custom', 'no_paket'])
            ->setRowClass(function ($data) {
                // return 'text-danger font-weight-bold line-through';
                $name =  $data->getTable();
                if ($name == 'ekatalog') {
                    if ($data->status == 'batal' || $data->Pesanan->State->nama == 'Batal') {
                        return 'text-danger font-weight-bold line-through';
                    }
                } else {
                    if ($data->Pesanan->State->nama == 'Batal') {
                        return 'text-danger font-weight-bold line-through';
                    }
                }
            })
            ->make(true);
    }
    public function get_lacak_penjualan($parameter, $value)
    {
        if ($parameter == 'no_po') {
            $val = str_replace("_",  "/",  $value);
            $si_ekat21 = DB::connection('si_21')->table('admjual_on')
                ->select(
                    'admjual_on.nopo_on as no_po',
                    'admjual_on.tglpo_on as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_on.pabrikadm_on')
                ->where('admjual_on.nopo_on', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_on.nopo_on')
                ->get();

            $si_ekat20 = DB::connection('si_20')->table('admjual_on')
                ->select(
                    'admjual_on.nopo_on as no_po',
                    'admjual_on.tglpo_on as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_on.pabrikadm_on')
                ->where('admjual_on.nopo_on', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_on.nopo_on')
                ->get();


            $si_spa21 = DB::connection('si_21')->table('admjual_off')
                ->select(
                    'admjual_off.nopo_off as no_po',
                    'admjual_off.tglpo_off as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_off.pabrikadm_off')
                ->where('admjual_off.nopo_off', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_off.nopo_off')
                ->get();

            $si_spa20 = DB::connection('si_20')->table('admjual_off')
                ->select(
                    'admjual_off.nopo_off as no_po',
                    'admjual_off.tglpo_off as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_off.pabrikadm_off')
                ->where('admjual_off.nopo_off', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_off.nopo_off')
                ->get();


            $si_spb21 = DB::connection('si_21')->table('admjual_spb')
                ->select(
                    'admjual_spb.nopo_spb as no_po',
                    'admjual_spb.tglpo_spb as tgl_po',
                    'spb.pelanggan_spb as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('spb', 'spb.nospb', '=', 'admjual_spb.noadm_spb')
                ->where('admjual_spb.nopo_spb', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_spb.nopo_spb')
                ->get();

            $si_spb20 = DB::connection('si_20')->table('admjual_spb')
                ->select(
                    'admjual_spb.nopo_spb as no_po',
                    'admjual_spb.tglpo_spb as tgl_po',
                    'spb.pelanggan_spb as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('spb', 'spb.nospb', '=', 'admjual_spb.noadm_spb')
                ->where('admjual_spb.nopo_spb', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_spb.nopo_spb')
                ->get();

            $spa = Pesanan::select(
                'pesanan.id',
                'pesanan.no_po',
                'pesanan.so',
                'pesanan.tgl_po',
                // 'm_state.nama as state_nama',
                // 'c_ekat.nama as c_ekat_nama',
                // 'c_spa.nama as c_spa_nama',
                // 'c_spb.nama as c_spb_nama',
                // 'ekatalog.satuan as satuan',
            )
                ->leftJoin('detail_pesanan',  'detail_pesanan.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('ekatalog',  'ekatalog.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('customer as c_ekat',  'c_ekat.id',  '=',  'ekatalog.customer_id')
                // ->leftJoin('spa',  'spa.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('customer as c_spa',  'c_spa.id',  '=',  'spa.customer_id')
                // ->leftJoin('spb',  'spb.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('customer as c_spb',  'c_spb.id',  '=',  'spb.customer_id')
                // ->leftJoin('m_state',  'm_state.id',  '=',  'pesanan.log_id')
                // ->leftJoin('m_state',  'm_state.id',  '=',  'pesanan.log_id')
                ->where('no_po', 'LIKE', '%' . $val . '%')
                ->groupby('pesanan.no_po')
                ->get();

            if (count($spa) > 0) {
                foreach ($spa as $s) {
                    $spa_data[] = array(
                        'so' => $s->so,
                        'no_po' => $s->no_po,
                        'tgl_po' => $s->tgl_po,
                        'sj' => $s->getSuratJalan()
                    );
                }
                $data = $si_ekat21->merge($si_ekat20)->merge($si_spa21)->merge($si_spa20)->merge($si_spb21)->merge($si_spb20)->merge($spa_data);
            } else {
                $data = $si_ekat21->merge($si_ekat20)->merge($si_spa21)->merge($si_spa20)->merge($si_spb21)->merge($si_spb20);
            }


            return response()->json(['data' => $data]);
            // $val = str_replace("-",  "/",  $value);
            // $data = Pesanan::select(

            //     'pesanan.no_po',
            //     'pesanan.so',
            //     'pesanan.tgl_po',
            //     'm_state.nama as state_nama',
            //     'c_ekat.nama as c_ekat_nama',
            //     'c_spa.nama as c_spa_nama',
            //     'c_spb.nama as c_spb_nama',
            //     'ekatalog.satuan as satuan',
            // )
            //     ->leftJoin('ekatalog',  'ekatalog.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_ekat',  'c_ekat.id',  '=',  'ekatalog.customer_id')
            //     ->leftJoin('spa',  'spa.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_spa',  'c_spa.id',  '=',  'spa.customer_id')
            //     ->leftJoin('spb',  'spb.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_spb',  'c_spb.id',  '=',  'spb.customer_id')
            //     ->leftJoin('m_state',  'm_state.id',  '=',  'pesanan.log_id')
            //     ->where('no_po', 'LIKE', '%' . $val . '%')
            //     ->get();
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('nama_customer', function ($data) {
            //         $name = explode('/', $data->so);
            //         if ($name[1] == 'EKAT') {
            //             $datas = $data->c_ekat_nama;
            //             if ($data->satuan) {
            //                 $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //             }
            //         } else if ($name[1] == 'SPA') {
            //             $datas = $data->c_spa_nama;
            //         } else if ($name[1] == 'SPB') {
            //             $datas = $data->c_spb_nama;
            //         }

            //         return $datas;
            //     })
            //     ->addColumn('so', function ($data) {
            //         if ($data->so) {
            //             return $data->so;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('no_po', function ($data) {
            //         if ($data->no_po) {
            //             return $data->no_po;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('tgl_po', function ($data) {
            //         if ($data->tgl_po) {
            //             if ($data->tgl_po != "0000-00-00" && !empty($data->tgl_po)) {
            //                 return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
            //             } else {
            //                 return '-';
            //             }
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('noseri', function () {
            //         return '';
            //     })
            //     ->addColumn('log', function ($data) {
            //         $progress = '';
            //         $tes = $data->cjumlahprd + $data->cjumlahpart;
            //         // if($tes > 0){
            //         //     $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
            //         //     if($hitung > 0){
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }else{
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }
            //         // }
            //         $datas = "";
            //         // if (!empty($data->state_nama)) {
            //         //     if ($data->state_nama == "Penjualan") {
            //         //         $datas .= '<span class="red-text badge">'. ucfirst($data->state_nama) . '</span>';
            //         //     } else {
            //         //         if($data->ekat_log == "batal"){
            //         //             $datas .= '<span class="red-text badge">'. ucfirst($data->ekat_log) . '</span>';
            //         //         }
            //         //         else if($data->spa_log == "batal"){
            //         //             $datas .= '<span class="red-text badge">'. ucfirst($data->spa_log) . '</span>';
            //         //         }
            //         //         else if($data->spb_log == "batal"){
            //         //             $datas .= '<span class="red-text badge">'. ucfirst($data->spb_log) . '</span>';
            //         //         }
            //         //         else{
            //         //             $datas .= $progress;
            //         //         }
            //         //     }
            //         // }
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['log',  'nama_customer'])
            //     ->make(true);
        } else if ($parameter == 'no_akn') {
            $si_ekat21 = DB::connection('si_21')->table('spa_on')
                ->select(
                    'spa_on.noaks_on as no_paket',
                    'spa_on.status_on as status',
                    'spa_on.tgl_buat_on as tgl_buat',
                    'spa_on.tgl_edit_on as tgl_edit',
                    'spa_on.instansi_on as instansi',
                    'distributor.pabrik as customer',
                )
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->where('spa_on.noaks_on', 'LIKE', '%' . $value . '%')
                ->groupby('spa_on.noaks_on')
                ->get();

            $si_ekat20 = DB::connection('si_20')->table('spa_on')
                ->select(
                    'spa_on.noaks_on as no_paket',
                    'spa_on.status_on as status',
                    'spa_on.tgl_buat_on as tgl_buat',
                    'spa_on.tgl_edit_on as tgl_edit',
                    'spa_on.instansi_on as instansi',
                    'distributor.pabrik as customer',
                )
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->where('spa_on.noaks_on', 'LIKE', '%' . $value . '%')
                ->groupby('spa_on.noaks_on')
                ->get();

            $spa = Ekatalog::with(['Pesanan.State', 'Customer'])->addSelect(['tgl_kontrak_custom' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog as e')
                    ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                    ->whereColumn('e.id', 'ekatalog.id')
                    ->limit(1);
            }])->where('no_paket', 'LIKE', '%' . $value . '%')
                ->get();
            $data = $si_ekat20->merge($si_ekat21)->merge($spa);

            return response()->json(['data' => $data]);

            // $val = str_replace("-",  "/",  $value);
            // $data = Ekatalog::with(['Pesanan.State', 'Customer'])->addSelect(['tgl_kontrak_custom' => function ($q) {
            //     $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
            //         ->from('ekatalog as e')
            //         ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
            //         ->whereColumn('e.id', 'ekatalog.id')
            //         ->limit(1);
            // }])->where('no_paket', 'LIKE', '%' . $val . '%')
            //     ->get();
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('status', function ($data) {
            //         $status = "";
            //         if ($data->status == "draft") {
            //             $status = '<span class="badge blue-text">Draft</span>';
            //         } else if ($data->status == "sepakat") {
            //             $status = '<span class="green-text badge">Sepakat</span>';
            //         } else if ($data->status == "negosiasi") {
            //             $status =  '<span class="yellow-text badge">Negosiasi</span>';
            //         } else {
            //             $status =  '<span class="red-text badge">Batal</span>';
            //         }
            //         return $status;
            //     })
            //     ->addColumn('so', function ($data) {
            //         if ($data->Pesanan->so) {
            //             return $data->Pesanan->so;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_paket', function ($data) {
            //         if ($data->no_paket) {
            //             return $data->no_paket;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_buat', function ($data) {
            //         if (!empty($data->tgl_buat)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
            //         }
            //     })
            //     ->addColumn('tgl_kontrak', function ($data) {
            //         if ($data->tgl_kontrak_custom != "") {
            //             if ($data->Pesanan->log_id) {
            //                 $tgl_sekarang = Carbon::now();
            //                 $tgl_parameter = $data->tgl_kontrak_custom;
            //                 $hari = $tgl_sekarang->diffInDays($tgl_parameter);
            //                 if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
            //                     if ($hari > 7) {
            //                         return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
            //                         <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
            //                     } else if ($hari > 0 && $hari <= 7) {
            //                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
            //                         <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
            //                     } else {
            //                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
            //                         <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
            //                     }
            //                 } else {
            //                     return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
            //                     <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih dari ' . $hari . ' Hari</small></div>';
            //                 }
            //             } else {
            //                 return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
            //             }
            //         }
            //     })
            //     ->addColumn('customer', function ($data) {
            //         return $data->Customer->nama;
            //     })
            //     ->addColumn('instansi', function ($data) {
            //         $datas = $data->satuan;
            //         $datas .= "<div><small>" . $data->instansi . "</small></div>";
            //         return $datas;
            //     })
            //     ->addColumn('log', function ($data) {
            //         $progress = '';
            //         // $tes = $data->cjumlahprd + $data->cjumlahpart;
            //         // if($tes > 0){
            //         //     $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
            //         //     if($hitung > 0){
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }else{
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }
            //         // }
            //         $datas = "";
            //         // if (!empty($data->Pesanan->State->nama)) {
            //         //     if ($data->Pesanan->State->nama == "Penjualan") {
            //         //         $datas .= '<span class="red-text badge">'. ucfirst($data->state_nama) . '</span>';
            //         //     } else {
            //         //         if($data->status == "batal"){
            //         //             $datas .= '<span class="red-text badge">Batal</span>';
            //         //         }
            //         //         else{
            //         //             $datas .= $progress;
            //         //         }
            //         //     }
            //         // }

            //         if (!empty($data->Pesanan->State->nama)) {
            //             if ($data->Pesanan->State->nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->Pesanan->State->nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->Pesanan->State->nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
            //         }

            //         return $datas;
            //     })
            //     ->rawColumns(['status', 'log', 'instansi',  'tgl_kontrak'])
            //     ->make(true);
        } else if ($parameter == 'customer') {
            $val = str_replace("_",  "/",  $value);
            // $ekatalog = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) use ($value) {
            //     $q->where('satuan', 'LIKE', '%' . $value . '%');
            // })->orwhereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) use ($value) {
            //     $q->where('instansi', 'LIKE', '%' . $value . '%');
            // })->orwhereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Customer', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            // $spa = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            // $spb = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            // $data = $ekatalog->merge($spa)->merge($spb);
            $data =  NoseriBarangJadi::select(
                'noseri_barang_jadi.noseri',
                'pesanan.no_po',
                'pesanan.so as no_so',
                'noseri_detail_pesanan.tgl_uji',
                'logistik.tgl_kirim as tgl_sj',
                'logistik.nosurat as no_sj',
                'produk.nama as p_nama as nama_produk',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
                'm_state.nama as state_nama',
            )
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->where('c_spa.nama', 'LIKE', '%' . $val . '%')
                ->orwhere('c_spb.nama', 'LIKE', '%' . $val . '%')
                ->orwhere('c_ekat.nama', 'LIKE', '%' . $val . '%')
                ->orwhere('ekatalog.instansi', 'LIKE', '%' . $val . '%')
                ->orwhere('ekatalog.satuan', 'LIKE', '%' . $val . '%')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->get();

            return response()->json(['data' => $data]);
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('noseri', function ($data) {
            //         return $data->noseri;
            //     })
            //     ->addColumn('nama_produk', function ($data) {
            //         if ($data->p_nama) {
            //             return $data->p_nama;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_so', function ($data) {
            //         if ($data->no_po) {
            //             return $data->no_po;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('nama_customer', function ($data) {
            //         if ($data->so) {
            //             $name = explode('/', $data->so);
            //             if ($name[1] == 'EKAT') {
            //                 $datas = $data->c_ekat_nama;
            //                 if ($data->satuan) {
            //                     $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //                 }
            //             } else if ($name[1] == 'SPA') {
            //                 $datas = $data->c_spa_nama;
            //             } else if ($name[1] == 'SPB') {
            //                 $datas = $data->c_spb_nama;
            //             }
            //             return $datas;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_uji', function ($data) {
            //         if (isset($data->tgl_uji)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_sj', function ($data) {
            //         if (isset($data->no_sj)) {
            //             return $data->no_sj;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_kirim', function ($data) {
            //         if (isset($data->tgl_sj)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_sj)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('status', function ($data) {
            //         $datas = "";
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['divisi_id', 'status', 'nama_customer'])
            //     ->make(true);
        } else if ($parameter == 'produk') {

            // $data = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.GudangBarangJadi.produk', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            $data =  NoseriBarangJadi::select(
                'noseri_barang_jadi.noseri',
                'pesanan.no_po',
                'pesanan.so',
                'noseri_detail_pesanan.tgl_uji',
                'logistik.tgl_kirim as tgl_sj',
                'logistik.nosurat as no_sj',
                'produk.nama as p_nama',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
                'm_state.nama as state_nama',
            )
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->where('produk.nama', 'LIKE', '%' . $value . '%')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->get();

            return response()->json(['data' => $data]);

            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('noseri', function ($data) {
            //         return $data->noseri;
            //     })
            //     ->addColumn('nama_produk', function ($data) {
            //         return $data->p_nama;
            //     })
            //     ->addColumn('no_so', function ($data) {
            //         if ($data->no_po) {
            //             return $data->no_po;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('nama_customer', function ($data) {
            //         if ($data->so) {
            //             $name = explode('/', $data->so);
            //             if ($name[1] == 'EKAT') {
            //                 $datas = $data->c_ekat_nama;
            //                 if ($data->satuan) {
            //                     $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //                 }
            //             } else if ($name[1] == 'SPA') {
            //                 $datas = $data->c_spa_nama;
            //             } else if ($name[1] == 'SPB') {
            //                 $datas = $data->c_spb_nama;
            //             }
            //             return $datas;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_uji', function ($data) {
            //         if (isset($data->tgl_uji)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_sj', function ($data) {
            //         if (isset($data->no_sj)) {
            //             return $data->no_sj;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_kirim', function ($data) {
            //         if (isset($data->tgl_sj)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_sj)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('status', function ($data) {
            //         $datas = "";
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['divisi_id', 'status', 'nama_customer'])
            //     ->make(true);
        } else if ($parameter == 'no_seri') {
            //Baru
            $si_ekat21 = DB::connection('si_21')->table('seri_on')
                ->select(
                    'seri_on.noseri_on as noseri',
                    'admjual_on.nopo_on as no_po',
                    'qc_on.tglterima_on as tglterima_on',
                    'qc_on.tglserah_on as tglserah_on',
                    'gudang_on.tglsj_on as tgl_sj',
                    'gudang_on.nosj_on as no_sj',
                    'spa_on.satuan_on as satuan',
                    'distributor.pabrik as c_ekat_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_on', 'gudang_on.nolkppgdg_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('qc_on', 'qc_on.nolkppqc_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('spa_on', 'spa_on.nolkpp_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_on.idprod_on')
                ->where('seri_on.noseri_on', $value)
                ->whereNotNull('gudang_on.tglsj_on')
                ->groupby('seri_on.noseri_on');

            $si_ekat20 = DB::connection('si_20')->table('seri_on')
                ->select(
                    'seri_on.noseri_on as noseri',
                    'admjual_on.nopo_on as no_po',
                    'qc_on.tglterima_on as tglterima_on',
                    'qc_on.tglserah_on as tglserah_on',
                    'gudang_on.tglsj_on as tgl_sj',
                    'gudang_on.nosj_on as no_sj',
                    'spa_on.satuan_on as satuan',
                    'distributor.pabrik as c_ekat_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_on', 'gudang_on.nolkppgdg_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('qc_on', 'qc_on.nolkppqc_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('spa_on', 'spa_on.nolkpp_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_on.idprod_on')
                ->where('seri_on.noseri_on', $value)
                ->whereNotNull('gudang_on.tglsj_on')
                ->groupby('seri_on.noseri_on');


            $si_spa21 = DB::connection('si_21')->table('seri_off')
                ->select(
                    'seri_off.noseri_off as noseri',
                    'admjual_off.nopo_off as no_po',
                    'qc_off.tglterima_off as tglterima_off',
                    'qc_off.tglserah_off as tglserah_off',
                    'gudang_off.tglsj_off as tgl_sj',
                    'gudang_off.nosj_off as no_sj',
                    'distributor.pabrik as c_spa_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_off', 'gudang_off.idordergdg_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('qc_off', 'qc_off.idorderqc_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('spa_off', 'spa_off.idorder_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_off.pabrik_off')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_off.idprod_off')
                ->where('seri_off.noseri_off', $value)
                ->whereNotNull('gudang_off.tglsj_off')
                ->groupby('seri_off.noseri_off');

            $si_spa20 = DB::connection('si_20')->table('seri_off')
                ->select(
                    'seri_off.noseri_off as noseri',
                    'admjual_off.nopo_off as no_po',
                    'qc_off.tglterima_off as tglterima_off',
                    'qc_off.tglserah_off as tglserah_off',
                    'gudang_off.tglsj_off as tgl_sj',
                    'gudang_off.nosj_off as no_sj',
                    'distributor.pabrik as c_spa_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_off', 'gudang_off.idordergdg_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('qc_off', 'qc_off.idorderqc_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('spa_off', 'spa_off.idorder_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_off.pabrik_off')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_off.idprod_off')
                ->where('seri_off.noseri_off', $value)
                ->whereNotNull('gudang_off.tglsj_off')
                ->groupby('seri_off.noseri_off');

            $si_spb21 = DB::connection('si_21')->table('seri_spb')
                ->select(
                    'seri_spb.noseri_spb as noseri',
                    'admjual_spb.nopo_spb as no_po',
                    'qc_spb.tglterimaqc_spb as tglterima_spb',
                    'qc_spb.tglserahqc_spb as tglserah_spb',
                    'gudang_spb.tglsjgdg_spb as tgl_sj',
                    'gudang_spb.nosjgdg_spb as no_sj',
                    'spb.pelanggan_spb as c_spb_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_spb', 'gudang_spb.nogdg_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('qc_spb', 'qc_spb.noqc_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('spb', 'spb.nospb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spb.idprod_spb')
                ->where('seri_spb.noseri_spb', $value)
                ->whereNotNull('gudang_spb.tglsjgdg_spb')
                ->groupby('seri_spb.noseri_spb');

            $si_spb20 = DB::connection('si_20')->table('seri_spb')
                ->select(
                    'seri_spb.noseri_spb as noseri',
                    'admjual_spb.nopo_spb as no_po',
                    'qc_spb.tglterimaqc_spb as tglterima_spb',
                    'qc_spb.tglserahqc_spb as tglserah_spb',
                    'gudang_spb.tglsjgdg_spb as tgl_sj',
                    'gudang_spb.nosjgdg_spb as no_sj',
                    'spb.pelanggan_spb as c_spb_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_spb', 'gudang_spb.nogdg_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('qc_spb', 'qc_spb.noqc_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('spb', 'spb.nospb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spb.idprod_spb')
                ->where('seri_spb.noseri_spb',  $value)
                ->whereNotNull('gudang_spb.tglsjgdg_spb')
                ->groupby('seri_spb.noseri_spb');

            $seriERP =  NoseriBarangJadi::where('noseri', $value);
            $Istransaksi = NoseriBarangJadi::join('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->where('noseri', $value)
                ->where('t_gbj_noseri.jenis', 'keluar');



            $spa =  NoseriBarangJadi::select(
                'noseri_barang_jadi.noseri',
                'pesanan.no_po',
                'pesanan.so',
                'noseri_detail_pesanan.tgl_uji',
                'logistik.tgl_kirim as tgl_sj',
                'logistik.nosurat as no_sj',
                'produk.nama as p_nama',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
                'm_state.nama as state_nama',
            )
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 't_gbj.pesanan_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->where('noseri_barang_jadi.noseri', $value)
                // ->whereNotNull('t_gbj.pesanan_id')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->latest('t_gbj_noseri.created_at');



            $noseriretur = NoseriBarangJadi::selectRaw(
                'noseri_barang_jadi.noseri,
                    retur_penjualan.no_retur as no_po,
                    pengiriman.tanggal as tgl_sj,
                    retur_penjualan.tgl_retur as tgl_uji,
                    pengiriman.no_pengiriman as no_sj,
                    produk.nama as p_nama,
                    customer.nama as c_ekat_nama,
                    m_state.nama as state_nama'
            )
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('retur_penjualan', 'retur_penjualan.id', '=', 't_gbj.retur_penjualan_id')
                ->leftjoin('pengiriman_noseri', 'pengiriman_noseri.noseri_barang_jadi_id', '=', 'noseri_barang_jadi.id')
                ->leftjoin('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
                ->leftjoin('pengiriman as pn', 'pn.retur_penjualan_id', '=', 'retur_penjualan.id')
                ->leftJoin('m_state', 'm_state.id', '=', 'retur_penjualan.state_id')
                ->leftJoin('customer', 'customer.id', '=', 'retur_penjualan.customer_id')
                ->where('noseri_barang_jadi.noseri',  $value)
                ->whereNotNull('t_gbj.retur_penjualan_id')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->groupBy('retur_penjualan.id');
            //dd($Istransaksi->count());
            $data = array();
            if ($si_spa21->count() > 0) {
                $data = $si_spa21->get();
            }

            if ($si_spa20->count() > 0) {
                $data = $si_spa20->get();
            }

            if ($si_spb20->count() > 0) {
                $data = $si_spb20->get();
            }

            if ($si_spb21->count() > 0) {
                $data = $si_spb21->get();
            }

            if ($si_ekat20->count() > 0) {
                $data = $si_ekat20->get();
            }

            if ($si_ekat21->count() > 0) {
                $data = $si_ekat21->get();
            }


            if ($seriERP->count() > 0) {

                if ($Istransaksi->count() > 0) {

                    if ($spa->count() > 0) {
                        $datas = $spa->first();

                        $data[] = array(
                            'noseri' => $value,
                            'no_po' => $datas->no_po,
                            'so' => $datas->so,
                            'tgl_uji' => $datas->tgl_uji,
                            'tgl_sj' => $datas->tgl_sj,
                            'no_sj' => $datas->no_sj,
                            'p_nama' => $datas->p_nama,
                            'c_ekat_nama' => $datas->c_ekat_nama,
                            'c_spb_nama' => $datas->c_spb_nama,
                            'satuan' => $datas->satuan,
                            'state_nama' => $datas->state_nama,
                        );
                    }

                    if ($noseriretur->count() > 0) {
                        $data = $noseriretur->get();
                    }
                } else {
                    $data[] = array(
                        'noseri' => $value,
                        'no_po' => '-',
                        'so' => '-',
                        'tgl_uji' => null,
                        'tgl_sj' => null,
                        'no_sj' => '-',
                        'p_nama' => '-',
                        'c_ekat_nama' => '-',
                        'c_spb_nama' => '-',
                        'satuan' => '-',
                        'state_nama' => 'Stok Barang',
                    );
                }
                //  'noseri_barang_jadi.noseri',
                // 'pesanan.no_po',
                // 'pesanan.so',
                // 'noseri_detail_pesanan.tgl_uji',
                // 'logistik.tgl_kirim as tgl_sj',
                // 'logistik.nosurat as no_sj',
                // 'produk.nama as p_nama',
                // 'c_ekat.nama as c_ekat_nama',
                // 'c_spa.nama as c_spa_nama',
                // 'c_spb.nama as c_spb_nama',
                // 'ekatalog.satuan as satuan',
                // 'm_state.nama as state_nama',

                // if($spa->count() > 0 ){
                //     $data = $spa->get();
                // }

                // if($noseriretur->count() > 0 ){
                //     $data = $noseriretur->get();
                // }

            }


            //  $data = $si_spa21->merge($si_spa20)->merge($si_spb20)->merge($si_spb21)->merge($si_ekat20)->merge($si_ekat21)->merge($spa)->merge($noseriretur);

            return response()->json(['data' => $data]);
            // $data =  NoseriBarangJadi::select(
            //     'noseri_barang_jadi.noseri',
            //     'pesanan.no_po',
            //     'pesanan.so',
            //     'noseri_detail_pesanan.tgl_uji',
            //     'logistik.tgl_kirim as tgl_sj',
            //     'logistik.nosurat as no_sj',
            //     'produk.nama as p_nama',
            //     'c_ekat.nama as c_ekat_nama',
            //     'c_spa.nama as c_spa_nama',
            //     'c_spb.nama as c_spb_nama',
            //     'ekatalog.satuan as satuan',
            //     'm_state.nama as state_nama',
            // )

            //     ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
            //     ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            //     ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
            //     ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
            //     ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
            //     ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
            //     ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
            //     ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            //     ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            //     ->leftJoin('pesanan', 'pesanan.id', '=', 't_gbj.pesanan_id')
            //     ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
            //     ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
            //     ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
            //     ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
            //     ->where('noseri_barang_jadi.noseri', 'LIKE', '%' . $value . '%')
            //     ->whereNotNull('t_gbj.pesanan_id')

            //     ->orderBy('noseri_barang_jadi.noseri', 'ASC')
            //     ->get();

            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('noseri', function ($data) {
            //         return $data->noseri;
            //     })
            //     ->addColumn('nama_produk', function ($data) {
            //         if ($data->p_nama) {
            //             return $data->p_nama;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_so', function ($data) {
            //         if ($data->no_po) {
            //             return $data->no_po;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('nama_customer', function ($data) {

            //         if ($data->so) {
            //             $name = explode('/', $data->so);
            //             if ($name[1] == 'EKAT') {
            //                 $datas = $data->c_ekat_nama;
            //                 if ($data->satuan) {
            //                     $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //                 }
            //             } else if ($name[1] == 'SPA') {
            //                 $datas = $data->c_spa_nama;
            //             } else if ($name[1] == 'SPB') {
            //                 $datas = $data->c_spb_nama;
            //             }
            //             return $datas;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_uji', function ($data) {

            //         if (isset($data->tgl_uji)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_sj', function ($data) {
            //         if (isset($data->no_sj)) {
            //             return $data->no_sj;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_kirim', function ($data) {
            //         if (isset($data->tgl_sj)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_sj)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('status', function ($data) {

            //         $datas = "";
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['status',  'nama_customer'])
            //     ->make(true);
        } else if ($parameter == 'no_so') {
            $val = str_replace("_",  "/",  $value);
            $data = Pesanan::select(
                'pesanan.no_po',
                'pesanan.so',
                'pesanan.tgl_po',
                'm_state.nama as state_nama',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
            )
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->where('so', 'LIKE', '%' . $val . '%')
                ->get();

            return response()->json(['data' => $data]);
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('nama_customer', function ($data) {
            //         $name = explode('/', $data->so);
            //         if ($name[1] == 'EKAT') {
            //             $datas = $data->c_ekat_nama;
            //             if ($data->satuan) {
            //                 $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //             }
            //         } else if ($name[1] == 'SPA') {
            //             $datas = $data->c_spa_nama;
            //         } else if ($name[1] == 'SPB') {
            //             $datas = $data->c_spb_nama;
            //         }

            //         return $datas;
            //     })
            //     ->addColumn('so', function ($data) {
            //         if ($data->so) {
            //             return $data->so;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('no_po', function ($data) {
            //         if ($data->no_po) {
            //             return $data->no_po;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('tgl_po', function ($data) {
            //         if ($data->tgl_po) {
            //             if ($data->tgl_po != "0000-00-00" && !empty($data->tgl_po)) {
            //                 return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
            //             } else {
            //                 return '-';
            //             }
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('log', function ($data) {
            //         $progress = '';
            //         $tes = $data->cjumlahprd + $data->cjumlahpart;
            //         $datas = "";
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['log', 'nama_customer'])
            //     ->make(true);
        } else if ($parameter == 'no_sj') {
            $val = str_replace("_",  "/",  $value);
            $erps = array();
            $merge = array();
            $si = array();
            $si_ekat21 = array();
            $si_ekat20 = array();
            $si_spa21 = array();
            $si_spa20 = array();
            $si_spb21 = array();
            $si_spb20 = array();

            $si_ekat21 = DB::connection('si_21')->table('gudang_on')
                ->select(
                    'gudang_on.tglsj_on as tgl_kirim',
                    'gudang_on.nosj_on as no_sj',
                    'admjual_on.nopo_on as po',
                    'distributor.pabrik as customer',
                    'ekspedisi2_on.noresi_on as resi',
                    'ekspedisi2_on.keteks2_on as ket',
                )
                ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'gudang_on.nolkppgdg_on')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'gudang_on.pabrikgdg_on')
                ->leftjoin('ekspedisi2_on', 'ekspedisi2_on.nolkppeksfk_on', '=', 'gudang_on.nolkppgdg_on')
                ->where('gudang_on.nosj_on', 'LIKE', '%' . $val . '%')
                ->groupby('gudang_on.nosj_on')
                ->get();

            $si_ekat20 = DB::connection('si_20')->table('gudang_on')
                ->select(
                    'gudang_on.tglsj_on as tgl_kirim',
                    'gudang_on.nosj_on as no_sj',
                    'admjual_on.nopo_on as po',
                    'distributor.pabrik as customer',
                    'ekspedisi2_on.noresi_on as resi',
                    'ekspedisi2_on.keteks2_on as ket',
                )
                ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'gudang_on.nolkppgdg_on')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'gudang_on.pabrikgdg_on')
                ->leftjoin('ekspedisi2_on', 'ekspedisi2_on.nolkppeksfk_on', '=', 'gudang_on.nolkppgdg_on')
                ->where('gudang_on.nosj_on', 'LIKE', '%' . $val . '%')
                ->groupby('gudang_on.nosj_on')
                ->get();


            $si_spa21 = DB::connection('si_21')->table('gudang_off')
                ->select(
                    'gudang_off.tglsj_off as tgl_kirim',
                    'gudang_off.nosj_off as no_sj',
                    'admjual_off.nopo_off as po',
                    'distributor.pabrik as customer',
                    'ekspedisi2_off.noresi_off as resi',
                    'ekspedisi2_off.keteks2_off as ket',
                )
                ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'gudang_off.idordergdg_off')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'gudang_off.pabrikgdg_off')
                ->leftjoin('ekspedisi2_off', 'ekspedisi2_off.idordereks_fk', '=', 'gudang_off.idordergdg_off')
                ->where('gudang_off.nosj_off', 'LIKE', '%' . $val . '%')
                ->groupby('gudang_off.nosj_off')
                ->get();

            $si_spa20 = DB::connection('si_20')->table('gudang_off')
                ->select(
                    'gudang_off.tglsj_off as tgl_kirim',
                    'gudang_off.nosj_off as no_sj',
                    'admjual_off.nopo_off as po',
                    'distributor.pabrik as customer',
                    'ekspedisi2_off.noresi_off as resi',
                    'ekspedisi2_off.keteks2_off as ket',
                )
                ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'gudang_off.idordergdg_off')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'gudang_off.pabrikgdg_off')
                ->leftjoin('ekspedisi2_off', 'ekspedisi2_off.idordereks_fk', '=', 'gudang_off.idordergdg_off')
                ->where('gudang_off.nosj_off', 'LIKE', '%' . $val . '%')
                ->groupby('gudang_off.nosj_off')
                ->get();



            $si_spb21 = DB::connection('si_21')->table('gudang_spb')
                ->select(
                    'gudang_spb.tglsjgdg_spb as tgl_kirim',
                    'gudang_spb.nosjgdg_spb as no_sj',
                    'admjual_spb.nopo_spb as po',
                    'spb.pelanggan_spb as customer',
                    'ekspedisi2_spb.noresi_spb as resi',
                    'ekspedisi2_spb.keteks2_spb as ket',
                )
                ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'gudang_spb.nogdg_spb')
                ->leftjoin('spb', 'spb.nospb', '=', 'gudang_spb.nogdg_spb')
                ->leftjoin('ekspedisi2_spb', 'ekspedisi2_spb.noeksfk_spb', '=', 'gudang_spb.nogdg_spb')
                ->where('gudang_spb.nosjgdg_spb', 'LIKE', '%' . $val . '%')
                ->groupby('gudang_spb.nosjgdg_spb')
                ->get();

            $si_spb20 = DB::connection('si_20')->table('gudang_spb')
                ->select(
                    'gudang_spb.tglsjgdg_spb as tgl_kirim',
                    'gudang_spb.nosjgdg_spb as no_sj',
                    'admjual_spb.nopo_spb as po',
                    'spb.pelanggan_spb as customer',
                    'ekspedisi2_spb.noresi_spb as resi',
                    'ekspedisi2_spb.keteks2_spb as ket',
                )
                ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'gudang_spb.nogdg_spb')
                ->leftjoin('spb', 'spb.nospb', '=', 'gudang_spb.nogdg_spb')
                ->leftjoin('ekspedisi2_spb', 'ekspedisi2_spb.noeksfk_spb', '=', 'gudang_spb.nogdg_spb')
                ->where('gudang_spb.nosjgdg_spb', 'LIKE', '%' . $val . '%')
                ->groupby('gudang_spb.nosjgdg_spb')
                ->get();

            $erp = Logistik::select(
                'logistik.id',
                'logistik.nosurat as no_sj',
                'logistik.tgl_kirim as tgl_sj',
                'logistik.noresi as resi',
                'logistik.ket',

                'c_ekat.nama as c_ekat_nama',

                'c_spa_prd.nama as c_spa_prd_nama',
                'c_spa_prt.nama as c_spa_prt_nama',

                'c_spb_prd.nama as c_spb_prd_nama',
                'c_spb_prt.nama as c_spb_prt_nama',

                'p_prd.no_po as po_prd',
                'p_prt.no_po as po_prt',

            )
                ->leftJoin('detail_logistik_part',  'detail_logistik_part.logistik_id',  '=',  'logistik.id')
                ->leftJoin('detail_pesanan_part',  'detail_pesanan_part.id',  '=',  'detail_logistik_part.detail_pesanan_part_id')
                ->leftJoin('pesanan as p_prt',  'p_prt.id',  '=',  'detail_pesanan_part.pesanan_id')

                ->leftJoin('detail_logistik',  'detail_logistik.logistik_id',  '=',  'logistik.id')
                ->leftJoin('detail_pesanan_produk',  'detail_pesanan_produk.id',  '=',  'detail_logistik.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan',  'detail_pesanan.id',  '=',  'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan as p_prd',  'p_prd.id',  '=',  'detail_pesanan.pesanan_id')


                ->leftJoin('ekatalog',  'ekatalog.pesanan_id',  '=',  'p_prd.id')
                ->leftJoin('customer as c_ekat',  'c_ekat.id',  '=',  'ekatalog.customer_id')

                ->leftJoin('spa as spa_prd',  'spa_prd.pesanan_id',  '=',  'p_prd.id')
                ->leftJoin('customer as c_spa_prd',  'c_spa_prd.id',  '=',  'spa_prd.customer_id')
                ->leftJoin('spa as spa_prt',  'spa_prt.pesanan_id',  '=',  'p_prt.id')
                ->leftJoin('customer as c_spa_prt',  'c_spa_prt.id',  '=',  'spa_prt.customer_id')

                ->leftJoin('spb as spb_prd',  'spb_prd.pesanan_id',  '=',  'p_prd.id')
                ->leftJoin('customer as c_spb_prd',  'c_spb_prd.id',  '=',  'spb_prd.customer_id')
                ->leftJoin('spb as spb_prt',  'spb_prt.pesanan_id',  '=',  'p_prt.id')
                ->leftJoin('customer as c_spb_prt',  'c_spb_prt.id',  '=',  'spb_prt.customer_id')


                ->where('logistik.nosurat',  'LIKE', '%' . $val . '%')
                ->orderBy('logistik.id', 'DESC')
                ->groupBy('logistik.id');

            if ($erp->count() > 0) {
                foreach ($erp->get() as $d) {
                    if ($d->c_ekat_nama != null) {
                        $c = $d->c_ekat_nama;
                    } else if ($d->c_spa_prd_nama != null) {
                        $c = $d->c_spa_prd_nama;
                    } else if ($d->c_spa_prt_nama != null) {
                        $c = $d->c_spa_prt_nama;
                    } else if ($d->c_spb_prd_nama != null) {
                        $c = $d->c_spb_prd_nama;
                    } else if ($d->c_spb_prt_nama != null) {
                        $c = $d->c_spb_prt_nama;
                    }

                    if ($d->po_prd != null) {
                        $po = $d->po_prd;
                    } else {
                        $po = $d->po_prt;
                    }


                    $erps[] = array(
                        'tgl_kirim' => $d->tgl_sj,
                        'no_sj' => $d->no_sj,
                        'po' => $po,
                        'customer' => $c,
                        'resi' => $d->resi,
                        'ket' => $d->ket
                    );
                }
            }

            $si =  $si_ekat21->merge($si_ekat20)->merge($si_spa21)->merge($si_spa20)->merge($si_spb21)->merge($si_spb20)->toArray();
            $merge = array_merge($erps, $si);



            return response()->json(['data' => $merge]);
        } else if ($parameter == 'no_seri_gbj') {
            $data = NoseriBarangJadi::where([
                ['noseri', 'LIKE', '%' . $value . '%'],
                // ['is_aktif', '=', '1'],
                // ['is_ready', '=', '0'],
                // ['is_change', '=', '1'],
                // ['is_delete', '=', '0']
            ])->get();
            $arr = array();
            foreach ($data as $key => $i) {

                if ($i->is_ready != 0) {
                    if ($i->NoseriTGbj->last()->detail->header->pesanan_id != NULL) {
                        $detail = $i->NoseriTGbj->last()->detail->header->pesanan->no_po;
                    } else {
                        $detail = $i->NoseriTGbj->last()->detail->header->deskripsi;
                    }
                } else {
                    $detail = 'Tersedia';
                }

                $arr[$key] = array(
                    'noseri' => $i->noseri,
                    'nama_produk' => $i->gudang->produk->nama,
                    'variasi' => $i->gudang->nama != NULL ? $i->gudang->nama : '-',
                    'state_nama' =>  $i->is_ready != 0 ? 'Terpakai' : 'Tersedia',
                    'keterangan' => $i->is_ready != 0 ? $detail : '-'

                );
            }
            return response()->json(['data' => $arr]);
        }
    }
    public function get_data_detail_spa($value)
    {
        $data = Spa::with(['Pesanan.State',  'Customer.Provinsi'])->addSelect([
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
            },
            'cjumlahprd' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
            },
            'ckirimpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
            }

        ])->where('id', $value)->first();
        $cek_1 = DetailPesanan::where('pesanan_id', $data->pesanan_id)->get()->count();
        $cek_2 = DetailPesananPart::where('pesanan_id', $data->pesanan_id)->get()->count();

        if ($cek_1 <= 0) {
            $param = 'part';
        } else if ($cek_2 <= 0) {
            $param = 'produk';
        } else {
            $param = 'semua';
        }

        $status = "";
        $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
        if ($data->log == "batal") {
            $status = '<span class="badge red-text">Batal</span>';
        } else {
            if ($hitung > 0) {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            } else {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            }
        }

        return view('page.penjualan.penjualan.detail_spa', ['data' => $data, 'param' => $param, 'status' => $status]);
    }

    public function get_data_detail_ekatalog($value)
    {
        $data  = Ekatalog::with(['Pesanan.State',  'Customer', 'Provinsi'])->addSelect([

            'tgl_kontrak_custom' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog as e')
                    ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                    ->whereColumn('e.id', 'ekatalog.id')
                    ->limit(1);
            },
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
            },
            'cjumlahprd' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
            },
            'ckirimpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
            }

        ])->where('id', $value)->first();

        $status = "";


        if ($data->Pesanan->log_id == "7") {
            $status = '<div><span class="badge red-text">' . $data->Pesanan->State->nama . '</span></div>';
        } else if ($data->log == "batal") {
            $status = '<div><span class="badge red-text">Batal</span></div>';
        } else {
            $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
            if ($hitung > 0) {
                $status = '<div class="align-center"><div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted align-center">Selesai</small></div>';
            } else {
                $status = '<div class="align-center"><div class="progress">
                            <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted align-center">Selesai</small></div>';
            }
        }
        $tgl_kontrak = "";
        if ($data->tgl_kontrak_custom != "") {
            if ($data->Pesanan->log_id != '10') {
                $tgl_sekarang = Carbon::now();
                $tgl_parameter = $data->tgl_kontrak_custom;
                $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                    if ($hari > 7) {
                        $tgl_kontrak =  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                    } else if ($hari > 0 && $hari <= 7) {
                        $tgl_kontrak =  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                    } else {
                        $tgl_kontrak =  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                    }
                } else {
                    $tgl_kontrak =  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                            <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih dari ' . $hari . ' Hari</small></div>';
                }
            } else {
                $tgl_kontrak = Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
            }
        }
        return view('page.penjualan.penjualan.detail_ekatalog', ['data' => $data, 'status' => $status, 'tgl_kontrak' => $tgl_kontrak]);
    }

    public function get_data_detail_ekatalog_ppic($id)
    { {
            $data = array();
            $pesanan = Pesanan::find($id);
            if ($pesanan) {
                $data['so'] = $pesanan->so;
                $data['no_po'] = $pesanan->no_po;
                $data['tgl_po'] = $pesanan->tgl_po;
                if ($pesanan->Ekatalog) {
                    $data['no_paket'] = $pesanan->Ekatalog->no_paket;
                    $data['customer'] = $pesanan->Ekatalog->Customer->nama;
                    $data['instansi'] = $pesanan->Ekatalog->instansi;
                    $data['instansi_alamat'] = $pesanan->Ekatalog->alamat;
                    if ($pesanan->Ekatalog->provinsi != '') {
                        $data['instansi_provinsi'] = $pesanan->Ekatalog->Provinsi->nama;
                    }
                    $data['no_urut'] = $pesanan->Ekatalog->no_urut;
                    $data['tgl_kontrak'] = $pesanan->Ekatalog->tgl_kontrak;
                    $data['tgl_edit'] = $pesanan->Ekatalog->tgl_edit;
                    $data['tgl_buat'] = $pesanan->Ekatalog->tgl_buat;
                    $data['deskripsi'] = $pesanan->Ekatalog->deskripsi;
                }


                if ($pesanan->Spb) {
                    $data['customer'] = $pesanan->Spb->Customer->nama;
                    $data['customer_alamat'] = $pesanan->Spb->Customer->alamat;
                    $data['customer_provinsi'] = $pesanan->Spb->Customer->Provinsi->nama;
                    $data['deskripsi'] = $pesanan->Spb->ket;
                }

                if ($pesanan->Spa) {
                    $data['customer'] = $pesanan->Spa->Customer->nama;
                    $data['customer_alamat'] = $pesanan->Spa->Customer->alamat;
                    $data['customer_provinsi'] = $pesanan->Spa->Customer->Provinsi->nama;
                    $data['deskripsi'] = $pesanan->Spa->ket;
                }


                if ($pesanan->DetailPesananPart) {
                    foreach ($pesanan->DetailPesananPart  as $key_detail => $detailpart) {
                        if (strpos($detailpart->Sparepart->kode, 'JASA') === false) {
                            $jenis = 'part';
                        } else {
                            $jenis = 'jasa';
                        }
                        $data['detail_pesanan'][$key_detail] = array(
                            'id' => $detailpart->id,
                            'nama_paket' => $detailpart->Sparepart->nama,
                            'jumlah' => $detailpart->jumlah,
                            'harga' => $detailpart->harga,
                            'jenis' => $jenis,

                        );
                    }
                }



                if ($pesanan->DetailPesanan) {
                    foreach ($pesanan->DetailPesanan  as $key_detail => $detail) {
                        $data['detail_pesanan'][$key_detail] = array(
                            'id' => $detail->id,
                            'nama_paket' => $detail->PenjualanProduk->nama,
                            'jumlah' => $detail->jumlah,
                            'harga' => $detail->harga,
                            'ongkir' => $detail->ongkir,
                            'jenis' => 'paket',
                            'detail_produk' => array()
                        );

                        foreach ($detail->DetailPesananProduk  as $key_detailp => $detailp) {

                            if ($detailp->GudangBarangJadi->nama != '') {
                                $nama_produk = $detailp->GudangBarangJadi->nama;
                            } else {
                                $nama_produk = $detailp->GudangBarangJadi->Produk->nama;
                            }

                            $data['detail_pesanan'][$key_detail]['detail_produk'][$key_detailp] = array(
                                'id' => $detailp->id,
                                'nama_produk' => $nama_produk,
                                'jenis' => 'variasi',
                                'jumlah' => $detailp->getJumlahPesanan(),

                            );
                        }
                    }
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil',
                    'data'    =>  $data
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data Tidak ditemukan',
                ], 200);
            }
        }
    }

    public function get_data_detail_spb($value)
    {
        $data  = Spb::with(['Pesanan.State',  'Customer.Provinsi'])->addSelect([
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
            },
            'cjumlahprd' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
            },
            'ckirimpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
            }

        ])->where('id', $value)->first();

        $status = "";
        $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
        if ($data->Pesanan->log_id == "7") {
            $status = '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
        } else if ($data->log == "batal") {
            $status = '<span class="badge red-text">Batal</span>';
        } else {
            if ($hitung > 0) {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            } else {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            }
        }

        return view('page.penjualan.penjualan.detail_spb', ['data' => $data, 'status' => $status]);
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
        $produk = DetailPesananProduk::whereHas('DetailPesanan.Pesanan.Ekatalog', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
        $produk_dsb = DetailPesananProdukDsb::whereHas('DetailPesananDsb.Pesanan.Ekatalog', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
        $data = $produk->merge($produk_dsb);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('paket_produk', function ($data) {
                if ($data->DetailPesanan) {
                    return $data->DetailPesanan->PenjualanProduk->nama . ' (' . $data->DetailPesanan->jumlah . ' unit)';
                } else {
                    return $data->DetailPesananDsb->PenjualanProduk->nama . ' (' . $data->DetailPesananDsb->jumlah . ' unit)' . ' <span class="badge info-text">Stok
                    distributor</span>';
                }
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
        $tahun = Carbon::now()->format('Y');
        $data  = Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po')->whereNotIn('log_id', ['7', '10', '20']);
        })->addSelect(['tgl_kontrak_custom' => function ($q) {
            $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                ->from('ekatalog as e')
                ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                ->whereColumn('e.id', 'ekatalog.id')
                ->limit(1);
        }, 'cjumlahprd' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
        }, 'clogprd' => function ($q) {
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id')
                ->limit(1);
        }])
            ->havingRaw('clogprd > 0')
            ->with(['Pesanan', 'Customer'])
            ->orderBy('tgl_buat', 'DESC')
            ->whereYear('ekatalog.tgl_buat',  $tahun)
            ->limit(20)
            ->get();
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
                // return $data->clogprd + $data->clogpart . '&&' . $data->cjumlahprd + $data->cjumlahpart;
                $hitung = floor(((($data->clogprd + $data->clogpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

                $datas = "";
                if (!empty($data->Pesanan->log_id)) {
                    $hitung = floor(((($data->clogprd + $data->clogpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

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
            ->addColumn('batas_kontrak', function ($data) {
                if ($data->tgl_kontrak_custom != "") {
                    if ($data->Pesanan->log_id != "10") {
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
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('button', function ($data) {
                return  '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
            })
            ->rawColumns(['batas_kontrak', 'button', 'status'])
            ->make(true);
    }
    public function get_data_ekatalog($value, $tahun)
    {
        $divisi_id = Auth::user()->divisi_id;

        if ($value == 'semua') {
            $data  = Ekatalog::with(['Pesanan.State',  'Customer', 'Provinsi'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                },
                'cseri' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlah' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahdsb' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan_dsb.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan_dsb')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan_dsb.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan_dsb.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                }
            ])->whereYear('tgl_buat',  $tahun)->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get();
        } else {
            $x = explode(',', $value);
            $data  = Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'ekatalog.pesanan_id');
                },
                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                },
                'cseri' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlah' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahdsb' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan_dsb.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan_dsb')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan_dsb.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan_dsb.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                }
            ])->whereYear('tgl_buat', $tahun)->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
            // ])->orderBy('created_at', 'DESC')->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
        }

        function persentase_and_status($data)
        {
            $datas = "";
            if ($data->Pesanan->log_id == '7') {
                $datas .= 'Penjualan';
            } else {
                if ($data->status == "batal") {
                    $datas .= 'Batal';
                } else {
                    $hitung = floor((($data->cseri / ($data->cjumlah + $data->cjumlahdsb)) * 100));
                    if ($data->cjumlah == 0 &&  $data->cjumlahdsb > 0) {
                        $datas .= 'Stok Distributor';
                    } else {
                        if ($hitung > 0) {
                            $datas = $hitung;
                        } else {
                            $datas = $hitung;
                        }
                    }
                }
            }
            return $datas;
        }

        $data = $data->map(function ($item) {
            $item->persentase = persentase_and_status($item);
            $item->is_batal = $this->cekBatal($item);
            $item->is_retur = $this->cekRetur($item);
            $item->is_editDo = $this->cekEditDoEkat($item);
            return $item;
        });


        return response()->json($data);
    }
    public function get_data_spa($value, $tahun)
    {
        // $divisi_id = Auth::user()->divisi_id;
        $x = explode(',', $value);
        $data = "";
        if ($value == 'semua') {
            $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cterkirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik_part.logistik_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spa.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spa.pesanan_id');
                },
                'c_tf' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },

            ])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        } else {
            $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cterkirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik_part.logistik_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spa.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spa.pesanan_id');
                },
                'c_tf' => function ($q) {
                    $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                        ->whereColumn('t_gbj.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                }

            ])->whereHas('pesanan', function ($q) use ($x) {
                $q->whereIN('log_id', $x);
            })->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        }




        $data = $data->map(function ($item) {
            $item->persentase = $this->persentase_and_status($item);
            $item->provinsi = $this->getProvinsi($item);
            $item->is_batal = $this->cekBatalNonEkat($item);
            $item->is_retur = $this->cekRetur($item);
            $item->is_edit = $this->cekEdit($item);
            $item->is_editDo = $this->cekEditDo($item);
            return $item;
        });

        return response()->json($data);
    }

    public function persentase_and_status($data)
    {
        $datas = "";
        $tes = $data->cjumlahprd + $data->cjumlahpart;
        if ($tes > 0) {
            $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
            if ($data->log == "batal") {
                $datas = 'Batal';
            } else {
                if ($hitung > 0) {
                    $datas = $hitung;
                } else {
                    $datas = $hitung;
                }
            }
        }
        return $datas;
    }

    public function getProvinsi($item)
    {
        return $item->Customer->Provinsi->nama;
    }

    public function cekBatal($item)
    {
        if ($item->cterkirim == 0  && $item->c_retur == 0  && $item->status == 'sepakat') {
            return true;
        } else {
            return false;
        }
        // if ($item->cterkirim == 0 && $item->c_batal == 0 && $item->Pesanan->log_id != 20 && $item->c_retur == 0) {
        //     return true;
        // } else {
        //     return false;
        // }
    }
    public function cekEditDoEkat($item)
    {
        if ($item->status == 'sepakat') {
            // if ($item->cterkirim == 0 && $item->status == 'sepakat') {
            return true;
        } else {
            return false;
        }
    }
    public function cekEditDo($item)
    {
        // if ($item->cterkirim == 0 || $item->terkirimpart) {
        return true;
    }
    public function cekEdit($item)
    {
        if ((($item->cjumlahprd > 0 && $item->c_tf == 0) || (($item->cujipart + $item->cujijasa) == 0 && $item->cjumlahpart > 0) && $item->Pesanan->log_id != 20) && $item->c_batal == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function cekBatalNonEkat($item)
    {
        // if ((($item->cterkirim == 0 && $item->c_tf > 0) || ($item->cterkirimpart == 0 && $item->cjumlahpart > 0)) && $item->Pesanan->log_id != 20 && $item->c_retur == 0) {
        if ((($item->cterkirim == 0 && $item->cjumlahprd > 0) || ($item->cterkirimpart == 0 && $item->cjumlahpart > 0)) && $item->Pesanan->log_id != 20 && $item->c_retur == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function cekRetur($item)
    {
        if ($item->cterkirim > 0 && $item->c_batal == 0 && $item->Pesanan->log_id != 20) {
            // if ($item->cterkirim > 0 && $item->c_batal == 0 && $item->Pesanan->log_id != 20 && $item->c_retur == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_data_spb($value, $tahun)
    {
        $divisi_id = Auth::user()->divisi_id;
        $x = explode(',', $value);
        $data = "";
        if ($value == 'semua') {
            $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cterkirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik_part.logistik_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spb.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
            ])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        } else {
            $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([
                'cterkirim' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cterkirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik_part.logistik_id')
                        ->where('logistik.status_id', 10)
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'c_batal' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po.id),0)')
                        ->from('riwayat_batal_po')
                        ->whereColumn('riwayat_batal_po.pesanan_id', 'spb.pesanan_id');
                },
                'c_retur' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po.id),0)')
                        ->from('riwayat_retur_po')
                        ->whereColumn('riwayat_retur_po.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
            ])->whereHas('pesanan', function ($q) use ($x) {
                $q->whereIN('log_id', $x);
            })->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        }



        $data = $data->map(function ($item) {
            $item->persentase = $this->persentase_and_status($item);
            $item->provinsi = $this->getProvinsi($item);
            $item->is_batal = $this->cekBatalNonEkat($item);
            $item->is_retur = $this->cekRetur($item);
            $item->is_edit = $this->cekEdit($item);
            $item->is_editDo = $this->cekEditDo($item);
            return $item;
        });

        return response()->json($data);
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

    public function create_penjualan(Request $request)
    {
        DB::beginTransaction();
        try {
            $tahunSekarang = Carbon::now()->format('Y');
            $periode = AktifPeriode::first()->tahun;
            if ($tahunSekarang !=  $periode) {
                $month = mt_rand(1, 12);
                $day = mt_rand(1, Carbon::createFromDate($periode, $month)->daysInMonth);
                $randomDate = Carbon::createFromDate($periode, $month, $day)->toDateTimeString();
            } else {
                $randomDate =  Carbon::now()->toDateTimeString();
            }
            // if (isset($request->produk)) {
            //     foreach ($request->produk as $produk) {
            //         if ($produk['stok_distributor'] == 'nondsb') {
            //             $cek_pp[] = $produk['id_produk'];
            //         }
            //     }
            //     if (count($cek_pp) != count(array_unique($cek_pp))) {
            //         return response()->json([
            //             'message' => 'Duplikasi Produk',
            //         ], 500);
            //     }
            // }

            $jnis = '';

            switch ($request->jenis) {
                case "ekatalog":
                    $jnis = 'EKAT';
                    break;
                case "spa":
                    $jnis = 'SPA';
                    break;
                case "spb":
                    $jnis = 'SPB';
                    break;
                default:
                    $jnis;
            }

            $pesanan =    Pesanan::create([
                'so' =>  $request->no_po != '' ? $this->createSObyPeriod($jnis, $periode) : NULL,
                'no_po' => $request->no_po,
                'tgl_po' => $request->tgl_po,
                'no_do' => $request->nomor_do ??= null,
                'tgl_do' => $request->tgl_do ??= null,
                'ket' =>  $request->ket_do,
                'log_id' => 7,
                'tujuan_kirim' => $request->nama_perusahaan,
                'alamat_kirim' => $request->alamat_perusahaan,
                'kemasan' => $request->kemasan,
                'ekspedisi_id' => $request->ekspedisi,
                'ket_kirim' => $request->keterangan,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);


            if (isset($request->sparepart)) {
                foreach ($request->sparepart as $sparepart) {

                    $dspb = DetailPesananPart::create([
                        'pesanan_id' =>  $pesanan->id,
                        'm_sparepart_id' => $sparepart['sparepart_id'],
                        'jumlah' => $sparepart['jumlah'],
                        'harga' => $sparepart['harga'],
                        'ppn' => $sparepart['pajak'] == 'true' ? 1 : 0,
                        'ongkir' => 0,
                    ]);
                }
            }

            if (isset($request->jasa)) {
                foreach ($request->jasa as $jasa) {
                    $dppt = DetailPesananPart::create([
                        'pesanan_id' =>  $pesanan->id,
                        'm_sparepart_id' => $jasa['jasa_id'],
                        'jumlah' => 1,
                        'harga' => $jasa['harga'],
                        'ppn' => $jasa['pajak'] == 'true' ? 1 : 0,
                        'ongkir' => 0,
                    ]);

                    OutgoingPesananPart::create([
                        'detail_pesanan_part_id' => $dppt->id,
                        'tanggal_uji' => $request->tgl_po,
                        'jumlah_ok' => 1,
                        'jumlah_nok' => 0
                    ]);
                }
            }
            if (isset($request->produk)) {

                foreach ($request->produk as $produk) {

                    if ($produk['stok_distributor'] == 'nondsb') {
                        // $detail_pesanan_ref =  DetailPesananRef::create([
                        //     'pesanan_id' => $pesanan->id,
                        //     'penjualan_produk_id' => $produk['id_produk'],
                        //     'jumlah' => $produk['jumlah'],
                        //     'harga' => $produk['harga'],
                        //     'ongkir' => $produk['ongkir'],
                        //     'ppn' => $produk['pajak'] == 'true' ? 1 : 0,
                        //     'kalibrasi' => $produk['kalibrasi'] == 'true' ? 1 : 0,
                        //     'keterangan' => $produk['catatan']
                        // ]);

                        $detail_pesanan = DetailPesanan::create([
                            'pesanan_id' => $pesanan->id,
                            'penjualan_produk_id' => $produk['id_produk'],
                            'jumlah' => $produk['jumlah'],
                            'harga' => $produk['harga'],
                            'ongkir' => $produk['ongkir'],
                            'ppn' => $produk['pajak'] == 'true' ? 1 : 0,
                            'kalibrasi' => $produk['kalibrasi'] == 'true' ? 1 : 0,
                            // 'keterangan' => $produk['catatan']
                        ]);
                        foreach ($produk['variasi'] as $variasi) {
                            DetailPesananProduk::create([
                                'detail_pesanan_id' => $detail_pesanan['id'],
                                'gudang_barang_jadi_id' => $variasi['variasiSelected']
                            ]);
                            // DetailPesananProdukRef::create([
                            //     'detail_pesanan_ref_id' => $detail_pesanan_ref['id'],
                            //     'gudang_barang_jadi_id' => $variasi['id'],
                            //     'jumlah' => $produk['jumlah']
                            // ]);
                        }
                    } else {
                        $dsb = DetailPesananDsb::create([
                            'pesanan_id' =>  $pesanan->id,
                            'penjualan_produk_id' => $produk['id_produk'],
                            'jumlah' => $produk['jumlah'],
                            'ppn' => $produk['pajak'] == 'true' ? 1 : 0,
                            'harga' => $produk['harga'],
                            'ongkir' => $produk['ongkir'],
                        ]);

                        foreach ($produk['variasi'] as $variasi) {
                            DetailPesananProdukDsb::create([
                                'detail_pesanan_dsb_id' => $dsb['id'],
                                'gudang_barang_jadi_id' => $variasi['variasiSelected']
                            ]);
                        }
                        if (isset($produk['noseridsb']) > 0) {
                            foreach ($produk['noseridsb'] as $noseri_dsb) {
                                NoseriDsb::create([
                                    'detail_pesanan_dsb' => $dsb['id'],
                                    'noseri' => $noseri_dsb
                                ]);
                            }
                        }
                    }
                }
            }

            if ($request->jenis == 'ekatalog') {
                Ekatalog::create([
                    'customer_id' => $request->customer_id != '' ?  $request->customer_id : 484,
                    'provinsi_id' => $request->provinsi == 'NULL' ? NULL : $request->provinsi,
                    'pesanan_id' => $pesanan->id,
                    'no_paket' => $request->no_paket != '' && $request->is_no_paket_disabled == true ? $request->no_paket_awal . $request->no_paket : NULL,
                    'no_urut' => $request->no_urut,
                    'deskripsi' => $request->deskripsi,
                    'instansi' => $request->instansi,
                    'alamat' => $request->alamat_instansi,
                    'satuan' => $request->satuan_kerja,
                    'status' => $request->status,
                    'tgl_kontrak' => $request->tgl_delivery,
                    'tgl_buat' => $request->tgl_buat,
                    'tgl_edit' => $request->tgl_edit,
                    'ket' => $request->keterangan,
                    'log' => 'penjualan',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }

            if ($request->jenis == 'spa') {
                Spa::create([
                    'customer_id' =>  $request->customer_id != '' ?  $request->customer_id : 484,
                    'pesanan_id' => $pesanan->id,
                    'ket' => $request->ket_do,
                    'log' => 'po',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }

            if ($request->jenis == 'spb') {
                Spb::create([
                    'customer_id' =>  $request->customer_id != '' ?  $request->customer_id : 484,
                    'pesanan_id' => $pesanan->id,
                    'ket' => $request->ket_do,
                    'log' => 'po',
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
            }

            DB::commit();
            return response()->json([
                'message' => 'ok',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Kesalahan' . $th->getMessage(),
            ], 500);
        }
    }
    public function create_penjualan_old(Request $request)
    {
        $tahunSekarang = Carbon::now()->format('Y');
        $periode = AktifPeriode::first()->tahun;

        if ($tahunSekarang !=  $periode) {
            $month = mt_rand(1, 12); // Random month between 1 and 12
            $day = mt_rand(1, Carbon::createFromDate($periode, $month)->daysInMonth); // Random day within the chosen month
            // Create Carbon instance with the random date
            $randomDate = Carbon::createFromDate($periode, $month, $day)->toDateTimeString();
        } else {
            $randomDate =  Carbon::now()->toDateTimeString();
        }

        if ($request->jenis_penjualan == 'ekatalog') {
            if ($request->status == 'sepakat' && ($request->namadistributor == 'belum' || $request->provinsi == "NULL")) {
                return response()->json([
                    'message' => 'Cek Form Kembali',
                ], 500);
            }
            // if ($request->no_po_ekat != NULL && ( $request->perusahaan_pengiriman_ekat == NULL || $request->alamat_pengiriman_ekat == NULL ||  $request->kemasan == NULL) ) {
            //         return response()->json([
            //             'message' => 'Cek Form Kembali',
            //         ], 500);
            // }
            //dd($request);
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
            $so = NULL;
            $no_po = NULL;
            $tgl_po = NULL;
            $no_do = NULL;
            $tgl_do = NULL;
            $ket_po = NULL;
            $log_id = "7";

            if ($request->no_po_ekat != "") {
                $so = $this->createSObyPeriod('EKAT', $periode);
                $no_po = $request->no_po_ekat;
                $tgl_po = $request->tanggal_po_ekat;
                $no_do = $request->no_do_ekat;
                $tgl_do = $request->tanggal_do_ekat;
                $ket_po = $request->keterangan_ekat;
                if ($request->status == 'sepakat') {
                    $log_id = "9";
                }
            }

            if ($request->status == 'batal') {
                $log_id = "20";
            }

            $pesanan = Pesanan::create([
                'so' => $so,
                'no_po' => $no_po,
                'tgl_po' => $tgl_po,
                'no_do' => $no_do,
                'tgl_do' => $tgl_do,
                'ket' =>  $ket_po,
                'log_id' => $log_id,
                'tujuan_kirim' => $request->perusahaan_pengiriman_ekat,
                'alamat_kirim' => $request->alamat_pengiriman_ekat,
                'kemasan' => $request->kemasan,
                'ekspedisi_id' => $request->ekspedisi,
                'ket_kirim' => $request->keterangan_pengiriman,
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
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
                'provinsi_id' => $request->provinsi == 'NULL' ? NULL : $request->provinsi,
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
                'log' => 'penjualan',
                'created_at' => $randomDate,
                'updated_at' => $randomDate,
            ]);

            $bool = true;
            if ($Ekatalog) {
                if (($request->status == 'sepakat') || ($request->status == 'negosiasi')) {

                    if (isset($request->stok_distributor)) {
                        $penjualan_produk_id  = array_values(array_diff_key($request->penjualan_produk_id, $request->stok_distributor));
                        $variasi  = array_values(array_diff_key($request->variasi, $request->stok_distributor));
                        $produk_jumlah  = array_values(array_diff_key($request->produk_jumlah, $request->stok_distributor));
                        $produk_harga  = array_values(array_diff_key($request->produk_harga, $request->stok_distributor));
                        $produk_ongkir  = array_values(array_diff_key($request->produk_ongkir, $request->stok_distributor));
                        $produk_ppn  = array_values(array_diff_key($request->produk_ppn, $request->stok_distributor));


                        foreach ($request->stok_distributor as $key) {
                            if (isset($request->penjualan_produk_id[$key])) {
                                $penjualan_produk_id_dsb[] = $request->penjualan_produk_id[$key];
                            }
                            if (isset($request->variasi[$key])) {
                                $variasi_dsb[] = $request->variasi[$key];
                            }
                            if (isset($request->produk_jumlah[$key])) {
                                $produk_jumlah_dsb[] = $request->produk_jumlah[$key];
                            }
                            if (isset($request->produk_harga[$key])) {
                                $produk_harga_dsb[] = $request->produk_harga[$key];
                            }
                            if (isset($request->produk_ongkir[$key])) {
                                $produk_ongkir_dsb[] = $request->produk_ongkir[$key];
                            }
                            if (isset($request->produk_ppn[$key])) {
                                $produk_ppn_dsb[] = $request->produk_ppn[$key];
                            }
                            if (isset($request->noSeriDistributor[$key])) {
                                $noseri_dsb[] = $request->noSeriDistributor[$key];
                            }
                        }
                        for ($i = 0; $i < count($penjualan_produk_id); $i++) {
                            $dspa = DetailPesanan::create([
                                'pesanan_id' => $x,
                                'penjualan_produk_id' => $penjualan_produk_id[$i],
                                'jumlah' => $produk_jumlah[$i],
                                'ppn' => isset($produk_ppn[$i]) ? $produk_ppn[$i] : 0,
                                'harga' => str_replace('.', "", $produk_harga[$i]),
                                'ongkir' =>  str_replace('.', "", $produk_ongkir[$i]),
                                'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                            ]);

                            for ($j = 0; $j < count($variasi[$i]); $j++) {
                                DetailPesananProduk::create([
                                    'detail_pesanan_id' => $dspa->id,
                                    'gudang_barang_jadi_id' => $variasi[$i][$j]
                                ]);
                            }
                        }

                        for ($i = 0; $i < count($penjualan_produk_id_dsb); $i++) {
                            $dsb = DetailPesananDsb::create([
                                'pesanan_id' => $x,
                                'penjualan_produk_id' => $penjualan_produk_id_dsb[$i],
                                'jumlah' => $produk_jumlah_dsb[$i],
                                'ppn' => isset($produk_ppn_dsb[$i]) ? $produk_ppn_dsb[$i] : 0,
                                'harga' => str_replace('.', "", $produk_harga_dsb[$i]),
                                'ongkir' => str_replace('.', "", $produk_ongkir_dsb[$i]),
                            ]);

                            if (isset($noseri_dsb[$i])) {
                                $noseri = explode(',', $noseri_dsb[$i]);
                                for ($j = 0; $j < count($noseri); $j++) {
                                    NoseriDsb::create([
                                        'detail_pesanan_dsb' => $dsb->id,
                                        'noseri' => $noseri[$j]
                                    ]);
                                }
                            }

                            for ($j = 0; $j < count($variasi_dsb[$i]); $j++) {
                                DetailPesananProdukDsb::create([
                                    'detail_pesanan_dsb_id' => $dsb->id,
                                    'gudang_barang_jadi_id' => $variasi_dsb[$i][$j]
                                ]);
                            }
                        }
                    } else {
                        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                            $dspa = DetailPesanan::create([
                                'pesanan_id' => $x,
                                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                'jumlah' => $request->produk_jumlah[$i],
                                'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                'ongkir' => 0,
                                'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                            ]);

                            for ($j = 0; $j < count($request->variasi[$i]); $j++) {
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
                    // for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                    //     if (empty($request->produk_ongkir[$i])) {
                    //         $ongkir[$i] = 0;
                    //     } else {
                    //         $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                    //     }
                    //     $dekat = DetailPesanan::create([
                    //         'pesanan_id' => $x,
                    //         'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                    //         'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                    //         'jumlah' => $request->produk_jumlah[$i],
                    //         'harga' => str_replace('.', "", $request->produk_harga[$i]),
                    //         'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                    //         'ongkir' => $ongkir[$i],
                    //     ]);

                    //     if (!$dekat) {
                    //         $bool = false;
                    //     } else {
                    //         for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                    //             $dekatp = DetailPesananProduk::create([
                    //                 'detail_pesanan_id' => $dekat->id,
                    //                 'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                    //             ]);
                    //             if (!$dekatp) {
                    //                 $bool = false;
                    //             }
                    //         }
                    //     }
                    // }
                } else {
                    if ($request->isi_produk == "isi") {
                        // for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        //     if (empty($request->produk_ongkir[$i])) {
                        //         $ongkir[$i] = 0;
                        //     } else {
                        //         $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                        //     }
                        //     $dekat = DetailPesanan::create([
                        //         'pesanan_id' => $x,
                        //         'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                        //         'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                        //         'jumlah' => $request->produk_jumlah[$i],
                        //         'harga' => str_replace('.', "", $request->produk_harga[$i]),
                        //         'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                        //         'ongkir' => $ongkir[$i],
                        //     ]);

                        //     if (!$dekat) {
                        //         $bool = false;
                        //     } else {
                        //         for ($j = 0; $j < count(array($request->variasi[$i])); $j++) {
                        //             $dekatp = DetailPesananProduk::create([
                        //                 'detail_pesanan_id' => $dekat->id,
                        //                 'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                        //             ]);
                        //             if (!$dekatp) {
                        //                 $bool = false;
                        //             }
                        //         }
                        //     }
                        // }
                        if (isset($request->stok_distributor)) {
                            $penjualan_produk_id  = array_values(array_diff_key($request->penjualan_produk_id, $request->stok_distributor));
                            $variasi  = array_values(array_diff_key($request->variasi, $request->stok_distributor));
                            $produk_jumlah  = array_values(array_diff_key($request->produk_jumlah, $request->stok_distributor));
                            $produk_harga  = array_values(array_diff_key($request->produk_harga, $request->stok_distributor));
                            $produk_ongkir  = array_values(array_diff_key($request->produk_ongkir, $request->stok_distributor));
                            $produk_ppn  = array_values(array_diff_key($request->produk_ppn, $request->stok_distributor));


                            foreach ($request->stok_distributor as $key) {
                                if (isset($request->penjualan_produk_id[$key])) {
                                    $penjualan_produk_id_dsb[] = $request->penjualan_produk_id[$key];
                                }
                                if (isset($request->variasi[$key])) {
                                    $variasi_dsb[] = $request->variasi[$key];
                                }
                                if (isset($request->produk_jumlah[$key])) {
                                    $produk_jumlah_dsb[] = $request->produk_jumlah[$key];
                                }
                                if (isset($request->produk_harga[$key])) {
                                    $produk_harga_dsb[] = $request->produk_harga[$key];
                                }
                                if (isset($request->produk_ongkir[$key])) {
                                    $produk_ongkir_dsb[] = $request->produk_ongkir[$key];
                                }
                                if (isset($request->produk_ppn[$key])) {
                                    $produk_ppn_dsb[] = $request->produk_ppn[$key];
                                }
                                if (isset($request->noSeriDistributor[$key])) {
                                    $noseri_dsb[] = $request->noSeriDistributor[$key];
                                }
                            }
                            for ($i = 0; $i < count($penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $x,
                                    'penjualan_produk_id' => $penjualan_produk_id[$i],
                                    'jumlah' => $produk_jumlah[$i],
                                    'ppn' => isset($produk_ppn[$i]) ? $produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga[$i]),
                                    'ongkir' =>  str_replace('.', "", $produk_ongkir[$i]),
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                ]);

                                for ($j = 0; $j < count($variasi[$i]); $j++) {
                                    DetailPesananProduk::create([
                                        'detail_pesanan_id' => $dspa->id,
                                        'gudang_barang_jadi_id' => $variasi[$i][$j]
                                    ]);
                                }
                            }
                            for ($i = 0; $i < count($penjualan_produk_id_dsb); $i++) {
                                $dsb = DetailPesananDsb::create([
                                    'pesanan_id' => $x,
                                    'penjualan_produk_id' => $penjualan_produk_id_dsb[$i],
                                    'jumlah' => $produk_jumlah_dsb[$i],
                                    'ppn' => isset($produk_ppn_dsb[$i]) ? $produk_ppn_dsb[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga_dsb[$i]),
                                    'ongkir' => str_replace('.', "", $produk_ongkir_dsb[$i]),
                                ]);

                                if (isset($noseri_dsb[$i])) {
                                    $noseri = explode(',', $noseri_dsb[$i]);
                                    for ($j = 0; $j < count($noseri); $j++) {
                                        NoseriDsb::create([
                                            'detail_pesanan_dsb' => $dsb->id,
                                            'noseri' => $noseri[$j]
                                        ]);
                                    }
                                }

                                for ($j = 0; $j < count($variasi_dsb[$i]); $j++) {
                                    DetailPesananProdukDsb::create([
                                        'detail_pesanan_dsb_id' => $dsb->id,
                                        'gudang_barang_jadi_id' => $variasi_dsb[$i][$j]
                                    ]);
                                }
                            }
                        } else {
                            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $x,
                                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                    'jumlah' => $request->produk_jumlah[$i],
                                    'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                    'ongkir' => 0,
                                ]);

                                for ($j = 0; $j < count($request->variasi[$i]); $j++) {
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
                        $bool = true;
                    }
                }
            } else {
                $bool = false;
            }
            if ($bool == true) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Ditambahkan',
                    'pesanan_id' => $pesanan->no_po != null ? $pesanan->id : 'refresh',
                ], 200);
            } else if ($bool == false) {
                return response()->json([
                    'message' => 'Cek Form Kembali',
                ], 500);
            }
        } else if ($request->jenis_penjualan == 'spa' || $request->jenis_penjualan == 'spb') {
            if ($request->perusahaan_pengiriman != NULL && $request->alamat_pengiriman != NULL &&  $request->kemasan != NULL) {
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
                    'so' => $this->createSObyPeriod($var, $periode),
                    'no_po' => $request->no_po,
                    'tgl_po' => $request->tanggal_po,
                    'no_do' => $request->no_do,
                    'tgl_do' => $request->tanggal_do,
                    'ket' =>  $request->keterangan,
                    'tujuan_kirim' => $request->perusahaan_pengiriman,
                    'alamat_kirim' => $request->alamat_pengiriman,
                    'kemasan' => $request->kemasan,
                    'ekspedisi_id' => $request->ekspedisi,
                    'ket_kirim' => $request->keterangan_pengiriman,
                    'log_id' => $k,
                    'created_at' => $randomDate,
                    'updated_at' => $randomDate,
                ]);
                $x = $pesanan->id;
                $no_po_nonekat = $pesanan->no_po;
                if ($request->jenis_penjualan == 'spa') {
                    $p = Spa::create([
                        'customer_id' => $request->customer_id,
                        'pesanan_id' => $x,
                        'ket' => $request->keterangan,
                        'log' => 'po',
                        'created_at' => $randomDate,
                        'updated_at' => $randomDate,
                    ]);
                    // $p = 'a';
                } else if ($request->jenis_penjualan == 'spb') {
                    $p = Spb::create([
                        'customer_id' => $request->customer_id,
                        'pesanan_id' => $x,
                        'ket' => $request->keterangan,
                        'log' => 'po',
                        'created_at' => $randomDate,
                        'updated_at' => $randomDate,
                    ]);
                }
                $bool = true;
                if ($p) {
                    if (in_array("produk", $request->jenis_pen)) {
                        if (isset($request->stok_distributor)) {
                            $penjualan_produk_id  = array_values(array_diff_key($request->penjualan_produk_id, $request->stok_distributor));
                            $variasi  = array_values(array_diff_key($request->variasi, $request->stok_distributor));
                            $produk_jumlah  = array_values(array_diff_key($request->produk_jumlah, $request->stok_distributor));
                            $produk_harga  = array_values(array_diff_key($request->produk_harga, $request->stok_distributor));
                            $produk_ongkir  = array_values(array_diff_key($request->produk_ongkir, $request->stok_distributor));
                            $produk_ppn  = array_values(array_diff_key($request->produk_ppn, $request->stok_distributor));


                            foreach ($request->stok_distributor as $key) {
                                if (isset($request->penjualan_produk_id[$key])) {
                                    $penjualan_produk_id_dsb[] = $request->penjualan_produk_id[$key];
                                }
                                if (isset($request->variasi[$key])) {
                                    $variasi_dsb[] = $request->variasi[$key];
                                }
                                if (isset($request->produk_jumlah[$key])) {
                                    $produk_jumlah_dsb[] = $request->produk_jumlah[$key];
                                }
                                if (isset($request->produk_harga[$key])) {
                                    $produk_harga_dsb[] = $request->produk_harga[$key];
                                }
                                if (isset($request->produk_ongkir[$key])) {
                                    $produk_ongkir_dsb[] = $request->produk_ongkir[$key];
                                }
                                if (isset($request->produk_ppn[$key])) {
                                    $produk_ppn_dsb[] = $request->produk_ppn[$key];
                                }
                                if (isset($request->noSeriDistributor[$key])) {
                                    $noseri_dsb[] = $request->noSeriDistributor[$key];
                                }
                            }
                            for ($i = 0; $i < count($penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $x,
                                    'penjualan_produk_id' => $penjualan_produk_id[$i],
                                    'jumlah' => $produk_jumlah[$i],
                                    'ppn' => isset($produk_ppn[$i]) ? $produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga[$i]),
                                    'ongkir' =>  str_replace('.', "", $produk_ongkir[$i]),
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                ]);

                                for ($j = 0; $j < count($variasi[$i]); $j++) {
                                    DetailPesananProduk::create([
                                        'detail_pesanan_id' => $dspa->id,
                                        'gudang_barang_jadi_id' => $variasi[$i][$j]
                                    ]);
                                }
                            }

                            for ($i = 0; $i < count($penjualan_produk_id_dsb); $i++) {
                                $dsb = DetailPesananDsb::create([
                                    'pesanan_id' => $x,
                                    'penjualan_produk_id' => $penjualan_produk_id_dsb[$i],
                                    'jumlah' => $produk_jumlah_dsb[$i],
                                    'ppn' => isset($produk_ppn_dsb[$i]) ? $produk_ppn_dsb[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga_dsb[$i]),
                                    'ongkir' => str_replace('.', "", $produk_ongkir_dsb[$i]),
                                ]);

                                // if($noseri_dsb[$i] != null){
                                //     $noseri = explode(',', $noseri_dsb[$i]);
                                //     for ($j = 0; $j < count($noseri); $j++) {
                                //         NoseriDsb::create([
                                //             'detail_pesanan_dsb' => $dsb->id,
                                //             'noseri' => $noseri[$j]
                                //         ]);
                                //     }

                                // }
                                if (isset($noseri_dsb[$i])) {
                                    $noseri = explode(',', $noseri_dsb[$i]);
                                    for ($j = 0; $j < count($noseri); $j++) {
                                        NoseriDsb::create([
                                            'detail_pesanan_dsb' => $dsb->id,
                                            'noseri' => $noseri[$j]
                                        ]);
                                    }
                                }
                                for ($j = 0; $j < count($variasi_dsb[$i]); $j++) {
                                    DetailPesananProdukDsb::create([
                                        'detail_pesanan_dsb_id' => $dsb->id,
                                        'gudang_barang_jadi_id' => $variasi_dsb[$i][$j]
                                    ]);
                                }
                            }
                        } else {
                            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $x,
                                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                    'jumlah' => $request->produk_jumlah[$i],
                                    'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                    'ongkir' => 0,
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                ]);

                                for ($j = 0; $j < count($request->variasi[$i]); $j++) {
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
                                'ppn' => isset($request->part_ppn[$i]) ? $request->part_ppn[$i] : 0,
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
                                'ppn' => isset($request->jasa_ppn[$i]) ? $request->jasa_ppn[$i] : 0,
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
                    return response()->json([
                        'status' => 200,
                        'message' => 'Berhasil Ditambahkan',
                        'pesanan_id' => $no_po_nonekat != null ? $x : 'refresh',
                    ], 200);
                } else if ($bool == false) {
                    return response()->json([
                        'message' => 'Cek Form Kembali',
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
    }
    // Create
    // public function create_penjualan(Request $request)
    // {
    //     if ($request->jenis_penjualan == 'ekatalog') {
    //         if ($request->status == 'sepakat' && ($request->namadistributor == 'belum' ||$request->provinsi == "NULL") ) {
    //                 return response()->json([
    //                     'message' => 'Cek Form Kembali',
    //                 ], 500);
    //         }
    //         // if ($request->no_po_ekat != NULL && ( $request->perusahaan_pengiriman_ekat == NULL || $request->alamat_pengiriman_ekat == NULL ||  $request->kemasan == NULL) ) {
    //         //         return response()->json([
    //         //             'message' => 'Cek Form Kembali',
    //         //         ], 500);
    //         // }
    //         //dd($request);
    //         // $this->validate(
    //         //     $request,
    //         //     [
    //         //         'no_paket' => 'required',
    //         //         'customer_id' => 'required',
    //         //         'status' => 'required',
    //         //         'tgl_kontrak' => 'required',
    //         //         'jumlah.*' => 'required',
    //         //         'penjualan_produk_id.*' => 'required'
    //         //     ],
    //         //     [
    //         //         'no_paket.required' => 'No Paket harus di isi',
    //         //         'customer_id.required' => 'Customer harus di isi',
    //         //         'status.required' => 'Status harus di pilih',
    //         //         'tgl_kontrak.required' => 'Tg; Kontrak harus di isi',
    //         //         'jumlah.required' => 'Jumlah Produk harus di isi',
    //         //         'penjualan_produk_id.required' => 'Produk harus di pilih',
    //         //     ]
    //         // );


    //         //Konversi No SO
    //         // $x = Ekatalog::max('id') + 1;
    //         // $y = Carbon::now()->format('Y');
    //         // $m = Carbon::now()->format('m');
    //         // $filter = new IntToRoman();
    //         $so = NULL;
    //         $no_po = NULL;
    //         $tgl_po = NULL;
    //         $no_do = NULL;
    //         $tgl_do = NULL;
    //         $ket_po = NULL;
    //         $log_id = "7";

    //         if ($request->no_po_ekat != "") {
    //             $so = $this->createSO('EKAT');
    //             $no_po = $request->no_po_ekat;
    //             $tgl_po = $request->tanggal_po_ekat;
    //             $no_do = $request->no_do_ekat;
    //             $tgl_do = $request->tanggal_do_ekat;
    //             $ket_po = $request->keterangan_ekat;
    //             if ($request->status == 'sepakat') {
    //                 $log_id = "9";
    //             }
    //         }

    //         $pesanan = Pesanan::create([
    //             'so' => $so,
    //             'no_po' => $no_po,
    //             'tgl_po' => $tgl_po,
    //             'no_do' => $no_do,
    //             'tgl_do' => $tgl_do,
    //             'ket' =>  $ket_po,
    //             'log_id' => $log_id,
    //             'tujuan_kirim' => $request->perusahaan_pengiriman_ekat,
    //             'alamat_kirim' => $request->alamat_pengiriman_ekat,
    //             'kemasan' => $request->kemasan,
    //             'ekspedisi_id' => $request->ekspedisi,
    //             'ket_kirim' => $request->keterangan_pengiriman,
    //             'created_at' => Carbon::now()->toDateTimeString(),
    //             'updated_at' => Carbon::now()->toDateTimeString(),
    //         ]);

    //         $x = $pesanan->id;
    //         if ($request->namadistributor == 'belum') {
    //             $c_id = '484';
    //         } else {
    //             $c_id = $request->customer_id;
    //         }

    //         if ($request->no_paket != "") {
    //             $nopaket = $request->jenis_paket . $request->no_paket;
    //         } else {
    //             $nopaket = "";
    //         }


    //         $Ekatalog = Ekatalog::create([
    //             'customer_id' => $c_id,
    //             'provinsi_id' => $request->provinsi == 'NULL' ? NULL : $request->provinsi,
    //             'pesanan_id' => $x,
    //             'no_paket' => $nopaket,
    //             'no_urut' => $request->no_urut,
    //             'deskripsi' => $request->deskripsi,
    //             'instansi' => $request->instansi,
    //             'alamat' => $request->alamatinstansi,
    //             'satuan' => $request->satuan_kerja,
    //             'status' => $request->status,
    //             'tgl_kontrak' => $request->batas_kontrak,
    //             'tgl_buat' => $request->tanggal_pemesanan,
    //             'tgl_edit' => $request->tanggal_edit,
    //             'ket' => $request->keterangan,
    //             'log' => 'penjualan'
    //         ]);

    //         $bool = true;
    //         if ($Ekatalog) {
    //             if (($request->status == 'sepakat') || ($request->status == 'negosiasi')) {

    //                 for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
    //                     if (empty($request->produk_ongkir[$i])) {
    //                         $ongkir[$i] = 0;
    //                     } else {
    //                         $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
    //                     }
    //                     $dekat = DetailPesanan::create([
    //                         'pesanan_id' => $x,
    //                         'penjualan_produk_id' => $request->penjualan_produk_id[$i],
    //                         'detail_rencana_penjualan_id' => $request->rencana_id[$i],
    //                         'jumlah' => $request->produk_jumlah[$i],
    //                         'harga' => str_replace('.', "", $request->produk_harga[$i]),
    //                         'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
    //                          'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
    //                        // 'kalibrasi' => $kalibrasi,
    //                         'ongkir' => $ongkir[$i],
    //                     ]);

    //                     if (!$dekat) {
    //                         $bool = false;
    //                     } else {
    //                         for ($j = 0; $j < count($request->variasi[$i]); $j++) {
    //                             $dekatp = DetailPesananProduk::create([
    //                                 'detail_pesanan_id' => $dekat->id,
    //                                 'gudang_barang_jadi_id' => $request->variasi[$i][$j]
    //                             ]);
    //                             if (!$dekatp) {
    //                                 $bool = false;
    //                             }
    //                         }
    //                     }
    //                 }
    //             } else {
    //                 if ($request->isi_produk == "isi") {
    //                     for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
    //                         if (empty($request->produk_ongkir[$i])) {
    //                             $ongkir[$i] = 0;
    //                         } else {
    //                             $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
    //                         }
    //                         $dekat = DetailPesanan::create([
    //                             'pesanan_id' => $x,
    //                             'penjualan_produk_id' => $request->penjualan_produk_id[$i],
    //                             'detail_rencana_penjualan_id' => $request->rencana_id[$i],
    //                             'jumlah' => $request->produk_jumlah[$i],
    //                             'harga' => str_replace('.', "", $request->produk_harga[$i]),
    //                             'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
    //                              'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
    //                             //'kalibrasi' => $kalibrasi,
    //                             'ongkir' => $ongkir[$i],
    //                         ]);

    //                         if (!$dekat) {
    //                             $bool = false;
    //                         } else {
    //                             for ($j = 0; $j < count(array($request->variasi[$i])); $j++) {
    //                                 $dekatp = DetailPesananProduk::create([
    //                                     'detail_pesanan_id' => $dekat->id,
    //                                     'gudang_barang_jadi_id' => $request->variasi[$i][$j]
    //                                 ]);
    //                                 if (!$dekatp) {
    //                                     $bool = false;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 } else {
    //                     $bool = true;
    //                 }
    //             }
    //         } else {
    //             $bool = false;
    //         }
    //         if ($bool == true) {
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Berhasil Ditambahkan',
    //                 'pesanan_id' => $pesanan->no_po != null ? $pesanan->id : 'refresh',
    //             ], 200);
    //         } else if ($bool == false) {
    //             return response()->json([
    //                 'message' => 'Cek Form Kembali',
    //             ], 500);
    //         }
    //     } else if ($request->jenis_penjualan == 'spa' || $request->jenis_penjualan == 'spb') {
    //     if( $request->perusahaan_pengiriman != NULL && $request->alamat_pengiriman != NULL &&  $request->kemasan != NULL){
    //         $count_array = count($request->jenis_pen);
    //         if (in_array("jasa", $request->jenis_pen) && $count_array == 1) {
    //             $k = '11';
    //         } else {
    //             $k = '9';
    //         }
    //         if ($request->jenis_penjualan == 'spa') {
    //             $var = 'SPA';
    //         } else if ($request->jenis_penjualan == 'spb') {
    //             $var = 'SPB';
    //         }
    //         $pesanan = Pesanan::create([
    //             'so' => $this->createSO($var),
    //             'no_po' => $request->no_po,
    //             'tgl_po' => $request->tanggal_po,
    //             'no_do' => $request->no_do,
    //             'tgl_do' => $request->tanggal_do,
    //             'ket' =>  $request->keterangan,
    //             'tujuan_kirim' => $request->perusahaan_pengiriman,
    //             'alamat_kirim' => $request->alamat_pengiriman,
    //             'kemasan' => $request->kemasan,
    //             'ekspedisi_id' => $request->ekspedisi,
    //             'ket_kirim' => $request->keterangan_pengiriman,
    //             'log_id' => $k
    //         ]);
    //         $x = $pesanan->id;
    //         $no_po_nonekat = $pesanan->no_po;
    //         if ($request->jenis_penjualan == 'spa') {
    //             $p = Spa::create([
    //                 'customer_id' => $request->customer_id,
    //                 'pesanan_id' => $x,
    //                 'ket' => $request->keterangan,
    //                 'log' => 'po'
    //             ]);
    //         } else if ($request->jenis_penjualan == 'spb') {
    //             $p = Spb::create([
    //                 'customer_id' => $request->customer_id,
    //                 'pesanan_id' => $x,
    //                 'ket' => $request->keterangan,
    //                 'log' => 'po'
    //             ]);
    //         }
    //         $bool = true;
    //         if ($p) {
    //             if (in_array("produk", $request->jenis_pen)) {
    //                 for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
    //                     $dspa = DetailPesanan::create([
    //                         'pesanan_id' => $x,
    //                         'penjualan_produk_id' => $request->penjualan_produk_id[$i],
    //                         'jumlah' => $request->produk_jumlah[$i],
    //                         'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
    //                         'harga' => str_replace('.', "", $request->produk_harga[$i]),
    //                          'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
    //                         //'kalibrasi' => $kalibrasi,
    //                         'ongkir' => 0,
    //                     ]);

    //                     for ($j = 0; $j < count($request->variasi[$i]); $j++) {
    //                         $dspap = DetailPesananProduk::create([
    //                             'detail_pesanan_id' => $dspa->id,
    //                             'gudang_barang_jadi_id' => $request->variasi[$i][$j]
    //                         ]);
    //                         if (!$dspap) {
    //                             $bool = false;
    //                         }
    //                     }
    //                 }
    //             }
    //             if (in_array("sparepart", $request->jenis_pen)) {
    //                 for ($i = 0; $i < count($request->part_id); $i++) {
    //                     $dspb = DetailPesananPart::create([
    //                         'pesanan_id' => $x,
    //                         'm_sparepart_id' => $request->part_id[$i],
    //                         'jumlah' => $request->part_jumlah[$i],
    //                         'harga' => str_replace('.', "", $request->part_harga[$i]),
    //                         'ppn' => isset($request->part_ppn[$i]) ? $request->part_ppn[$i] : 0,
    //                         'ongkir' => 0,
    //                     ]);
    //                     if (!$dspb) {
    //                         $bool = false;
    //                     }
    //                 }
    //             }
    //             if (in_array("jasa", $request->jenis_pen)) {
    //                 for ($i = 0; $i < count($request->jasa_id); $i++) {
    //                     $dspb = DetailPesananPart::create([
    //                         'pesanan_id' => $x,
    //                         'm_sparepart_id' => $request->jasa_id[$i],
    //                         'jumlah' => 1,
    //                         'harga' => str_replace('.', "", $request->jasa_harga[$i]),
    //                         'ppn' => isset($request->jasa_ppn[$i]) ? $request->jasa_ppn[$i] : 0,
    //                         'ongkir' => 0,
    //                     ]);

    //                     $qcspb = OutgoingPesananPart::create([
    //                         'detail_pesanan_part_id' => $dspb->id,
    //                         'tanggal_uji' => $request->tanggal_po,
    //                         'jumlah_ok' => 1,
    //                         'jumlah_nok' => 0,
    //                     ]);

    //                     if (!$dspb) {
    //                         $bool = false;
    //                     }
    //                 }
    //             }
    //         } else {
    //             $bool = false;
    //         }

    //         if ($bool == true) {
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Berhasil Ditambahkan',
    //                 'pesanan_id' => $no_po_nonekat != null ? $x : 'refresh',
    //             ], 200);
    //         } else if ($bool == false) {
    //             return response()->json([
    //                 'message' => 'Cek Form Kembali',
    //             ], 500);
    //         }
    //     }
    //     }else{
    //     return response()->json([
    //         'message' => 'Cek Form Kembali',
    //     ], 500);
    //     }
    // }


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



    function penjualanDetailEdit($id)
    {
        try {
            //code...

            $pesanan = Pesanan::find($id);
            $data = (object) [];
            $produk = [];
            $part = [];
            $jasa = [];
            $barang = [];
            if (count($pesanan->DetailPesanan) > 0) {
                $barang[] = "produk";
                foreach ($pesanan->DetailPesanan as $detail_pesanan) {
                    $produk[] = array(
                        'id' => $detail_pesanan->id,
                        'id_produk' => $detail_pesanan->penjualan_produk_id,
                        'harga' => $detail_pesanan->harga,
                        'jumlah' => $detail_pesanan->jumlah,
                        'ongkir' => $detail_pesanan->ongkir,
                        'pajak' => $detail_pesanan->ppn == 1 ? true  : false,
                        'stok_distributor' => 'nondsb ',
                        'kalibrasi' => $detail_pesanan->kalibrasi == 1 ? true  : false,
                        'catatan' => $detail_pesanan->keterangan ?? "",
                        "variasi" =>  $detail_pesanan->DetailPesananProdukVariasiSet(),
                        'noseridsb' => array()
                    );
                }
            }
            if (count($pesanan->DetailPesananDsb) > 0) {
                count($barang) <= 0 ?  $barang[] = "produk" : '';

                foreach ($pesanan->DetailPesananDsb as $detail_pesanan) {
                    $produk[] = array(
                        'id' => $detail_pesanan->id,
                        'id_produk' => $detail_pesanan->penjualan_produk_id,
                        'harga' => $detail_pesanan->harga,
                        'jumlah' => $detail_pesanan->jumlah,
                        'ongkir' => $detail_pesanan->ongkir,
                        'pajak' => $detail_pesanan->ppn == 1 ? true  : false,
                        'stok_distributor' => 'dsb',
                        'kalibrasi' => $detail_pesanan->kalibrasi == 1 ? true  : false,
                        "variasi" =>  $detail_pesanan->DetailPesananProdukVariasiSet(),
                        'noseridsb' => $detail_pesanan->NoseriDsb->pluck('noseri')->toArray()

                    );
                }
            }
            if (count($pesanan->DetailPesananPartJasa()) > 0) {
                $barang[] = "jasa";
                foreach ($pesanan->DetailPesananPartJasa() as $part_jasa) {
                    $jasa[] = array(
                        'id' => $part_jasa->id,
                        'sparepart_id' => $part_jasa->m_sparepart_id,
                        'harga' => $part_jasa->harga,
                        'jumlah' => $part_jasa->jumlah,
                        'pajak' => $part_jasa->ppn == 1 ? true  : false,

                    );
                }
            }
            if (count($pesanan->DetailPesananPartNonJasa()) > 0) {
                $barang[] = "part";
                foreach ($pesanan->DetailPesananPartNonJasa() as $part_nonjasa) {
                    $part[] = array(
                        'id' => $part_nonjasa->id,
                        'sparepart_id' => $part_nonjasa->m_sparepart_id,
                        'harga' => $part_nonjasa->harga,
                        'jumlah' => $part_nonjasa->jumlah,
                        'pajak' => $part_nonjasa->ppn == 1 ? true  : false,

                    );
                }
            }

            if ($pesanan->Ekatalog) {

                $alamat_pengiriman = 'lainnya';
                if ($pesanan->Ekatalog->no_paket != '') {
                    $paket = explode('-', $pesanan->Ekatalog->no_paket, 2);
                    $no_paket_awal =  $paket[0] . '-';
                    $no_paket_akhir =  $paket[1];
                } else {
                    $no_paket_awal =  '';
                    $no_paket_akhir =  '';
                }
                if ($pesanan->tujuan_kirim != '') {
                    $c = Customer::where('nama', $pesanan->tujuan_kirim)->count();
                    $e = Ekatalog::where('satuan', $pesanan->tujuan_kirim)->count();
                    if ($c > 0) {
                        $alamat_pengiriman = 'distributor';
                    }
                    if ($e > 0) {
                        $alamat_pengiriman = 'instansi';
                    }
                }

                $data->jenis = 'ekatalog';
                $data->customer_id = $pesanan->Ekatalog->customer_id;
                $data->nama = $pesanan->Ekatalog->customer_id != 484 ?  $pesanan->Ekatalog->Customer->nama : '';
                $data->alamat = $pesanan->Ekatalog->customer_id != 484 ?  $pesanan->Ekatalog->Customer->alamat : '';
                $data->telepon = $pesanan->Ekatalog->customer_id != 484 ?  $pesanan->Ekatalog->Customer->telepon : '';
                $data->customer_provinsi  = $pesanan->Ekatalog->customer_id != 484 ?  $pesanan->Ekatalog->Customer->Provinsi->nama : '';
                $data->alamat_pengiriman = $alamat_pengiriman;
                $data->is_customer_diketahui = $pesanan->Ekatalog->customer_id == 484 ? false : true;
                $data->alamat =  $pesanan->Ekatalog->customer_id != 484 ? $pesanan->Ekatalog->Customer->alamat : '';
                $data->no_urut = $pesanan->Ekatalog->no_urut;
                $data->no_paket_awal = $no_paket_awal;
                $data->no_paket_akhir = $no_paket_akhir;
                $data->status = $pesanan->Ekatalog->status;
                $data->tgl_buat = $pesanan->Ekatalog->tgl_buat;
                $data->tgl_delivery = $pesanan->Ekatalog->tgl_kontrak;
                $data->tgl_edit = $pesanan->Ekatalog->tgl_edit;
                $data->instansi = $pesanan->Ekatalog->instansi;
                $data->satuan_kerja = $pesanan->Ekatalog->satuan;
                $data->alamat_instansi = $pesanan->Ekatalog->alamat;
                $data->provinsi = $pesanan->Ekatalog->provinsi_id;
                $data->provinsi_nama = $pesanan->Ekatalog->provinsi_id != '' ? $pesanan->Ekatalog->Provinsi->nama : '';
                $data->deskripsi = $pesanan->Ekatalog->deskripsi;
                $data->keterangan = $pesanan->Ekatalog->ket ?? '';
            }
            if ($pesanan->Spa) {
                $alamat_pengiriman = 'lainnya';

                if ($pesanan->tujuan_kirim != '') {
                    $c = Customer::where('nama', $pesanan->tujuan_kirim)->count();

                    if ($c > 0) {
                        $alamat_pengiriman = 'distributor';
                    }
                }


                $data->jenis = 'spa';
                $data->nama = $pesanan->Spa->customer_id != 484 ?  $pesanan->Spa->Customer->nama : '';
                $data->alamat =  $pesanan->Spa->customer_id != 484 ? $pesanan->Spa->Customer->alamat : '';
                $data->telepon =  $pesanan->Spa->customer_id != 484 ? $pesanan->Spa->Customer->telepon : '';
                $data->customer_provinsi  = $pesanan->Spa->customer_id != 484 ?  $pesanan->Spa->Customer->Provinsi->nama : '';
                $data->alamat_pengiriman = $alamat_pengiriman;
                $data->is_customer_diketahui = $pesanan->Spa->customer_id == 484 ? false : true;
                $data->customer_id = $pesanan->Spa->customer_id;
                $data->ket = $pesanan->Spa->ket;
            }
            if ($pesanan->Spb) {

                $alamat_pengiriman = 'lainnya';

                if ($pesanan->tujuan_kirim != '') {
                    $c = Customer::where('nama', $pesanan->tujuan_kirim)->count();

                    if ($c > 0) {
                        $alamat_pengiriman = 'distributor';
                    }
                }
                $data->jenis = 'spb';
                $data->nama = $pesanan->Spb->customer_id != 484 ?  $pesanan->Spa->Customer->nama : '';
                $data->alamat =  $pesanan->Spb->customer_id != 484 ? $pesanan->Spb->Customer->alamat : '';
                $data->telepon = $pesanan->Spb->customer_id != 484 ?  $pesanan->Spb->Customer->telepon : '';
                $data->customer_provinsi  = $pesanan->Spb->customer_id != 484 ?  $pesanan->Spb->Customer->Provinsi->nama : '';
                $data->alamat_pengiriman = $alamat_pengiriman;
                $data->is_customer_diketahui = $pesanan->Spb->customer_id == 484 ? false : true;
                $data->customer_id = $pesanan->Spb->customer_id;
                $data->ket = $pesanan->Spb->ket;
            }

            $data->so = $pesanan->so;
            $data->barang = $barang;
            $data->no_po = $pesanan->no_po;
            $data->no_po = $pesanan->no_po;
            $data->tgl_po  = $pesanan->tgl_po;
            $data->nomor_do = $pesanan->no_do;
            $data->tgl_do = $pesanan->tgl_do;
            $data->ket_do = $pesanan->ket ?? '';
            $data->nama_perusahaan = $pesanan->tujuan_kirim ?? '';
            $data->alamat_perusahaan = $pesanan->alamat_kirim ?? '';
            $data->kemasan = $pesanan->kemasan;
            $data->ekspedisi = $pesanan->ekspedisi_id;
            $data->produk = $produk;
            $data->jasa = $jasa;
            $data->sparepart = $part;
        } catch (\Throwable $th) {
            //throw $th;
            $data = $th->getMessage();
        }


        return response()->json($data);
    }


    function penjualanStoreEdit(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            $tahunSekarang = Carbon::now()->format('Y');
            $periode = AktifPeriode::first()->tahun;
            if ($tahunSekarang !=  $periode) {
                $month = mt_rand(1, 12);
                $day = mt_rand(1, Carbon::createFromDate($periode, $month)->daysInMonth);
                $randomDate = Carbon::createFromDate($periode, $month, $day)->toDateTimeString();
            } else {
                $randomDate =  Carbon::now()->toDateTimeString();
            }

            $jnis = '';

            switch ($request->jenis) {
                case "ekatalog":
                    $jnis = 'EKAT';
                    break;
                case "spa":
                    $jnis = 'SPA';
                    break;
                case "spb":
                    $jnis = 'SPB';
                    break;
                default:
                    $jnis;
            }



            $poid = $request->id;
            $pesanan = Pesanan::find($request->id);
            $generate = $pesanan->so;
            if ($pesanan->so == '') {
                if ($request->no_po != '') {
                    $generate = $this->createSObyPeriod($jnis, $periode);
                }
            }

            $pesanan->so = $generate;
            $pesanan->no_po = $request->no_po;
            $pesanan->tgl_po = $request->tgl_po;
            $pesanan->no_do = $request->nomor_do ??= null;
            $pesanan->tgl_do = $request->tgl_do ??= null;
            $pesanan->ket =  $request->ket_do;
            $pesanan->log_id = 7;
            $pesanan->tujuan_kirim = $request->nama_perusahaan;
            $pesanan->alamat_kirim = $request->alamat_perusahaan;
            $pesanan->kemasan = $request->kemasan;
            $pesanan->ekspedisi_id = $request->ekspedisi;
            $pesanan->ket_kirim = $request->keterangan;
            $pesanan->created_at = $randomDate;
            $pesanan->updated_at = $randomDate;
            $pesanan->save();

            if (count($pesanan->DetailPesanan) > 0) {
                $dekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();

                if (count($dekatp) > 0) {
                    $deldekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                }
                $dekat = DetailPesanan::where('pesanan_id', $poid)->get();
                if (count($dekat) > 0) {
                    $deldekat = DetailPesanan::where('pesanan_id', $poid)->delete();
                }
            }



            if (count($pesanan->DetailPesananDsb) > 0) {

                $seridsb = NoseriDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                });


                if ($seridsb->count() > 0) {
                    NoseriDsb::whereIN('id', $seridsb->pluck('id')->toArray());
                }

                $dsb = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();

                if (count($dsb) > 0) {
                    $deldspap = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                }

                $dsbpa = DetailPesananDsb::where('pesanan_id', $poid)->get();
                if (count($dsbpa) > 0) {
                    $deldsbpa = DetailPesananDsb::where('pesanan_id', $poid)->delete();
                }
            }

            if (count($pesanan->DetailPesananPartNonJasa()) > 0) {
                $dpart = DetailPesananPart::whereHas('Sparepart', function ($q) {
                    $q->where('kode', 'not like', '%Jasa%');
                })->where('pesanan_id', $poid)->get();
                if (count($dpart) > 0) {
                    $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                        $q->where('kode', 'not like', '%Jasa%');
                    })->where('pesanan_id', $poid)->delete();
                }
            }
            if (count($pesanan->DetailPesananPartJasa()) > 0) {
                $dpart = DetailPesananPart::whereHas('Sparepart', function ($q) {
                    $q->where('kode', 'not like', '%Jasa%');
                })->where('pesanan_id', $poid)->get();


                if (count($dpart) > 0) {

                    OutgoingPesananPart::whereHas('DetailPesananPart', function ($q)  use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();


                    $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                        $q->where('kode', 'not like', '%Jasa%');
                    })->where('pesanan_id', $poid)->delete();
                }
            }


            if (isset($request->sparepart)) {
                foreach ($request->sparepart as $sparepart) {

                    $dspb = DetailPesananPart::create([
                        'pesanan_id' =>  $pesanan->id,
                        'm_sparepart_id' => $sparepart['sparepart_id'],
                        'jumlah' => $sparepart['jumlah'],
                        'harga' => $sparepart['harga'],
                        'ppn' => $sparepart['pajak'] == 'true' ? 1 : 0,
                        'ongkir' => 0,
                    ]);
                }
            }

            if (isset($request->jasa)) {
                foreach ($request->jasa as $jasa) {

                    $dppt = DetailPesananPart::create([
                        'pesanan_id' =>  $pesanan->id,
                        'm_sparepart_id' => $jasa['jasa_id'],
                        'jumlah' => 1,
                        'harga' => $jasa['harga'],
                        'ppn' => $jasa['pajak'] == 'true' ? 1 : 0,
                        'ongkir' => 0,
                    ]);


                    OutgoingPesananPart::create([
                        'detail_pesanan_part_id' => $dppt->id,
                        'tanggal_uji' => $request->tgl_po,
                        'jumlah_ok' => 1,
                        'jumlah_nok' => 0
                    ]);
                }
            }
            if (isset($request->produk)) {

                foreach ($request->produk as $produk) {

                    if ($produk['stok_distributor'] == 'nondsb') {
                        $detail_pesanan = DetailPesanan::create([
                            'pesanan_id' => $pesanan->id,
                            'penjualan_produk_id' => $produk['id_produk'],
                            'jumlah' => $produk['jumlah'],
                            'harga' => $produk['harga'],
                            'ongkir' => $produk['ongkir'],
                            'ppn' => $produk['pajak'] == 'true' ? 1 : 0,
                            'kalibrasi' => $produk['kalibrasi'] == 'true' ? 1 : 0,
                        ]);
                        foreach ($produk['variasi'] as $variasi) {
                            DetailPesananProduk::create([
                                'detail_pesanan_id' => $detail_pesanan['id'],
                                'gudang_barang_jadi_id' => $variasi['variasiSelected']
                            ]);
                        }
                    } else {
                        $dsb = DetailPesananDsb::create([
                            'pesanan_id' =>  $pesanan->id,
                            'penjualan_produk_id' => $produk['id_produk'],
                            'jumlah' => $produk['jumlah'],
                            'ppn' => $produk['pajak'] == 'true' ? 1 : 0,
                            'harga' => $produk['harga'],
                            'ongkir' => $produk['ongkir'],
                        ]);

                        foreach ($produk['variasi'] as $variasi) {
                            DetailPesananProdukDsb::create([
                                'detail_pesanan_dsb_id' => $dsb['id'],
                                'gudang_barang_jadi_id' => $variasi['variasiSelected']
                            ]);
                        }
                        if (isset($produk['noseridsb']) > 0) {
                            foreach ($produk['noseridsb'] as $noseri_dsb) {
                                NoseriDsb::create([
                                    'detail_pesanan_dsb' => $dsb['id'],
                                    'noseri' => $noseri_dsb
                                ]);
                            }
                        }
                    }
                }
            }


            if ($request->jenis == 'ekatalog') {
                $ekatalog = Ekatalog::find($pesanan->Ekatalog->id);

                $ekatalog->customer_id = $request->customer_id != '' ?  $request->customer_id : 484;
                $ekatalog->provinsi_id = $request->provinsi == 'NULL' ? NULL : $request->provinsi;
                $ekatalog->no_paket = $request->no_paket != '' && $request->is_no_paket_disabled == true ? $request->no_paket_awal . $request->no_paket : NULL;
                $ekatalog->no_urut = $request->no_urut;
                $ekatalog->deskripsi = $request->deskripsi;
                $ekatalog->instansi = $request->instansi;
                $ekatalog->alamat = $request->alamat_instansi;
                $ekatalog->satuan = $request->satuan_kerja;
                $ekatalog->status = $request->status;
                $ekatalog->tgl_kontrak = $request->tgl_delivery;
                $ekatalog->tgl_buat = $request->tgl_buat;
                $ekatalog->tgl_edit = $request->tgl_edit;
                $ekatalog->ket = $request->keterangan;
                $ekatalog->save();
            }


            if ($request->jenis == 'spa') {
                $spa = Spa::find($pesanan->Spa->id);
                $spa->customer_id =  $request->customer_id != '' ?  $request->customer_id : 484;
                $spa->pesanan_id = $pesanan->id;
                $spa->ket = $request->ket_do;
                $spa->save();
            }
            if ($request->jenis == 'spb') {
                $spb = Spb::find($pesanan->Spa->id);
                $spb->customer_id =  $request->customer_id != '' ?  $request->customer_id : 484;
                $spb->pesanan_id = $pesanan->id;
                $spb->ket = $request->ket_do;
                $spb->save();
            }


            DB::commit();
            return response()->json([
                'message' => 'ok',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'message' => 'Kesalahan' . $th->getMessage(),
            ], 500);
        }
    }
    public function update_penjualan($id, $jenis)
    {

        if ($jenis == 'ekatalog') {
            $ekatalog = Ekatalog::find($id);

            $item = array();
            foreach ($ekatalog->Pesanan->DetailPesanan as $key_paket => $d) {
                $item[$key_paket] = array(
                    'jenis' => 'po',
                    'id' => $d->id,
                    'detail_rencana_penjualan_id' => 0,
                    'penjualan_produk_id' => $d->penjualan_produk_id,
                    'nama' => $d->PenjualanProduk->nama,
                    'jumlah' => $d->jumlah,
                    'harga' => $d->harga,
                    'ongkir' => $d->ongkir,
                    'ppn' => $d->ppn,
                    'kalibrasi' => $d->kalibrasi,
                    'is_kalibrasi' => $d->PenjualanProduk->produk->whereNotNull('kode_lab_id')->count() > 0 ? true : false,
                    'detail' => array(),
                    'seri' =>  ""
                );
                foreach ($d->DetailPesananProduk as $key_prd => $e) {
                    $item[$key_paket]['detail'][$key_prd] = array(
                        'id' => $e->id,
                        'gbj_id' => $e->GudangBarangJadi->id,
                        'nama' => $e->GudangBarangJadi->Produk->nama,
                        'variasi' => $e->GudangBarangJadi->nama,

                    );
                }
            }
            if ($ekatalog->Pesanan->DetailPesananDsb->isEmpty()) {
                $data = $item;
            } else {
                if ($ekatalog->Pesanan->DetailPesanan->isEmpty()) {
                    foreach ($ekatalog->Pesanan->DetailPesananDsb as $key_paket => $d) {
                        if ($d->NoseriDsb->isEmpty()) {
                            $seri =    "";
                        } else {
                            $seri =    implode(',', collect($d->NoseriDsb->pluck("noseri"))->toArray());
                        }

                        $item_dsb[$key_paket] = array(
                            'jenis' => 'dsb',
                            'id' => $d->id,
                            'detail_rencana_penjualan_id' => 0,
                            'penjualan_produk_id' => $d->penjualan_produk_id,
                            'nama' => $d->PenjualanProduk->nama,
                            'jumlah' => $d->jumlah,
                            'harga' => $d->harga,
                            'ongkir' => $d->ongkir,
                            'ppn' => $d->ppn,
                            'kalibrasi' => 0,
                            'is_kalibrasi' => $d->PenjualanProduk->produk->whereNotNull('kode_lab_id')->count() > 0 ? true : false,
                            'detail' => array(),
                            'seri' => $seri
                        );

                        foreach ($d->DetailPesananProdukDsb as $key_prd => $e) {
                            $item_dsb[$key_paket]['detail'][$key_prd] = array(
                                'id' => $e->id,
                                'gbj_id' => $e->GudangBarangJadi->id,
                                'nama' => $e->GudangBarangJadi->Produk->nama,
                                'variasi' => $e->GudangBarangJadi->nama,
                            );
                        }
                    }
                    $data = $item_dsb;
                } else {
                    foreach ($ekatalog->Pesanan->DetailPesananDsb as $key_paket => $d) {
                        if ($d->NoseriDsb->isEmpty()) {
                            $seri =    "";
                        } else {
                            $seri =    implode(',', collect($d->NoseriDsb->pluck("noseri"))->toArray());
                        }

                        $item_dsb[$key_paket] = array(
                            'jenis' => 'dsb',
                            'id' => $d->id,
                            'detail_rencana_penjualan_id' => 0,
                            'penjualan_produk_id' => $d->penjualan_produk_id,
                            'nama' => $d->PenjualanProduk->nama,
                            'jumlah' => $d->jumlah,
                            'harga' => $d->harga,
                            'ongkir' => $d->ongkir,
                            'ppn' => $d->ppn,
                            'kalibrasi' => 0,
                            'is_kalibrasi' => $d->PenjualanProduk->produk->whereNotNull('kode_lab_id')->count() > 0 ? 'gen' : 'not_gen',
                            'detail' => array(),
                            'seri' => $seri
                        );

                        foreach ($d->DetailPesananProdukDsb as $key_prd => $e) {
                            $item_dsb[$key_paket]['detail'][$key_prd] = array(
                                'id' => $e->id,
                                'gbj_id' => $e->GudangBarangJadi->id,
                                'nama' => $e->GudangBarangJadi->Produk->nama,
                                'variasi' => $e->GudangBarangJadi->nama,
                            );
                        }
                    }
                    $data = array_merge($item, $item_dsb);
                }
            }


            //return response()->json($data);
            return view('page.penjualan.penjualan.edit_ekatalog', ['e' => $ekatalog, 'item' => $data]);
        } else if ($jenis == 'spa') {
            $spa = Spa::find($id);
            $item = array();
            if ($spa->Pesanan->DetailPesananDsb->isEmpty() && $spa->Pesanan->DetailPesanan->isEmpty()) {
                $data = array();
            } else {
                foreach ($spa->Pesanan->DetailPesanan as $key_paket => $d) {
                    $item[$key_paket] = array(
                        'jenis' => 'po',
                        'id' => $d->id,
                        'detail_rencana_penjualan_id' => 0,
                        'penjualan_produk_id' => $d->penjualan_produk_id,
                        'nama' => $d->PenjualanProduk->nama,
                        'jumlah' => $d->jumlah,
                        'harga' => $d->harga,
                        'ongkir' => $d->ongkir,
                        'kalibrasi' => $d->kalibrasi,
                        'is_kalibrasi' => $d->PenjualanProduk->produk->whereNotNull('kode_lab_id')->count() > 0 ? 'gen' : 'not_gen',
                        'ppn' => $d->ppn,
                        'detail' => array(),
                        'seri' =>  ""
                    );
                    foreach ($d->DetailPesananProduk as $key_prd => $e) {
                        $item[$key_paket]['detail'][$key_prd] = array(
                            'id' => $e->id,
                            'gbj_id' => $e->GudangBarangJadi->id,
                            'nama' => $e->GudangBarangJadi->Produk->nama,
                            'variasi' => $e->GudangBarangJadi->nama,

                        );
                    }
                }


                if ($spa->Pesanan->DetailPesananDsb->isEmpty()) {
                    $data = $item;
                } else {
                    foreach ($spa->Pesanan->DetailPesananDsb as $key_paket => $d) {
                        if ($d->NoseriDsb->isEmpty()) {
                            $seri =    "";
                        } else {
                            $seri =    implode(',', collect($d->NoseriDsb->pluck("noseri"))->toArray());
                        }

                        $item_dsb[$key_paket] = array(
                            'jenis' => 'dsb',
                            'id' => $d->id,
                            'detail_rencana_penjualan_id' => 0,
                            'penjualan_produk_id' => $d->penjualan_produk_id,
                            'nama' => $d->PenjualanProduk->nama,
                            'jumlah' => $d->jumlah,
                            'harga' => $d->harga,
                            'ongkir' => $d->ongkir,
                            'ppn' => $d->ppn,
                            'kalibrasi' => 0,
                            'is_kalibrasi' => $d->PenjualanProduk->produk->whereNotNull('kode_lab_id')->count() > 0 ? true : false,
                            'detail' => array(),
                            'seri' => $seri
                        );

                        foreach ($d->DetailPesananProdukDsb as $key_prd => $e) {
                            $item_dsb[$key_paket]['detail'][$key_prd] = array(
                                'id' => $e->id,
                                'gbj_id' => $e->GudangBarangJadi->id,
                                'nama' => $e->GudangBarangJadi->Produk->nama,
                                'variasi' => $e->GudangBarangJadi->nama,
                            );
                        }
                    }
                    $data = array_merge($item, $item_dsb);
                }
            }
            // return response()->json($data);
            return view('page.penjualan.penjualan.edit_spa', ['e' => $spa, 'item' => $data]);
        } else {
            $spb = Spb::find($id);
            $item = array();
            if ($spb->Pesanan->DetailPesananDsb->isEmpty() && $spb->Pesanan->DetailPesanan->isEmpty()) {
                $data = array();
            } else {
                foreach ($spb->Pesanan->DetailPesanan as $key_paket => $d) {
                    $item[$key_paket] = array(
                        'jenis' => 'po',
                        'id' => $d->id,
                        'detail_rencana_penjualan_id' => 0,
                        'penjualan_produk_id' => $d->penjualan_produk_id,
                        'nama' => $d->PenjualanProduk->nama,
                        'jumlah' => $d->jumlah,
                        'harga' => $d->harga,
                        'ongkir' => $d->ongkir,
                        'kalibrasi' => $d->kalibrasi,
                        'is_kalibrasi' => $d->PenjualanProduk->produk->whereNotNull('kode_lab_id')->count() > 0 ? true : false,
                        'ppn' => $d->ppn,
                        'detail' => array(),
                        'seri' =>  ""
                    );
                    foreach ($d->DetailPesananProduk as $key_prd => $e) {
                        $item[$key_paket]['detail'][$key_prd] = array(
                            'id' => $e->id,
                            'gbj_id' => $e->GudangBarangJadi->id,
                            'nama' => $e->GudangBarangJadi->Produk->nama,
                            'variasi' => $e->GudangBarangJadi->nama,

                        );
                    }
                }


                if ($spb->Pesanan->DetailPesananDsb->isEmpty()) {
                    $data = $item;
                } else {
                    foreach ($spb->Pesanan->DetailPesananDsb as $key_paket => $d) {
                        if ($d->NoseriDsb->isEmpty()) {
                            $seri =    "";
                        } else {
                            $seri =    implode(',', collect($d->NoseriDsb->pluck("noseri"))->toArray());
                        }

                        $item_dsb[$key_paket] = array(
                            'jenis' => 'dsb',
                            'id' => $d->id,
                            'detail_rencana_penjualan_id' => 0,
                            'penjualan_produk_id' => $d->penjualan_produk_id,
                            'nama' => $d->PenjualanProduk->nama,
                            'jumlah' => $d->jumlah,
                            'harga' => $d->harga,
                            'ongkir' => $d->ongkir,
                            'ppn' => $d->ppn,
                            'kalibrasi' => 0,
                            'is_kalibrasi' => $d->PenjualanProduk->produk->whereNotNull('kode_lab_id')->count() > 0 ? true : false,
                            'detail' => array(),
                            'seri' => $seri
                        );

                        foreach ($d->DetailPesananProdukDsb as $key_prd => $e) {
                            $item_dsb[$key_paket]['detail'][$key_prd] = array(
                                'id' => $e->id,
                                'gbj_id' => $e->GudangBarangJadi->id,
                                'nama' => $e->GudangBarangJadi->Produk->nama,
                                'variasi' => $e->GudangBarangJadi->nama,
                            );
                        }
                    }
                    $data = array_merge($item, $item_dsb);
                }
            }

            return view('page.penjualan.penjualan.edit_spb', ['e' => $spb, 'item' => $data]);
        }
    }
    public function update_ekatalog(Request $request, $id)
    {

        // dd($request->all());


        $tahunSekarang = Carbon::now()->format('Y');
        $periode = AktifPeriode::first()->tahun;

        //   if($tahunSekarang !=  $periode){
        //       $month = mt_rand(1, 12); // Random month between 1 and 12
        //       $day = mt_rand(1, Carbon::createFromDate($periode, $month)->daysInMonth); // Random day within the chosen month
        //       // Create Carbon instance with the random date
        //       $randomDate = Carbon::createFromDate( $periode, $month, $day)->toDateTimeString();
        //   }else{
        //       $randomDate =  Carbon::now()->toDateTimeString();
        //   }

        if ($request->status_akn == 'sepakat' && ($request->namadistributor == 'belum' || $request->provinsi == "NULL")) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }

        if ($request->status == 'sepakat' && ($request->perusahaan_pengiriman == NULL || $request->alamat_pengiriman == NULL ||  $request->kemasan == NULL)) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }

        // echo json_encode($request->all());
        if ($request->namadistributor == 'belum') {
            $c_id = '484';
        } else {
            $c_id = $request->customer_id;
        }

        $ekatalog = Ekatalog::find($id);

        if ($request->status_akn == 'draft' ||  $request->status_akn == 'batal') {
            if ($request->no_paket == '') {
                $c_akn = NULL;
            } else {
                $c_akn = $request->has('isi_nopaket') ? $request->jenis_paket . $request->no_paket : NULL;
            }

            $akn = $c_akn;
        } else {
            $akn = $request->jenis_paket . $request->no_paket;
        }

        $poid = $ekatalog->pesanan_id;
        $ekatalog->customer_id = $c_id;
        $ekatalog->provinsi_id = $request->provinsi == "NULL" ? NULL : $request->provinsi;
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

        $p = Pesanan::find($poid);
        if ($p->so == NULL && $request->no_po_ekat != NULL && ($request->status_akn != "draft" || $request->status_akn != "batal")) {
            $p->so = $this->createSObyPeriod('EKAT', $periode);
        }
        $p->no_po = $request->no_po_ekat;
        $p->tgl_po = $request->tanggal_po_ekat;
        $p->no_do = $request->no_do_ekat;
        $p->tgl_do = $request->tanggal_do_ekat;
        $p->tujuan_kirim = $request->perusahaan_pengiriman;
        $p->alamat_kirim = $request->alamat_pengiriman;
        $p->kemasan = $request->kemasan;
        $p->ekspedisi_id = $request->ekspedisi;
        $p->ket_kirim = $request->keterangan_pengiriman;
        $p->ket = $request->keterangan_ekat;


        if ($request->status_akn == "sepakat" && $request->no_po_ekat != NULL) {
            $p->log_id = "9";
        } elseif ($request->status_akn == "batal") {
            $p->log_id = "20";
        }
        $p->save();

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


            $dsb = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->get();

            if (count($dsb) > 0) {
                $deldspap = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->delete();
                if (!$deldspap) {
                    $bool = false;
                }
            }

            $dsbpa = DetailPesananDsb::where('pesanan_id', $poid)->get();
            if (count($dsbpa) > 0) {
                $deldsbpa = DetailPesananDsb::where('pesanan_id', $poid)->delete();
                if (!$deldsbpa) {
                    $bool = false;
                }
            }

            if ($bool == true) {
                if (($request->status_akn == "sepakat") || ($request->status_akn == "negosiasi")) {
                    if (isset($request->stok_distributor)) {

                        //Distributor
                        if (isset($request->stok_distributor)) {
                            $penjualan_produk_id  = array_values(array_diff_key($request->penjualan_produk_id, $request->stok_distributor));
                            $variasi  = array_values(array_diff_key($request->variasi, $request->stok_distributor));
                            $produk_jumlah  = array_values(array_diff_key($request->produk_jumlah, $request->stok_distributor));
                            $produk_harga  = array_values(array_diff_key($request->produk_harga, $request->stok_distributor));
                            $produk_ppn  = array_values(array_diff_key($request->produk_ppn, $request->stok_distributor));
                            $produk_ongkir  = array_values(array_diff_key($request->produk_ongkir, $request->stok_distributor));
                            $produk_ongkir  = array_values(array_diff_key($request->produk_ongkir, $request->stok_distributor));

                            foreach ($request->stok_distributor as $key) {
                                if (isset($request->penjualan_produk_id[$key])) {
                                    $penjualan_produk_id_dsb[] = $request->penjualan_produk_id[$key];
                                }
                                if (isset($request->variasi[$key])) {
                                    $variasi_dsb[] = $request->variasi[$key];
                                }
                                if (isset($request->produk_jumlah[$key])) {
                                    $produk_jumlah_dsb[] = $request->produk_jumlah[$key];
                                }
                                if (isset($request->produk_harga[$key])) {
                                    $produk_harga_dsb[] = $request->produk_harga[$key];
                                }
                                if (isset($request->produk_ongkir[$key])) {
                                    $produk_ongkir_dsb[] = $request->produk_ongkir[$key];
                                }
                                if (isset($request->produk_ppn[$key])) {
                                    $produk_ppn_dsb[] = $request->produk_ppn[$key];
                                }
                                if (isset($request->noSeriDistributor[$key])) {
                                    $noseri_dsb[] = $request->noSeriDistributor[$key];
                                }
                            }


                            for ($i = 0; $i < count($penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id[$i],
                                    'jumlah' => $produk_jumlah[$i],
                                    'ppn' => isset($produk_ppn[$i]) ? $produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga[$i]),
                                    'ongkir' =>  str_replace('.', "", $produk_ongkir[$i]),
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                ]);

                                for ($j = 0; $j < count($variasi[$i]); $j++) {
                                    DetailPesananProduk::create([
                                        'detail_pesanan_id' => $dspa->id,
                                        'gudang_barang_jadi_id' => $variasi[$i][$j]
                                    ]);
                                }
                            }



                            for ($i = 0; $i < count($penjualan_produk_id_dsb); $i++) {
                                $dsb = DetailPesananDsb::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id_dsb[$i],
                                    'jumlah' => $produk_jumlah_dsb[$i],
                                    'ppn' => isset($produk_ppn_dsb[$i]) ? $produk_ppn_dsb[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga_dsb[$i]),
                                    'ongkir' =>  str_replace('.', "", $produk_ongkir_dsb[$i])
                                ]);

                                if (isset($noseri_dsb[$i])) {
                                    $noseri = explode(',', $noseri_dsb[$i]);
                                    for ($j = 0; $j < count($noseri); $j++) {
                                        NoseriDsb::create([
                                            'detail_pesanan_dsb' => $dsb->id,
                                            'noseri' => $noseri[$j]
                                        ]);
                                    }
                                }
                                for ($j = 0; $j < count($variasi_dsb[$i]); $j++) {
                                    DetailPesananProdukDsb::create([
                                        'detail_pesanan_dsb_id' => $dsb->id,
                                        'gudang_barang_jadi_id' => $variasi_dsb[$i][$j]
                                    ]);
                                }
                            }
                        } else {
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
                                    'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
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
                                'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                //'kalibrasi' => $kalibrasi,
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
                } elseif (($request->status_akn == "draft") || ($request->status_akn == "batal")) {
                    if ($request->isi_produk == "isi") {
                        if (isset($request->stok_distributor)) {
                            $penjualan_produk_id  = array_values(array_diff_key($request->penjualan_produk_id, $request->stok_distributor));
                            $variasi  = array_values(array_diff_key($request->variasi, $request->stok_distributor));
                            $produk_jumlah  = array_values(array_diff_key($request->produk_jumlah, $request->stok_distributor));
                            $produk_harga  = array_values(array_diff_key($request->produk_harga, $request->stok_distributor));
                            $produk_ppn  = array_values(array_diff_key($request->produk_ppn, $request->stok_distributor));
                            $produk_ongkir  = array_values(array_diff_key($request->produk_ongkir, $request->stok_distributor));

                            foreach ($request->stok_distributor as $key) {
                                if (isset($request->penjualan_produk_id[$key])) {
                                    $penjualan_produk_id_dsb[] = $request->penjualan_produk_id[$key];
                                }
                                if (isset($request->variasi[$key])) {
                                    $variasi_dsb[] = $request->variasi[$key];
                                }
                                if (isset($request->produk_jumlah[$key])) {
                                    $produk_jumlah_dsb[] = $request->produk_jumlah[$key];
                                }
                                if (isset($request->produk_harga[$key])) {
                                    $produk_harga_dsb[] = $request->produk_harga[$key];
                                }
                                if (isset($request->produk_ongkir[$key])) {
                                    $produk_ongkir_dsb[] = $request->produk_ongkir[$key];
                                }
                                if (isset($request->produk_ppn[$key])) {
                                    $produk_ppn_dsb[] = $request->produk_ppn[$key];
                                }
                                if (isset($request->noSeriDistributor[$key])) {
                                    $noseri_dsb[] = $request->noSeriDistributor[$key];
                                }
                            }


                            for ($i = 0; $i < count($penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id[$i],
                                    'jumlah' => $produk_jumlah[$i],
                                    'ppn' => isset($produk_ppn[$i]) ? $produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga[$i]),
                                    'ongkir' =>  str_replace('.', "", $produk_ongkir[$i])
                                ]);

                                for ($j = 0; $j < count($variasi[$i]); $j++) {
                                    DetailPesananProduk::create([
                                        'detail_pesanan_id' => $dspa->id,
                                        'gudang_barang_jadi_id' => $variasi[$i][$j]
                                    ]);
                                }
                            }



                            for ($i = 0; $i < count($penjualan_produk_id_dsb); $i++) {
                                $dsb = DetailPesananDsb::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id_dsb[$i],
                                    'jumlah' => $produk_jumlah_dsb[$i],
                                    'ppn' => isset($produk_ppn_dsb[$i]) ? $produk_ppn_dsb[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga_dsb[$i]),
                                    'ongkir' =>  str_replace('.', "", $produk_ongkir_dsb[$i])
                                ]);

                                if (isset($noseri_dsb[$i])) {
                                    $noseri = explode(',', $noseri_dsb[$i]);
                                    for ($j = 0; $j < count($noseri); $j++) {
                                        NoseriDsb::create([
                                            'detail_pesanan_dsb' => $dsb->id,
                                            'noseri' => $noseri[$j]
                                        ]);
                                    }
                                }
                                for ($j = 0; $j < count($variasi_dsb[$i]); $j++) {
                                    DetailPesananProdukDsb::create([
                                        'detail_pesanan_dsb_id' => $dsb->id,
                                        'gudang_barang_jadi_id' => $variasi_dsb[$i][$j]
                                    ]);
                                }
                            }
                        } else {
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
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                    'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
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
                }
            } else {
                $bool = false;
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } else if ($bool == false) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
    }
    public function update_spa(Request $request, $id)
    {
        if ($request->perusahaan_pengiriman_nonakn == NULL || $request->alamat_pengiriman == NULL ||  $request->kemasan == NULL) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
        $spa = Spa::find($id);
        $poid = $spa->pesanan_id;
        $spa->customer_id = $request->customer_id;
        $uspa = $spa->save();
        $bool = true;
        if ($uspa) {
            $pesanan = Pesanan::find($spa->pesanan_id);
            $pesanan->no_do = $request->no_do;
            $pesanan->tgl_do = $request->tanggal_do;
            $pesanan->no_po = $request->no_po;
            $pesanan->ket = $request->keterangan;
            $pesanan->tujuan_kirim = $request->perusahaan_pengiriman_nonakn;
            $pesanan->alamat_kirim = $request->alamat_pengiriman;
            $pesanan->kemasan = $request->kemasan;
            $pesanan->ket_kirim = $request->keterangan_pengiriman;
            $pesanan->ekspedisi_id = $request->ekspedisi;
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
                $dsb = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();

                if (count($dsb) > 0) {
                    $deldspap = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                    if (!$deldspap) {
                        $bool = false;
                    }
                }

                $dsbpa = DetailPesananDsb::where('pesanan_id', $poid)->get();
                if (count($dsbpa) > 0) {
                    $deldsbpa = DetailPesananDsb::where('pesanan_id', $poid)->delete();
                    if (!$deldsbpa) {
                        $bool = false;
                    }
                }

                if ($request->jenis_pen) {
                    if (in_array("produk", $request->jenis_pen)) {
                        //Distributor
                        if (isset($request->stok_distributor)) {


                            $penjualan_produk_id  = array_values(array_diff_key($request->penjualan_produk_id, $request->stok_distributor));
                            $variasi  = array_values(array_diff_key($request->variasi, $request->stok_distributor));
                            $produk_jumlah  = array_values(array_diff_key($request->produk_jumlah, $request->stok_distributor));
                            $produk_harga  = array_values(array_diff_key($request->produk_harga, $request->stok_distributor));
                            $produk_ppn  = array_values(array_diff_key($request->produk_ppn, $request->stok_distributor));


                            foreach ($request->stok_distributor as $key) {
                                if (isset($request->penjualan_produk_id[$key])) {
                                    $penjualan_produk_id_dsb[] = $request->penjualan_produk_id[$key];
                                }
                                if (isset($request->variasi[$key])) {
                                    $variasi_dsb[] = $request->variasi[$key];
                                }
                                if (isset($request->produk_jumlah[$key])) {
                                    $produk_jumlah_dsb[] = $request->produk_jumlah[$key];
                                }
                                if (isset($request->produk_harga[$key])) {
                                    $produk_harga_dsb[] = $request->produk_harga[$key];
                                }
                                if (isset($request->produk_ongkir[$key])) {
                                    $produk_ongkir_dsb[] = $request->produk_ongkir[$key];
                                }
                                if (isset($request->produk_ppn[$key])) {
                                    $produk_ppn_dsb[] = $request->produk_ppn[$key];
                                }
                                if (isset($request->noSeriDistributor[$key])) {
                                    $noseri_dsb[] = $request->noSeriDistributor[$key];
                                }
                            }

                            for ($i = 0; $i < count($penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id[$i],
                                    'jumlah' => $produk_jumlah[$i],
                                    'ppn' => isset($produk_ppn[$i]) ? $produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga[$i]),
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                ]);

                                for ($j = 0; $j < count($variasi[$i]); $j++) {
                                    DetailPesananProduk::create([
                                        'detail_pesanan_id' => $dspa->id,
                                        'gudang_barang_jadi_id' => $variasi[$i][$j]
                                    ]);
                                }
                            }


                            for ($i = 0; $i < count($penjualan_produk_id_dsb); $i++) {
                                $dsb = DetailPesananDsb::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id_dsb[$i],
                                    'jumlah' => $produk_jumlah_dsb[$i],
                                    'ppn' => isset($produk_ppn_dsb[$i]) ? $produk_ppn_dsb[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga_dsb[$i]),
                                ]);

                                if (isset($noseri_dsb[$i])) {
                                    $noseri = explode(',', $noseri_dsb[$i]);
                                    for ($j = 0; $j < count($noseri); $j++) {
                                        NoseriDsb::create([
                                            'detail_pesanan_dsb' => $dsb->id,
                                            'noseri' => $noseri[$j]
                                        ]);
                                    }
                                }

                                for ($j = 0; $j < count($variasi_dsb[$i]); $j++) {
                                    DetailPesananProdukDsb::create([
                                        'detail_pesanan_dsb_id' => $dsb->id,
                                        'gudang_barang_jadi_id' => $variasi_dsb[$i][$j]
                                    ]);
                                }
                            }
                        } else {
                            if ($dspa) {
                                for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                                    $c = DetailPesanan::create([
                                        'pesanan_id' => $poid,
                                        'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                        'jumlah' => $request->produk_jumlah[$i],
                                        'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                        'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                        'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                        // 'kalibrasi' => $kalibrasi,
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
                                    'ppn' => isset($request->part_ppn[$i]) ? $request->part_ppn[$i] : 0,
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
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } else if ($bool == false) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
    }
    public function update_spb(Request $request, $id)
    {
        if ($request->perusahaan_pengiriman_nonakn == NULL || $request->alamat_pengiriman == NULL ||  $request->kemasan == NULL) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
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
            $pesanan->tujuan_kirim = $request->perusahaan_pengiriman_nonakn;
            $pesanan->alamat_kirim = $request->alamat_pengiriman;
            $pesanan->kemasan = $request->kemasan;
            $pesanan->ket_kirim = $request->keterangan_pengiriman;
            $pesanan->ekspedisi_id = $request->ekspedisi;
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


                $dsb = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();

                if (count($dsb) > 0) {
                    $deldspap = DetailPesananProdukDsb::whereHas('DetailPesananDsb', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                    if (!$deldspap) {
                        $bool = false;
                    }
                }

                $dsbpa = DetailPesananDsb::where('pesanan_id', $poid)->get();
                if (count($dsbpa) > 0) {
                    $deldsbpa = DetailPesananDsb::where('pesanan_id', $poid)->delete();
                    if (!$deldsbpa) {
                        $bool = false;
                    }
                }


                if ($request->jenis_pen) {
                    if (in_array("produk", $request->jenis_pen)) {
                        //Distributor

                        if (isset($request->stok_distributor)) {


                            $penjualan_produk_id  = array_values(array_diff_key($request->penjualan_produk_id, $request->stok_distributor));
                            $variasi  = array_values(array_diff_key($request->variasi, $request->stok_distributor));
                            $produk_jumlah  = array_values(array_diff_key($request->produk_jumlah, $request->stok_distributor));
                            $produk_harga  = array_values(array_diff_key($request->produk_harga, $request->stok_distributor));
                            $produk_ppn  = array_values(array_diff_key($request->produk_ppn, $request->stok_distributor));


                            foreach ($request->stok_distributor as $key) {
                                if (isset($request->penjualan_produk_id[$key])) {
                                    $penjualan_produk_id_dsb[] = $request->penjualan_produk_id[$key];
                                }
                                if (isset($request->variasi[$key])) {
                                    $variasi_dsb[] = $request->variasi[$key];
                                }
                                if (isset($request->produk_jumlah[$key])) {
                                    $produk_jumlah_dsb[] = $request->produk_jumlah[$key];
                                }
                                if (isset($request->produk_harga[$key])) {
                                    $produk_harga_dsb[] = $request->produk_harga[$key];
                                }
                                if (isset($request->produk_ongkir[$key])) {
                                    $produk_ongkir_dsb[] = $request->produk_ongkir[$key];
                                }
                                if (isset($request->produk_ppn[$key])) {
                                    $produk_ppn_dsb[] = $request->produk_ppn[$key];
                                }
                                if (isset($request->noSeriDistributor[$key])) {
                                    $noseri_dsb[] = $request->noSeriDistributor[$key];
                                }
                            }

                            for ($i = 0; $i < count($penjualan_produk_id); $i++) {
                                $dspa = DetailPesanan::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id[$i],
                                    'jumlah' => $produk_jumlah[$i],
                                    'ppn' => isset($produk_ppn[$i]) ? $produk_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga[$i]),
                                    'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
                                ]);

                                for ($j = 0; $j < count($variasi[$i]); $j++) {
                                    DetailPesananProduk::create([
                                        'detail_pesanan_id' => $dspa->id,
                                        'gudang_barang_jadi_id' => $variasi[$i][$j]
                                    ]);
                                }
                            }


                            for ($i = 0; $i < count($penjualan_produk_id_dsb); $i++) {
                                $dsb = DetailPesananDsb::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $penjualan_produk_id_dsb[$i],
                                    'jumlah' => $produk_jumlah_dsb[$i],
                                    'ppn' => isset($produk_ppn_dsb[$i]) ? $produk_ppn_dsb[$i] : 0,
                                    'harga' => str_replace('.', "", $produk_harga_dsb[$i]),
                                ]);

                                if (isset($noseri_dsb[$i])) {
                                    $noseri = explode(',', $noseri_dsb[$i]);
                                    for ($j = 0; $j < count($noseri); $j++) {
                                        NoseriDsb::create([
                                            'detail_pesanan_dsb' => $dsb->id,
                                            'noseri' => $noseri[$j]
                                        ]);
                                    }
                                }

                                for ($j = 0; $j < count($variasi_dsb[$i]); $j++) {
                                    DetailPesananProdukDsb::create([
                                        'detail_pesanan_dsb_id' => $dsb->id,
                                        'gudang_barang_jadi_id' => $variasi_dsb[$i][$j]
                                    ]);
                                }
                            }
                        } else {
                            if ($dspa) {
                                for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                                    $c = DetailPesanan::create([
                                        'pesanan_id' => $poid,
                                        'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                        'jumlah' => $request->produk_jumlah[$i],
                                        'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                        'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                        'kalibrasi' => isset($request->produk_kalibrasi[$i]) ? $request->produk_kalibrasi[$i] : 0,
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
                                    'ppn' => isset($request->part_ppn[$i]) ? $request->part_ppn[$i] : 0,
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
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } else if ($bool == false) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
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
                    $po->ket = $request->keterangan;
                    $pou = $po->save();
                    if (!$pou) {
                        $bool = false;
                    }
                } else if (empty($request->no_do) && empty($request->tgl_do)) {
                    $po->no_do = "";
                    $po->tgl_do = NULL;
                    $po->ket = $request->keterangan;
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
                $po->ket = $request->keterangan;
                $pou = $po->save();

                if (!$pou) {
                    $bool = false;
                }
            } else if (empty($request->no_do) && empty($request->tgl_do)) {
                $po->no_do = "";
                $po->tgl_do = NULL;
                $po->ket = $request->keterangan;
                $pou = $po->save();
                $bool = true;
            } else {
                $bool = false;
            }
        }

        if ($bool == true) {
            return response()->json(['data' => 'success'], 200);
        } else if ($bool == false) {
            return response()->json(['data' => 'error'], 500);
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
        $data = "";
        if ($jenis == "EKAT") {
            $data = Ekatalog::where('id', $id)->with('Pesanan')->first();
        } else if ($jenis == "SPA") {
            $data = Spa::where('id', $id)->with('Pesanan')->first();
        } else if ($jenis == "SPB") {
            $data = Spb::where('id', $id)->with('Pesanan')->first();
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
    // public function  get_data_laporan_penjualan($penjualan, $distributor, $tanggal_awal, $tanggal_akhir)
    // {

    //     $x = ['spa'];
    //     $penjualan = 'spa';

    //     if ($distributor == 'semua') {
    //         if ($x == ['ekatalog', 'spa', 'spb']) {
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->whereRaw('TRIM(no_po) <> ""')
    //             // ->wherenotnull('no_po');
    //         } else if ($x == ['ekatalog', 'spa']) {
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spb = 0')
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->whereRaw('TRIM(no_po) <> ""')
    //             // ->wherenotnull('no_po')

    //         } else if ($x == ['ekatalog', 'spb']) {
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spa = 0')
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->whereRaw('TRIM(no_po) <> ""')
    //             // ->wherenotnull('no_po');

    //         } else if ($x == ['spa', 'spb']) {
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('ekat = 0')
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->whereRaw('TRIM(no_po) <> ""')
    //             // ->wherenotnull('no_po');

    //         } else if ($penjualan == 'ekatalog') {
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('ekat > 0')
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->whereRaw('TRIM(no_po) <> ""')
    //             // ->wherenotnull('no_po');

    //         } else if ($penjualan == 'spa') {
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spa > 0')
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             //  ->whereRaw('TRIM(no_po) <> ""')
    //             // ->wherenotnull('no_po');

    //         } else if ($penjualan == 'spb') {
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spb > 0')
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->whereRaw('TRIM(no_po) <> ""')
    //             // ->wherenotnull('no_po');

    //         }
    //     } else {


    //         if ($x == ['ekatalog', 'spa', 'spb']) {

    //             $ekt_id = Ekatalog::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();
    //             $spa_id = Spa::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();
    //             $spb_id = Spb::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();

    //             $collection1 = collect($ekt_id);
    //             $collection2 = collect($spa_id);
    //             $collection3 = collect($spb_id);

    //             $mergedCollection = $collection1->merge($collection2)->merge($collection3);

    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
    //                 // ->whereRaw('TRIM(no_po) <> ""')
    //                 ->whereIN('id', $mergedCollection);
    //             // ->wherenotnull('no_po');

    //         } else if ($x == ['ekatalog', 'spa']) {
    //             $ekt_id = Ekatalog::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();
    //             $spa_id = Spa::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();


    //             $collection1 = collect($ekt_id);
    //             $collection2 = collect($spa_id);


    //             $mergedCollection = $collection1->merge($collection2);
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spb = 0')
    //                 // ->whereRaw('TRIM(no_po) <> ""')
    //                 ->whereIN('id', $mergedCollection)
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->wherenotnull('no_po');

    //         } else if ($x == ['ekatalog', 'spb']) {
    //             $ekt_id = Ekatalog::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();
    //             $spb_id = Spb::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();

    //             $collection1 = collect($ekt_id);
    //             $collection2 = collect($spb_id);


    //             $mergedCollection = $collection1->merge($collection2);

    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spa = 0')
    //                 // ->whereRaw('TRIM(no_po) <> ""')
    //                 ->whereIN('id', $mergedCollection)
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->wherenotnull('no_po');

    //         } else if ($x == ['spa', 'spb']) {


    //             $spa_id = Spa::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();
    //             $spb_id = Spb::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();

    //             $collection1 = collect($spa_id);
    //             $collection2 = collect($spb_id);


    //             $mergedCollection = $collection1->merge($collection2);
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('ekat = 0')
    //                 // ->whereRaw('TRIM(no_po) <> ""')
    //                 ->whereIn('pesanan.id', $mergedCollection)
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->wherenotnull('no_po');
    //         } else if ($penjualan == 'ekatalog') {

    //             $ekt_id = Ekatalog::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();

    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('ekat > 0')
    //                 // ->whereRaw('TRIM(no_po) <> ""')
    //                 ->whereIn('pesanan.id', $ekt_id)
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->wherenotnull('no_po');
    //         } else if ($penjualan == 'spa') {
    //             $spa_id = Spa::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();

    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spa > 0')
    //                 //  ->whereRaw('TRIM(no_po) <> ""')
    //                 ->whereIn('pesanan.id', $spa_id)
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
    //             // ->wherenotnull('no_po');

    //         } else if ($penjualan == 'spb') {
    //             $spb_id = Spb::where('customer_id', $distributor)->pluck('pesanan_id')->toArray();
    //             //GET PESANAN
    //             $data = Pesanan::addSelect([
    //                 'spa' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spa.id),0)')
    //                         ->from('spa')
    //                         ->whereColumn('spa.pesanan_id', 'pesanan.id');
    //                 },
    //                 'spb' => function ($q) {
    //                     $q->selectRaw('coalesce(count(spb.id),0)')
    //                         ->from('spb')
    //                         ->whereColumn('spb.pesanan_id', 'pesanan.id');
    //                 },
    //                 'ekat' => function ($q) {
    //                     $q->selectRaw('coalesce(count(ekatalog.id),0)')
    //                         ->from('ekatalog')
    //                         ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
    //                 }
    //             ])
    //                 ->havingRaw('spb > 0')
    //                 //  ->whereRaw('TRIM(no_po) <> ""')
    //                 ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
    //                 ->whereIn('pesanan.id', $spb_id);
    //             // ->wherenotnull('no_po');
    //         }
    //     }
    //     $pesananIds = $data->pluck('id')->toArray();

    //     $data_dpp = DetailPesananProduk::leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //         ->whereIN('detail_pesanan.pesanan_id', $pesananIds);

    //     $dppIds = $data_dpp->pluck('detail_pesanan_produk.id')->toArray();

    //     $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
    //         ->selectRaw('"-" AS no_paket')
    //         ->selectRaw('"-" AS instansi')
    //         ->selectRaw('"-" AS alamat_instansi')
    //         ->selectRaw('"-" AS status')
    //         ->selectRaw('"-" AS satuan')
    //         ->selectRaw('"-" AS no_urut')
    //         ->selectRaw('"-" AS tgl_buat')
    //         ->selectRaw('"-" AS tgl_kontrak')
    //         ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
    //         ->whereIn('spb.pesanan_id', $pesananIds)->get();

    //     $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
    //         ->selectRaw('"-" AS no_paket')
    //         ->selectRaw('"-" AS instansi')
    //         ->selectRaw('"-" AS alamat_instansi')
    //         ->selectRaw('"-" AS status')
    //         ->selectRaw('"-" AS satuan')
    //         ->selectRaw('"-" AS no_urut')
    //         ->selectRaw('"-" AS tgl_buat')
    //         ->selectRaw('"-" AS tgl_kontrak')
    //         ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
    //         ->whereIn('spa.pesanan_id', $pesananIds)->get();

    //     $ekatalog = Ekatalog::select(
    //         DB::raw("DATE_FORMAT(ekatalog.tgl_buat, '%d-%m-%Y') as tgl_buat"),
    //         DB::raw("DATE_FORMAT(ekatalog.tgl_kontrak, '%d-%m-%Y') as tgl_kontrak"),
    //         'ekatalog.pesanan_id as id',
    //         'ekatalog.ket',
    //         'ekatalog.no_urut as no_urut',
    //         'customer.nama',
    //         'ekatalog.no_paket',
    //         'ekatalog.instansi',
    //         'ekatalog.alamat as alamat_instansi',
    //         'ekatalog.satuan',
    //         'ekatalog.status'
    //     )
    //         ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
    //         ->whereIn('ekatalog.pesanan_id', $pesananIds)->get();

    //     $dataInfo =   $ekatalog->merge($spa)->merge($spb);

    //     //return response()->json($dataInfo);

    //     //GET SURAT JALAN
    //     $surat_jalan = Logistik::select('detail_pesanan.pesanan_id as id', 'nosurat', DB::raw("DATE_FORMAT(tgl_kirim, '%d-%m-%Y') as tgl_kirim"))
    //         ->leftJoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
    //         ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
    //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //         ->whereIN('detail_logistik.detail_pesanan_produk_id', $dppIds)
    //         ->groupBy('logistik.id')
    //         ->get();

    //     //GET SURAT JALAN PART
    //     $surat_jalan_part = Logistik::select('detail_pesanan_part.pesanan_id as id', 'nosurat', DB::raw("DATE_FORMAT(tgl_kirim, '%d-%m-%Y') as tgl_kirim"))
    //         ->leftJoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
    //         ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
    //         ->whereIN('detail_logistik_part.detail_pesanan_part_id', $dppIds)
    //         ->groupBy('logistik.id')
    //         ->get();

    //     //GET NOSERI
    //     $noseri = NoseriBarangJadi::select('detail_pesanan.id as id', 'detail_pesanan.penjualan_produk_id', 'noseri')
    //         ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
    //         ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
    //         ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
    //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
    //         ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();


    //     $noseriBatal = RiwayatBatalPoSeri::select('detail_pesanan.id as id', 'detail_pesanan.penjualan_produk_id', 'noseri_barang_jadi.noseri')
    //         ->leftJoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', '=', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
    //         ->leftJoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', '=', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
    //         ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'riwayat_batal_po_seri.noseri_id')
    //         ->leftJoin('riwayat_batal_po', 'riwayat_batal_po.id', '=', 'riwayat_batal_po_paket.riwayat_batal_po_id')
    //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'riwayat_batal_po_paket.detail_pesanan_id')
    //         ->whereIN('riwayat_batal_po.pesanan_id', $data->pluck('id')->toArray())->get();

    //     $noseriRetur = RiwayatReturPoSeri::select('detail_pesanan.id as id', 'detail_pesanan.penjualan_produk_id', 'noseri_barang_jadi.noseri')
    //         ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'riwayat_retur_po_seri.noseri_id')
    //         ->leftJoin('riwayat_retur_po_prd', 'riwayat_retur_po_prd.id', '=', 'riwayat_retur_po_seri.detail_riwayat_retur_prd_id')
    //         ->leftJoin('riwayat_retur_po_paket', 'riwayat_retur_po_paket.id', '=', 'riwayat_retur_po_prd.detail_riwayat_retur_paket_id')
    //         ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'riwayat_retur_po_paket.detail_pesanan_id')
    //         ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();

    //     //GET SPAREPART
    //     $detail_pesanan_part = DetailPesananPart::select(
    //         'detail_pesanan_part.id',
    //         'detail_pesanan_part.pesanan_id',
    //         'detail_pesanan_part.m_sparepart_id',
    //         'm_sparepart.nama',
    //         'detail_pesanan_part.harga',
    //         // DB::raw('(SELECT COALESCE((SUM(dp.jumlah) * dp.harga) + dp.ongkir, 0)
    //         // FROM detail_pesanan_part AS dp
    //         // WHERE dp.pesanan_id = detail_pesanan_part.pesanan_id
    //         // AND dp.m_sparepart_id = detail_pesanan_part.m_sparepart_id) AS harga'),
    //         DB::raw('(SELECT COALESCE(SUM(dp.jumlah), 0)
    //     FROM detail_pesanan_part AS dp
    //     WHERE dp.pesanan_id = detail_pesanan_part.pesanan_id
    //     AND dp.m_sparepart_id = detail_pesanan_part.m_sparepart_id) AS jumlah'),
    //         DB::raw('(SELECT COALESCE(SUM(dp.ongkir), 0)
    //     FROM detail_pesanan_part AS dp
    //     WHERE dp.pesanan_id = detail_pesanan_part.pesanan_id
    //     AND dp.m_sparepart_id = detail_pesanan_part.m_sparepart_id) AS ongkir'),
    //     )
    //         ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
    //         ->whereIN('detail_pesanan_part.pesanan_id', $data->pluck('id')->toArray())->get();


    //     //GET DETAIL PESANAN DSB
    //     $detail_pesanan_dsb = DetailPesananDsb::select(
    //         'detail_pesanan_dsb.id',
    //         'detail_pesanan_dsb.pesanan_id',
    //         'detail_pesanan_dsb.penjualan_produk_id',
    //         'penjualan_produk.nama as nama',
    //         'penjualan_produk.nama_alias as nama_alias',
    //         'detail_pesanan_dsb.harga',
    //         // DB::raw('(SELECT COALESCE((SUM(dp.jumlah) * dp.harga) + dp.ongkir, 0)
    //         // FROM detail_pesanan_dsb AS dp
    //         // WHERE dp.pesanan_id = detail_pesanan_dsb.pesanan_id
    //         // AND dp.penjualan_produk_id = detail_pesanan_dsb.penjualan_produk_id) AS harga'),
    //         DB::raw('(SELECT COALESCE(SUM(dp.jumlah), 0)
    //     FROM detail_pesanan_dsb AS dp
    //     WHERE dp.pesanan_id = detail_pesanan_dsb.pesanan_id
    //     AND dp.penjualan_produk_id = detail_pesanan_dsb.penjualan_produk_id) AS jumlah'),
    //         DB::raw('(SELECT COALESCE(SUM(dp.ongkir), 0)
    //     FROM detail_pesanan_dsb AS dp
    //     WHERE dp.pesanan_id = detail_pesanan_dsb.pesanan_id
    //     AND dp.penjualan_produk_id = detail_pesanan_dsb.penjualan_produk_id) AS ongkir')
    //     )
    //         ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan_dsb.penjualan_produk_id')
    //         ->whereIN('detail_pesanan_dsb.pesanan_id', $data->pluck('id')->toArray())->get();


    //     //GET DETAIL PESANAN
    //     $detail_pesanan = DetailPesanan::select(
    //         'detail_pesanan.id',
    //         'detail_pesanan.pesanan_id',
    //         'detail_pesanan.penjualan_produk_id',
    //         'penjualan_produk.nama as nama',
    //         'penjualan_produk.nama_alias as nama_alias',
    //         'detail_pesanan.harga',
    //         // DB::raw('(SELECT COALESCE((SUM(dp.jumlah) * dp.harga) + dp.ongkir, 0)
    //         // FROM detail_pesanan AS dp
    //         // WHERE dp.pesanan_id = detail_pesanan.pesanan_id
    //         // AND dp.penjualan_produk_id = detail_pesanan.penjualan_produk_id) AS harga'),
    //         DB::raw('(SELECT COALESCE(SUM(dp.jumlah), 0)
    //     FROM detail_pesanan AS dp
    //     WHERE dp.pesanan_id = detail_pesanan.pesanan_id
    //     AND dp.penjualan_produk_id = detail_pesanan.penjualan_produk_id) AS jumlah'),
    //         DB::raw('(SELECT COALESCE(SUM(dp.ongkir), 0)
    //     FROM detail_pesanan AS dp
    //     WHERE dp.pesanan_id = detail_pesanan.pesanan_id
    //     AND dp.penjualan_produk_id = detail_pesanan.penjualan_produk_id) AS ongkir'),
    //         DB::raw('(SELECT COALESCE(SUM(riwayat_batal_po_paket.jumlah), 0)
    //     FROM riwayat_batal_po_paket
    //     WHERE riwayat_batal_po_paket.detail_pesanan_id = detail_pesanan.id
    //     ) AS jumlah_batal'),
    //         DB::raw('(SELECT COALESCE(SUM(riwayat_retur_po_paket.jumlah), 0)
    //     FROM riwayat_retur_po_paket
    //     WHERE riwayat_retur_po_paket.detail_pesanan_id = detail_pesanan.id
    //     ) AS jumlah_retur')
    //     )
    //         ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
    //         ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();

    //     //GROUP DATA
    //     $groupedDataSeri = collect($noseri)->groupBy('id');
    //     $groupedDataSeriBatal = collect($noseriBatal)->groupBy('id');
    //     $groupedDataSeriRetur = collect($noseriRetur)->groupBy('id');
    //     $groupedDataPrd = collect($detail_pesanan)->groupBy('pesanan_id');
    //     $groupedDataPrdDsb = collect($detail_pesanan_dsb)->groupBy('pesanan_id');
    //     $groupedDataPart = collect($detail_pesanan_part)->groupBy('pesanan_id');
    //     $groupedDataSj = collect($surat_jalan)->groupBy('id')->toArray();
    //     $groupedDataSjPart = collect($surat_jalan_part)->groupBy('id')->toArray();
    //     $infoByID = [];
    //     foreach ($dataInfo as $infoItem) {
    //         $infoByID[$infoItem->id] = $infoItem;
    //     }

    //     //GROUP BY REF ID
    //     $noseri_group = $groupedDataSeri->map(function ($items, $key) {
    //         $uniqueItems = $items->unique('noseri')->values()->all();
    //         return [
    //             'id' => $key,
    //             'data' => $uniqueItems,
    //         ];
    //     })->values()->all();

    //     $noseri_groupBatal = $groupedDataSeriBatal->map(function ($items, $key) {
    //         $uniqueItems = $items->unique('noseri')->values()->all();
    //         return [
    //             'id' => $key,
    //             'data' => $uniqueItems,
    //         ];
    //     })->values()->all();

    //     $noseri_groupRetur = $groupedDataSeriRetur->map(function ($items, $key) {
    //         $uniqueItems = $items->unique('noseri')->values()->all();
    //         return [
    //             'id' => $key,
    //             'data' => $uniqueItems,
    //         ];
    //     })->values()->all();

    //     //GROUP BY REF ID
    //     $detail_pesanan_part_group = $groupedDataPart->map(function ($items, $key) {
    //         $uniqueItems = $items->unique('m_sparepart_id')->values()->all();
    //         return [
    //             'pesanan_id' => $key,
    //             'data' => $uniqueItems,
    //         ];
    //     })->values()->all();

    //     //GROUP BY REF ID
    //     $detail_pesanan_group = $groupedDataPrd->map(function ($items, $key) {
    //         $uniqueItems = $items->unique('penjualan_produk_id')->values()->all();
    //         return [
    //             'pesanan_id' => $key,
    //             'data' => $uniqueItems,
    //         ];
    //     })->values()->all();

    //     //GROUP BY REF ID DSB
    //     $detail_pesanan_dsb_group = $groupedDataPrdDsb->map(function ($items, $key) {
    //         $uniqueItems = $items->unique('penjualan_produk_id')->values()->all();
    //         return [
    //             'pesanan_id' => $key,
    //             'data' => $uniqueItems,
    //         ];
    //     })->values()->all();



    //     //SET NOSERI TO INDEX
    //     $seriByID = [];
    //     foreach ($noseri_group as $seriItem) {
    //         $seriByID[$seriItem['id']] = $seriItem['data'];
    //     }

    //     $seriBatalByID = [];
    //     foreach ($noseri_groupBatal as $seriItem) {
    //         $seriBatalByID[$seriItem['id']] = $seriItem['data'];
    //     }

    //     $seriReturByID = [];
    //     foreach ($noseri_groupRetur as $seriItem) {
    //         $seriReturByID[$seriItem['id']] = $seriItem['data'];
    //     }


    //     //SET INDEX NOSERI TO DETAIL PESANAN
    //     foreach ($detail_pesanan_group as $key => $pesananItem) {
    //         foreach ($pesananItem['data'] as $keys => $p) {
    //             $pesananID = $p['id'];
    //             if (isset($seriByID[$pesananID])) {
    //                 $detail_pesanan_group[$key]['data'][$keys]['seri'] = $seriByID[$pesananID];
    //             } else {
    //                 $detail_pesanan_group[$key]['data'][$keys]['seri'] = [];
    //             }

    //             if (isset($seriBatalByID[$pesananID])) {
    //                 $detail_pesanan_group[$key]['data'][$keys]['seri_batal'] = $seriBatalByID[$pesananID];
    //             } else {
    //                 $detail_pesanan_group[$key]['data'][$keys]['seri_batal'] = [];
    //             }

    //             if (isset($seriReturByID[$pesananID])) {
    //                 $detail_pesanan_group[$key]['data'][$keys]['seri_retur'] = $seriReturByID[$pesananID];
    //             } else {
    //                 $detail_pesanan_group[$key]['data'][$keys]['seri_retur'] = [];
    //             }
    //         }
    //     }

    //     //SET PESANAN
    //     foreach ($data->get() as $d) {
    //         $pesanan[] = array(
    //             'id' => $d->id,
    //             'so' => $d->so,
    //             'nama' => '-',
    //             'no_paket' => '-',
    //             'instansi' => '-',
    //             'alamat_instansi' => '-',
    //             'satuan' => '-',
    //             'no_urut' => '-',
    //             'tgl_buat' => '-',
    //             'tgl_kontrak' => '-',
    //             'status' => '-',
    //             'po' => $d->no_po,
    //             'tgl_po' => $d->tgl_po != null ? date('d-m-Y', strtotime($d->tgl_po)) : '-',
    //             'ket' => $d->ket,
    //             'log_id' => $d->log_id,
    //             'nosurat' => [],
    //             'nosurat_part' => []
    //         );
    //     }

    //     $produkByPesananId = [];
    //     $produkDsbByPesananId = [];
    //     $partByPesananId = [];

    //     foreach ($pesanan as &$pesananItem) {
    //         $pesananID = $pesananItem['id'];
    //         if (array_key_exists($pesananID, $groupedDataSj)) {
    //             // $pesanan[$key]['nosurat'] = $groupedDataSj[$pesananID];
    //             $pesananItem['nosurat'] = $groupedDataSj[$pesananID];
    //         }
    //     }

    //     foreach ($pesanan as  &$pesananItem) {
    //         $pesananID = $pesananItem['id'];
    //         if (array_key_exists($pesananID, $groupedDataSjPart)) {
    //             $pesananItem['nosurat_part'] = $groupedDataSjPart[$pesananID];
    //         }
    //     }

    //     foreach ($pesanan as  &$pesananItem) {
    //         $pesananID = $pesananItem['id'];
    //         if (array_key_exists($pesananID, $infoByID)) {
    //             $pesananItem['nama'] = $infoByID[$pesananID]->nama;
    //             $pesananItem['no_paket'] = $infoByID[$pesananID]->no_paket;
    //             $pesananItem['instansi'] = $infoByID[$pesananID]->instansi;
    //             $pesananItem['alamat_instansi'] = $infoByID[$pesananID]->alamat_instansi;
    //             $pesananItem['satuan'] =  $infoByID[$pesananID]->satuan;
    //             $pesananItem['no_urut'] =  $infoByID[$pesananID]->no_urut;
    //             $pesananItem['tgl_buat'] =  $infoByID[$pesananID]->tgl_buat;
    //             $pesananItem['tgl_kontrak'] =  $infoByID[$pesananID]->tgl_kontrak;
    //             $pesananItem['status'] =  $infoByID[$pesananID]->status;
    //         }
    //     }

    //     // Group $produk array items by pesanan_id
    //     foreach ($detail_pesanan_part_group as $item) {
    //         $pesanansId = $item['pesanan_id'];

    //         // Check if the pesanan_id exists in $produkByPesananId array
    //         if (!array_key_exists($pesanansId, $partByPesananId)) {
    //             $partByPesananId[$pesanansId] = [];
    //         }

    //         // Add the produk item to the corresponding pesanan_id
    //         $partByPesananId[$pesanansId][] = $item;
    //     }


    //     foreach ($pesanan as &$pesananItem) {
    //         $pesananId = $pesananItem['id'];

    //         // Check if pesanan_id exists in $produkByPesananId array
    //         if (array_key_exists($pesananId, $partByPesananId)) {
    //             $pesananItem['part'] = $partByPesananId[$pesananId][0]['data'];
    //         } else {
    //             $pesananItem['part'] = [];
    //         }
    //     }

    //     //----------------------------------------

    //     // Group $produk array items by pesanan_id
    //     foreach ($detail_pesanan_group as $item) {
    //         $pesananId = $item['pesanan_id'];

    //         // Check if the pesanan_id exists in $produkByPesananId array
    //         if (!array_key_exists($pesananId, $produkByPesananId)) {
    //             $produkByPesananId[$pesananId] = [];
    //         }

    //         // Add the produk item to the corresponding pesanan_id
    //         $produkByPesananId[$pesananId][] = $item;
    //     }

    //     // Group $produk array items by pesanan_id
    //     foreach ($detail_pesanan_dsb_group as $item) {
    //         $pesananId = $item['pesanan_id'];

    //         // Check if the pesanan_id exists in $produkByPesananId array
    //         if (!array_key_exists($pesananId, $produkDsbByPesananId)) {
    //             $produkDsbByPesananId[$pesananId] = [];
    //         }

    //         // Add the produk item to the corresponding pesanan_id
    //         $produkDsbByPesananId[$pesananId][] = $item;
    //     }

    //     // Update $pesanan array with produk items based on pesanan_id
    //     foreach ($pesanan as &$pesananItem) {
    //         $pesananId = $pesananItem['id'];

    //         // Check if pesanan_id exists in $produkByPesananId array
    //         if (array_key_exists($pesananId, $produkDsbByPesananId)) {
    //             $pesananItem['produk_dsb'] = $produkDsbByPesananId[$pesananId][0]['data'];
    //         } else {
    //             $pesananItem['produk_dsb'] = [];
    //         }
    //     }

    //     foreach ($pesanan as &$pesananItem) {
    //         $pesananId = $pesananItem['id'];

    //         // Check if pesanan_id exists in $produkByPesananId array
    //         if (array_key_exists($pesananId, $produkByPesananId)) {
    //             $pesananItem['produk'] = $produkByPesananId[$pesananId][0]['data'];
    //         } else {
    //             $pesananItem['produk'] = [];
    //         }
    //     }






    //     //  return response()->json($seriBatalByID);
    //     // return response()->json($pesanan);










    //     $x = explode(',', $penjualan);
    //     if ($distributor == 'semua') {
    //         if ($x == ['ekatalog', 'spa', 'spb']) {
    //             $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();

    //             $prd = $Ekatalog->merge($Spa)->merge($Spb);
    //             $part = $Part_Spa->merge($Part_Spb);
    //             $data = $prd->merge($part);
    //         } else if ($x == ['ekatalog', 'spa']) {
    //             $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Spb  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();

    //             $prd = $Ekatalog->merge($Spb);
    //             $data = $prd->merge($Part);
    //         } else if ($x == ['ekatalog', 'spb']) {
    //             $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();

    //             $prd = $Ekatalog->merge($Spb);
    //             $data = $prd->merge($Part);
    //         } else if ($x == ['spa', 'spb']) {

    //             $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //             $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();

    //             $prd = $Spa->merge($Spb);
    //             $part = $Part_Spa->merge($Part_Spb);
    //             $data = $prd->merge($part);
    //         } else if ($penjualan == 'ekatalog') {
    //             $data  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get();
    //         } else if ($penjualan == 'spa') {
    //             $prd  = collect(DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get());
    //             $part =  collect(DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get());
    //             $data = $prd->merge($part);
    //         } else if ($penjualan == 'spb') {
    //             $prd  = collect(DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get());
    //             $part =  collect(DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
    //             })->get());
    //             $data = $prd->merge($part);
    //         }
    //     } else {
    //         if ($x == ['ekatalog', 'spa', 'spb']) {
    //             $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();

    //             $prd = $Ekatalog->merge($Spa)->merge($Spb);
    //             $part = $Part_Spa->merge($Part_Spb);
    //             $data = $prd->merge($part);
    //         } else if ($x == ['ekatalog', 'spa']) {
    //             $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $prd = $Ekatalog->merge($Spa);
    //             $data = $prd->merge($Part);
    //         } else if ($x == ['ekatalog', 'spb']) {
    //             $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $prd = $Ekatalog->merge($Spb);
    //             $data = $prd->merge($Part);
    //         } else if ($x == ['spa', 'spb']) {
    //             $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part_Spa  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part_Spb  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $part = $Part_Spa->merge($Part_Spb);
    //             $prd = $Spa->merge($Spb);
    //             $data = $part->merge($prd);
    //         } else if ($penjualan == 'ekatalog') {
    //             $data = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //         } else if ($penjualan == 'spa') {
    //             $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $data = $Spa->merge($Part);
    //         } else if ($penjualan == 'spb') {
    //             $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
    //                 $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
    //                     ->where('customer_id', $distributor);
    //             })->get();
    //             $data = $Spb->merge($Part);
    //         }
    //     }
    //     return datatables()->of($data)
    //         ->addIndexColumn()
    //         ->addColumn('so', function ($data) {
    //             return $data->Pesanan->so;
    //         })
    //         ->addColumn('no_paket', function ($data) {
    //             $name = explode('/', $data->pesanan->so);
    //             if ($name[1] == 'EKAT') {
    //                 return $data->Pesanan->Ekatalog->no_paket;
    //             } else {
    //                 return '';
    //             }
    //         })
    //         ->addColumn('no_so', function ($data) {
    //             return $data->Pesanan->so;
    //         })
    //         ->addColumn('no_po', function ($data) {
    //             return $data->Pesanan->no_po;
    //         })
    //         ->addColumn('no_sj', function () {
    //             return '-';
    //         })
    //         ->addColumn('nama_customer', function ($data) {
    //             $name = explode('/', $data->pesanan->so);
    //             if ($name[1] == 'EKAT') {
    //                 return $data->Pesanan->Ekatalog->Customer->nama;
    //             } elseif ($name[1] == 'SPA') {
    //                 return $data->Pesanan->Spa->Customer->nama;
    //             } else {
    //                 return $data->Pesanan->Spb->Customer->nama;
    //             }
    //         })
    //         ->addColumn('tgl_kontrak', function ($data) {
    //             $name = explode('/', $data->pesanan->so);
    //             if ($name[1] == 'EKAT') {
    //                 return $data->Pesanan->Ekatalog->tgl_kontrak;
    //             } else {
    //                 return '';
    //             }
    //         })
    //         ->addColumn('tgl_kirim', function () {
    //             return '-';
    //         })
    //         ->addColumn('tgl_po', function ($data) {
    //             return $data->Pesanan->tgl_po;
    //         })
    //         ->addColumn('instansi', function ($data) {
    //             $name = explode('/', $data->pesanan->so);
    //             if ($name[1] == 'EKAT') {
    //                 return $data->Pesanan->Ekatalog->instansi;
    //             } else {
    //                 return '-';
    //             }
    //         })
    //         ->addColumn('satuan', function ($data) {
    //             $name = explode('/', $data->pesanan->so);
    //             if ($name[1] == 'EKAT') {
    //                 return $data->Pesanan->Ekatalog->satuan;
    //             } else {
    //                 return '-';
    //             }
    //         })
    //         ->addColumn('nama_produk', function ($data) {
    //             if ($data->PenjualanProduk) {
    //                 return $data->penjualanproduk->nama;
    //             } else {
    //                 return $data->Sparepart->nama;
    //             }
    //         })
    //         ->addColumn('no_seri', function () {
    //             return '-';
    //         })
    //         ->addColumn('jumlah', function ($data) {
    //             return $data->jumlah;
    //         })
    //         ->addColumn('harga', function ($data) {
    //             return $data->harga;
    //         })
    //         ->addColumn('subtotal', function ($data) {
    //             return $data->jumlah * $data->harga;
    //         })
    //         ->addColumn('total', function ($data) {
    //             return $data->jumlah * $data->harga;
    //         })
    //         ->addColumn('log', function () {
    //             return '-';
    //         })
    //         ->addColumn('ket', function ($data) {
    //             $name = explode('/', $data->pesanan->so);
    //             if ($name[1] == 'EKAT') {
    //                 return $data->Pesanan->Ekatalog->ket;
    //             } elseif ($name[1] == 'SPA') {
    //                 return $data->Pesanan->Spa->ket;
    //             } else {
    //                 return $data->Pesanan->Spb->ket;
    //             }
    //         })
    //         ->addColumn('kosong', function () {
    //             return '';
    //         })
    //         ->make(true);
    // }

    // Laporan
    public function  get_data_laporan_penjualan($penjualan, $distributor, $tanggal_awal, $tanggal_akhir)
    {
        $tanggal_awal = $tanggal_awal . ' 00:00:01';
        $tanggal_akhir = $tanggal_akhir . ' 23:59:00';
        $x = explode(',', $penjualan);
        if ($distributor == 'semua') {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spa)->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($x == ['ekatalog', 'spa']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['ekatalog', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['spa', 'spb']) {

                $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Spa->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($penjualan == 'ekatalog') {
                $data  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else if ($penjualan == 'spa') {
                $prd  = collect(DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $part =  collect(DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $prd->merge($part);
            } else if ($penjualan == 'spb') {
                $prd  = collect(DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $part =  collect(DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $prd->merge($part);
            }
        } else {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();

                $prd = $Ekatalog->merge($Spa)->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($x == ['ekatalog', 'spa']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $prd = $Ekatalog->merge($Spa);
                $data = $prd->merge($Part);
            } else if ($x == ['ekatalog', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['spa', 'spb']) {
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $part = $Part_Spa->merge($Part_Spb);
                $prd = $Spa->merge($Spb);
                $data = $part->merge($prd);
            } else if ($penjualan == 'ekatalog') {
                $data = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
            } else if ($penjualan == 'spa') {
                $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $data = $Spa->merge($Part);
            } else if ($penjualan == 'spb') {
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir])
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
                if ($data->pesanan->so != NULL) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->no_paket;
                    } else {
                        return '';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('no_so', function ($data) {
                return $data->Pesanan->so != NULL ?  $data->Pesanan->so : '-';
            })
            ->addColumn('no_po', function ($data) {
                return $data->Pesanan->no_po != NULL ?  $data->Pesanan->no_po : '-';
            })
            ->addColumn('no_sj', function () {
                return '-';
            })
            ->addColumn('nama_customer', function ($data) {
                if ($data->pesanan->so != NULL) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->Customer->nama;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Pesanan->Spa->Customer->nama;
                    } else {
                        return $data->Pesanan->Spb->Customer->nama;
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                if ($data->pesanan->so != NULL) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->tgl_kontrak;
                    } else {
                        return '';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl_kirim', function () {
                return '-';
            })
            ->addColumn('tgl_po', function ($data) {
                return $data->Pesanan->tgl_po;
            })
            ->addColumn('instansi', function ($data) {
                if ($data->pesanan->so != NULL) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->instansi;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('satuan', function ($data) {
                if ($data->pesanan->so != NULL) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->satuan;
                    } else {
                        return '-';
                    }
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
                if ($data->pesanan->so != NULL) {
                    $name = explode('/', $data->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->Pesanan->Ekatalog->ket;
                    } elseif ($name[1] == 'SPA') {
                        return $data->Pesanan->Spa->ket;
                    } else {
                        return $data->Pesanan->Spb->ket;
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('kosong', function () {
                return '';
            })
            ->make(true);
    }

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
        $ekatalog = Pesanan::whereHas('Ekatalog', function ($q) {
            $q->where('status', 'sepakat');
        })
            ->whereBetween('tgl_po', [$tgl_awal, $tgl_akhir])
            ->select('pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });

        $ekatalog_count = [];
        $ekatalog_graph = [];

        foreach ($ekatalog as $key => $value) {
            $ekatalog_count[(int)  $key] = count($value);
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
            ->select('pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });


        $spa_count = [];
        $spa_graph = [];
        foreach ($spa as $key => $value) {
            $spa_count[(int)  $key] = count($value);
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
            ->select('pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });


        $spb_count = [];
        $spb_graph = [];

        foreach ($spb as $key => $value) {
            $spb_count[(int)  $key] = count($value);
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
        $tahun = Carbon::now()->format('Y');
        $penj = Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNull('so')->where('log_id', '7')->whereNotIn('log_id', ['20', '10']);
        })->where('status', 'sepakat')->count();

        $gudang = Pesanan::whereIn('id', function ($q) {
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
        })
            ->whereNotIn('log_id', ['7', '20', '10'])
            ->whereYear('created_at', $tahun)
            ->count();
        //QC


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
            'ctfpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                    ->from('detail_pesanan_part')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'cqcpart' => function ($q) {
                $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                    ->from('outgoing_pesanan_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->where('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },  'clogprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'clogpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }

        ])
            ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi'])
            ->whereYear('created_at', $tahun)
            ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
            ->orderBy('tgl_kontrak', 'asc')
            ->count();

        //LOGISTIK
        $logprd = Pesanan::whereIn('id', function ($q) {
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
        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
            ->whereYear('created_at', $tahun)
            ->whereNotIn('log_id', ['7', '9', '20', '10']);

        $logpart = Pesanan::whereIn('id', function ($q) {
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
                        where detail_pesanan_part.pesanan_id = pesanan.id)) AND SUM(outgoing_pesanan_part.jumlah_ok) > 0");
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
            ->whereYear('created_at', $tahun)
            ->whereNotIn('log_id', ['7', '20', '10']);

        $logjasa = Pesanan::whereIn('id', function ($q) {
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
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
            ->whereYear('created_at', $tahun)
            ->whereNotIn('log_id', ['7', '20', '10'])->union($logprd)->union($logpart)->orderBy('id', 'desc')->count();

        $log = $logjasa;
        return view('page.penjualan.dashboard', ['belum_so' => $penj, 'so_belum_gudang' => $gudang, 'so_belum_qc' => $qc, 'so_belum_logistik' => $log]);
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

        $check = Pesanan::whereYear('created_at', $this->getYear())->where('so', 'like', '%' . $this->getYear() . '%')->get('so');
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

    function createSObyPeriod($value, $years)
    {
        $check = Pesanan::whereYear('created_at', $years)->where('so', 'like', '%' . $years . '%')->get('so');
        $max_number = 0;
        foreach ($check as $c) {
            if ($c->so == NULL) {
                $no = 'SO/' . $value . '/' . $this->getMonth() . '/' . $years . '/1';
            } else {
                $get = explode('/', $c->so);
                if ($get[1] == $value) {
                    if ($get[4] > $max_number)
                        $max_number = $get[4];
                }
            }
        }
        $no = 'SO/' . $value . '/' . $this->getMonth() . '/' . $years . '/' . ($max_number + 1) . '';
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
            $e = Ekatalog::where('no_urut', $val)->whereNotIn('id', [$id])->whereYear('created_at', $this->getYear())->count();
            return $e;
        } else {
            $e = Ekatalog::where('no_urut',  $val)->whereYear('created_at', $this->getYear())->count();
            return $e;
        }
    }

    public function check_variasi_jumlah($id)
    {
        // $gbj = GudangBarangJadi::find($id);
        // $jumlah_ekatalog = $this->get_count_ekatalog($id, $gbj->produk_id, "sepakat") + $this->get_count_ekatalog($id, $gbj->produk_id, "negosiasi");
        // $jumlah_po = $this->get_count_spa_po($id, $gbj->produk_id);
        // $jumlah = $gbj->stok - ($jumlah_ekatalog + $jumlah_po);
        // return $jumlah;

        $data = GudangBarangJadi::addSelect(['count_barang' => function ($query) {
            $query->selectRaw('count(noseri_barang_jadi.id)')
                ->from('noseri_barang_jadi')
                ->where(['noseri_barang_jadi.is_ready' => 0])
                ->whereColumn('noseri_barang_jadi.gdg_barang_jadi_id', 'gdg_barang_jadi.id')
                ->limit(1);
        }, 'count_ekat_sepakat' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                ->limit(1);
        },  'count_ekat_nego' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                ->limit(1);
        },  'count_ekat_draft' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7")  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                ->limit(1);
        },  'count_ekat_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                ->limit(1);
        },  'count_spa_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                ->limit(1);
        },  'count_spb_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                ->limit(1);
        },])
            ->find($id);

        $jumlahdiminta = intval($data->count_ekat_sepakat) + intval($data->count_ekat_nego) + intval($data->count_ekat_draft) + intval($data->count_ekat_po) + intval($data->count_spa_po) + intval($data->count_spb_po);
        $jumlahstok = intval($data->count_barang);
        return $jumlahstok - $jumlahdiminta;
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


    public function export_laporan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan)
    {
        $x = explode(',', $jenis);
        $waktu = Carbon::now();

        if ($x == ['ekatalog', 'spa', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Semua ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['ekatalog', 'spa']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Ekatalog dan SPA ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['ekatalog', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Ekatalog dan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['spa', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan SPA dan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'ekatalog') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Ekatalog ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'spa') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan SPA ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'spb') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        }
    }

    public function manager_penjualan_show()
    {
        return view('manager.penjualan.so.show');
    }

    public function manager_penjualan_show_data($jenis, $value)
    {
        if ($jenis == "ekatalog") {
            $x = explode(',', $value);
            $data = "";

            if ($value == 'semua') {
                $data  = Ekatalog::with(['Pesanan.State',  'Customer', 'Provinsi'])->addSelect([

                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    },
                    'cseri' => function ($q) {
                        $q->selectRaw('count(noseri_logistik.id)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlah' => function ($q) {
                        $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    }

                ])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get();
            } else {
                $data  = Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([

                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    },
                    'cseri' => function ($q) {
                        $q->selectRaw('count(noseri_logistik.id)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlah' => function ($q) {
                        $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    }

                ])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
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
                    if ($data->Pesanan->log_id == '7' || $data->Pesanan->log_id == '9') {
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
                    } else {
                        if ($data->status == "batal") {
                            $datas = '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="EKAT" data-provinsi="">
                                <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </button>
                            </a>';
                        } else {
                            $hitung = floor((($data->cseri / $data->cjumlah) * 100));
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

                    if ($data->tgl_kontrak_custom != "") {
                        if ($data->Pesanan->log_id != "10") {
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
                        } else {
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
                    // if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    // }
                    // else {
                    //     $return = '<a data-toggle="modal" data-jenis="ekatalog" class="batalmodal" data-id="' . $data->id . '" >
                    //         <button class="btn btn-outline-danger btn-sm" type="button">
                    //             <i class="fas fa-times"></i>
                    //             Batal
                    //             </button>
                    //         </a>';
                    // }
                    return $return;
                })
                ->rawColumns(['button', 'status', 'tgl_kontrak'])
                ->make(true);
        } else if ($jenis == "spa") {
            $x = explode(',', $value);
            if ($value == 'semua') {
                $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }

                ])->orderBy('id', 'DESC')->get();
            } else {
                $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }

                ])->whereHas('pesanan', function ($q) use ($x) {
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
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

                    if ($data->log == "batal") {
                        $datas = '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPA" data-provinsi="">
                                    <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                        <i class="fas fa-times"></i>
                                        Batal
                                    </button>
                                </a>';
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
                    // if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    // }
                    // else{
                    //     $return = '<a data-toggle="modal" data-jenis="spa" class="batalmodal" data-id="' . $data->id . '" >
                    //         <button class="btn btn-outline-danger btn-sm" type="button">
                    //             <i class="fas fa-times"></i>
                    //             Batal
                    //             </button>
                    //         </a>';
                    // }
                    return $return;
                })
                ->rawColumns(['button', 'status'])
                ->make(true);
        } else if ($jenis == "spb") {
            $x = explode(',', $value);
            if ($value == 'semua') {
                $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }

                ])->orderBy('id', 'DESC')->get();
            } else {
                $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }

                ])->whereHas('pesanan', function ($q) use ($x) {
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
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

                    if ($data->log == "batal") {
                        $datas = '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPB" data-provinsi="">
                                    <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                        <i class="fas fa-times"></i>
                                        Batal
                                    </button>
                                </a>';
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
                    // if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    // }
                    // else{
                    //     $return = '<a data-toggle="modal" data-jenis="spb" class="batalmodal" data-id="' . $data->id . '" >
                    //         <button class="btn btn-outline-danger btn-sm" type="button">
                    //             <i class="fas fa-times"></i>
                    //             Batal
                    //             </button>
                    //         </a>';
                    // }
                    return $return;
                })
                ->rawColumns(['button', 'status'])
                ->make(true);
        }
    }

    public function store_ekat_emindo(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),  [
            'no_paket' => 'unique:ekatalog,no_paket'
        ]);
        if ($validator->fails()) {
            if ($request->provinsi != '') {
                $provinsi = Provinsi::where('nama', 'like', '%' . $request->provinsi . '%')->first();
                $p = $provinsi->id;
            } else {
                $p = NULL;
            }


            $e = Ekatalog::where('no_paket', $request->no_paket)->first();
            $data = Ekatalog::find($e->id);


            if ($data->customer_id != 213) {
                $data->customer_id = 213;
                $data->save();
            }

            if ($data->provinsi_id == NULL) {
                $data->provinsi_id = $p;
                $data->save();
            }

            if ($data->alamat == '-' || $data->alamat == NUll) {
                $data->alamat = $request->alamat;
                $data->save();
            }

            if ($data->deskripsi == '-' || $data->deskripsi == NUll) {
                $data->deskripsi = $request->deskripsi;
                $data->save();
            }
            if ($data->instansi == '-' || $data->instansi == NUll) {
                $data->instansi = $request->instansi;
                $data->save();
            }
            if ($data->satuan == '-' || $data->satuan == NUll) {
                $data->satuan = $request->satuan;
                $data->save();
            }

            if ($request->tglkontrak != '') {
                if ($data->tgl_kontrak == NUll) {
                    $data->tgl_kontrak = $request->tglkontrak;
                    $data->save();
                }
            }

            if ($request->tgledit != '') {
                if ($data->tgl_edit == NUll) {
                    $data->tgl_edit = $request->tgledit;
                    $data->save();
                }
            }

            if ($data->ket == NULL) {
                $data->ket =  $request->ket;
                $data->save();
            }

            if ($data->status !=  $request->status) {
                $data->status = $request->status;
                $data->save();
            }



            $dekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($data) {
                $q->where('pesanan_id',  $data->pesanan_id);
            })->get();

            if (count($dekatp) > 0) {
                $deldekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($data) {
                    $q->where('pesanan_id', $data->pesanan_id);
                })->delete();
            }
            $dekat = DetailPesanan::where('pesanan_id', $data->pesanan_id)->get();

            if (count($dekat) > 0) {
                $deldekat = DetailPesanan::where('pesanan_id', $data->pesanan_id)->delete();
            }

            foreach ($request->produk as $dp) {
                $dekatpaket = DetailPesanan::create([
                    'pesanan_id' => $e->pesanan_id,
                    'penjualan_produk_id' => $dp['id'],
                    'jumlah' => $dp['qty'],
                    'harga' => $dp['price'],
                    'ongkir' => $dp['shippingcharge'],
                ]);

                for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                    $dekatprd = DetailPesananProduk::create([
                        'detail_pesanan_id' => $dekatpaket->id,
                        'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],

                    ]);
                }
            }


            $id = array();
            $saveresponse = SaveResponse::where('tipe', 'ekatalog')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->AKN == $request->no_paket) {
                    $id[] = $hasil->id;
                }
            }

            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'ekatalog',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => '-',
                'nourut' => $data->no_urut,
                'AKN' => $data->no_paket,
                'PO' => '-',
                'tgl_po' => '-',
                'DO' => '-',
                'tgl_do' => '-',
                'Satuan' => $data->satuan,
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();
        } else {

            $x = "";
            $pesanan = Pesanan::create([
                'log_id' => '7',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            $x = $pesanan->id;

            if ($request->provinsi != '') {
                $provinsi = Provinsi::where('nama', 'like', '%' . $request->provinsi . '%')->first();
                $p = $provinsi->id;
            } else {
                $p = $request->provinsi;
            }

            if ($request->satuan != '') {
                $sat = $request->satuan;
            } else {
                $sat = '-';
            }


            $Ekatalog = Ekatalog::create([
                'customer_id' => 213,
                'provinsi_id' => $p,
                'no_paket' => $request->no_paket,
                'no_urut' => $request->no_urut,
                'deskripsi' => $request->deskripsi,
                'instansi' => $request->instansi,
                'alamat' => $request->alamat,
                'satuan' => $request->satuan,
                'tgl_kontrak' => $request->tglkontrak,
                'tgl_buat' => $request->tglbuat,
                'tgl_edit' => $request->tgledit,
                'ket' => $request->ket,
                'status' => $request->status,
                'log' => 'penjualan',
                'pesanan_id' => $x

            ]);

            foreach ($request->produk as $dp) {
                $dekatpaket = DetailPesanan::create([
                    'pesanan_id' => $x,
                    'penjualan_produk_id' => $dp['id'],
                    'jumlah' => $dp['qty'],
                    'harga' => $dp['price'],
                    'ongkir' => $dp['shippingcharge'],
                ]);

                for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                    $dekatprd = DetailPesananProduk::create([
                        'detail_pesanan_id' => $dekatpaket->id,
                        'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],

                    ]);
                }
            }

            $id = array();
            $saveresponse = SaveResponse::where('tipe', 'ekatalog')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->AKN == $request->no_paket) {
                    $id[] = $hasil->id;
                }
            }

            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'ekatalog',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => '-',
                'nourut' => $request->no_urut,
                'AKN' => $request->no_paket,
                'PO' => '-',
                'tgl_po' => '-',
                'DO' => '-',
                'tgl_do' => '-',
                'Satuan' => $sat,
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();
        }
        return response()->json(['message'  => 'Berhasil']);
    }

    public function store_ekat_emindo_po(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            'no_paket' => 'unique:ekatalog,no_paket'
        ]);
        if ($validator->fails()) {
            $e = Ekatalog::where('no_paket', $request->no_paket)->first();
            $ekat = Ekatalog::find($e->id);
            $po = Pesanan::find($ekat->pesanan_id);

            if ($po->no_po == NULL) {
                $po->so = $this->createSO('EKAT');
                $po->no_po = $request->no_po;
                $po->save();
            }

            if ($po->tgl_po == NULL) {
                $po->tgl_po = $request->tgl_po;
                $po->save();
            }

            if ($po->no_do == NULL) {
                $po->no_do = $request->no_do;
                $po->save();
            }

            if ($po->tgl_do == NULL) {
                $po->tgl_do = $request->tgl_do;
                $po->save();
            }

            if ($po->ket == NULL) {
                $po->ket = $request->ket;
                $po->save();
            }

            if ($po->log_id == 7) {
                $po->log_id = 9;
                $po->save();
            }



            $dekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($ekat) {
                $q->where('pesanan_id',  $ekat->pesanan_id);
            })->get();

            if (count($dekatp) > 0) {
                $deldekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($ekat) {
                    $q->where('pesanan_id', $ekat->pesanan_id);
                })->delete();
            }
            $dekat = DetailPesanan::where('pesanan_id', $ekat->pesanan_id)->get();

            if (count($dekat) > 0) {
                $deldekat = DetailPesanan::where('pesanan_id', $ekat->pesanan_id)->delete();
            }


            foreach ($request->produk as $dp) {
                $dekatpaket = DetailPesanan::create([
                    'pesanan_id' => $e->pesanan_id,
                    'penjualan_produk_id' => $dp['id'],
                    'jumlah' => $dp['qty'],
                    'harga' => $dp['price'],
                    'ongkir' => $dp['shippingcharge'],
                ]);

                for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                    $dekatprd = DetailPesananProduk::create([
                        'detail_pesanan_id' => $dekatpaket->id,
                        'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],
                    ]);
                }
            }


            $id = array();
            $saveresponse = SaveResponse::where('tipe', 'ekatalog')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->AKN == $ekat->no_paket) {
                    $id[] = $hasil->id;
                }
            }

            if ($request->no_do != '') {
                $do = $request->no_do;
            } else {
                $do = '-';
            }

            if ($request->tgl_do != '') {
                $tgldo = $request->tgl_do;
            } else {
                $tgldo = '-';
            }

            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'ekatalog',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => $po->so,
                'nourut' => $ekat->no_urut,
                'AKN' => $ekat->no_paket,
                'PO' =>  $request->no_po,
                'tgl_po' => $request->tgl_po,
                'DO' => $do,
                'tgl_do' => $tgldo,
                'Satuan' =>  $ekat->satuan,
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();


            return response()->json([
                'status' => 200,
                'message' => 'OK',
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'TIdak ditemukan',
            ], 200);
        }
    }

    public function store_spa_emindo(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'no_po' => 'unique:pesanan,no_po'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 200,
                'message' => 'po',
            ], 200);
        } else {
            $x = "";
            $pesanan = Pesanan::create([
                'so' => $this->createSO('SPA'),
                'no_po' => $request->no_po,
                'tgl_po' => $request->tgl_po,
                'no_do' => $request->no_do,
                'tgl_do' => $request->tgl_do,
                'ket' =>  $request->ket,
                'log_id' => '7',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            $x = $pesanan->id;

            $Spa = Spa::create([
                'customer_id' => 213,
                'pesanan_id' => $x,
                'ket' => $request->ket,
                'log' => 'po'
            ]);

            if ($request->produk) {
                foreach ($request->produk as $dp) {
                    $dekatpaket = DetailPesanan::create([
                        'pesanan_id' => $x,
                        'penjualan_produk_id' => $dp['id'],
                        'jumlah' => $dp['qty'],
                        'harga' => $dp['price'],
                        'ongkir' => $dp['shippingcharge'],
                    ]);

                    for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                        $dekatprd = DetailPesananProduk::create([
                            'detail_pesanan_id' => $dekatpaket->id,
                            'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],

                        ]);
                    }
                }
            }

            if ($request->sparepart) {
                foreach ($request->sparepart as $sp) {
                    $depart = DetailPesananPart::create([
                        'pesanan_id' => $x,
                        'm_sparepart_id' => $sp['id'],
                        'jumlah' => $sp['qty'],
                        'harga' => $sp['price'],
                        'ongkir' => 0,
                    ]);
                }
            }

            if ($request->jasa) {
                foreach ($request->jasa as $js) {
                    $dejasa = DetailPesananPart::create([
                        'pesanan_id' => $x,
                        'm_sparepart_id' => $js['id'],
                        'jumlah' => 1,
                        'harga' => $js['price'],
                        'ongkir' => 0,
                    ]);


                    $qcspb = OutgoingPesananPart::create([
                        'detail_pesanan_part_id' => $dejasa->id,
                        'tanggal_uji' => $request->tgl_po,
                        'jumlah_ok' => 1,
                        'jumlah_nok' => 0
                    ]);
                }
            }

            $id = array();
            $saveresponse = SaveResponse::where('tipe',  'spa')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->PO == $request->no_po) {
                    $id[] = $hasil->id;
                }
            }
            if ($request->no_do != '') {
                $do = $request->no_do;
            } else {
                $do = '-';
            }

            if ($request->tgl_do != '') {
                $tgldo = $request->tgl_do;
            } else {
                $tgldo = '-';
            }


            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'spa',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => $pesanan->so,
                'nourut' => '-',
                'AKN' => '-',
                'PO' => $request->no_po,
                'tgl_po' => $request->tgl_po,
                'DO' => $do,
                'tgl_do' => $tgldo,
                'Satuan' => '-',
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();

            return response()->json([
                'status' => 200,
                'message' => 'ok',
            ], 200);
        }
    }

    public function update_do(Request $request)
    {
        $get = Pesanan::where('no_po', $request->no_po)->first();
        if ($get) {
            if ($get->no_do != '') {
                return response()->json(['message' => 'donotnull']);
            } else {
                $pesanan = Pesanan::find($get->id);
                $pesanan->no_do = $request->no_do;
                $pesanan->tgl_do = $request->tgl_do;
                $update = $pesanan->save();

                if ($pesanan->Ekatalog) {
                    $id = array();
                    $saveresponse = SaveResponse::where('tipe',  'ekatalog')->get();

                    foreach ($saveresponse as $s) {
                        $hasil = json_decode($s->parameter);
                        if ($hasil->AKN == $pesanan->Ekatalog->no_paket) {
                            $id[] = $hasil->id;
                        }
                    }

                    if ($request->no_do != '') {
                        $do = $request->no_do;
                    } else {
                        $do = '-';
                    }

                    if ($request->tgl_do != '') {
                        $tgldo = $request->tgl_do;
                    } else {
                        $tgldo = '-';
                    }

                    if ($id > 0) {
                        SaveResponse::whereIn('id',  $id)->delete();
                    }

                    $save =  SaveResponse::create([
                        'tipe' => 'ekatalog',
                        'url' =>  URL::current(),
                        'parameter' =>  '-',
                        'response' => 'ok',
                        'method' => 'post',
                        'created_at' => Carbon::now()

                    ]);

                    $field = array(
                        'id' => $save->id,
                        'SO' => $pesanan->so,
                        'nourut' => $pesanan->Ekatalog->no_urut,
                        'AKN' => $pesanan->Ekatalog->no_paket,
                        'PO' =>  $pesanan->no_po,
                        'tgl_po' => $pesanan->tgl_po,
                        'DO' => $do,
                        'tgl_do' => $tgldo,
                        'Satuan' =>  $pesanan->Ekatalog->satuan,
                    );
                    $data = json_encode($field);
                    $get_response = SaveResponse::find($save->id);
                    $get_response->parameter = $data;
                    $get_response->save();
                }

                if ($pesanan->Spa) {
                    $id = array();
                    $saveresponse = SaveResponse::where('tipe',  'spa')->get();

                    foreach ($saveresponse as $s) {
                        $hasil = json_decode($s->parameter);
                        if ($hasil->PO == $pesanan->no_po) {
                            $id[] = $hasil->id;
                        }
                    }
                    if ($request->no_do != '') {
                        $do = $request->no_do;
                    } else {
                        $do = '-';
                    }

                    if ($request->tgl_do != '') {
                        $tgldo = $request->tgl_do;
                    } else {
                        $tgldo = '-';
                    }


                    if ($id > 0) {
                        SaveResponse::whereIn('id',  $id)->delete();
                    }

                    $save =  SaveResponse::create([
                        'tipe' => 'spa',
                        'url' =>  URL::current(),
                        'parameter' =>  '-',
                        'response' => 'ok',
                        'method' => 'post',
                        'created_at' => Carbon::now()

                    ]);

                    $field = array(
                        'id' => $save->id,
                        'SO' => $pesanan->so,
                        'nourut' => '-',
                        'AKN' => '-',
                        'PO' => $pesanan->no_po,
                        'tgl_po' => $pesanan->tgl_po,
                        'DO' => $do,
                        'tgl_do' => $tgldo,
                        'Satuan' => '-',
                    );
                    $data = json_encode($field);
                    $get_response = SaveResponse::find($save->id);
                    $get_response->parameter = $data;
                    $get_response->save();
                }

                return response()->json(['message' => 'Berhasil']);
            }
        } else {
            return response()->json(['message' => 'ponull']);
        }
    }

    public function get_data_ekatalog_emindo($akn)
    {

        $e = Ekatalog::where('no_paket', $akn)->first();
        if ($e) {
            $data = array();
            $detailpesanan = DetailPesanan::where('pesanan_id', $e->pesanan_id)->get();

            if ($e->Provinsi) {
                $provinsi =   $e->Provinsi->nama;
            } else {
                $provinsi = NULL;
            }

            $data = array(
                'ekatalog_id' => $e->id,
                'provinsi' => $provinsi,
                'no_paket' => $e->no_paket,
                'no_urut' => $e->no_urut,
                'deskripsi' => $e->deskripsi,
                'instansi' => $e->instansi,
                'alamat' => $e->alamat,
                'satuan' => $e->satuan,
                'tglkontrak' => $e->tgl_kontrak,
                'tglbuat' => $e->tgl_buat,
                'tgledit' => $e->tgl_edit,
                'ket' => $e->ket,
                'status' => $e->status,
            );

            if (count($detailpesanan) > 0) {
                foreach ($detailpesanan as $key_detailpesanan => $detailpesanan) {
                    $data['produk'][$key_detailpesanan] = array(
                        'id' => $detailpesanan->penjualan_produk_id,
                        'produk' => $detailpesanan->PenjualanProduk->nama,
                        'qty' => $detailpesanan->jumlah,
                        'price' => $detailpesanan->harga,
                        'shippingcharge' => $detailpesanan->ongkir,
                        'subtotal' => ($detailpesanan->harga * $detailpesanan->jumlah) + $detailpesanan->ongkir,
                        'detailproduk' => array(),
                        'detailprodukvarian' => array()
                    );

                    for ($j = 0; $j < 1; $j++) {
                        $data['produk'][$key_detailpesanan]['detailproduk'][0] = array(array(
                            'nama' => $detailpesanan->PenjualanProduk->nama,
                            'nama_alias' => $detailpesanan->PenjualanProduk->nama_alias,
                            'harga' => $detailpesanan->PenjualanProduk->harga,
                            'status' => $detailpesanan->PenjualanProduk->status,
                            'created_at' => $detailpesanan->PenjualanProduk->created_at,
                            'updated_at' => $detailpesanan->PenjualanProduk->updated_at,
                        ));
                    }

                    foreach ($detailpesanan->PenjualanProduk->Produk as $key_produk => $produk) {

                        $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk] = array(
                            'id' => $produk->id,
                            'produk_id' =>  $produk->produk_id,
                            'kelompok_produk_id' => $produk->kelompok_produk_id,
                            'merk' =>  $produk->merk,
                            'nama' =>  $produk->nama,
                            'nama_coo' =>  $produk->nama_coo,
                            'coo' =>  $produk->coo,
                            'no_akd' =>  $produk->no_akd,
                            'ket' => $produk->ket,
                            'status' => $produk->status,
                            'created_at' => $produk->created_at,
                            'updated_at' => $produk->updated_at,
                            'pivot' => $produk->pivot,
                        );

                        foreach ($produk->GudangBarangJadi as $key_v => $v) {
                            $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk]['gudang_barang_jadi'][$key_v] = array(
                                'id' => $v->id,
                                'produk_id' => $v->produk_id,
                                'nama' => $v->nama,
                                'deskripsi' => $v->deskripsi,
                                'stok' => $v->stok(),
                                'stok_siap' => $v->stok_siap,
                                'satuan_id' => $v->satuan_id,
                                'gambar' => $v->gambar,
                                'dim_p' => $v->dim_p,
                                'dim_l' => $v->dim_l,
                                'dim_t' => $v->dim_t,
                                'status' => $v->status,
                                'created_by' => $v->created_by,
                                'updated_by' => $v->updated_by,
                                'created_at' => $v->created_at,
                                'updated_at' => $v->updated_at,
                            );
                        }
                    }

                    foreach ($detailpesanan->DetailPesananProduk as $key_detailpesananproduk => $detailpesananproduk) {
                        if ($detailpesananproduk->GudangBarangJadi->nama != '') {
                            $variasi = $detailpesananproduk->GudangBarangJadi->nama;
                        } else {
                            $variasi = $detailpesananproduk->GudangBarangJadi->Produk->nama;
                        }
                        $data['produk'][$key_detailpesanan]['detailprodukvarian'][$key_detailpesananproduk] = array(
                            'namaprd' => $detailpesananproduk->GudangBarangJadi->Produk->nama,
                            'id' => $detailpesananproduk->GudangBarangJadi->id,
                            'nama' => $variasi,
                            'label' => $variasi . ' (' . $detailpesananproduk->GudangBarangJadi->stok() . ')',
                            'value' => $detailpesananproduk->GudangBarangJadi->id,
                            'stok' => $detailpesananproduk->GudangBarangJadi->stok(),
                        );
                    }
                }
            }

            //  $field = array([
            //         'AKN'=> $e->no_paket,
            //         'PO'=> null,
            //         'DO'=> 'DO123',
            //         'Instansi'=> 'Instansi Ini',
            // ]);
            // $fields = json_encode($field);

            //  SaveResponse::create([
            //   'tipe' => 'ekatalog',
            //   'url' =>  URL::current(),
            //   'parameter' =>  $fields,
            //   'response' => 'ok',
            //   'method' => 'post',
            //   'created_at' => Carbon::now()]);



            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
                'data'    => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan',
            ], 200);
        }
    }

    public function get_data_spa_emindo($po)
    {
        $p = Pesanan::where('no_po', $po)->first();
        if ($p) {
            $data = array();
            $detailpesanan = DetailPesanan::where('pesanan_id', $p->id)->get();
            $detailpesananpart =  DetailPesananPart::whereHas('Sparepart', function ($q) {
                $q->where('kode', 'not like', '%Jasa%');
            })->where('pesanan_id', $p->id)->get();
            $detailpesananjasa =  DetailPesananPart::whereHas('Sparepart', function ($q) {
                $q->where('kode', 'like', '%Jasa%');
            })->where('pesanan_id', $p->id)->get();

            $data = array(
                'no_po' => $p->no_po,
                'tgl_po' => $p->tgl_po,
                'no_do' => $p->no_do,
                'tgl_do' => $p->tgl_do,
                'ket' => $p->ket,
            );


            if (count($detailpesanan) > 0) {
                foreach ($detailpesanan as $key_detailpesanan => $detailpesanan) {
                    $data['produk'][$key_detailpesanan] = array(
                        'id' => $detailpesanan->penjualan_produk_id,
                        'produk' => $detailpesanan->PenjualanProduk->nama,
                        'qty' => $detailpesanan->jumlah,
                        'price' => $detailpesanan->harga,
                        'shippingcharge' => $detailpesanan->ongkir,
                        'subtotal' => ($detailpesanan->harga * $detailpesanan->jumlah) + $detailpesanan->ongkir,
                        'detailproduk' => array(),
                        'detailprodukvarian' => array()
                    );

                    for ($j = 0; $j < 1; $j++) {
                        $data['produk'][$key_detailpesanan]['detailproduk'][0] = array(array(
                            'nama' => $detailpesanan->PenjualanProduk->nama,
                            'nama_alias' => $detailpesanan->PenjualanProduk->nama_alias,
                            'harga' => $detailpesanan->PenjualanProduk->harga,
                            'status' => $detailpesanan->PenjualanProduk->status,
                            'created_at' => $detailpesanan->PenjualanProduk->created_at,
                            'updated_at' => $detailpesanan->PenjualanProduk->updated_at,
                        ));
                    }

                    foreach ($detailpesanan->PenjualanProduk->Produk as $key_produk => $produk) {

                        $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk] = array(
                            'id' => $produk->id,
                            'produk_id' =>  $produk->produk_id,
                            'kelompok_produk_id' => $produk->kelompok_produk_id,
                            'merk' =>  $produk->merk,
                            'nama' =>  $produk->nama,
                            'nama_coo' =>  $produk->nama_coo,
                            'coo' =>  $produk->coo,
                            'no_akd' =>  $produk->no_akd,
                            'ket' => $produk->ket,
                            'status' => $produk->status,
                            'created_at' => $produk->created_at,
                            'updated_at' => $produk->updated_at,
                            'pivot' => $produk->pivot,
                        );

                        foreach ($produk->GudangBarangJadi as $key_v => $v) {
                            $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk]['gudang_barang_jadi'][$key_v] = array(
                                'id' => $v->id,
                                'produk_id' => $v->produk_id,
                                'nama' => $v->nama,
                                'deskripsi' => $v->deskripsi,
                                'stok' => $v->stok(),
                                'stok_siap' => $v->stok_siap,
                                'satuan_id' => $v->satuan_id,
                                'gambar' => $v->gambar,
                                'dim_p' => $v->dim_p,
                                'dim_l' => $v->dim_l,
                                'dim_t' => $v->dim_t,
                                'status' => $v->status,
                                'created_by' => $v->created_by,
                                'updated_by' => $v->updated_by,
                                'created_at' => $v->created_at,
                                'updated_at' => $v->updated_at,
                            );
                        }
                    }

                    foreach ($detailpesanan->DetailPesananProduk as $key_detailpesananproduk => $detailpesananproduk) {
                        if ($detailpesananproduk->GudangBarangJadi->nama != '') {
                            $variasi = $detailpesananproduk->GudangBarangJadi->nama;
                        } else {
                            $variasi = $detailpesananproduk->GudangBarangJadi->Produk->nama;
                        }
                        $data['produk'][$key_detailpesanan]['detailprodukvarian'][$key_detailpesananproduk] = array(
                            'namaprd' => $detailpesananproduk->GudangBarangJadi->Produk->nama,
                            'id' => $detailpesananproduk->GudangBarangJadi->id,
                            'nama' => $variasi,
                            'label' => $variasi . ' (' . $detailpesananproduk->GudangBarangJadi->stok() . ')',
                            'value' => $detailpesananproduk->GudangBarangJadi->id,
                            'stok' => $detailpesananproduk->GudangBarangJadi->stok(),
                        );
                    }
                }
            }

            if (count($detailpesananpart) > 0) {
                foreach ($detailpesananpart as $key_detailpesananpart => $detailpesananpart) {
                    $data['sparepart'][$key_detailpesananpart] = array(
                        'id' => $detailpesananpart->Sparepart->id,
                        'sparepart' => $detailpesananpart->Sparepart->nama,
                        'qty' => $detailpesananpart->jumlah,
                        'price' => $detailpesananpart->harga,
                        'subtotal' => $detailpesananpart->harga * $detailpesananpart->jumlah
                    );
                }
            }

            if (count($detailpesananjasa) > 0) {
                foreach ($detailpesananjasa as $key_detailpesananjasa => $detailpesananjasa) {
                    $data['jasa'][$key_detailpesananjasa] = array(
                        'id' => $detailpesananjasa->Sparepart->id,
                        'jasa' => $detailpesananjasa->Sparepart->nama,
                        'qty' => $detailpesananjasa->jumlah,
                        'price' => $detailpesananjasa->harga,
                        'subtotal' => $detailpesananjasa->harga * $detailpesananjasa->jumlah
                    );
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
                'data'    => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan',
            ], 200);
        }
    }

    public function cek_paket($akn)

    {
        $e = Ekatalog::where('no_paket', $akn)->first();
        if ($e) {
            if ($e->customer_id == 484) {
                return response()->json(['message'  => 'Tidak Ditemukan']);
            } else {
                $dp = TFProduksi::where('pesanan_id', $e->pesanan_id)->count();


                if ($dp > 0) {
                    return response()->json(['message'  => 'Sudah Proses']);
                } else {
                    return response()->json(['message'  => 'Belum Proses']);
                }
            }
        } else {
            return response()->json(['message'  => 'Tidak Ditemukan']);
        }
    }

    public function cek_po($po)

    {
        $e = Pesanan::where('no_po', $po)->first();
        if ($e) {
            $dp = TFProduksi::where('pesanan_id',  $e->id)->count();

            if ($dp > 0) {
                return response()->json(['message'  => 'Sudah Proses']);
            } else {
                return response()->json(['message'  => 'Belum Proses']);
            }
        } else {
            return response()->json(['message'  => 'Tidak Ditemukan']);
        }
    }

    public function penjualan_data_emindo()
    {
        $json_array = array();
        $saveresponse = SaveResponse::whereIN('tipe', ['ekatalog', 'spa'])->get();
        foreach ($saveresponse as $s) {
            $json_array[] = json_decode($s->parameter);
        }
        return response()->json([
            'status' => 200,
            'message' =>  'Berhasil',
            'data'    =>  $json_array
        ], 200);
    }
    public function cancel_po($id)
    {

        $po = Pesanan::find($id);
        $po->log_id = 20;
        $po->save();

        return response()->json([
            'status' => 200,
            'message' =>  'Berhasil'
        ], 200);
    }

    public function get_laporans($tanggal_awal, $tanggal_akhir)
    {
        $data = Pesanan::addSelect([
            'spa' => function ($q) {
                $q->selectRaw('coalesce(count(spa.id),0)')
                    ->from('spa')
                    ->whereColumn('spa.pesanan_id', 'pesanan.id');
            },
            'spb' => function ($q) {
                $q->selectRaw('coalesce(count(spb.id),0)')
                    ->from('spb')
                    ->whereColumn('spb.pesanan_id', 'pesanan.id');
            },
            'ekat' => function ($q) {
                $q->selectRaw('coalesce(count(ekatalog.id),0)')
                    ->from('ekatalog')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id');
            }
        ])
            ->havingRaw('ekat > 0')
            ->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);

        $pesananIds = $data->pluck('id')->toArray();

        $data_dpp = DetailPesananProduk::leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->whereIN('detail_pesanan.pesanan_id', $pesananIds);

        $dppIds = $data_dpp->pluck('detail_pesanan_produk.id')->toArray();

        $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
            ->selectRaw('"-" AS no_paket')
            ->selectRaw('"-" AS instansi')
            ->selectRaw('"-" AS alamat_instansi')
            ->selectRaw('"-" AS status')
            ->selectRaw('"-" AS satuan')
            ->selectRaw('"-" AS no_urut')
            ->selectRaw('"-" AS tgl_buat')
            ->selectRaw('"-" AS tgl_kontrak')
            ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
            ->whereIn('spb.pesanan_id', $pesananIds)->get();

        $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
            ->selectRaw('"-" AS no_paket')
            ->selectRaw('"-" AS instansi')
            ->selectRaw('"-" AS alamat_instansi')
            ->selectRaw('"-" AS status')
            ->selectRaw('"-" AS satuan')
            ->selectRaw('"-" AS no_urut')
            ->selectRaw('"-" AS tgl_buat')
            ->selectRaw('"-" AS tgl_kontrak')
            ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
            ->whereIn('spa.pesanan_id', $pesananIds)->get();

        $ekatalog = Ekatalog::select(
            DB::raw("DATE_FORMAT(ekatalog.tgl_buat, '%d-%m-%Y') as tgl_buat"),
            DB::raw("DATE_FORMAT(ekatalog.tgl_kontrak, '%d-%m-%Y') as tgl_kontrak"),
            'ekatalog.pesanan_id as id',
            'ekatalog.ket',
            'ekatalog.no_urut as no_urut',
            'customer.nama',
            'ekatalog.no_paket',
            'ekatalog.instansi',
            'ekatalog.alamat as alamat_instansi',
            'ekatalog.satuan',
            'ekatalog.status'
        )
            ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
            ->whereIn('ekatalog.pesanan_id', $pesananIds)->get();

        $dataInfo =   $ekatalog->merge($spa)->merge($spb);

        //return response()->json($dataInfo);

        //GET SURAT JALAN
        $surat_jalan = Logistik::select('detail_pesanan.pesanan_id as id', 'nosurat', DB::raw("DATE_FORMAT(tgl_kirim, '%d-%m-%Y') as tgl_kirim"))
            ->leftJoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->whereIN('detail_logistik.detail_pesanan_produk_id', $dppIds)
            ->groupBy('logistik.id')
            ->get();

        //GET SURAT JALAN PART
        $surat_jalan_part = Logistik::select('detail_pesanan_part.pesanan_id as id', 'nosurat', DB::raw("DATE_FORMAT(tgl_kirim, '%d-%m-%Y') as tgl_kirim"))
            ->leftJoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
            ->whereIN('detail_logistik_part.detail_pesanan_part_id', $dppIds)
            ->groupBy('logistik.id')
            ->get();

        //GET NOSERI
        $noseri = NoseriBarangJadi::select('detail_pesanan.id as id', 'detail_pesanan.penjualan_produk_id', 'noseri', 'detail_pesanan.pesanan_id as p_id')
            ->leftJoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
            ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();

        $noseriDsb = NoseriDsb::select('detail_pesanan_dsb.id as id', 'detail_pesanan_dsb.penjualan_produk_id', 'noseri')
            ->leftJoin('detail_pesanan_dsb', 'detail_pesanan_dsb.id', '=', 'noseri_dsb.detail_pesanan_dsb')
            ->whereIN('detail_pesanan_dsb.pesanan_id', $data->pluck('id')->toArray())->get();

        //GET SPAREPART
        $detail_pesanan_part = DetailPesananPart::select(
            'detail_pesanan_part.id',
            'detail_pesanan_part.pesanan_id',
            'detail_pesanan_part.m_sparepart_id',
            'm_sparepart.nama',
            'detail_pesanan_part.harga',
            // DB::raw('(SELECT COALESCE((SUM(dp.jumlah) * dp.harga) + dp.ongkir, 0)
            // FROM detail_pesanan_part AS dp
            // WHERE dp.pesanan_id = detail_pesanan_part.pesanan_id
            // AND dp.m_sparepart_id = detail_pesanan_part.m_sparepart_id) AS harga'),
            DB::raw('(SELECT COALESCE(SUM(dp.jumlah), 0)
            FROM detail_pesanan_part AS dp
            WHERE dp.pesanan_id = detail_pesanan_part.pesanan_id
            AND dp.m_sparepart_id = detail_pesanan_part.m_sparepart_id) AS jumlah'),
            DB::raw('(SELECT COALESCE(SUM(dp.ongkir), 0)
            FROM detail_pesanan_part AS dp
            WHERE dp.pesanan_id = detail_pesanan_part.pesanan_id
            AND dp.m_sparepart_id = detail_pesanan_part.m_sparepart_id) AS ongkir'),
        )
            ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
            ->whereIN('detail_pesanan_part.pesanan_id', $data->pluck('id')->toArray())->get();


        //GET DETAIL PESANAN DSB
        $detail_pesanan_dsb = DetailPesananDsb::select(
            'detail_pesanan_dsb.id',
            'detail_pesanan_dsb.pesanan_id',
            'detail_pesanan_dsb.penjualan_produk_id',
            'penjualan_produk.nama as nama',
            'penjualan_produk.nama_alias as nama_alias',
            'detail_pesanan_dsb.harga',
            // DB::raw('(SELECT COALESCE((SUM(dp.jumlah) * dp.harga) + dp.ongkir, 0)
            // FROM detail_pesanan_dsb AS dp
            // WHERE dp.pesanan_id = detail_pesanan_dsb.pesanan_id
            // AND dp.penjualan_produk_id = detail_pesanan_dsb.penjualan_produk_id) AS harga'),
            DB::raw('(SELECT COALESCE(SUM(dp.jumlah), 0)
            FROM detail_pesanan_dsb AS dp
            WHERE dp.pesanan_id = detail_pesanan_dsb.pesanan_id
            AND dp.penjualan_produk_id = detail_pesanan_dsb.penjualan_produk_id) AS jumlah'),
            DB::raw('(SELECT COALESCE(SUM(dp.ongkir), 0)
            FROM detail_pesanan_dsb AS dp
            WHERE dp.pesanan_id = detail_pesanan_dsb.pesanan_id
            AND dp.penjualan_produk_id = detail_pesanan_dsb.penjualan_produk_id) AS ongkir')
        )
            ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan_dsb.penjualan_produk_id')
            ->whereIN('detail_pesanan_dsb.pesanan_id', $data->pluck('id')->toArray())->get();


        //GET DETAIL PESANAN
        $detail_pesanan = DetailPesanan::select(
            'detail_pesanan.id',
            'detail_pesanan.pesanan_id',
            'detail_pesanan.penjualan_produk_id',
            'penjualan_produk.nama as nama',
            'penjualan_produk.nama_alias as nama_alias',
            'detail_pesanan.harga',
            // DB::raw('(SELECT COALESCE((SUM(dp.jumlah) * dp.harga) + dp.ongkir, 0)
            // FROM detail_pesanan AS dp
            // WHERE dp.pesanan_id = detail_pesanan.pesanan_id
            // AND dp.penjualan_produk_id = detail_pesanan.penjualan_produk_id) AS harga'),
            DB::raw('(SELECT COALESCE(SUM(dp.jumlah), 0)
            FROM detail_pesanan AS dp
            WHERE dp.pesanan_id = detail_pesanan.pesanan_id
            AND dp.penjualan_produk_id = detail_pesanan.penjualan_produk_id) AS jumlah'),
            DB::raw('(SELECT COALESCE(SUM(dp.ongkir), 0)
            FROM detail_pesanan AS dp
            WHERE dp.pesanan_id = detail_pesanan.pesanan_id
            AND dp.penjualan_produk_id = detail_pesanan.penjualan_produk_id) AS ongkir')
        )
            ->selectRaw("CONCAT(detail_pesanan.pesanan_id, '-', detail_pesanan.penjualan_produk_id) AS combined_value")
            ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
            ->whereIN('detail_pesanan.pesanan_id', $data->pluck('id')->toArray())->get();

        //GROUP DATA



        foreach ($noseri as $item) {
            $key = $item['p_id'] . '-' . $item['penjualan_produk_id'];

            if (!isset($groupedDataSeri[$key])) {
                $groupedDataSeri[$key] = [
                    'id' => $item['id'],
                    'p_id' => $key,
                    'data' => []
                ];
            }

            $groupedDataSeri[$key]['data'][] = $item['noseri'];
        }

        foreach ($groupedDataSeri as $g) {
            $noseri_group[] = array(
                "p_id" => $g['p_id'],
                "data" => $g['data']
            );
        }



        //   $groupedDataSeri = collect($noseri)->groupBy('id');
        // $groupedDataSeri = collect($noseri)->groupBy('p_id');
        // $groupedDataSeri = collect($groupedDataSeri)->groupBy('id');

        $groupedDataSeriDsb = collect($noseriDsb)->groupBy('id');
        $groupedDataPrd = collect($detail_pesanan)->groupBy('pesanan_id');
        $groupedDataPrdDsb = collect($detail_pesanan_dsb)->groupBy('pesanan_id');
        $groupedDataPart = collect($detail_pesanan_part)->groupBy('pesanan_id');
        $groupedDataSj = collect($surat_jalan)->groupBy('id')->toArray();
        $groupedDataSjPart = collect($surat_jalan_part)->groupBy('id')->toArray();
        $infoByID = [];
        foreach ($dataInfo as $infoItem) {
            $infoByID[$infoItem->id] = $infoItem;
        }


        //GROUP BY REF ID
        // $noseri_group = $groupedDataSeri->map(function ($items, $key) {
        //     $uniqueItems = $items->unique('noseri')->values()->all();
        //     return [
        //         'id' => $key,
        //         'data' => $uniqueItems,
        //     ];
        // })->values()->all();



        $noseri_groupDsb = $groupedDataSeriDsb->map(function ($items, $key) {
            $uniqueItems = $items->unique('noseri')->values()->all();
            return [
                'id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();

        //GROUP BY REF ID
        $detail_pesanan_part_group = $groupedDataPart->map(function ($items, $key) {
            $uniqueItems = $items->unique('m_sparepart_id')->values()->all();
            return [
                'pesanan_id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();

        //GROUP BY REF ID
        $detail_pesanan_group = $groupedDataPrd->map(function ($items, $key) {
            $uniqueItems = $items->unique('penjualan_produk_id')->values()->all();
            return [
                'pesanan_id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();

        //GROUP BY REF ID DSB
        $detail_pesanan_dsb_group = $groupedDataPrdDsb->map(function ($items, $key) {
            $uniqueItems = $items->unique('penjualan_produk_id')->values()->all();
            return [
                'pesanan_id' => $key,
                'data' => $uniqueItems,
            ];
        })->values()->all();



        // //SET NOSERI TO INDEX
        // $seriByID = [];
        // foreach ($noseri_group as $seriItem) {
        //     $seriByID[$seriItem['id']] = $seriItem['data'];
        // }

        //SET NOSERI TO INDEX
        $seriDsbByID = [];
        foreach ($noseri_groupDsb as $seriItem) {
            $seriDsbByID[$seriItem['id']] = $seriItem['data'];
        }


        //SET INDEX NOSERI TO DETAIL PESANAN
        foreach ($detail_pesanan_group as $key => $pesananItem) {
            foreach ($pesananItem['data'] as $keys => $p) {
                $pesananID = $p['combined_value'];
                $find = collect($noseri_group)->where('p_id', $pesananID)->first();
                if ($find) {
                    $detail_pesanan_group[$key]['data'][$keys]['seri'] = $find['data'];
                } else {
                    $detail_pesanan_group[$key]['data'][$keys]['seri'] = [];
                }
            }
        }
        // $result = array_map(function ($item) {
        //     return $item['data'];
        // }, array_filter($noseri_group, function ($item) {
        //     return $item['p_id'] === '7312-31';
        // }));

        // return $result[0];
        // return response()->json($detail_pesanan_group);


        foreach ($detail_pesanan_dsb_group as $key => $pesananItem) {
            foreach ($pesananItem['data'] as $keys => $p) {
                $pesananID = $p['id'];
                if (isset($seriDsbByID[$pesananID])) {
                    $detail_pesanan_dsb_group[$key]['data'][$keys]['seri'] = $seriDsbByID[$pesananID];
                } else {
                    $detail_pesanan_dsb_group[$key]['data'][$keys]['seri'] = [];
                }
            }
        }
        $pesanan = array();
        //SET PESANAN
        foreach ($data->get() as $d) {
            $pesanan[] = array(
                'id' => $d->id,
                'so' => $d->so,
                'nama' => '-',
                'no_paket' => '-',
                'instansi' => '-',
                'alamat_instansi' => '-',
                'satuan' => '-',
                'no_urut' => '-',
                'tgl_buat' => '-',
                'tgl_kontrak' => '-',
                'status' => '-',
                'po' => $d->no_po,
                'tgl_po' => $d->tgl_po != null ? date('d-m-Y', strtotime($d->tgl_po)) : '-',
                'ket' => $d->ket,
                'log_id' => $d->log_id,
                'nosurat' => [],
                'nosurat_part' => []
            );
        }

        $produkByPesananId = [];
        $produkDsbByPesananId = [];
        $partByPesananId = [];

        foreach ($pesanan as &$pesananItem) {
            $pesananID = $pesananItem['id'];
            if (array_key_exists($pesananID, $groupedDataSj)) {
                // $pesanan[$key]['nosurat'] = $groupedDataSj[$pesananID];
                $pesananItem['nosurat'] = $groupedDataSj[$pesananID];
            }
        }

        foreach ($pesanan as  &$pesananItem) {
            $pesananID = $pesananItem['id'];
            if (array_key_exists($pesananID, $groupedDataSjPart)) {
                $pesananItem['nosurat_part'] = $groupedDataSjPart[$pesananID];
            }
        }

        foreach ($pesanan as  &$pesananItem) {
            $pesananID = $pesananItem['id'];
            if (array_key_exists($pesananID, $infoByID)) {
                $pesananItem['nama'] = $infoByID[$pesananID]->nama;
                $pesananItem['no_paket'] = $infoByID[$pesananID]->no_paket;
                $pesananItem['instansi'] = $infoByID[$pesananID]->instansi;
                $pesananItem['alamat_instansi'] = $infoByID[$pesananID]->alamat_instansi;
                $pesananItem['satuan'] =  $infoByID[$pesananID]->satuan;
                $pesananItem['no_urut'] =  $infoByID[$pesananID]->no_urut;
                $pesananItem['tgl_buat'] =  $infoByID[$pesananID]->tgl_buat;
                $pesananItem['tgl_kontrak'] =  $infoByID[$pesananID]->tgl_kontrak;
                $pesananItem['status'] =  $infoByID[$pesananID]->status;
            }
        }

        // Group $produk array items by pesanan_id
        foreach ($detail_pesanan_part_group as $item) {
            $pesanansId = $item['pesanan_id'];

            // Check if the pesanan_id exists in $produkByPesananId array
            if (!array_key_exists($pesanansId, $partByPesananId)) {
                $partByPesananId[$pesanansId] = [];
            }

            // Add the produk item to the corresponding pesanan_id
            $partByPesananId[$pesanansId][] = $item;
        }


        foreach ($pesanan as &$pesananItem) {
            $pesananId = $pesananItem['id'];

            // Check if pesanan_id exists in $produkByPesananId array
            if (array_key_exists($pesananId, $partByPesananId)) {
                $pesananItem['part'] = $partByPesananId[$pesananId][0]['data'];
            } else {
                $pesananItem['part'] = [];
            }
        }

        //----------------------------------------

        // Group $produk array items by pesanan_id
        foreach ($detail_pesanan_group as $item) {
            $pesananId = $item['pesanan_id'];

            // Check if the pesanan_id exists in $produkByPesananId array
            if (!array_key_exists($pesananId, $produkByPesananId)) {
                $produkByPesananId[$pesananId] = [];
            }

            // Add the produk item to the corresponding pesanan_id
            $produkByPesananId[$pesananId][] = $item;
        }

        // Group $produk array items by pesanan_id
        foreach ($detail_pesanan_dsb_group as $item) {
            $pesananId = $item['pesanan_id'];

            // Check if the pesanan_id exists in $produkByPesananId array
            if (!array_key_exists($pesananId, $produkDsbByPesananId)) {
                $produkDsbByPesananId[$pesananId] = [];
            }

            // Add the produk item to the corresponding pesanan_id
            $produkDsbByPesananId[$pesananId][] = $item;
        }

        // Update $pesanan array with produk items based on pesanan_id
        foreach ($pesanan as &$pesananItem) {
            $pesananId = $pesananItem['id'];

            // Check if pesanan_id exists in $produkByPesananId array
            if (array_key_exists($pesananId, $produkDsbByPesananId)) {
                $pesananItem['produk_dsb'] = $produkDsbByPesananId[$pesananId][0]['data'];
            } else {
                $pesananItem['produk_dsb'] = [];
            }
        }

        foreach ($pesanan as &$pesananItem) {
            $pesananId = $pesananItem['id'];

            // Check if pesanan_id exists in $produkByPesananId array
            if (array_key_exists($pesananId, $produkByPesananId)) {
                $pesananItem['produk'] = $produkByPesananId[$pesananId][0]['data'];
            } else {
                $pesananItem['produk'] = [];
            }
        }
        return response()->json($pesanan);
    }

    public function cek_noretur(Request $request)
    {
        $data = RiwayatReturPo::where('no_retur', $request->no_retur)->count();

        if ($data > 0) {
            return response()->json([
                'status' => 500,
                'message' => 'Duplikasi Nomor Retur',
            ], 500);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Berhasil',
        ], 200);
    }
    public function get_detail_prd_retur_po($id)
    {

        $dpp = DetailPesananProduk::where('detail_pesanan_id', $id);
        $item = array();

        $seri = NoseriDetailLogistik::select('t_gbj_detail.detail_pesanan_produk_id', 't_gbj_noseri.id', 'noseri_barang_jadi.id as noseri_id', 'noseri_barang_jadi.noseri', 'noseri_logistik.id as noseri_logistik_id')
            ->addSelect([
                'item' => function ($q) {
                    $q->selectRaw('coalesce(count(riwayat_retur_po_seri.id),0)')
                        ->from('riwayat_retur_po_seri')
                        ->whereColumn('riwayat_retur_po_seri.noseri_logistik_id', 'noseri_logistik.id');
                }
            ])
            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftjoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
            ->leftjoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->havingRaw('item = 0')
            ->whereIN('t_gbj_detail.detail_pesanan_produk_id', $dpp->pluck('id')->toArray())
            ->get();

        // $seri = NoseriTGbj::select('detail_pesanan_produk_id','t_gbj_noseri.id','noseri_barang_jadi.id as noseri_id','noseri')
        // ->join('t_gbj_detail','t_gbj_detail.id','=','t_gbj_noseri.t_gbj_detail_id')
        // ->join('noseri_barang_jadi','noseri_barang_jadi.id','=','t_gbj_noseri.noseri_id')
        // ->whereIN('t_gbj_detail.detail_pesanan_produk_id',$dpp->pluck('id')->toArray())
        // ->get();

        foreach ($dpp->get()  as $key_p => $d) {
            $item[$key_p] = array(
                'id' => $d->id,
                'gbj_id' => $d->gudang_barang_jadi_id,
                'nama' => $d->GudangBarangJadi->Produk->nama,
                'variasi' => $d->GudangBarangJadi->nama,
                'seri' => array()
            );

            foreach ($seri as  $s) {
                if ($d->id == $s['detail_pesanan_produk_id']) {
                    $item[$key_p]['seri'][] = $s;
                }
            }
        }
        return response()->json($item);
    }
    public function get_detail_paket_retur_po($id)
    {
        $data = DetailPesanan::addSelect([
            'item' => function ($q) {
                $q->selectRaw('coalesce(count(detail_pesanan_produk.id),0)')
                    ->from('detail_pesanan_produk')
                    ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id');
            },
            'seri_log' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                    ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_logistik.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->where('logistik.status_id', 10)
                    ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id');
            },
        ])->where('pesanan_id', $id)->get();

        $obj = array();

        foreach ($data as $d) {
            $jumlah = 0;
            if ($d->getJumlahPrdLog() != $d->item) {
                $jumlah = 0;
            } else {
                $jumlah = $d->seri_log / $d->item;
                $jumlah =  $jumlah - $d->getJumlahRetur();
            }
            $obj[] = array(
                'id' => $d->id,
                'nama' => $d->PenjualanProduk->nama,
                'jumlah_kirim' => $jumlah,
                'jumlah_po' => $d->jumlah
            );
        }
        return response()->json($obj);
    }

    function kirim_batal_po_divisi_semua($divisi, Request $request)
    {
        $id = $request->id;
        $data = RiwayatBatalPoPrd::select('riwayat_batal_po_prd.id', 'detail_pesanan_produk.id as detail_pesanan_produk_id', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id', 'gdg_barang_jadi.id as gudang_barang_jadi_id', 'produk.nama', 'gdg_barang_jadi.nama as variasi', 'produk.merk')
            ->addSelect([
                'c_batal' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                },
                'jumlah_tf' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->where('riwayat_batal_po_seri.status', 0)
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                },
                'jumlah' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                }
            ])
            ->leftJoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', '=', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
            ->leftJoin('riwayat_batal_po', 'riwayat_batal_po.id', '=', 'riwayat_batal_po_paket.riwayat_batal_po_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'riwayat_batal_po_prd.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->havingRaw('c_batal > 0')
            ->where('riwayat_batal_po.id', $id);

        $item = RiwayatBatalPoSeri::select('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_seri.id', 'riwayat_batal_po_seri.t_tfbj_noseri_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_barang_jadi.noseri')
            ->leftjoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'riwayat_batal_po_seri.noseri_id')
            ->whereIN('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', $data->pluck('id')->toArray())
            ->where('riwayat_batal_po_seri.posisi', $divisi)
            ->where('riwayat_batal_po_seri.status', 1)
            ->get();


        $dataPart = RiwayatBatalPoPart::select('riwayat_batal_po_part.id', 'm_sparepart.nama', 'riwayat_batal_po_part.jumlah', 'riwayat_batal_po_part.jumlah as jumlah_sisa', 'riwayat_batal_po_part.detail_pesanan_part_id')
            ->selectRaw('"part" as jenis')
            ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'riwayat_batal_po_part.detail_pesanan_part_id')
            ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
            ->where('posisi', $divisi)
            ->where('status', 1)
            ->where('riwayat_batal_po_part.riwayat_batal_po_id', $id);

        $object = array();
        foreach ($data->get() as $key_p => $d) {
            $object[$key_p] = array(
                'id' => $d->id,
                'detail_pesanan_produk_id' => $d->detail_pesanan_produk_id,
                'detail_riwayat_batal_paket_id' => $d->detail_riwayat_batal_paket_id,
                'gudang_barang_jadi_id' => $d->gudang_barang_jadi_id,
                'nama' => $d->nama,
                'variasi' => $d->variasi,
                'merk' => $d->merk,
                'c_batal' => $d->c_batal,
                'jumlah_tf' => $d->jumlah_tf,
                'jumlah' => $d->jumlah,
                'jenis' => 'produk',
                'noseri' => array(),

            );

            foreach ($item as $s) {
                if ($d->id == $s['detail_riwayat_batal_prd_id']) {
                    $object[$key_p]['noseri'][] = $s;
                }
            }
        }

        foreach ($dataPart->get() as  $d) {
            $object[] = $d;
        }

        $items = new stdClass();
        $items->id = $request->id;
        $items->item = $object;

        $obj =  json_decode(json_encode($items), FALSE);
        // dd($obj);
        DB::beginTransaction();
        try {

            $seri_id = array();
            $seri_batal = array();
            //code...

            foreach ($obj->item as $produk) {
                $jenis_item[] = $produk->jenis;
                if ($produk->jenis == 'part') {
                    $part_id[] = $produk->detail_pesanan_part_id;
                    $batal_part_id[] = $produk->id;
                }
            }
            // dd($jenis_item);
            if (in_array('produk', $jenis_item)) {
                $tf = TFProduksi::create([
                    'batal_pesanan_id' => $request->id,
                    'dari' => $divisi == 'qc' ? 23 : 15,
                    'ke' => 13,
                    'deskripsi' => 'Batal Pesanan',
                    'tgl_masuk' => Carbon::now(),
                    'jenis' => 'masuk'
                ]);

                foreach ($obj->item as $produk) {
                    if ($produk->jenis == 'produk') {
                        # code...
                        $tfd = TFProduksiDetail::create([
                            't_gbj_id' => $tf->id,
                            'gdg_brg_jadi_id' => $produk->gudang_barang_jadi_id,
                            'qty' => count($produk->noseri),
                            'jenis' => 'masuk'
                        ]);
                        foreach ($produk->noseri as $seri) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $tfd->id,
                                'noseri_id' =>  $seri->noseri_id,
                                'jenis' => 'masuk'
                            ]);
                            $seri_id[] =  $seri->t_tfbj_noseri_id;
                            $seri_batal[] =  $seri->id;
                        }
                    }
                }
            }

            if ($divisi == 'qc') {
                if (in_array('produk', $jenis_item)) {
                    $ndp = NoseriDetailPesanan::whereIN('t_tfbj_noseri_id', $seri_id);
                    if ($ndp->count() > 0) {
                        // NoseriDetailPesanan::whereIN('id', $ndp->pluck('id')->toArray())->delete();
                        // NoseriTGbj::whereIN('id', $seri_id)->delete();
                        RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                            'status' => 0
                        ]);
                    } else {
                        RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                            'status' => 0
                        ]);
                        // NoseriTGbj::whereIN('id', $seri_id)->delete();
                    }
                }

                if (in_array('part', $jenis_item)) {
                    // $opp = OutgoingPesananPart::whereIN('detail_pesanan_part_id', $part_id);
                    RiwayatBatalPoPart::whereIN('id', $batal_part_id)->update([
                        'status' => 0
                    ]);

                    // if ($opp->count() > 0) {
                    //     $opp->delete();
                    // }
                }

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Di tambahkan',
                ], 200);
            }

            if ($divisi == 'logistik') {
                if (in_array('produk', $jenis_item)) {
                    $ndl = NoseriDetailLogistik::select('noseri_logistik.id')
                        ->join('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->whereIN('noseri_detail_pesanan.t_tfbj_noseri_id', $seri_id);

                    RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                        'status' => 0
                    ]);

                    if ($ndl->count() > 0) {
                        foreach ($ndl->get() as $noseri) {

                            // //Cek Noseri
                            // $seriLog =  NoseriDetailLogistik::find($noseri->id);

                            // //Cek Detail Logistik
                            // $detail = DetailLogistik::find($seriLog->detail_logistik_id);
                            // $detailId = $detail->id;

                            // //Cek Logistik
                            // $log = Logistik::find($detail->logistik_id);
                            // $logId = $log->id;

                            // //Cek Logistik Part
                            // $partLog = DetailLogistikPart::where('logistik_id', $logId)->count();

                            // //Hapus Noseri
                            // NoseriDetailLogistik::where('id', $noseri->id)->delete();

                            // //Cek dan Hapus
                            // $cekNdl = NoseriDetailLogistik::where('detail_logistik_id', $detailId)->count();

                            // //dd($cekNdl);

                            // if ($cekNdl == 0) {
                            //     DetailLogistik::where('id', $detailId)->delete();
                            // }

                            // $cekL = DetailLogistik::where('logistik_id', $logId)->count();

                            // if ($cekL == 0 && $partLog == 0) {
                            //     Logistik::where('id', $logId)->delete();
                            // }
                        }
                    } else {
                        // NoseriDetailPesanan::whereIN('t_tfbj_noseri_id', $seri_id)->delete();
                        // NoseriTGbj::whereIN('id', $seri_id)->delete();
                        RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                            'status' => 0
                        ]);
                    }
                }

                if (in_array('part', $jenis_item)) {
                    $dlp = DetailLogistikPart::whereIN('detail_pesanan_part_id', $part_id);

                    RiwayatBatalPoPart::whereIN('id', $batal_part_id)->update([
                        'status' => 0
                    ]);

                    if ($dlp->count() > 0) {
                        // foreach ($dlp->get() as $d) {

                        //     $logId = $d->logistik_id;
                        //     DetailLogistikPart::where('id', $d->id)->delete();

                        //     $partLog =  DetailLogistikPart::where('logistik_id', $logId)->count();
                        //     $cekL = DetailLogistik::where('logistik_id', $logId)->count();

                        //     if ($cekL == 0 && $partLog == 0) {
                        //         Logistik::where('id', $logId)->delete();
                        //     }
                        // }
                    } else {
                        // $opp = OutgoingPesananPart::whereIN('detail_pesanan_part_id', $part_id);
                        RiwayatBatalPoPart::whereIN('id', $batal_part_id)->update([
                            'status' => 0
                        ]);

                        // if ($opp->count() > 0) {
                        //     $opp->delete();
                        // }
                    }
                }

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Di tambahkan',
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal Di tambahkan' . $th->getMessage(),
            ], 500);
        }
        // DB::beginTransaction();
        // try {
        //     $seri_id = array();
        //     $seri_batal = array();
        //     //code...
        //     $tf = TFProduksi::create([
        //         'batal_pesanan_id' => $request->id,
        //         'dari' => $divisi == 'qc' ? 23 : 15,
        //         'ke' => 13,
        //         'deskripsi' => 'Batal Pesanan',
        //         'tgl_masuk' => Carbon::now(),
        //         'jenis' => 'masuk'
        //     ]);
        //     foreach ($obj->item as $produk) {
        //         # code...
        //         $tfd = TFProduksiDetail::create([
        //             't_gbj_id' => $tf->id,
        //             'gdg_brg_jadi_id' => $produk->gudang_barang_jadi_id,
        //             'qty' => count($produk->noseri),
        //             'jenis' => 'masuk'
        //         ]);
        //         foreach ($produk->noseri as $seri) {
        //                 NoseriTGbj::create([
        //                 't_gbj_detail_id' => $tfd->id,
        //                 'noseri_id' =>  $seri->noseri_id,
        //                 'jenis' => 'masuk'
        //             ]);
        //                 $seri_id[] =  $seri->t_tfbj_noseri_id;
        //                 $seri_batal[] =  $seri->id;
        //         }
        //     }

        //     if($divisi == 'qc'){
        //         $ndp = NoseriDetailPesanan::whereIN('t_tfbj_noseri_id',$seri_id);
        //         if($ndp->count() > 0){
        //             NoseriDetailPesanan::whereIN('id', $ndp->pluck('id')->toArray())->delete();
        //             NoseriTGbj::whereIN('id', $seri_id)->delete();
        //             RiwayatBatalPoSeri::whereIN('id',$seri_batal)->update([
        //                 'status' => 0
        //             ]);

        //             DB::commit();
        //             return response()->json([
        //                 'status' => 200,
        //                 'message' => 'Berhasil Di tambahkan',
        //             ], 200);

        //         }else{
        //             RiwayatBatalPoSeri::whereIN('id',$seri_batal)->update([
        //                 'status' => 0
        //             ]);
        //             NoseriTGbj::whereIN('id', $seri_id)->delete();

        //             DB::commit();
        //             return response()->json([
        //                 'status' => 200,
        //                 'message' => 'Berhasil Di tambahkan',
        //             ], 200);
        //         }
        //     }

        //     if($divisi == 'log'){
        //         $ndl = NoseriDetailLogistik::select('noseri_logistik.id')
        //         ->join('noseri_detail_pesanan','noseri_detail_pesanan.id','=','noseri_logistik.noseri_detail_pesanan_id')
        //         ->whereIN('noseri_detail_pesanan.t_tfbj_noseri_id',$seri_id);

        //           RiwayatBatalPoSeri::whereIN('id',$seri_batal)->update([
        //                 'status' => 0
        //             ]);

        //         if($ndl->count() > 0){
        //             foreach($ndl->get() as $noseri){

        //                     //Cek Noseri
        //                     $seriLog =  NoseriDetailLogistik::find($noseri->id);

        //                     //Cek Detail Logistik
        //                     $detail = DetailLogistik::find($seriLog->detail_logistik_id);
        //                     $detailId = $detail->id;

        //                     //Cek Logistik
        //                     $log = Logistik::find($detail->logistik_id);
        //                     $logId = $log->id;

        //                     //Hapus Noseri
        //                     NoseriDetailLogistik::where('id',$noseri->id)->delete();

        //                     //Cek dan Hapus
        //                     $cekNdl = NoseriDetailLogistik::where('detail_logistik_id',$detailId)->count();

        //                     //dd($cekNdl);

        //                     if($cekNdl == 0){
        //                         DetailLogistik::where('id',$detailId)->delete();
        //                     }

        //                     $cekL = DetailLogistik::where('logistik_id',$logId)->count();

        //                     if($cekL == 0){
        //                         Logistik::where('id',$logId)->delete();
        //                     }
        //             }

        //             DB::commit();
        //             return response()->json([
        //                 'status' => 200,
        //                 'message' => 'Berhasil Di tambahkan',
        //             ], 200);

        //         }else{
        //             NoseriDetailPesanan::whereIN('t_tfbj_noseri_id',$seri_id)->delete();
        //             NoseriTGbj::whereIN('id', $seri_id)->delete();
        //             RiwayatBatalPoSeri::whereIN('id',$seri_batal)->update([
        //                 'status' => 0
        //             ]);

        //             DB::commit();
        //             return response()->json([
        //                 'status' => 200,
        //                 'message' => 'Berhasil Di tambahkan',
        //             ], 200);
        //         }
        //     }
        // } catch (\Throwable $th) {
        //     //throw $th;
        //     DB::rollback();
        //     return response()->json([
        //         'status' => 500,
        //         'message' => 'Gagal Di tambahkan'.$th->getMessage(),
        //     ], 500);
        // }
    }

    function kirim_batal_po_divisi($divisi, Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);

        DB::beginTransaction();
        try {
            $seri_id = array();
            $seri_batal = array();
            //code...

            foreach ($obj->item as $produk) {
                $jenis_item[] = $produk->jenis;
                if ($produk->jenis == 'part') {
                    $part_id[] = $produk->detail_pesanan_part_id;
                    $batal_part_id[] = $produk->id;
                }
            }

            if (in_array('produk', $jenis_item)) {
                $tf = TFProduksi::create([
                    'batal_pesanan_id' => $request->id,
                    'dari' => $divisi == 'qc' ? 23 : 15,
                    'ke' => 13,
                    'deskripsi' => 'Batal Pesanan',
                    'tgl_masuk' => Carbon::now(),
                    'jenis' => 'masuk'
                ]);

                foreach ($obj->item as $produk) {
                    if ($produk->jenis == 'produk') {
                        # code...
                        $tfd = TFProduksiDetail::create([
                            't_gbj_id' => $tf->id,
                            'gdg_brg_jadi_id' => $produk->gudang_barang_jadi_id,
                            'qty' => count($produk->noseri),
                            'jenis' => 'masuk'
                        ]);
                        foreach ($produk->noseri as $seri) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $tfd->id,
                                'noseri_id' =>  $seri->noseri_id,
                                'jenis' => 'masuk'
                            ]);
                            $seri_id[] =  $seri->t_tfbj_noseri_id;
                            $seri_batal[] =  $seri->id;
                        }
                    }
                }
            }

            if ($divisi == 'qc') {
                if (in_array('produk', $jenis_item)) {
                    RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                        'status' => 0
                    ]);
                    // $ndp = NoseriDetailPesanan::whereIN('t_tfbj_noseri_id', $seri_id);
                    // if ($ndp->count() > 0) {
                    //     NoseriDetailPesanan::whereIN('id', $ndp->pluck('id')->toArray())->delete();
                    //     NoseriTGbj::whereIN('id', $seri_id)->delete();
                    //     RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                    //         'status' => 0
                    //     ]);


                    // } else {
                    //     RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                    //         'status' => 0
                    //     ]);
                    //     NoseriTGbj::whereIN('id', $seri_id)->delete();
                    // }

                }
                if (in_array('part', $jenis_item)) {
                    $opp = OutgoingPesananPart::whereIN('detail_pesanan_part_id', $part_id);
                    RiwayatBatalPoPart::whereIN('id', $batal_part_id)->update([
                        'status' => 0
                    ]);

                    // if ($opp->count() > 0) {
                    //     $opp->delete();
                    // }
                }

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Di tambahkan',
                ], 200);
            }

            if ($divisi == 'logistik') {
                if (in_array('produk', $jenis_item)) {
                    // $ndl = NoseriDetailLogistik::select('noseri_logistik.id')
                    //     ->join('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    //     ->whereIN('noseri_detail_pesanan.t_tfbj_noseri_id', $seri_id);

                    RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                        'status' => 0
                    ]);

                    // if ($ndl->count() > 0) {
                    //     foreach ($ndl->get() as $noseri) {

                    //         //Cek Noseri
                    //         $seriLog =  NoseriDetailLogistik::find($noseri->id);

                    //         //Cek Detail Logistik
                    //         $detail = DetailLogistik::find($seriLog->detail_logistik_id);
                    //         $detailId = $detail->id;

                    //         //Cek Logistik
                    //         $log = Logistik::find($detail->logistik_id);
                    //         $logId = $log->id;

                    //         //Cek Logistik Part
                    //         $partLog = DetailLogistikPart::where('logistik_id', $logId)->count();

                    //         //Hapus Noseri
                    //         NoseriDetailLogistik::where('id', $noseri->id)->delete();

                    //         //Cek dan Hapus
                    //         $cekNdl = NoseriDetailLogistik::where('detail_logistik_id', $detailId)->count();

                    //         //dd($cekNdl);

                    //         if ($cekNdl == 0) {
                    //             DetailLogistik::where('id', $detailId)->delete();
                    //         }

                    //         $cekL = DetailLogistik::where('logistik_id', $logId)->count();

                    //         if ($cekL == 0 && $partLog == 0) {
                    //             Logistik::where('id', $logId)->delete();
                    //         }
                    //     }


                    // } else {
                    //     NoseriDetailPesanan::whereIN('t_tfbj_noseri_id', $seri_id)->delete();
                    //     NoseriTGbj::whereIN('id', $seri_id)->delete();
                    //     RiwayatBatalPoSeri::whereIN('id', $seri_batal)->update([
                    //         'status' => 0
                    //     ]);


                    // }
                }


                if (in_array('part', $jenis_item)) {
                    $dlp = DetailLogistikPart::whereIN('detail_pesanan_part_id', $part_id);

                    RiwayatBatalPoPart::whereIN('id', $batal_part_id)->update([
                        'status' => 0
                    ]);

                    // if ($dlp->count() > 0) {
                    //     foreach ($dlp->get() as $d) {

                    //         $logId = $d->logistik_id;
                    //         DetailLogistikPart::where('id', $d->id)->delete();

                    //         $partLog =  DetailLogistikPart::where('logistik_id', $logId)->count();
                    //         $cekL = DetailLogistik::where('logistik_id', $logId)->count();

                    //         if ($cekL == 0 && $partLog == 0) {
                    //             Logistik::where('id', $logId)->delete();
                    //         }
                    //     }
                    // } else {
                    //     $opp = OutgoingPesananPart::whereIN('detail_pesanan_part_id', $part_id);
                    //     RiwayatBatalPoPart::whereIN('id', $batal_part_id)->update([
                    //         'status' => 0
                    //     ]);

                    //     if ($opp->count() > 0) {
                    //         $opp->delete();
                    //     }
                    // }
                }

                DB::commit();
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Di tambahkan',
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal Di tambahkan' . $th->getMessage(),
            ], 500);
        }
    }

    function seri_batal_po_divisi($divisi, $id)
    {
        $data = RiwayatBatalPoSeri::select('riwayat_batal_po_seri.id', 'riwayat_batal_po_seri.t_tfbj_noseri_id', 'noseri_barang_jadi.id as noseri_id', 'noseri_barang_jadi.noseri')
            ->leftjoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 'riwayat_batal_po_seri.noseri_id')
            ->where('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', $id)
            ->where('riwayat_batal_po_seri.posisi', $divisi)
            ->where('riwayat_batal_po_seri.status', 1)
            ->get();
        return response()->json($data);
    }

    function detail_batal_po_divisi($divisi, $id)
    {
        $data = RiwayatBatalPoPaket::select('riwayat_batal_po_paket.id', 'penjualan_produk.nama')
            ->addSelect([
                'c_batal' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftJoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_prd.detail_riwayat_batal_paket_id', 'riwayat_batal_po_paket.id')
                        ->limit(1);
                },
            ])
            ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'riwayat_batal_po_paket.detail_pesanan_id')
            ->leftJoin('penjualan_produk', 'penjualan_produk.id', '=', 'detail_pesanan.penjualan_produk_id')
            ->havingRaw('c_batal > 0')
            ->where('riwayat_batal_po_id', $id);

        $dataPart = RiwayatBatalPoPart::select('riwayat_batal_po_part.id', 'm_sparepart.nama', 'riwayat_batal_po_part.jumlah', 'riwayat_batal_po_part.jumlah as jumlah_sisa', 'riwayat_batal_po_part.detail_pesanan_part_id')
            ->selectRaw('"part" as jenis')
            ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'riwayat_batal_po_part.detail_pesanan_part_id')
            ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
            ->where('posisi', $divisi)
            ->where('status', 1)
            ->where('riwayat_batal_po_part.riwayat_batal_po_id', $id);

        $item = RiwayatBatalPoPrd::select('riwayat_batal_po_prd.id', 'detail_pesanan_produk.id as detail_pesanan_produk_id', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id', 'gdg_barang_jadi.id as gudang_barang_jadi_id', 'produk.nama', 'gdg_barang_jadi.nama as variasi', 'produk.merk')
            ->addSelect([
                'c_batal' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                },
                'jumlah_tf' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->where('riwayat_batal_po_seri.status', 0)
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                },
                'jumlah' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->whereColumn('riwayat_batal_po_seri.detail_riwayat_batal_prd_id', 'riwayat_batal_po_prd.id')
                        ->limit(1);
                }
            ])
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'riwayat_batal_po_prd.detail_pesanan_produk_id')
            ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->havingRaw('c_batal > 0')
            ->whereIN('detail_riwayat_batal_paket_id', $data->pluck('id')->toArray())->get();

        $obj = array();

        foreach ($data->get() as $key_p => $d) {
            $obj[$key_p] = array(
                'id' => $d->id,
                'nama' => $d->nama,
                'produk' => array(),
                'jenis' => 'produk'
            );

            foreach ($item as  $s) {
                if ($d->id == $s['detail_riwayat_batal_paket_id']) {
                    $s['jumlah_sisa'] = $s->jumlah - $s->jumlah_tf;
                    $s['jenis'] = 'produk';
                    $obj[$key_p]['produk'][] = $s;
                }
            }
        }

        foreach ($dataPart->get() as  $d) {
            $obj[] = array(
                'id' => $d->id,
                'detail_pesanan_part_id' => $d->detail_pesanan_part_id,
                'nama' => $d->nama,
                'jumlah' => $d->jumlah,
                'jenis' => 'part',
            );
        }
        return response()->json($obj);
    }

    public function batal_po_show_divisi($divisi)
    {

        $data = RiwayatBatalPo::select('riwayat_batal_po.id', 'riwayat_batal_po.ket', 'pesanan.so', 'pesanan.no_po', 'c_ekat.nama as c_ekat', 'c_spa.nama as c_spa', 'c_spb.nama as c_spb')
            ->addSelect([
                'c_batal_part' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_part.id),0)')
                        ->from('riwayat_batal_po_part')
                        ->where('riwayat_batal_po_part.posisi', $divisi)
                        ->where('riwayat_batal_po_part.status', 1)
                        ->whereColumn('riwayat_batal_po_part.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                },
                'c_batal_semua' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_part.id),0)')
                        ->from('riwayat_batal_po_part')
                        ->where('riwayat_batal_po_part.posisi', $divisi)
                        ->whereColumn('riwayat_batal_po_part.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                },
                'c_batal_part_tf' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_part.id),0)')
                        ->from('riwayat_batal_po_part')
                        ->where('riwayat_batal_po_part.posisi', $divisi)
                        ->where('riwayat_batal_po_part.status', 0)
                        ->whereColumn('riwayat_batal_po_part.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                },
                'c_batal' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftJoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->leftJoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->where('riwayat_batal_po_seri.status', 1)
                        ->whereColumn('riwayat_batal_po_paket.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                },
                'c_batal_tf' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftJoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->leftJoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->where('riwayat_batal_po_seri.status', 0)
                        ->whereColumn('riwayat_batal_po_paket.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                },
                'c_batal_semua' => function ($q) use ($divisi) {
                    $q->selectRaw('coalesce(count(riwayat_batal_po_seri.id),0)')
                        ->from('riwayat_batal_po_seri')
                        ->leftJoin('riwayat_batal_po_prd', 'riwayat_batal_po_prd.id', 'riwayat_batal_po_seri.detail_riwayat_batal_prd_id')
                        ->leftJoin('riwayat_batal_po_paket', 'riwayat_batal_po_paket.id', 'riwayat_batal_po_prd.detail_riwayat_batal_paket_id')
                        ->where('riwayat_batal_po_seri.posisi', $divisi)
                        ->whereColumn('riwayat_batal_po_paket.riwayat_batal_po_id', 'riwayat_batal_po.id')
                        ->limit(1);
                }
            ])
            ->leftJoin('pesanan', 'pesanan.id', '=', 'riwayat_batal_po.pesanan_id')
            ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
            ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
            ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
            ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spa.customer_id')
            ->havingRaw('c_batal > 0 or c_batal_part > 0')
            ->get();

        $obj = array();
        foreach ($data as $d) {
            # code...
            $customer = '';
            if ($d->c_ekat != null) {
                $customer = $d->c_ekat;
            }
            if ($d->c_spb != null) {
                $customer = $d->c_spb;
            }
            if ($d->c_spa != null) {
                $customer = $d->c_spa;
            }

            $obj[] = array(
                'id' => $d->id,
                'so' => $d->so,
                'no_po' => $d->no_po,
                'customer' => $customer,
                'ket' => $d->ket,
                'jumlah' => $d->c_batal_semua + $d->c_batal_semua,
                'jumlah_tf' => $d->c_batal_tf + $d->c_batal_part_tf
            );
        }

        return response()->json($obj);
    }
    public function get_detail_paket_batal_po($id)
    {

        $prd = DetailPesanan::addSelect([
            'item' => function ($q) {
                $q->selectRaw('coalesce(count(detail_pesanan_produk.id),0)')
                    ->from('detail_pesanan_produk')
                    ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id');
            },
            'seri_log' => function ($q) {
                $q->selectRaw('coalesce(count(t_gbj_noseri.id),0)')
                    ->from('t_gbj_noseri')
                    ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('detail_pesanan_produk', 't_gbj_detail.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                    ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id');
            },
        ])
            // ->havingRaw('seri_log > 0')
            ->where('pesanan_id', $id);

        $part = DetailPesananPart::addSelect([
            'c_uji' => function ($q) {
                $q->selectRaw('coalesce(count(outgoing_pesanan_part.id),0)')
                    ->from('outgoing_pesanan_part')
                    ->where('outgoing_pesanan_part.is_ready', '1')
                    ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
            },
            'c_log' => function ($q) {
                $q->selectRaw('coalesce(count(outgoing_pesanan_part.id),0)')
                    ->from('outgoing_pesanan_part')
                    ->where('outgoing_pesanan_part.is_ready', '0')
                    ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
            },
            'c_sj' => function ($q) {
                $q->selectRaw('coalesce(count(detail_logistik_part.id),0)')
                    ->from('detail_logistik_part')
                    ->whereColumn('detail_logistik_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
            },
        ])->where('pesanan_id', $id);

        $obj = array();

        if ($prd->count() > 0) {
            foreach ($prd->get() as $key_d => $d) {
                // $jumlah = 0;
                // if ($d->getJumlahPrdTf() != $d->item) {
                //     $jumlah = 0;
                // } else {
                //     $jumlah = $d->seri_log / $d->item;
                //     $jumlah =  $jumlah - $d->getJumlahBatal();
                // }
                $obj[] = array(
                    'id' => $d->id,
                    'nama' => $d->PenjualanProduk->nama,
                    'qty' => $d->jumlah - $d->getJumlahBatal(),
                    'jumlah_po' => $d->jumlah,
                    'jenis' => 'produk',
                    'produk' => array()
                );

                foreach ($d->DetailPesananProduk as $key_e => $e) {
                    $obj[$key_d]['produk'][$key_e] = array(
                        'id' => $e->id,
                        'nama' => $e->GudangBarangjadi->nama,
                        'gudang_barang_jadi_id' => $e->gudang_barang_jadi_id
                    );
                }
            }
        }

        if ($part->count() > 0) {
            foreach ($part->get() as $d) {
                $p = 0;
                if ($d->c_uji > 0) {
                    $p = 1;
                }
                if ($d->c_log > 0 || $d->c_sj > 0) {
                    $p = 2;
                }

                $ps = ['po', 'qc', 'logistik'];
                $obj[] = array(
                    'id' => $d->id,
                    'nama' => $d->Sparepart->nama,
                    'qty' => $d->jumlah - $d->getJumlahBatal(),
                    'jenis' => $d->Sparepart->jenis,
                    'posisi' => $ps[$p],
                );
            }
        }
        return response()->json($obj);

        // $data = Pesanan::find($id);
        // $item = array();

        // if ($data->DetailPesanan) {
        //     foreach ($data->DetailPesanan as $key_d => $d) {
        //         $item[$key_d] = array(
        //             'id' => $d->id,
        //             'nama' => $d->PenjualanProduk->nama,
        //             'qty' => $d->jumlah,
        //             'produk' => array(),
        //             'jenis' => 'produk'
        //         );
        //         foreach ($d->DetailPesananProduk as $key_e => $e) {
        //             $item[$key_d]['produk'][$key_e] = array(
        //                 'id' => $e->id,
        //                 'gudang_barang_jadi_id' => $e->gudang_barang_jadi_id
        //             );
        //         }
        //     }
        // }

        // if ($data->DetailPesananPart) {
        //     foreach ($data->DetailPesananPart as $d) {
        //         $item[] = array(
        //             'id' => $d->id,
        //             'nama' => $d->Sparepart->nama,
        //             'jumlah' => $d->jumlah - $d->getJumlahBatal(),
        //             'jenis' => 'part'
        //         );
        //     }
        // }
        // return response()->json($item);
    }

    public function kirim_prd_retur_po(Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        DB::beginTransaction();
        try {
            //code...
            foreach ($obj->item as $item) {
                $rpo =  RiwayatReturPo::create([
                    'pesanan_id' => $obj->pesanan_id,
                    'no_retur' => $obj->no_retur,
                ]);
                $p =  RiwayatReturPoPaket::create([
                    'detail_pesanan_id' => $item->id,
                    'riwayat_retur_po_id' => $rpo->id,
                    'jumlah' => $item->jml_retur,
                ]);
                $tf = TFProduksi::create([
                    'retur_pesanan_id' => $rpo->id,
                    'dari' => 26,
                    'ke' => 13,
                    'deskripsi' => $obj->no_retur,
                    'tgl_masuk' => Carbon::now(),
                    'jenis' => 'masuk'
                ]);
                foreach ($item->produk as $produk) {
                    $r =  RiwayatReturPoPrd::create([
                        'detail_riwayat_retur_paket_id' => $p->id,
                        'detail_pesanan_produk_id' => $produk->id,
                        'gudang_barang_jadi_id' => $produk->gbj_id,
                    ]);

                    $tfd = TFProduksiDetail::create([
                        't_gbj_id' => $tf->id,
                        'gdg_brg_jadi_id' => $produk->gbj_id,
                        'qty' => count($produk->noSeriSelected),
                        'jenis' => 'masuk'
                    ]);

                    foreach ($produk->noSeriSelected as $seri) {
                        RiwayatReturPoSeri::create([
                            'detail_riwayat_retur_prd_id' => $r->id,
                            't_tfbj_noseri_id' => $seri->id,
                            'noseri_id' => $seri->noseri_id,
                            'noseri_logistik_id' => $seri->noseri_logistik_id,
                        ]);

                        NoseriTGbj::create([
                            't_gbj_detail_id' => $tfd->id,
                            'noseri_id' =>  $seri->noseri_id,
                            'jenis' => 'masuk'
                        ]);
                    }
                }
                // $riwayat = RiwayatReturPoPaket::where('detail_pesanan_id', $item->id);
                // $dpid = DetailPesanan::find($item->id);
                // if ($riwayat->count() > 0) {

                //     $riwayats = $riwayat->first();
                //     $riwayats->jumlah = $riwayats->jumlah + $item->jml_retur;
                //     $riwayats->save();

                //     $tf = TFProduksi::create([
                //         'retur_pesanan_id' => $dpid->pesanan_id,
                //         'dari' => 26,
                //         'ke' => 13,
                //         'deskripsi' => 'Retur Penjualan',
                //         'tgl_masuk' => Carbon::now(),
                //         'jenis' => 'masuk'
                //     ]);

                //     foreach ($item->produk as $produk) {
                //         $riwayat_prd = RiwayatReturPoPrd::where(['detail_pesanan_produk_id' => $produk->id, 'detail_riwayat_retur_paket_id' => $riwayats->id, 'gudang_barang_jadi_id' => $produk->gbj_id]);

                //         $tfd = TFProduksiDetail::create([
                //             't_gbj_id' => $tf->id,
                //             'gdg_brg_jadi_id' => $produk->gbj_id,
                //             'qty' => count($produk->noSeriSelected),
                //             'jenis' => 'masuk'
                //         ]);

                //         foreach ($produk->noSeriSelected as $seri) {
                //             RiwayatReturPoSeri::create([
                //                 'detail_riwayat_retur_prd_id' => $riwayat_prd->first()->id,
                //                 'detail_pesanan_produk_id' => $riwayat_prd->first()->detail_pesanan_produk_id,
                //                 't_tfbj_noseri_id' => $seri->id,
                //                 'noseri_id' => $seri->noseri_id,
                //                 'noseri_logistik_id' => $seri->noseri_logistik_id,
                //             ]);

                //             $tfd = NoseriTGbj::create([
                //                 't_gbj_detail_id' => $tfd->id,
                //                 'noseri_id' =>  $seri->noseri_id,
                //                 'jenis' => 'masuk'
                //             ]);
                //         }
                //     }
                // } else {
                //     $p =  RiwayatReturPoPaket::create([
                //         'detail_pesanan_id' => $item->id,
                //         'jumlah' => $item->jml_retur,
                //     ]);

                //     $tf = TFProduksi::create([
                //         'retur_pesanan_id' => $dpid->pesanan_id,
                //         'dari' => 26,
                //         'ke' => 13,
                //         'deskripsi' => 'Retur Penjualan',
                //         'tgl_masuk' => Carbon::now(),
                //         'jenis' => 'masuk'
                //     ]);

                //     foreach ($item->produk as $produk) {
                //         $r =  RiwayatReturPoPrd::create([
                //             'detail_riwayat_retur_paket_id' => $p->id,
                //             'detail_pesanan_produk_id' => $produk->id,
                //             'gudang_barang_jadi_id' => $produk->gbj_id,
                //         ]);

                //         $tfd = TFProduksiDetail::create([
                //             't_gbj_id' => $tf->id,
                //             'gdg_brg_jadi_id' => $produk->gbj_id,
                //             'qty' => count($produk->noSeriSelected),
                //             'jenis' => 'masuk'
                //         ]);

                //         foreach ($produk->noSeriSelected as $seri) {
                //             RiwayatReturPoSeri::create([
                //                 'detail_riwayat_retur_prd_id' => $r->id,
                //                 't_tfbj_noseri_id' => $seri->id,
                //                 'noseri_id' => $seri->noseri_id,
                //                 'noseri_logistik_id' => $seri->noseri_logistik_id,
                //             ]);

                //             $tfd = NoseriTGbj::create([
                //                 't_gbj_detail_id' => $tfd->id,
                //                 'noseri_id' =>  $seri->noseri_id,
                //                 'jenis' => 'masuk'
                //             ]);
                //         }
                //     }
                // }
            }
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => 404,
                'message' => 'Gagal Dikirim' . $th,
            ], 200);
        }
    }

    public function kirim_prd_batal_po(Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        // dd($obj);


        DB::beginTransaction();
        try {


            // $tgbj = TFProduksi::where('pesanan_id', $obj->pesanan_id);
            $po = RiwayatBatalPo::where('pesanan_id', $obj->pesanan_id);

            // if ($tgbj->count() > 0) {
            if ($po->count() > 0) {
                foreach ($obj->item as $item) {
                    if ($item->jenis == 'produk') {
                        $riwayatPrd = RiwayatBatalPoPaket::where('detail_pesanan_id', $item->id);
                        if ($riwayatPrd->count() > 0) {
                            $riwayats = $riwayatPrd->first();
                            $riwayats->jumlah = $riwayats->jumlah + $item->jumlah;
                            $riwayats->save();
                        } else {
                            $rb =   RiwayatBatalPoPaket::create([
                                'riwayat_batal_po_id' => $po->first()->id,
                                'detail_pesanan_id' => $item->id,
                                'jumlah' => $item->jumlah,
                            ]);

                            foreach ($item->produk as $produk) {
                                RiwayatBatalPoPrd::create([
                                    'detail_riwayat_batal_paket_id' => $rb->id,
                                    'gudang_barang_jadi_id' => $produk->gudang_barang_jadi_id,
                                    'detail_pesanan_produk_id' => $produk->id
                                ]);
                            }
                        }
                    } else {
                        $riwayatPart = RiwayatBatalPoPart::where('detail_pesanan_part_id', $item->id);
                        if ($riwayatPart->count() > 0) {
                            $riwayats = $riwayatPart->first();
                            $riwayats->jumlah = $riwayats->jumlah + $item->jumlah;
                            $riwayats->save();
                        } else {
                            RiwayatBatalPoPart::create([
                                'riwayat_batal_po_id' => $po->first()->id,
                                'detail_pesanan_part_id' => $item->id,
                                'jumlah' => $item->jumlah,
                                'jenis' => $item->jenis,
                                'posisi' => $item->posisi,
                                'status' => $item->posisi == 'po' ? 0 : 1,
                            ]);
                        }
                    }
                }
            } else {
                $po =  RiwayatBatalPo::create([
                    'pesanan_id' => $obj->pesanan_id,
                    'ket' => $obj->ket,
                ]);

                foreach ($obj->item as $item) {
                    if ($item->jenis == 'produk') {
                        $rb =     RiwayatBatalPoPaket::create([
                            'riwayat_batal_po_id' => $po->id,
                            'detail_pesanan_id' => $item->id,
                            'jumlah' => $item->jumlah,
                        ]);

                        foreach ($item->produk as $produk) {
                            RiwayatBatalPoPrd::create([
                                'detail_riwayat_batal_paket_id' => $rb->id,
                                'gudang_barang_jadi_id' => $produk->gudang_barang_jadi_id,
                                'detail_pesanan_produk_id' => $produk->id
                            ]);
                        }
                    } else {
                        RiwayatBatalPoPart::create([
                            'riwayat_batal_po_id' => $po->id,
                            'detail_pesanan_part_id' => $item->id,
                            'jumlah' => $item->jumlah,
                            'jenis' => $item->jenis,
                            'posisi' => $item->posisi,
                            'status' => $item->posisi == 'po' ? 0 : 1,
                        ]);
                    }
                }
            }
            //   } else {
            $itemx['item'] = $obj->item;
            $itemx['ket'] =  $obj->ket;

            SystemLog::create([
                'tipe' => 'Penjualan',
                'header' =>  $obj->pesanan_id,
                'subjek' =>   'Batal PO',
                'response' =>   json_encode($itemx),
            ]);
            //}


            //Penjualan PO
            $j_po = Pesanan::find($obj->pesanan_id)->DetailPesanan->sum('jumlah');
            $j_poPart = Pesanan::find($obj->pesanan_id)->DetailPesananPart->sum('jumlah');

            //Riwayat Batal
            $j_batal = RiwayatBatalPo::where('pesanan_id', $obj->pesanan_id)->first()->RiwayatBatalPoPaket->sum('jumlah');
            $j_batalPart = RiwayatBatalPo::where('pesanan_id', $obj->pesanan_id)->first()->RiwayatBatalPoPart->sum('jumlah');


            if (($j_po + $j_poPart) == ($j_batal + $j_batalPart)) {
                $p = Pesanan::find($obj->pesanan_id);
                $p->log_id = 20;
                $p->save();
            }

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'status' => 500,
                'status' => $th->getMessage(),
                'message' => 'Gagal Dikirim',
            ], 500);
        }
    }

    public function get_detail_prd_batal_po($id)
    {
        $dpp = DetailPesananProduk::where('detail_pesanan_id', $id);
        $item = array();

        $seri = NoseriTGbj::select('detail_pesanan_produk_id', 't_gbj_noseri.id', 'noseri_barang_jadi.id as noseri_id', 'noseri')
            ->join('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->join('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
            ->whereIN('t_gbj_detail.detail_pesanan_produk_id', $dpp->pluck('id')->toArray())
            ->get();

        foreach ($dpp->get()  as $key_p => $d) {
            $item[$key_p] = array(
                'id' => $d->id,
                'gbj_id' => $d->gudang_barang_jadi_id,
                'nama' => $d->GudangBarangJadi->Produk->nama,
                'variasi' => $d->GudangBarangJadi->nama,
                'seri' => array()
            );

            foreach ($seri as  $s) {
                if ($d->id == $s['detail_pesanan_produk_id']) {
                    $item[$key_p]['seri'][] = $s;
                }
            }
        }


        return response()->json($item);
    }
    public function cetak_surat_perintah($id)
    {
        $pesanan = Pesanan::find($id);
        $customPaper = array(0, 0, 605.44, 788.031);
        $data = [];

        if ($pesanan->DetailPesanan->isNotEmpty()) {

            foreach ($pesanan->DetailPesanan as $key => $prd) {
                $pesanan_prd[$key] = array(
                    'no' => $key + 1,
                    'kode' => '-',
                    'nama' => $prd->penjualanproduk->nama_alias == '' ? $prd->penjualanproduk->nama : $prd->penjualanproduk->nama_alias,
                    'variasi' => $prd->GetVariasi(),
                    'jumlah' => $prd->jumlah,
                    'pajak' => $prd->ppn == '1' ? 'PPn' : '-',
                    'satuan' => 'UNIT'
                );
            }
        } else {
            $pesanan_prd = array();
        }

        if ($pesanan->DetailPesananPart->isNotEmpty()) {
            foreach ($pesanan->DetailPesananPart as $key => $part) {
                $pesanan_part[$key] = array(
                    'no' => count($pesanan_prd) + $key + 1,
                    'kode' => '-',
                    'nama' => $part->Sparepart->nama,
                    'jumlah' => $part->jumlah,
                    'pajak' => $part->ppn == '1' ? 'PPn' : '-',
                    'satuan' => 'UNIT'
                );
            }
        } else {
            $pesanan_part = array();
        }

        if (count($pesanan_prd) > 0 && count($pesanan_part) <= 0) {
            $data =  array_chunk($pesanan_prd, 9);
        } else if (count($pesanan_part) > 0  && count($pesanan_prd) <= 0) {
            $data = array_chunk($pesanan_part, 9);
        } else if (count($pesanan_prd) > 0 && count($pesanan_part) > 0) {
            $merge = array_merge($pesanan_prd, $pesanan_part);
            $data = array_chunk($merge, 9);
        }

        if ($pesanan->Ekatalog) {
            $cs = $pesanan->Ekatalog->Customer->nama;
            $alamat_cs = $pesanan->Ekatalog->Customer->alamat;
            $ket_paket = $pesanan->ket;
            $no_paket = $pesanan->Ekatalog->no_paket;
            $catatan =  $pesanan->Ekatalog->ket;
        } elseif ($pesanan->Spa) {
            $cs = $pesanan->Spa->Customer->nama;
            $alamat_cs = $pesanan->Spa->Customer->alamat;
            $ket_paket = '';
            $no_paket = $pesanan->ket;
            $catatan =  '';
        } elseif ($pesanan->Spb) {
            $cs = $pesanan->Spb->Customer->nama;
            $alamat_cs = $pesanan->Spb->Customer->alamat;
            $ket_paket = $pesanan->ket_kirim;
            $no_paket = '';
            $catatan =  $pesanan->ket;
        }


        $header = array(
            'customer' => $cs,
            'alamat_customer' =>   $alamat_cs,
            'tujuan_kirim' => $pesanan->tujuan_kirim != NULL ? $pesanan->tujuan_kirim : '-',
            'alamat_kirim' => $pesanan->alamat_kirim != NULL ?  $pesanan->alamat_kirim : '-',
            'so' =>  $pesanan->so,
            'tgl_so' =>   Carbon::parse($pesanan->created_at)->format('d M Y'),
            'no_po' =>  $pesanan->no_po,
            'tgl_po' => $pesanan->tgl_po != NULL ? Carbon::parse($pesanan->tgl_po)->format('d M Y') : '-',
            'kemasan' =>  $pesanan->kemasan,
            'tgl_kirim' =>  $pesanan->tgl_do != NULL ? Carbon::parse($pesanan->tgl_do)->format('d M Y') : '-',
            'ekspedisi' =>   $pesanan->ekspedisi_id != NULL ? $pesanan->Ekspedisi->nama  : '-',
            'ket_kirim' =>  $pesanan->ket_kirim,
            'ket_paket' =>  $ket_paket,
            'no_paket' => $no_paket,
            //*Tambahan Penjuaalan
            'catatan' => $catatan,
            //
            'item' => $data
        );

        // return response()->json($header);
        $pdf = PDF::loadView('page.penjualan.surat.surat-perintah-kirim', ['data' => $header, 'pesanan' => $pesanan, 'count_page' => count($data)])->setOptions(['defaultFont' => 'sans-serif'])->setPaper($customPaper);
        return $pdf->stream('');
    }
}
