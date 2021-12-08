<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

// model
use App\Models\Produk;
use App\Models\JadwalPerakitan;
use App\Models\GudangBarangJadi;
use App\Models\Ekatalog;
use App\Models\Spa;
use App\Models\Spb;

// event
use App\Events\TestEvent;

class PpicController extends Controller
{
    // API
    public function getEvent($status = "", Request $request)
    {
        $this->updateStatus();
        $event = JadwalPerakitan::with('Produk.produk')->orderBy('tanggal_mulai', 'asc');

        if (isset($request->state)) {
            $state = $this->convertState($request->state);
            $event->where('state', $state);
        }

        if (isset($request->konfirmasi)) {
            $event->where('konfirmasi', $request->konfirmasi);
        }

        if ($status == "penyusunan") {
            $event = $event->where('status', 1);
        } else if ($status == "pelaksanaan") {
            $event = $event->where('status', 2);
        } else if ($status == "selesai") {
            $event = $event->where('status', 3);
        } else if ($status == "datatables") {
            return DataTables::of($event)->addIndexColumn()->make(true);
        }

        return $event->get();
    }

    public function get_data_barang_jadi()
    {
        $data = GudangBarangJadi::with('produk.KelompokProduk', 'produk.product', 'satuan')->get();
        return $data;
    }

    public function get_data_perakitan()
    {
        // $data = JadwalPerakitan::with('Produk.produk.product')->get();
        $data = DB::table('jadwal_perakitan')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'jadwal_perakitan.produk_id')
            ->join('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            ->join('m_produk', 'm_produk.id', '=', 'produk.produk_id')
            ->select('jadwal_perakitan.id', 'produk.nama as nama', 'jadwal_perakitan.jumlah', 'jadwal_perakitan.tanggal_mulai', 'jadwal_perakitan.tanggal_selesai', 'jadwal_perakitan.status')
            ->orderBy('jadwal_perakitan.tanggal_mulai', 'desc')->get();
        return $data;
    }

    public function get_data_so()
    {
        $Ekatalog = collect(Ekatalog::with('Pesanan')->orderBy('id', 'DESC')->get());
        $Spa = collect(Spa::with('Pesanan')->orderBy('id', 'DESC')->get());
        $Spb = collect(Spb::with('Pesanan')->orderBy('id', 'DESC')->get());
        $data = $Ekatalog->merge($Spa)->merge($Spb);

        return $data;
    }

    public function updateStatus()
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
            // $data->status = 1;
            $data->status = 'penyusunan';
            $data->save();
        }

        $pelaksanaan = JadwalPerakitan::whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month)->get();
        foreach ($pelaksanaan as $data) {
            // $data->status = 2;
            $data->status = 'pelaksanaan';
            $data->save();
        }

        $selesai = JadwalPerakitan::where('tanggal_mulai', '<', "$year-$month-01")->get();
        foreach ($selesai as $data) {
            // $data->status = 3;
            $data->status = 'selesai';
            $data->save();
        }
    }

    public function getProduk()
    {
        $model = Produk::all();

        return $model;
    }

    public function addEvent(Request $request)
    {
        $data = [
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'warna' => $request->warna,
            'konfirmasi_rencana' => 0,
            'konfirmasi_perubahan' => 0,
            'proses_konfirmasi' => 0
        ];
        JadwalPerakitan::create($data);

        return $this->getEvent($request->status, $request);
    }

    public function deleteEvent(Request $request)
    {
        JadwalPerakitan::destroy($request->id);
        return JadwalPerakitan::with("Produk")->get();
    }



    public function updateConfirmation(Request $request)
    {
        $event = JadwalPerakitan::where('status', $request->status)->get();
        foreach ($event as $data) {
            if (isset($request->proses_konfirmasi)) $data->proses_konfirmasi = $request->proses_konfirmasi;
            if (isset($request->konfirmasi_rencana)) $data->konfirmasi_rencana = $request->konfirmasi_rencana;
            if (isset($request->konfirmasi_perubahan)) $data->konfirmasi_perubahan = $request->konfirmasi_perubahan;
            $data->save();
        }

        return $this->getEvent($request->status, $request);
    }

    public function resetConfirmation()
    {
        // $event = JadwalPerakitan::all();
        // foreach ($event as $data) {
        //     if ($data->status == "penyusunan") {
        //         $data->konfirmasi_rencana = 0;
        //         $data->konfirmasi_perubahan = 0;
        //     } else if ($data->status == "pelaksanaan") {
        //         $data->konfirmasi_rencana = 1;
        //         $data->konfirmasi_perubahan = 0;
        //     }
        //     $data->proses_konfirmasi = 0;
        //     $data->save();
        // }

        // return "success";
        $token = Str::random(60);
        $user = User::find(3);
        $user->api_token = hash('sha256', $token); // <- This will be used in client access
        $user->save();
        return User::find(3);
    }

    public function getGbjQuery()
    {
        $query = GudangBarangJadi::with('produk', 'noseri')->get();

        return $query;
    }

    public function getGbjDatatable()
    {
        $query = $this->getGbjQuery();

        return DataTables::of($query)->addIndexColumn()->make(true);
    }

    public function testBroadcast(Request $request)
    {
        broadcast(new TestEvent($request->message))->toOthers();
        return "success";
    }
}
