<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Pesanan;
use App\Models\Customer;
use App\Models\Ekatalog;
use App\Models\NoseriDsb;
use App\Models\AktifPeriode;
use Illuminate\Http\Request;
use App\Models\DetailPesanan;
use App\Models\DetailPesananDsb;
use App\Models\DetailPesananPart;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPesananProduk;

use App\Models\OutgoingPesananPart;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPesananProdukDsb;

class PenjualanControllerNew extends Controller
{
    protected $penjualanController;
    public function __construct(PenjualanController $penjualanController)
    {
        $this->penjualanController = $penjualanController;
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
                    $generate = $this->penjualanController->createSObyPeriod($jnis, $periode);
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
                            'keterangan' => $produk['catatan']
                        ]);
                        foreach ($produk['variasi'] as $variasi) {
                            DetailPesananProduk::create([
                                'detail_pesanan_id' => $detail_pesanan['id'],
                                'gudang_barang_jadi_id' => $variasi['id']
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
                                'gudang_barang_jadi_id' => $variasi['id']
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

    function penjualanStore(Request $request)
    {
        // dd($request->all());
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
                'so' =>  $request->no_po != '' ? $this->penjualanController->createSObyPeriod($jnis, $periode) : NULL,
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

            $x = $pesanan->id;
            $no_po_nonekat = $pesanan->no_po;

            DB::commit();
            return response()->json([
                'message' => 'ok',
                'pesanan_id' =>  $no_po_nonekat != null ? $x : 'refresh',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Kesalahan' . $th->getMessage(),
            ], 500);
        }
    }
}
