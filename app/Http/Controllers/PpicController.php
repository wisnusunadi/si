<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

// model
use App\Models\JadwalPerakitan;
use App\Models\GudangBarangJadi;
use App\Models\KomentarPpic;

// event
use App\Events\TestEvent;

class PpicController extends Controller
{
    // API
    public function getEvent($status = "", Request $request)
    {
        $month = date('m');
        $year = date('Y');
        $event = JadwalPerakitan::with('Produk', 'Status', 'State')->orderBy('tanggal_mulai', 'asc');

        if (isset($request->state)) {
            $state = $this->convertState($request->state);
            $event->where('state', $state);
        }

        if (isset($request->konfirmasi)) {
            $event->where('konfirmasi', $request->konfirmasi);
        }

        if ($status == "pelaksanaan") {
            $event = $event->whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month);
            $this->updateStatus($event, 2); // pelaksanaan
        } else if ($status == "penyusunan") {
            $month += 1;
            $event = $event->where('tanggal_mulai', '>=', "$year-$month-01");
            $this->updateStatus($event, 1); // penyusunan
        } else if ($status == "selesai") {
            $event = $event->where('tanggal_mulai', '<', "$year-$month-01");
            $this->updateStatus($event, 3); // selesai
        }

        return $event->get();
    }

    public function getProduk()
    {
        $model = GudangBarangJadi::all();

        return $model;
    }

    public function addEvent(Request $request)
    {
        $status = 0;
        if ($request->status == 'penyusunan') $status = 1;
        if ($request->status == 'pelaksanaan') $status = 2;
        if ($request->status == 'selesai') $status = 3;
        $data = [
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $status,
            'state' => 1,
            'warna' => $request->warna,
            'konfirmasi' => 0
        ];
        JadwalPerakitan::create($data);

        return $this->getEvent($request->status, $request);
    }

    public function deleteEvent(Request $request)
    {
        JadwalPerakitan::destroy($request->id);
        return $this->getEvent($request->status, $request);
    }

    public function updateEvent(Request $request)
    {
        $state = $this->convertState($request->state);
        $status = $this->convertStatus($request->status);

        if (isset($request->acc) && isset($request->reject)) {
            foreach ($request->acc as $data) {
                JadwalPerakitan::find($data['id'])->update(['konfirmasi' => 1]);
            }

            foreach ($request->reject as $data) {
                JadwalPerakitan::find($data['id'])->update(['konfirmasi' => 2]);
            }
        } else {
            $event = JadwalPerakitan::where('status', $status)->get();
            foreach ($event as $data) {
                if (isset($request->state)) $data->state = $state;
                $data->konfirmasi = 0;
                $data->save();
            }
        }

        return $this->getEvent($request->status, $request);
    }

    public function updateStatus($event, $status)
    {
        foreach ($event as $data) {
            $data->status = $status;
            $data->save();
        }
    }

    public function resetEvent()
    {
        $event = JadwalPerakitan::all();
        foreach ($event as $data) {
            if ($data->status == 1) {
                $data->konfirmasi = 0;
                $data->state = 1;
            } else if ($data->status == 2) {
                $data->konfirmasi = 1;
                $data->state = 2;
            }
            $data->save();
        }

        return "success";
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

    // helper function
    public function convertState($state_input)
    {
        $state = 0;
        if ($state_input == 'perencanaan') $state = 1;
        if ($state_input == 'persetujuan') $state = 2;
        if ($state_input == 'perubahan') $state = 3;

        return $state;
    }

    public function convertStatus($status_input)
    {
        $status = 0;
        if ($status_input == 'penyusunan') $status = 1;
        if ($status_input == 'pelaksanaan') $status = 2;
        if ($status_input == 'selesai') $status = 3;

        return $status;
    }

    public function getKomentar()
    {
        $komentar = KomentarPpic::orderBy('tanggal', 'desc')->get();

        return $komentar;
    }

    public function addkomentar(Request $request)
    {
        $data = [
            "tanggal" => date('Y-m-d H:i:s'),
            "state" => $request->state,
            "status" => $request->status,
            "komentar" => $request->komentar,
        ];

        KomentarPpic::create($data);

        return $this->getKomentar();
    }
}
