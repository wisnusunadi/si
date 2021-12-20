<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// model
use App\Models\JadwalPerakitan;
use App\Models\GudangBarangJadi;

// event
use App\Models\DetailPesananProduk;
use App\Models\GudangKarantinaDetail;

class PpicController extends Controller
{
    // Properties
    public function change_status($status)
    {
        if ($status == 'penyusunan') return 6;
        else if ($status == 'pelaksanaan') return 7;
        else if ($status == 'selesai') return 8;
        return $status;
    }

    public function change_state($state)
    {
        if ($state == 'perencanaan') return 17;
        else if ($state == 'persetujuan') return 18;
        else if ($state == 'perubahan') return 19;
        return $state;
    }

    // API
    public function get_data_perakitan($status = "all")
    {
        $this->update_perakitan_status();
        $status = $this->change_status($status);
        if ($status == $this->change_status('penyusunan')) {
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orderBy('tanggal_mulai', 'desc')->get();
        } else if ($status == $this->change_status("pelaksanaan")) {
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orderBy('tanggal_mulai', 'desc')->get();
        } else {
            $data = JadwalPerakitan::with('Produk.produk')->orderBy('tanggal_mulai', 'desc')->get();
        }

        return $data;
    }

    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk.KelompokProduk', 'produk.product', 'satuan')->get();
        return $data;
    }

    public function get_data_so()
    {
        $data = DetailPesananProduk::with(
            'GudangBarangJadi.produk',
            'DetailPesanan.Pesanan.Ekatalog.Customer',
            'DetailPesanan.Pesanan.Spa.Customer',
            'DetailPesanan.Pesanan.Spb.Customer',
            'DetailPesanan.Pesanan.log'
        )
            ->get();
        return $data;
    }

    public function create_data_perakitan(Request $request)
    {
        $status = $this->change_status($request->status);
        $state = $this->change_state($request->state);

        $data = [
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $status,
            'state' => $state,
            'konfirmasi' => $request->konfirmasi,
            'warna' => $request->warna,
        ];
        JadwalPerakitan::create($data);

        return $this->get_data_perakitan($status);
    }

    public function update_data_perakitan(Request $request, $id)
    {
        $data = JadwalPerakitan::find($id);
        if (isset($request->tanggal_mulai)) {
            $data->tanggal_mulai = $request->tanggal_mulai;
        }
        if (isset($request->tanggal_selesai)) {
            $data->tanggal_selesai = $request->tanggal_selesai;
        }
        if (isset($request->state)) {
            $state = $this->change_state($request->state);
            $data->state = $state;
        }
        if (isset($request->konfirmasi)) {
            $data->konfirmasi = $request->konfirmasi;
        }
        $data->save();

        return $this->get_data_perakitan($request->status);
    }

    public function update_many_data_perakitan(Request $request, $status)
    {
        if (isset($request->data)) {
            foreach ($request->data as $data) {
                $this->update_data_perakitan($request, $data['id']);
            }
        } else {
            $event = JadwalPerakitan::where('status', $this->change_status($status))->get();
            foreach ($event as $data) {
                if (isset($request->tanggal_mulai)) {
                    $data->tanggal_mulai = $request->tanggal_mulai;
                }
                if (isset($request->tanggal_selesai)) {
                    $data->tanggal_selesai = $request->tanggal_selesai;
                }
                if (isset($request->state)) {
                    $state = $this->change_state($request->state);
                    $data->state = $state;
                }
                if (isset($request->konfirmasi)) {
                    $data->konfirmasi = $request->konfirmasi;
                }
                $data->save();
            }
        }

        return $this->get_data_perakitan($status);
    }

    public function delete_data_perakitan(Request $request, $id)
    {
        $data = JadwalPerakitan::find($id);
        $data->delete();
    }

    public function counting_status_data_perakitan()
    {
        $penyusunan = count(JadwalPerakitan::where('status', $this->change_status('penyusunan'))->get());
        $pelaksanaan = count(JadwalPerakitan::where('status', $this->change_status('pelaksanaan'))->get());
        $selesai = count(JadwalPerakitan::where('status', $this->change_status('selesai'))->get());

        return [$penyusunan, $pelaksanaan, $selesai];
    }


    // helper function
    public function update_perakitan_status()
    {
        $month = date('m');
        $year = date('Y');

        if ($month != 12) {
            $new_month = $month + 1;
            $new_year = $year;
        } else {
            $new_month = 1;
            $new_year = $year + 1;
        }
        $penyusunan = JadwalPerakitan::where('tanggal_mulai', '>=', "$new_year-$new_month-01")->get();
        foreach ($penyusunan as $data) {
            $data->status = $this->change_status('penyusunan');
            $data->save();
        }

        $pelaksanaan = JadwalPerakitan::whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month)->get();
        foreach ($pelaksanaan as $data) {
            $data->status = $this->change_status('pelaksanaan');
            $data->save();
        }

        $selesai = JadwalPerakitan::where('tanggal_mulai', '<', "$year-$month-01")->get();
        foreach ($selesai as $data) {
            $data->status = $this->change_status('selesai');
            $data->save();
        }
    }

    public function test_query()
    {
        $data = JadwalPerakitan::all();
        foreach ($data as $item) {
            if ($this->change_status($item->status) == 'penyusunan') {
                $item->state = 17;
            } else {
                $item->state = 18;
            }

            $item->save();
        }
        return $data;
    }
}
