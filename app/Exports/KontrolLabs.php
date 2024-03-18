<?php

namespace App\Exports;

use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\UjiLabDetail;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

use Maatwebsite\Excel\Concerns\FromCollection;

class KontrolLabs implements WithTitle, FromView, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */


    public function __construct(string $dsb, string $tanggal_awal, string $tanggal_akhir)
    {
        $this->dsb = $dsb;
        $this->tanggal_awal = $tanggal_awal;
        $this->tanggal_akhir = $tanggal_akhir;
    }


    public function view(): View
    {
        // $obj = array();
        // $d = $this->dsb;
        // return view('page.lab.kontrol_lab', ['data' => $obj,'x' => $d]);

        if ($this->dsb != "null") {
            $ekt_id = Ekatalog::where('customer_id', $this->dsb)->pluck('pesanan_id')->toArray();
            $spa_id = Spa::where('customer_id', $this->dsb)->pluck('pesanan_id')->toArray();
            $spb_id = Spb::where('customer_id', $this->dsb)->pluck('pesanan_id')->toArray();

            $collection1 = collect($ekt_id);
            $collection2 = collect($spa_id);
            $collection3 = collect($spb_id);

            $mergedCollection = $collection1->merge($collection2)->merge($collection3);

            $getLab = UjiLabDetail::select('uji_lab.pesanan_id as p_id',)
                ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                ->where('uji_lab_detail.status', '!=', 'belum')
                ->whereBetween('uji_lab_detail.tgl_masuk', [$this->tanggal_awal, $this->tanggal_akhir])
                ->pluck('p_id')->toArray();
            $filterCus =  array_values(array_intersect($getLab, $mergedCollection->toArray()));





            $data = UjiLabDetail::select(
                'jenis_pemilik.nama as jp',
                'pesanan.no_po',
                'uji_lab.pesanan_id as p_id',
                'karyawans.nama as pemeriksa',
                'uji_lab.no_order',
                'uji_lab.nama',
                'uji_lab.alamat',
                'uji_lab_detail.no',
                'uji_lab_detail.no_sertifikat',
                'uji_lab_detail.tgl_masuk',
                'metode_lab.metode',
                'produk.nama as produk',
                'noseri_barang_jadi.noseri',
                'uji_lab_detail.tgl_kalibrasi',
                'uji_lab_detail.status',
                'uji_lab_detail.cetak_log',
                'uji_lab_detail.tf_log',
                'logistik.tgl_kirim'
            )
                ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
                ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'noseri_logistik.detail_logistik_id', '=', 'detail_logistik.id')
                ->leftJoin('logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->leftJoin('detail_metode_lab', 'detail_metode_lab.id', '=', 'uji_lab_detail.metode_id')
                ->leftJoin('metode_lab', 'metode_lab.id', '=', 'detail_metode_lab.metode_lab_id')
                ->leftJoin('erp_kesehatan.karyawans', 'karyawans.id', '=', 'uji_lab_detail.pemeriksa_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'uji_lab.pesanan_id')
                ->leftJoin('jenis_pemilik', 'jenis_pemilik.id', '=', 'uji_lab.jenis_pemilik_id')
                ->where('uji_lab_detail.status', '!=', 'belum')
                ->whereIN('pesanan.id', $filterCus)
                ->orderBy('no');


            // ->whereBetween('tgl_masuk',[$this->tanggal_awal, $this->tanggal_akhir]);

            if (count($filterCus) == 0) {
                return view('page.lab.kontrol_lab', ['data' => array()]);
            }

            $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
                ->whereIn('spb.pesanan_id', $filterCus)->get();

            $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
                ->whereIn('spa.pesanan_id',  $filterCus)->get();

            $ekatalog = Ekatalog::select('ekatalog.pesanan_id as id', 'ekatalog.ket', 'ekatalog.tgl_buat', 'ekatalog.tgl_kontrak', 'ekatalog.no_urut as no_urut', 'customer.nama', 'ekatalog.no_paket', 'ekatalog.instansi', 'ekatalog.alamat as alamat_instansi', 'ekatalog.satuan', 'ekatalog.status')
                ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
                ->whereIn('ekatalog.pesanan_id', $filterCus)->get();
        } else {
            $data = UjiLabDetail::select(
                'jenis_pemilik.nama as jp',
                'pesanan.no_po',
                'uji_lab.pesanan_id as p_id',
                'karyawans.nama as pemeriksa',
                'uji_lab.no_order',
                'uji_lab.nama',
                'uji_lab.alamat',
                'uji_lab_detail.no',
                'uji_lab_detail.no_sertifikat',
                'uji_lab_detail.tgl_masuk',
                'metode_lab.metode',
                'produk.nama as produk',
                'noseri_barang_jadi.noseri',
                'uji_lab_detail.tgl_kalibrasi',
                'uji_lab_detail.status',
                'uji_lab_detail.cetak_log',
                'uji_lab_detail.tf_log',
                'logistik.tgl_kirim'
            )
                ->leftJoin('uji_lab', 'uji_lab.id', '=', 'uji_lab_detail.uji_lab_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'uji_lab_detail.detail_pesanan_produk_id')
                ->leftJoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                ->leftJoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'uji_lab_detail.noseri_id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'noseri_logistik.detail_logistik_id', '=', 'detail_logistik.id')
                ->leftJoin('logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
                ->leftJoin('t_gbj_noseri', 't_gbj_noseri.id', '=', 'noseri_detail_pesanan.t_tfbj_noseri_id')
                ->leftJoin('noseri_barang_jadi', 'noseri_barang_jadi.id', '=', 't_gbj_noseri.noseri_id')
                ->leftJoin('detail_metode_lab', 'detail_metode_lab.id', '=', 'uji_lab_detail.metode_id')
                ->leftJoin('metode_lab', 'metode_lab.id', '=', 'detail_metode_lab.metode_lab_id')
                ->leftJoin('erp_kesehatan.karyawans', 'karyawans.id', '=', 'uji_lab_detail.pemeriksa_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'uji_lab.pesanan_id')
                ->leftJoin('jenis_pemilik', 'jenis_pemilik.id', '=', 'uji_lab.jenis_pemilik_id')
                ->where('uji_lab_detail.status', '!=', 'belum')
                ->whereBetween('tgl_kalibrasi', [$this->tanggal_awal, $this->tanggal_akhir])
                ->orderBy('no');

            $spb = Spb::select('spb.pesanan_id as id', 'customer.nama', 'spb.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spb.customer_id')
                ->whereIn('spb.pesanan_id', $data->pluck('p_id')->toArray())->get();

            $spa = Spa::select('spa.pesanan_id as id', 'customer.nama', 'spa.ket')
                ->selectRaw('"" AS no_paket')
                ->selectRaw('"-" AS instansi')
                ->selectRaw('"-" AS alamat_instansi')
                ->selectRaw('"-" AS status')
                ->selectRaw('"-" AS satuan')
                ->selectRaw('"-" AS no_urut')
                ->selectRaw('"-" AS tgl_buat')
                ->selectRaw('"-" AS tgl_kontrak')
                ->leftJoin('customer', 'customer.id', '=', 'spa.customer_id')
                ->whereIn('spa.pesanan_id', $data->pluck('p_id')->toArray())->get();

            $ekatalog = Ekatalog::select('ekatalog.pesanan_id as id', 'ekatalog.ket', 'ekatalog.tgl_buat', 'ekatalog.tgl_kontrak', 'ekatalog.no_urut as no_urut', 'customer.nama', 'ekatalog.no_paket', 'ekatalog.instansi', 'ekatalog.alamat as alamat_instansi', 'ekatalog.satuan', 'ekatalog.status')
                ->leftJoin('customer', 'customer.id', '=', 'ekatalog.customer_id')
                ->whereIn('ekatalog.pesanan_id', $data->pluck('p_id')->toArray())->get();
        }



        $dataInfo =   $ekatalog->merge($spa)->merge($spb);
        if ($data->get()->isEmpty()) {
            $obj = array();
            return view('page.lab.kontrol_lab', ['data' => $obj]);
        } else {
            foreach ($data->get() as $index => $d) {
                $obj[] = array(
                    // create sprintf
                    'no' => sprintf('%05d', $d->no),
                    'p_id' => $d->p_id,
                    'no_po' => $d->no_po,
                    'no_order' => 'LAB-' . str_pad($d->no_order, 4, '0', STR_PAD_LEFT),
                    'tgl_masuk' => $d->tgl_masuk,
                    'nama_alat' => $d->metode,
                    'type' => $d->produk,
                    'noseri' => $d->noseri,
                    'nama_pemilik' => $d->jp,
                    'nama_pemilik_sert' => $d->nama,
                    'alamat' => $d->alamat,
                    'tgl_kalibrasi' => $d->tgl_kalibrasi,
                    'no_sertifikat' => $d->no_sertifikat,
                    'pemeriksa' => $d->pemeriksa,
                    'status' => $d->status,
                    'nosj' => str_pad($d->no_order, 4, '0', STR_PAD_LEFT) . '/LAB/' . date('m', strtotime($d->tgl_kalibrasi)) . '/' . date('y', strtotime($d->tgl_kalibrasi)),
                    'tgl_serah' => $d->tf_log,
                    'keterangan' => null,
                    'tgl_kirim' => $d->tgl_kirim,
                    'dicetak' => $d->cetak_log,
                );
            }
        }
        $merge = [];

        foreach ($obj as $labItem) {
            $mergedItem = $labItem;
            $p_id = $labItem['p_id'];

            foreach ($dataInfo as $poItem) {
                if ($poItem['id'] === $p_id) {
                    $mergedItem['info'] = $poItem;
                    break;
                }
            }

            $merge[] = $mergedItem;
        }

        return view('page.lab.kontrol_lab', ['data' => $merge]);
    }
    public function title(): string
    {
        return 'Kontrol Lab';
    }
}
