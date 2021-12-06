<?php

namespace App\Http\Controllers;

use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\Logistik;
use App\Models\NoseriCoo;
use App\Models\NoseriDetailLogistik;
use Illuminate\Http\Request;
use PDF;
use App\Models\Pesanan;
use Ekatalog;
use Illuminate\Support\Carbon;

class DcController extends Controller
{
    public function pdf_coo($id)
    {
        $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->get();
        $pdf = PDF::loadView('page.dc.coo.pdf_semua', ['data' => $data])->setPaper('A4');
        return $pdf->stream('');
    }
    public function pdf_semua_coo($id)
    {
        $data = NoseriCoo::whereHas('Noserilogistik', function ($q) use ($id) {
            $q->where('detail_logistik_id', $id);
        })->get();
        $count = $data->count();
        $pdf = PDF::loadView('page.dc.coo.pdf_semua', ['data' => $data, 'count' => $count])->setPaper('A4');
        return $pdf->stream('');
    }
    public function pdf_seri_coo($id)
    {
        $data = NoseriCoo::where('noseri_logistik_id', $id)->first();
        $tgl_sj = $data->Noserilogistik->DetailLogistik->logistik->tgl_kirim;
        $bulan =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('m');
        $tahun =  Carbon::createFromFormat('Y-m-d', $tgl_sj)->format('Y');
        $romawi = $this->toRomawi($bulan);
        $footer = Carbon::createFromFormat('Y-m-d', $tgl_sj)->isoFormat('D MMMM Y');

        $pdf = PDF::loadView('page.dc.coo.pdf', ['data' => $data, 'romawi' => $romawi, 'tahun' => $tahun, 'footer' => $footer])->setPaper('A4');
        return $pdf->stream('');
    }
    public function get_data_coo()
    {
        $data = NoseriCoo::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('kosong', function () {
                return '-';
            })
            ->addColumn('seri', function ($data) {
                return $data->Noserilogistik->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->addColumn('so', function ($data) {
                return $data->Noserilogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
            })
            ->addColumn('no_paket', function ($data) {
                return $data->Noserilogistik->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->no_paket;
            })
            ->addColumn('nama_produk', function ($data) {
                if ($data->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo != '') {
                    return $data->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->nama_coo;
                } else {
                    return '';
                }
            })
            ->addColumn('noakd', function ($data) {
                if ($data->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd != '') {
                    return $data->Noserilogistik->DetailLogistik->DetailPesananProduk->GudangBarangJadi->Produk->no_akd;
                } else {
                    return '';
                }
            })
            ->addColumn('bulan', function ($data) {
                $bulan =  Carbon::createFromFormat('Y-m-d', $data->Noserilogistik->DetailLogistik->logistik->tgl_kirim)->format('m');
                $romawi = $this->toRomawi($bulan);
                return $romawi;
            })
            ->addColumn('tgl_sj', function ($data) {
                return  $data->Noserilogistik->DetailLogistik->logistik->tgl_kirim;
            })
            ->addColumn('laporan', function ($data) {
                return '
                    <a href="' . route('dc.seri.coo.pdf', $data->Noserilogistik->id) . '" target="_blank">
                    <i class="fas fa-file"></i>
                                                        </a>
                  ';
            })
            ->rawColumns(['laporan'])
            ->make(true);
    }
    public function get_data_so()
    {
        $Ekatalog = collect(Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->Has('Ekatalog')->get());
        $Spa = collect(Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->Has('Spa')->get());
        $Spb = collect(Pesanan::Has('DetailPesanan.DetailPesananProduk.NoseriDetailPesanan.NoseriDetailLogistik')->Has('Spb')->get());
        $data = $Ekatalog->merge($Spa)->merge(($Spb));
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_paket', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->no_paket;
                } else {
                    return '';
                }
            })
            ->addColumn('batas_paket', function () {
                return '';
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->customer->nama;
                } else if ($name[1] == 'SPA') {
                    return $data->spa->customer->nama;
                } else {
                    return $data->spb->customer->nama;
                }
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return $data->ekatalog->instansi;
                } else {
                    return '';
                }
            })
            ->addColumn('status', function ($data) {
                return '';
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->so);
                if ($name[1] == 'EKAT') {
                    return '<a href="' . route('dc.so.detail', [$data->id, 'ekatalog']) . '">
                    <i class="fas fa-search"></i>
                </a>';
                } else if ($name[1] == 'SPA') {
                    return '<a href="' . route('dc.so.detail', [$data->id, 'spa']) . '">
                    <i class="fas fa-search"></i>
                </a>';
                } else {
                    return '<a href="' . route('dc.so.detail', [$data->id, 'spb']) . '">
                    <i class="fas fa-search"></i>
                </a>';
                }
            })
            ->rawColumns(['button'])
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
                    return $data->DetailPesananProduk->GudangBarangJadi->nama;
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

                if ($coo == 0) {
                    return '<span class="badge red-text">Belum Tersedia</span>';
                } else {
                    return ' <span class="badge green-text">Tersedia</span>';
                }
            })
            ->addColumn('button', function ($data) {

                $value = array();
                $get = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->get();
                foreach ($get as $d) {
                    $value[] = $d->id;
                }
                $coo = NoseriCoo::whereIN('noseri_logistik_Id', $value)->get()->count();

                if ($coo == 0) {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                    </div>';
                } else {
                    return ' <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="noserishow dropdown-item" type="button" data-id="' . $data->id . '">
                            <i class="fas fa-eye"></i>
                            Detail
                        </a>
                        <a href="' . route('dc.coo.semua.pdf', [$data->id]) . '">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-file"></i>
                                Laporan PDF
                            </button>
                        </a>
                    </div>';
                }
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
    }
    public function get_data_detail_seri_so($id)
    {
        $data = NoseriDetailLogistik::where('detail_logistik_id', $id)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function ($data) {
                $get = NoseriCoo::where('noseri_logistik_id', $data->id)->get()->count();

                if ($get == 0) {
                    return '  <div class="form-check">
                    <input class=" form-check-input yet nosericheck" type="checkbox" data-value="' . $data->detail_logistik_id . '" data-id="' . $data->id . '" />
                    </div>';
                } else {
                    return '';
                }
            })
            ->addColumn('noseri', function ($data) {
                return  $data->NoseriDetailPesanan->NoseriTGbj->NoseriBarangJadi->noseri;
            })
            ->addColumn('nocoo', function ($data) {
                return '';
            })
            ->addColumn('diket', function ($data) {
                return '';
            })
            ->addColumn('laporan', function ($data) {
                $get = NoseriCoo::where('noseri_logistik_id', $data->id)->get()->count();

                if ($get == 0) {
                    return '
                    <a >
                    <i class="fas fa-file" style="color: grey"></i>
                                                        </a>
                  ';
                } else {
                    return '
                    <a href="' . route('dc.seri.coo.pdf', $data->id) . '" target="_blank">
                    <i class="fas fa-file"></i>
                                                        </a>
                  ';
                }
            })
            ->rawColumns(['checkbox', 'laporan'])
            ->make(true);
    }
    public function get_data_detail_select_seri_so($id, $value)
    {
        $array_seri = explode(',', $id);
        if ($id == 0) {
            $data =  NoseriDetailLogistik::where('detail_logistik_id', $value)->get();
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

            return view('page.dc.so.detail_ekatalog', ['data' => $data]);
        } else {
            return view('page.dc.so.detail_spa');
        }
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
    public function create_coo(Request $request, $value)
    {

        if ($request->diketahui == 'spa') {
            $nama = NULL;
            $jabatan = NULL;
        } else {

            $nama = $request->nama;
            $jabatan = $request->jabatan;
        }
        $replace_array_seri = strtr($value, array('[' => '', ']' => ''));
        $array_seri = explode(',', $replace_array_seri);
        $bool = true;
        for ($i = 0; $i < count($array_seri); $i++) {
            $c = NoseriCoo::create([
                'nama' => $nama,
                'jabatan' => $jabatan,
                'noseri_logistik_id' => $array_seri[$i],
            ]);
            if (!$c) {
                $bool = false;
            }
        }
        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }
    //Another 
    public function bulan_romawi($value)
    {
        $bulan =  Carbon::createFromFormat('Y-m-d', $value)->format('m');
        $to = new DcController();
        $x = $to->toRomawi($bulan);
        return $x;
    }
    public function tahun($value)
    {
        $tahun =  Carbon::createFromFormat('Y-m-d', $value)->format('Y');
        return $tahun;
    }
    public function tgl_footer($value)
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
}
