<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailPenjualanProduk;
use App\Models\Ekatalog;
use App\Models\KelompokProduk;
use App\Models\PenjualanProduk;
use App\Models\RencanaPenjualan;
use App\Models\Pesanan;
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
use App\Exports\CustomerData;
use App\Exports\EkspedisiData;
use App\Exports\ProdukData;
use App\Models\AktifPeriode;
use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\DetailPesananProduk;
use App\Models\DetailProdukRw;
use App\Models\Ekspedisi;
use App\Models\Logistik;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Arr;
use App\Models\GudangKarantinaDetail;
use App\Models\GudangKarantinaNoseri;
use App\Models\JalurEkspedisi;
use App\Models\kesehatan\Karyawan;
use App\Models\NoseriBarangJadi;
use App\Models\Sparepart;
use App\Models\SparepartGudang;
use App\Models\SystemLog;
use App\Models\UserLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

// produk
use App\Models\Mproduk;
use App\Models\Produk;
use App\Models\GudangBarangJadi;
use App\Models\RiwayatAktifPeriode;

use function PHPUnit\Framework\returnValueMap;

class MasterController extends Controller
{
    public function select_parent_rw()
    {
        $prd = Produk::select('id', 'nama')->has("DetailProdukRw")->get();
        $prd = Produk::select('id', 'nama')->has("DetailProdukRw")->get();

        return response()->json($prd);
    }
    public function select_item_rw($id)
    {
        $prd = DetailProdukRw::addSelect([
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_barang_jadi.id),0)')
                    ->from('noseri_barang_jadi')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                    ->where('noseri_barang_jadi.is_ready', '0')
                    ->whereNull('noseri_barang_jadi.used_by')
                    ->whereColumn('gdg_barang_jadi.produk_id', 'detail_produks_rw.produk_id');
            },
        ])
            ->where('produk_parent_id', $id)->get();
        $prd = DetailProdukRw::addSelect([
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_barang_jadi.id),0)')
                    ->from('noseri_barang_jadi')
                    ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                    ->where('noseri_barang_jadi.is_ready', '0')
                    ->whereNull('noseri_barang_jadi.used_by')
                    ->whereColumn('gdg_barang_jadi.produk_id', 'detail_produks_rw.produk_id');
            },
        ])
            ->where('produk_parent_id', $id)->get();

        return response()->json(['jumlah' => $prd->min('ckirimprd')]);
        return response()->json(['jumlah' => $prd->min('ckirimprd')]);
    }

    public function indexSparepart()
    {
        try {
            $part = Sparepart::with('kategori')->get();

            return response()->json($part);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }
    public function createEditSparepart(Request $r)
    {
        try {
            $part = collect($r->json())->map(function ($x) {
                $part = Sparepart::updateOrCreate(
                    [
                        'id' => isset($x['id']) ? $x['id'] : null
                    ],
                    [
                        'kelompok_produk_id' => $x['kelompok_produk_id'],
                        'kode' => $x['kode'],
                        'nama' => $x['nama'],
                        'jenis' => 'part'
                    ]
                );
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil disimpan',
                'data' => $part
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteSparepart(Request $request)
    {
        try {
            $part = collect($request->json())->map(function ($x) {
                $part = Sparepart::where('id', $x['id'])->delete();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Data berhasil dihapus',
                'data' => $part
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function get_all_past_no_seri(Request $r)
    {
        $si_ekat21 = DB::connection('si_21')->table('seri_on')
            ->select(
                'seri_on.noseri_on as noseri'
            )
            ->leftjoin('gudang_on', 'gudang_on.nolkppgdg_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('qc_on', 'qc_on.nolkppqc_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('spa_on', 'spa_on.nolkpp_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
            ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_on.idprod_on')
            ->where('seri_on.noseri_on', 'LIKE', '%' . $r->input('term', '') . '%')
            ->whereNotNull('gudang_on.tglsj_on')
            ->groupby('seri_on.noseri_on')
            ->limit(10)->get();

        $si_ekat20 = DB::connection('si_20')->table('seri_on')
            ->select(
                'seri_on.noseri_on as noseri'
            )
            ->leftjoin('gudang_on', 'gudang_on.nolkppgdg_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('qc_on', 'qc_on.nolkppqc_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('spa_on', 'spa_on.nolkpp_on', '=', 'seri_on.lkppfk_on')
            ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
            ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_on.idprod_on')
            ->where('seri_on.noseri_on', 'LIKE', '%' . $r->input('term', '') . '%')
            ->whereNotNull('gudang_on.tglsj_on')
            ->groupby('seri_on.noseri_on')
            ->get();


        $si_spa21 = DB::connection('si_21')->table('seri_off')
            ->select(
                'seri_off.noseri_off as noseri'
            )
            ->leftjoin('gudang_off', 'gudang_off.idordergdg_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('qc_off', 'qc_off.idorderqc_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('spa_off', 'spa_off.idorder_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_off.pabrik_off')
            ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_off.idprod_off')
            ->where('seri_off.noseri_off', 'LIKE', '%' . $r->input('term', '') . '%')
            ->whereNotNull('gudang_off.tglsj_off')
            ->groupby('seri_off.noseri_off')
            ->get();

        $si_spa20 = DB::connection('si_20')->table('seri_off')
            ->select(
                'seri_off.noseri_off as noseri'
            )
            ->leftjoin('gudang_off', 'gudang_off.idordergdg_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('qc_off', 'qc_off.idorderqc_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('spa_off', 'spa_off.idorder_off', '=', 'seri_off.idorderfk_off')
            ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_off.pabrik_off')
            ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_off.idprod_off')
            ->where('seri_off.noseri_off', 'LIKE', '%' . $r->input('term', '') . '%')
            ->whereNotNull('gudang_off.tglsj_off')
            ->groupby('seri_off.noseri_off')
            ->get();

        $si_spb21 = DB::connection('si_21')->table('seri_spb')
            ->select(
                'seri_spb.noseri_spb as noseri'
            )
            ->leftjoin('gudang_spb', 'gudang_spb.nogdg_spb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('qc_spb', 'qc_spb.noqc_spb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('spb', 'spb.nospb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spb.idprod_spb')
            ->where('seri_spb.noseri_spb', 'LIKE', '%' . $r->input('term', '') . '%')
            ->whereNotNull('gudang_spb.tglsjgdg_spb')
            ->groupby('seri_spb.noseri_spb')
            ->get();

        $si_spb20 = DB::connection('si_20')->table('seri_spb')
            ->select(
                'seri_spb.noseri_spb as noseri'
            )
            ->leftjoin('gudang_spb', 'gudang_spb.nogdg_spb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('qc_spb', 'qc_spb.noqc_spb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('spb', 'spb.nospb', '=', 'seri_spb.nogdgfk')
            ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spb.idprod_spb')
            ->where('seri_spb.noseri_spb', 'LIKE', '%' . $r->input('term', '') . '%')
            ->whereNotNull('gudang_spb.tglsjgdg_spb')
            ->groupby('seri_spb.noseri_spb')
            ->get();

        $erp = DB::connection('erp')->table('noseri_barang_jadi')
            ->select('noseri_barang_jadi.noseri as noseri')
            ->where('noseri_barang_jadi.noseri', 'LIKE', '%' . $r->input('term', '') . '%')
            ->get();

        $data = $si_spa21->merge($si_spa20)->merge($si_spb20)->merge($si_spb21)->merge($si_ekat20)->merge($si_ekat21)->merge($erp);
        return response()->json($data);
    }
    public function get_divisi_karyawan($id, Request $r)
    {
        $data = Karyawan::where([['divisi_id', '=', $id], ['nama', 'LIKE', '%' . $r->input('term', '') . '%']])->select('nama', 'id')->get();
        return response()->json($data);
    }
    //Get Data Table
    public function get_data_detail_ekspedisi($id)
    {
        $data = Logistik::where('ekspedisi_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if (isset($data->detaillogistik[0])) {
                    return $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->so;
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
                if (isset($data->detaillogistik[0])) {
                    $name = explode('/', $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->ekatalog->customer->nama;
                    } else if ($name[1] == 'SPA') {
                        return $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->spa->customer->nama;
                    } else if ($name[1] == 'SPB') {
                        return $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->spb->customer->nama;
                    }
                } else {
                    if (isset($data->detaillogistikpart->first()->detailpesananpart->pesanan->spa)) {
                        return $data->detaillogistikpart->first()->detailpesananpart->pesanan->spa->customer->nama;
                    } else if (isset($data->detaillogistikpart->first()->detailpesananpart->pesanan->spb)) {
                        return $data->detaillogistikpart->first()->detailpesananpart->pesanan->spb->customer->nama;
                    }
                }
                return;
            })
            ->addColumn('alamat', function ($data) {
                if (isset($data->detaillogistik[0])) {
                    $name = explode('/', $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return   $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->ekatalog->customer->alamat;
                    } else if ($name[1] == 'SPA') {
                        return  $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->spa->customer->alamat;
                    } else if ($name[1] == 'SPB') {
                        return  $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->spb->customer->alamat;
                    }
                } else {
                    if (isset($data->detaillogistikpart->first()->detailpesananpart->pesanan->spa)) {
                        return $data->detaillogistikpart->first()->DetailPesananPart->pesanan->spa->customer->alamat;
                    } else if (isset($data->detaillogistikpart->first()->detailpesananpart->pesanan->spb)) {
                        return $data->detaillogistikpart->first()->DetailPesananPart->pesanan->spb->customer->alamat;
                    }
                }
                return;
            })
            ->addColumn('telp', function ($data) {
                if (isset($data->detaillogistik[0])) {
                    $name = explode('/', $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->so);
                    if ($name[1] == 'EKAT') {
                        return   $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->ekatalog->customer->telp;
                    } elseif ($name[1] == 'SPA') {
                        return  $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->spa->customer->telp;
                    }
                } else {
                    if (isset($data->detaillogistikpart->first()->detailpesananpart->pesanan->spa)) {
                        return $data->detaillogistikpart->first()->DetailPesananPart->pesanan->spa->customer->telp;
                    } else if (isset($data->detaillogistikpart->first()->detailpesananpart->pesanan->spb)) {
                        return $data->detaillogistikpart->first()->DetailPesananPart->pesanan->spb->customer->telp;
                    }
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
                // $x = DetailPesananProduk::where('pesanan_id', $data->detaillogistik[0]->DetailPesananProduk->detailpesanan->pesanan->id)->get();
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
    public function get_data_all_ekspedisi(Request $r)
    {
        try {
            $ekspedisi = Ekspedisi::where('nama', 'LIKE', '%' . $r->input('term', '') . '%')->get();

            return response()->json($ekspedisi);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ]);
        }
    }

    public function get_data_ekspedisi($value1, $value2)
    {
        $x = explode(',', $value1);
        $divisi_id = auth()->user()->divisi_id;

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
                        <i class="fas fa-eye"></i>
                        Detail
                    </button>
                </a>';
                if ($divisi_id == "15") {
                    $x = array();
                    $y = array();
                    $n = array();

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

                    $return .= '" data-provinsi_nama="';
                    foreach ($data->provinsi as $u) {
                        $n[] = $u->nama;
                    }
                    $return .= implode(',', $n);

                    $return .= '">
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-pencil-alt"></i>
                        Edit
                    </button>
                </a>
                <a data-toggle="modal" class="hapusmodal" data-id="' . $data->id . '" data-target="#hapusmodal">
                    <button class="dropdown-item" type="button">
                    <i class="far fa-trash-alt"></i>
                        Hapus
                    </button>
                </a>';
                }
                $return .= ' </div>';
                return $return;
            })
            ->rawColumns(['button', 'jurusan', 'via'])
            ->make(true);
    }
    public function get_data_produk()
    {
        return datatables()->of(Produk::with('KelompokProduk'))->toJson();
    }
    public function get_data_produk_variasi(Request $r)
    {
        $prd = GudangBarangJadi::with('Produk')->whereHas('Produk', function ($q) use ($r) {
            $q->where('nama', 'LIKE', '%' . $r->input('term', '') . '%');
        })->get();
        $data = array();

        foreach ($prd as $key_prd => $p) {
            $data[$key_prd] = array(
                'id' => $p->id,
                'nama' => $p->Produk->nama . " " . $p->nama
            );
        }

        return response()->json($data);
    }
    public function get_data_customer($divisi_id, $value)
    {
        $divisi = $divisi_id;
        $x = explode(',', $value);
        if ($value == 0 || $value == 'kosong') {
            $data = Customer::with('Provinsi')->WhereNotIN('id', ['484'])->orderby('nama', 'ASC')->get();
        } else {
            $data = Customer::with('Provinsi')->WhereNotIN('id', ['484'])->whereHas('Provinsi', function ($q) use ($x) {
                $q->whereIN('status', $x);
            })->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->editColumn('email', function ($data) {
                if (!empty($data->email)) {
                    return $data->email;
                } else {
                    return '-';
                }
            })
            ->editColumn('telp', function ($data) {
                if (!empty($data->telp)) {
                    return $data->telp;
                } else {
                    return '-';
                }
            })
            ->addColumn('prov', function ($data) {
                return $data->Provinsi->nama;
            })
            ->addColumn('ktp', function ($data) {
                if (!empty($data->ktp)) {
                    return $data->ktp;
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) use ($divisi) {

                $datas = "";
                $datas .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a href="' . route('penjualan.customer.detail', $data->id) . '">
                    <button class="dropdown-item" type="button">
                      <i class="fas fa-eye"></i>
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
            ->addColumn('nama_alias', function ($data) {
                // $id = $data->id;
                // $s = Produk::where('coo', '1')->whereHas('PenjualanProduk', function ($q) use ($id) {
                //     $q->where('id', $id);
                // })->first();
                return $data->nama_alias;
            })
            ->addColumn('no_akd', function ($data) {
                $id = $data->id;
                $s = Produk::where('coo', '1')->whereHas('PenjualanProduk', function ($q) use ($id) {
                    $q->where('id', $id);
                })->first();
                if (!empty($s->no_akd)) {
                    return $s->no_akd;
                }
            })
            ->addColumn('merk', function ($data) {
                $id = $data->id;
                $s = Produk::where('coo', '1')->whereHas('PenjualanProduk', function ($q) use ($id) {
                    $q->where('id', $id);
                })->first();
                if (!empty($s->merk)) {
                    return $s->merk;
                }
            })
            ->addColumn('jenis_paket', function ($data) {
                if ($data->status == 'ekat') {
                    return  '<span class="badge purple-text">Ekatalog</span>';
                } else {
                    return '<span class="badge blue-text">Non Ekatalog</span>';
                }
            })
            ->addColumn('is_aktif', function ($data) {
                if ($data->is_aktif == "1") {
                    return '<span class="badge green-text">Aktif</span>';
                } else {
                    return '<span class="badge red-text">Tidak Aktif</span>';
                }
            })
            ->addColumn('is_aktif', function ($data) {
                if ($data->is_aktif == "1") {
                    return '<span class="badge green-text">Aktif</span>';
                } else {
                    return '<span class="badge red-text">Tidak Aktif</span>';
                }
            })
            ->addColumn('button', function ($data) {
                return  '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <button class="dropdown-item" type="button" id="showmodal">
                        <i class="fas fa-eye"></i>
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
            ->rawColumns(['nama', 'button', 'jenis_paket', 'is_aktif'])
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

    public function get_instansi_customer($id, $year, Request $request)
    {
        $datarc = RencanaPenjualan::where('instansi', 'LIKE', '%' . $request->input('term', '') . '%')->where([['customer_id', '=', $id], ['tahun', '=', $year]])->pluck('instansi');
        $datarl = Ekatalog::where('instansi', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('instansi')->pluck('instansi');

        $data = $datarl->merge($datarc);

        // return $data;
        $datas1 = json_decode(json_encode($data));
        $datass = array_unique($datas1);
        return json_encode($datass);
        // print_r(array_count_values($data));
        // return json_encode($datas1);
    }

    public function get_ekatalog_satuan(Request $request)
    {
        $data = Ekatalog::where('satuan', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('satuan')->get();
        return json_encode($data);
        // print_r(array_count_values($data));
        // return json_encode($datas1);
    }

    public function get_ekatalog_deskripsi(Request $request)
    {
        $data = Ekatalog::where('deskripsi', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('deskripsi')->get();
        return json_encode($data);
        // print_r(array_count_values($data));
        // return json_encode($datas1);
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

        $Ekatalog = collect(Ekatalog::with('Pesanan')->where('customer_id', $id)->addSelect([
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
        ])->with(['Pesanan.State', 'Customer'])->orderBy('id', 'DESC')->get());

        $Spa = collect(Spa::with('Pesanan')->where('customer_id', $id)->addSelect([
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

        $Spb = collect(Spb::with('Pesanan')->where('customer_id', $id)->addSelect([
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
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        if ($data) {
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
                        if ($data->status == "batal") {
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
                            return '<span class="badge red-text">Batal</span>';
                        } else {
                            if ($data->Pesanan->log_id == "7") {
                                return '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
                            } else {
                                return $progress;
                            }
                        }
                    }
                })
                ->addColumn('button', function ($data) {
                    $name =  $data->getTable();

                    if ($name == 'ekatalog') {
                        return  '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                    } else if ($name == 'spa') {
                        return  '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                        <button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                    } else {
                        return  '
                            <a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                            <button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                    }
                })
                ->rawColumns(['status', 'button'])
                ->make(true);
        }
    }

    public function get_data_master_produk()
    {
        $data = Produk::select();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kelompok_produk', function ($data) {
                $res = "";
                if ($data->KelompokProduk->nama == 'Alat Kesehatan') {
                    $res .= '<span class="badge blue-text">';
                } else if ($data->KelompokProduk->nama == 'Water Treatment') {
                    $res .= '<span class="badge orange-text">';
                } else if ($data->KelompokProduk->nama == 'Aksesoris') {
                    $res .= '<span class="badge purple-text">';
                } else if ($data->KelompokProduk->nama == 'Lain Lain') {
                    $res .= '<span class="badge red-text">';
                } else if ($data->KelompokProduk->nama == 'Sparepart') {
                    $res .= '<span class="badge green-text">';
                }
                $res .= $data->KelompokProduk->nama . '</span>';
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
                if ($data->coo == "1") {
                    return '<span class="badge green-text">Produk Utama</span>';
                } else {
                    return '<span class="badge red-text">Bukan Produk Utama</span>';
                }
            })
            ->addColumn('aksi', function ($data) {
                if (Auth::user()->divisi_id == '9') {
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
            $obj = [
                'nama' => $request->nama_ekspedisi,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'telp' => $request->telepon,
                'ket' => $request->keterangan,
                'jalur' => JalurEkspedisi::whereIn('id', $request->jalur)->get()->pluck('nama'),
                'provinsi' => $request->jurusan == 'provinsi' ? Provinsi::whereIn('id', $request->provinsi)->get()->pluck('nama') : 'Seluruh Indonesia',
            ];

            SystemLog::create([
                'tipe' => 'Logistik',
                'subjek' => 'Tambah Ekspedisi Baru',
                'response' => json_encode($obj),
                'user_id' => $request->user_id,
            ]);
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
            'izin_usaha' => $request->izin_usaha,
            'modal_usaha' => $request->modal_usaha,
            'hasil_penjualan' => $request->hasil_penjualan,
            'nama_pemilik' => $request->pemilik,
            'ket' => $request->keterangan,
        ]);

        if ($c) {
            $obj = [
                'nama' => $request->nama_customer,
                'telp' => $request->telepon,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'id_provinsi' => $request->provinsi,
                'ktp' => $request->ktp,
                'npwp' => $request->npwp,
                'batas' => $request->batas,
                'pic' => $request->pic,
                'izin_usaha' => $request->izin_usaha,
                'modal_usaha' => $request->modal_usaha,
                'hasil_penjualan' => $request->hasil_penjualan,
                'nama_pemilik' => $request->pemilik,
                'ket' => $request->keterangan,
            ];

            SystemLog::create([
                'tipe' => 'Penjualan',
                'subjek' => 'Tambah Customer / Distributor',
                'response' => json_encode($obj),
                'user_id' => Auth::user()->id
            ]);
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

        $validator = Validator::make($request->all(), [
            'produk_id' => 'required ',
            'jumlah' => 'required'
        ]);

        if ($validator->fails()) {
            $bool = false;
        } else {
            $PenjualanProduk = PenjualanProduk::create([
                'nama' => $request->nama_paket,
                'nama_alias' => $request->nama_alias,
                'harga' => $harga_convert,
                'status' => $request->jenis_paket,
                'is_aktif' => $request->is_aktif
            ]);

            // for ($i = 0; $i < count($request->produk_id); $i++) {
            //     array_push($sync_data, ['produk_id' => $request->produk_id[$i], 'jumlah' => $request->jumlah[$i]]);
            // }


            for ($i = 0; $i < count($request->produk_id); $i++) {
                $PenjualanProduk->Produk()->attach($request->produk_id[$i], ['jumlah' => $request->jumlah[$i]]);
            }

            $bool = true;
        }

        if ($bool == true) {
            return redirect()->back()->with('success', 'success');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Detail Penjualan error');
        }


        // for ($i = 0; $i < count($request->produk_id); $i++) {
        //     DetailPenjualanProduk::create([
        // 'penjualan_produk_id' => $request->select_produk_id
        // 'paket_id
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

        return view("page.penjualan.produk.edit", ['penjualanproduk' => $penjualanproduk]);
    }


    //Edit
    public function edit_coo_data_produk($id)
    {
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
        $customer->izin_usaha = $request->izin_usaha;
        $customer->modal_usaha = $request->modal_usaha;
        $customer->hasil_penjualan = $request->hasil_penjualan;
        $customer->nama_pemilik = $request->pemilik;
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

    public function update_kode_lab(Request $request, $id)
    {
        try {
            $p = Produk::find($id);
            $p->kode_lab_id = $request->kode_lab_id;
            $p->save();

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 404,
                'message' => 'Cek Kembali Form',
            ], 200);
        }
    }
    public function update_coo_master_produk(Request $request, $id)
    {
        $p = Produk::find($id);
        $p->no_akd = $request->no_akd;
        $p->nama_coo = $request->nama_coo;
        $p->coo = $request->coo;
        $u = $p->save();
        if ($u) {
            $obj = [
                'produk' => $p->nama,
                'nama_coo' => $request->nama_coo,
                'no_akd' => $request->no_akd,
                'coo' => $request->coo == 1 ? 'Produk Utama' : 'Bukan Produk Utama',
            ];

            SystemLog::create([
                'tipe' => 'DC',
                'subjek' => 'Perubahan Data COO Produk',
                'response' => json_encode($obj),
                'user_id' => $request->user_id,
            ]);
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
    public function delete_ekspedisi($id)
    {
        $ekspedisi = Ekspedisi::find($id);
        $ekspedisi->delete();

        if ($ekspedisi) {
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
        $status = "";

        $PenjualanProduk = PenjualanProduk::find($id);
        $PenjualanProduk->nama_alias = $request->nama_alias;
        $PenjualanProduk->nama = $request->nama_paket;
        $PenjualanProduk->status = $status;
        $PenjualanProduk->harga = $harga_convert;
        $PenjualanProduk->status = $request->jenis_paket;
        $PenjualanProduk->is_aktif = $request->is_aktif;
        $PenjualanProduk->save();

        $produk_array = [];
        for ($i = 0; $i < count($request->produk_id); $i++) {
            array_push($produk_array, ['produk_id' => $request->produk_id[$i], 'jumlah' => $request->jumlah[$i]]);
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
            $obj = [
                'nama' => $request->nama_ekspedisi,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'telp' => $request->telepon,
                'ket' => $request->keterangan,
                'jalur' => JalurEkspedisi::whereIn('id', $request->jalur)->get()->pluck('nama'),
                'provinsi' => $request->jurusan == 'provinsi' ? Provinsi::whereIn('id', $request->provinsi_id)->get()->pluck('nama') : 'Seluruh Indonesia',
            ];

            SystemLog::create([
                'tipe' => 'Logistik',
                'subjek' => 'Ubah Ekspedisi',
                'response' => json_encode($obj),
                'user_id' => $request->user_id,
            ]);
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
        $e = Ekspedisi::find($id);
        return view('page.logistik.ekspedisi.detail', ['e' => $e]);
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
    public function select_customer_rencana(Request $request)
    {
        $data = Customer::Has('RencanaPenjualan')->where('nama', 'LIKE', '%' . $request->input('term', '') . '%')->orderby('nama', 'ASC')->get();
        echo json_encode($data);
    }
    public function select_penjualan_produk(Request $request)
    {
        $data = PenjualanProduk::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->where('is_aktif', '1')
            ->orderby('nama', 'ASC')
            ->get();
        return response()->json($data);
    }
    public function select_penjualan_produk_id($id)
    {
        // $data = PenjualanProduk::with('Produk.GudangBarangJadi')->where('id', $id)
        //     ->get();

        $data = array();
        $pp = PenjualanProduk::where('id', $id)->get();

        foreach ($pp as $key_pp => $pp) {
            $data[$key_pp] = array(
                'id' => $pp->id,
                'nama' => $pp->nama,
                'nama_alias' => $pp->nama_alias,
                'harga' => $pp->harga,
                'status' => $pp->status,
                'created_at' => $pp->created_at,
                'updated_at' => $pp->updated_at,
                'is_kalibrasi' => $pp->Produk->whereNotNull('kode_lab_id')->count() > 0 ? true : false,

            );

            foreach ($pp->Produk as $key_p => $p) {
                $data[$key_pp]['produk'][$key_p] = array(
                    'id' => $p->id,
                    'produk_id' => $p->produk_id,
                    'merk' => $p->merk,
                    'nama' => $p->nama,
                    'kode_lab_id' => $p->kode_lab_id,
                    'nama_coo' => $p->nama_coo,
                    'coo' => $p->coo,
                    'no_akd' => $p->no_akd,
                    'ket' => $p->ket,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'pivot' => $p->pivot,
                    'status' => $p->status,
                );

                foreach ($p->GudangBarangJadi as $key_v => $v) {
                    $data[$key_pp]['produk'][$key_p]['gudang_barang_jadi'][$key_v] = array(
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
        }
        // echo json_encode($data);
        return response()->json($data);
    }
    public function select_penjualan_produk_param(Request $request, $value)
    {
        if ($value == 'ekatalog') {
            $data = PenjualanProduk::where('nama_alias', 'LIKE', '%' . $request->input('term', '') . '%')
                ->where('is_aktif', '1')
                ->where('status', 'ekat')
                ->orderby('nama', 'ASC')
                ->get();
        } else {
            $data = PenjualanProduk::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
                ->where('is_aktif', '1')
                ->orderby('nama', 'ASC')
                ->get();
        }
        echo json_encode($data);
    }
    public function check_no_akd($id, $val)
    {
        $data = Produk::where('no_akd', $val)->whereNotIn('id', $id)->count();
        return $data;
    }

    function select_m_sparepart(Request $request)
    {
        $data = Sparepart::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->where('kode', 'not like', '%jasa%')
            ->orderby('nama', 'ASC')
            ->get();
        return response()->json($data);
    }
    function select_m_jasa(Request $request)
    {
        $data = Sparepart::where('nama', 'LIKE', '%' . $request->input('term', '') . '%')
            ->where('kode', 'like', '%jasa%')
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
        $data = GudangKarantinaDetail::select('t_gk_detail.gbj_id', DB::raw('CONCAT("(",produk.merk,") ",produk.nama," ",gdg_barang_jadi.nama) as name'))
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

    // function create_user_log(Request $request)
    // {
    //     $row = new UserLog();
    //     $row->user_id = $request->userid;
    //     $row->user_nama = $request->usernama;
    //     $row->subjek = $request->subjek;
    //     $row->table = $this->table();
    //     $row->keterangan = $request->keterangan;
    //     $row->aksi = $request->aksi;
    //     $row->created_at = Carbon::now();
    //     $row->save();
    // }

    public function export_customer()
    {
        $waktu = Carbon::now();
        return Excel::download(new CustomerData(), 'Daftar Customer  ' . $waktu->toDateTimeString() . '.xlsx');
    }
    public function export_ekspedisi()
    {
        $waktu = Carbon::now();
        return Excel::download(new EkspedisiData(), 'Daftar Ekspedisi  ' . $waktu->toDateTimeString() . '.xlsx');
    }
    public function export_produk()
    {
        $waktu = Carbon::now();
        return Excel::download(new ProdukData(), 'Daftar Produk ' . $waktu->toDateTimeString() . '.xlsx');
    }
    public function get_stok_pesanan(Request $r)
    {
        if ($r->jenis == "paket") {
            $detail_pesanan = DetailPesanan::where('id', $r->id)->addSelect([
                'count_gudang' => function ($q) {
                    $q->selectRaw('count(t_gbj_noseri.id)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
                        ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                        ->limit(1);
                }, 'count_qc_ok' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                        ->where('noseri_detail_pesanan.is_ready', 0)
                        ->where('noseri_detail_pesanan.status', 'ok')
                        ->limit(1);
                },
                'count_qc_nok' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                        ->where('noseri_detail_pesanan.status', 'nok')
                        ->limit(1);
                }, 'count_log' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        // ->leftjoin('detail_logistik', 'detail_logistik.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                        ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                        ->limit(1);
                },
                'count_belum_kirim' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        // ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                        ->where('logistik.status_id', '11')
                        ->limit(1);
                },
                'count_kirim' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        // ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                        ->where('logistik.status_id', '10')
                        ->limit(1);
                }, 'count_jumlah' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan_produk')
                        ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.produk_id', '=', 'gdg_barang_jadi.produk_id')
                        ->whereColumn('detail_penjualan_produk.penjualan_produk_id', 'detail_pesanan.penjualan_produk_id')
                        ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                        ->limit(1);
                }
                // 'count_gudang' => function ($q) {
                //     $q->selectRaw('count(t_gbj_noseri.id)')
                //         ->from('t_gbj_noseri')
                //         ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 't_gbj_detail.detail_pesanan_produk_id')
                //         ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                //         ->limit(1);
                // }, 'count_qc_ok' => function ($q) {
                //     $q->selectRaw('count(noseri_detail_pesanan.id)')
                //         ->from('noseri_detail_pesanan')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //         ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                //         ->where('noseri_detail_pesanan.status', 'ok')
                //         ->limit(1);
                // },
                // 'count_qc_nok' => function ($q) {
                //     $q->selectRaw('count(noseri_detail_pesanan.id)')
                //         ->from('noseri_detail_pesanan')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //         ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                //         ->where('noseri_detail_pesanan.status', 'nok')
                //         ->limit(1);
                // }, 'count_log' => function ($q) {
                //     $q->selectRaw('count(noseri_logistik.id)')
                //         ->from('noseri_logistik')
                //         ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                //         // ->leftjoin('detail_logistik', 'detail_logistik.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                //         ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                //         ->limit(1);
                // },
                // 'count_belum_kirim' => function ($q) {
                //     $q->selectRaw('count(noseri_logistik.id)')
                //         ->from('noseri_logistik')
                //         // ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //         ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                //         ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                //         ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                //         ->where('logistik.status_id', '11')
                //         ->limit(1);
                // },
                // 'count_kirim' => function ($q) {
                //     $q->selectRaw('count(noseri_logistik.id)')
                //         ->from('noseri_logistik')
                //         // ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                //         ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                //         ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                //         ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                //         ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                //         ->where('logistik.status_id', '10')
                //         ->limit(1);
                // }, 'count_jumlah' => function ($q) {
                //     $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                //         ->from('detail_pesanan_produk')
                //         ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                //         ->join('detail_penjualan_produk', 'detail_penjualan_produk.produk_id', '=', 'gdg_barang_jadi.produk_id')
                //         ->whereColumn('detail_penjualan_produk.penjualan_produk_id', 'detail_pesanan.penjualan_produk_id')
                //         ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
                //         ->limit(1);
                // }
            ])->with('PenjualanProduk')->first();

            // $data = array();
            // $data['detail']['penjualan_produk']['nama'] = $detail_pesanan->PenjualanProduk->nama;
            // $data['detail']['count_gudang'] = $detail_pesanan->count_gudang;
            // $data['detail']['count_jumlah'] = $detail_pesanan->count_jumlah;
            // $data['detail']['count_log'] = $detail_pesanan->count_log;
            // $data['detail']['count_qc_nok'] =  $detail_pesanan->count_qc_nok;
            // $data['detail']['count_qc_ok'] = $detail_pesanan->count_qc_ok;
            // $data['detail'] = $detail_pesanan;
            // $data['gudang'] = $detail_pesanan->count_jumlah - $detail_pesanan->count_gudang + $detail_pesanan->count_qc_nok;
            // $data['qc'] =  $detail_pesanan->count_gudang - $detail_pesanan->count_qc_ok;
            // $data['log'] =  $detail_pesanan->count_qc_ok -  $detail_pesanan->count_log + $detail_pesanan->count_belum_kirim;
            // $data['kir'] =  $detail_pesanan->count_kirim;
            // $data['detail']['jenis'] = 'paket';
            $data = array();
            $data['detail']['penjualan_produk']['nama'] = $detail_pesanan->PenjualanProduk->nama;
            $data['detail']['count_gudang'] = $detail_pesanan->count_gudang;
            $data['detail']['count_jumlah'] = $detail_pesanan->count_jumlah;
            $data['detail']['count_log'] = $detail_pesanan->count_log;
            $data['detail']['count_qc_nok'] =  $detail_pesanan->count_qc_nok;
            $data['detail']['count_qc_ok'] = $detail_pesanan->count_qc_ok;
            $data['detail'] = $detail_pesanan;
            $data['gudang'] = $detail_pesanan->count_jumlah - $detail_pesanan->count_gudang + $detail_pesanan->count_qc_nok;
            $data['qc'] =  $detail_pesanan->count_gudang - $detail_pesanan->count_qc_ok;
            $data['log'] =  $detail_pesanan->count_qc_ok -  $detail_pesanan->count_log + $detail_pesanan->count_belum_kirim;
            $data['kir'] =  $detail_pesanan->count_kirim;
            $data['detail']['jenis'] = 'paket';



            return response()->json($data);
        } else if ($r->jenis == "variasi") {

            $detail_pesanan_produk = DetailPesananProduk::where('id', $r->id)->addSelect([
                'count_gudang' => function ($q) {
                    $q->selectRaw('count(t_gbj_noseri.id)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                        ->limit(1);
                },
                'count_jumlah' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_pesanan.penjualan_produk_id', '=', 'detail_penjualan_produk.penjualan_produk_id')
                        ->join('gdg_barang_jadi', 'gdg_barang_jadi.produk_id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.id', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('gdg_barang_jadi.id', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->limit(1);
                },
                'count_qc_ok' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                        ->where('status', 'ok')
                        ->where('is_ready', 0)
                        ->limit(1);
                },
                'count_qc_nok' => function ($q) {
                    $q->selectRaw('count(noseri_detail_pesanan.id)')
                        ->from('noseri_detail_pesanan')
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                        ->where('status', 'ok')
                        ->limit(1);
                },
                'count_log' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                        ->limit(1);
                },
                'count_belum_kirim' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->whereColumn('detail_logistik.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                        ->where('logistik.status_id', '11')
                        ->limit(1);
                },
                'count_kirim' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                        ->whereColumn('detail_logistik.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                        ->where('logistik.status_id', '10')
                        ->limit(1);
                }
            ])->with('GudangBarangJadi.Produk')->first();
            $data = array();
            $data['detail']['penjualan_produk']['nama'] = $detail_pesanan_produk->GudangBarangJadi->Produk->nama;
            $data['detail']['count_gudang'] = $detail_pesanan_produk->count_gudang;
            $data['detail']['count_jumlah'] = $detail_pesanan_produk->count_jumlah;
            $data['detail']['count_log'] = $detail_pesanan_produk->count_log;
            $data['detail']['count_qc_nok'] =  $detail_pesanan_produk->count_qc_nok;
            $data['detail']['count_qc_ok'] = $detail_pesanan_produk->count_qc_ok;
            $data['detail']['jenis'] = 'variasi';
            $data['gudang'] = $detail_pesanan_produk->count_jumlah - $detail_pesanan_produk->count_gudang;
            $data['qc'] =  $detail_pesanan_produk->count_gudang - $detail_pesanan_produk->count_qc_ok;
            $data['log'] =  $detail_pesanan_produk->count_qc_ok -  $detail_pesanan_produk->count_log + $detail_pesanan_produk->count_belum_kirim;
            $data['kir'] =  $detail_pesanan_produk->count_kirim;
            return response()->json($data);
        } else if ($r->jenis == "part") {
            $detail_pesanan_part = DetailPesananPart::where('id', $r->id)->addSelect([
                'count_qc_ok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok),0)')
                        ->from('outgoing_pesanan_part')
                        ->where('outgoing_pesanan_part.is_ready', 0)
                        ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
                },
                'count_qc_nok' => function ($q) {
                    $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_nok),0)')
                        ->from('outgoing_pesanan_part')
                        ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
                }, 'count_log' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->whereColumn('detail_logistik_part.detail_pesanan_part_id', 'detail_pesanan_part.id');
                },
                'count_belum_kirim' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik_part.logistik_id')
                        ->whereColumn('detail_logistik_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
                        ->where('logistik.status_id', '11')
                        ->limit(1);
                },
                'count_kirim' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->leftjoin('logistik', 'logistik.id', '=', 'detail_logistik_part.logistik_id')
                        ->whereColumn('detail_logistik_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
                        ->where('logistik.status_id', '10')
                        ->limit(1);
                }
            ])->with('Sparepart')->first();

            $data = array();
            $data['detail']['penjualan_produk']['nama'] = $detail_pesanan_part->Sparepart->nama;
            $data['detail']['count_gudang'] =  $detail_pesanan_part->jumlah;
            $data['detail']['count_jumlah'] = '-';
            $data['detail']['count_log'] = $detail_pesanan_part->count_log;
            $data['detail']['count_qc_nok'] =  $detail_pesanan_part->count_qc_nok;
            $data['detail']['count_qc_ok'] =  $detail_pesanan_part->count_qc_ok;
            $data['detail']['jenis'] = 'part';
            $data['gudang'] = 0;
            $data['qc'] =   intval($detail_pesanan_part->jumlah - $detail_pesanan_part->count_qc_ok);
            $data['log'] = intval($detail_pesanan_part->count_qc_ok - $detail_pesanan_part->count_log + $detail_pesanan_part->count_belum_kirim);
            $data['kir'] =   intval($detail_pesanan_part->count_kirim);
            return response()->json($data);
        }
    }

    // kategori
    public function indexKategori()
    {
        try {
            $kategori = MProduk::all();

            return response()->json([
                'success' => true,
                'kategori' => $kategori
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function postOrEditKategori(Request $request)
    {
        try {
            $kategori = collect($request->json())->map(function ($item) {
                $kategori = MProduk::updateOrCreate(
                    ['id' => isset($item['id']) ? $item['id'] : null],
                    [
                        'nama' => $item['nama'],
                        'kode' => $item['kode']
                    ]
                );
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan kategori',
                'kategori' => $kategori
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteKategori(Request $request)
    {
        try {
            $kategori = collect($request->json())->map(function ($item) {
                $kategori = MProduk::where('id', $item['id'])->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus kategori',
                'kategori' => $kategori
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function indexProdukWithKodeLab()
    {
        try {
            $produkLab = Produk::with('KodeLab')->where('kode_lab_id', '!=', null)->orderBy('nama')->get();
            $produkNonLab = Produk::where('kode_lab_id', null)->orderBy('nama')->get();
            return response()->json([
                'success' => true,
                'produk' => $produkLab,
                'produkNonLab' => $produkNonLab
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function indexProduk()
    {
        try {
            $produk = Produk::with('GudangBarangJadi')->with('product')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'produk_id' => $item->produk_id,
                    'kelompok_produk_id' => $item->kelompok_produk_id,
                    'merk' => $item->merk,
                    'kategori' => $item->product->nama,
                    'nama' => $item->nama,
                    'nama_coo' => $item->nama_coo,
                    'coo' => $item->coo,
                    'no_akd' => $item->no_akd,
                    'status' => $item->status == 1 ? true : false,
                    'generate_seri' => $item->generate_seri,
                    'kode' => $item->kode,
                    'gudang_barang_jadi' => $item->GudangBarangJadi
                ];
            });

            return response()->json([
                'success' => true,
                'produk' => $produk
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function postOrEditProduk(Request $request)
    {
        try {
            $produk = collect($request->json())->map(function ($item) {
                $produk = Produk::updateOrCreate(
                    [
                        'id' => isset($item['id']) ? $item['id'] : null
                    ],
                    [
                        'produk_id' => $item['produk_id'],
                        'kelompok_produk_id' => $item['kelompok_produk_id'],
                        'kode' => $item['kode'],
                        'merk' => $item['merk'],
                        'nama' => $item['nama'],
                        'nama_coo' => isset($item['nama_coo']) ? $item['nama_coo'] : null,
                        'coo' => isset($item['coo']) ? $item['coo'] : 1,
                        'no_akd' => $item['no_akd'],
                        'status' => $item['status'],
                        'generate_seri' => $item['generate_seri'],
                    ]
                );

                foreach ($item['gudang_barang_jadi'] as $gudang) {
                    $gbj = GudangBarangJadi::updateOrCreate(
                        [
                            'id' => isset($gudang['id']) ? $gudang['id'] : null
                        ],
                        [
                            'produk_id' => $produk->id,
                            'nama' => isset($gudang['nama']) ? $gudang['nama'] : ' ',
                            'stok' => $gudang['stok'],
                            'stok_siap' => $gudang['stok_siap'],
                            'satuan_id' => $gudang['satuan_id'],
                        ]
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menambahkan produk',
                'produk' => $produk
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function deleteProduk(Request $request)
    {
        try {
            $produk = collect($request->json())->map(function ($item) {
                $gudang = GudangBarangJadi::where('produk_id', $item['id'])->delete();

                $produk = Produk::where('id', $item['id'])->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus produk',
                'produk' => $produk
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function changeStatusProduk(Request $request)
    {
        try {
            $produk = Produk::find($request->id);
            $produk->status = $request->status;
            $produk->save();

            return response()->json([
                'success' => true,
                'data' => $produk
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function reset_periode($id)
    {
        try {
            DB::beginTransaction();
            //code...
            $data = RiwayatAktifPeriode::find($id);
            $data->status = 'selesai';
            $data->save();


            $aktif = AktifPeriode::first();
            $aktif->tahun = Carbon::now()->year;
            $aktif->save();


            DB::commit();
            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function update_periode(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            //code...
            $data = RiwayatAktifPeriode::find($id);
            $getTahun = json_decode($data->isi)->years;

            if ($request->status == 'terima') {
                $aktif = AktifPeriode::first();
                $aktif->tahun = $getTahun;
                $aktif->save();
            }
            $data->status = $request->status;
            $data->tgl_konfirmasi = Carbon::now()->format('Y-m-d H:i:s');
            $data->save();
            DB::commit();
            return response()->json([
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function show_periode()
    {
        $data = RiwayatAktifPeriode::all();
        $getYearsAktif = AktifPeriode::first();
        if ($getYearsAktif->tahun != Carbon::now()->format('Y')) {
            $isOpen = false;
        } else {
            $isOpen = true;
        }
        $obj = array();
        foreach ($data as $d) {
            $x = json_decode($d->isi);
            $obj[] = array(
                'id' => $d->id,
                'periode' => $x->years,
                'alasan' => $x->alasan,
                'pemohon' => $d->user,
                'durasi_buka' => $x->maxOpenDay . ' Hari',
                'status' => $d->status,
                'tgl_persetujuan' => $d->tgl_konfirmasi,
                'tgl_pengajuan' => $d->created_at,
                'tgl_tutup' => $d->created_at->addDays($x->maxOpenDay),
            );
        }
        return response()->json([
            'success' => true,
            'data' => $obj,
            'isOpen' => $isOpen
        ], 200);
    }

    public function permintaan_periode(Request $request)
    {
        try {
            //code...
            DB::beginTransaction();
            $cek = RiwayatAktifPeriode::where(function ($query) {
                $query->where('user', Auth::user()->nama)
                    ->where('status', 'pengajuan')
                    ->orWhere('status', 'terima');
            })
                ->count();

            if ($cek > 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan Lama Belum di proses'
                ], 500);
            } else {
                RiwayatAktifPeriode::create([
                    'user' => Auth::user()->nama,
                    'isi' => json_encode($request->all()),
                    'status' => 'pengajuan'
                ]);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Permintaan Periode Berhasil Di kirim'
                ], 200);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function get_permintaan_pengajuan()
    {
        try {
            $data = RiwayatAktifPeriode::where('status', 'pengajuan')->get();
            $obj = array();
            foreach ($data as $d) {
                $x = json_decode($d->isi);
                $obj[] = array(
                    'id' => $d->id,
                    'periode' => $x->years,
                    'alasan' => $x->alasan,
                    'pemohon' => $d->user,
                    'permintaan_durasi_buka' => $x->maxOpenDay . ' Hari',
                    'status' => $d->status,
                    'tgl_pengajuan' => Carbon::parse($d->created_at)->format('d-m-Y')
                );
            }
            return response()->json($obj);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function changeGenerateProduk(Request $request)
    {
        try {
            $produk = Produk::find($request->id);
            $produk->generate_seri = $request->generate_seri;
            $produk->save();
            return response()->json([
                'success' => true,
                'data' => $produk
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function select_penjualan_produk_param_new(Request $request, $value)
    {
        if ($value == 'ekatalog') {
            $paket = PenjualanProduk::
                // $paket = PenjualanProduk::addSelect(['stok' => function ($q) {
                //     $q->selectRaw('count(noseri_barang_jadi.id)')
                //         ->from('noseri_barang_jadi')
                //         ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                //         ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                //         ->leftjoin('detail_penjualan_produk', 'detail_penjualan_produk.produk_id', '=', 'produk.id')
                //         ->where('is_ready', 0)
                //         ->whereColumn('detail_penjualan_produk.penjualan_produk_id', 'penjualan_produk.id');
                // }])
                where('is_aktif', '1')
                ->where('status', 'ekat')
                ->get();
            foreach ($paket as $key_p => $p) {
                $item[$key_p] = array(
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'harga' => $p->harga,
                    'stok' => $p->stok,
                    'is_kalibrasi' => $p->Produk->whereNotNull('kode_lab_id')->count() > 0 ? true : false,
                );

                foreach ($p->Produk as $key_prd => $produk) {
                    $item[$key_p]['variasi'][] = array(
                        'id' => $produk->GudangBarangJadi->first()->id,
                        'produk_id' => $produk->id
                    );
                }
            }
        } else {
            $paket = PenjualanProduk::where('is_aktif', '1')
                ->get();
            foreach ($paket as $key_p => $p) {
                $item[$key_p] = array(
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'harga' => $p->harga,
                );
                foreach ($p->Produk as $key_prd => $produk) {
                    $item[$key_p]['variasi'][] = array(
                        'id' => $produk->GudangBarangJadi->first()->id,
                        'produk_id' => $produk->id
                    );
                }
            }
        }
        return response()->json($item);
    }
}
