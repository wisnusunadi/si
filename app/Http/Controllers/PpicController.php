<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

// model
use App\Models\Part;
use App\Models\Event;
use App\Models\DetailProduk;
use App\Models\BillOfMaterial;
use App\Models\Bppb;
use App\Models\Produk;

class PpicController extends Controller
{
    // API
    public function getPart()
    {
        $model = Part::all();

        return DataTables::of($model)->addIndexColumn()->make(true);
    }

    public function getEvent($status)
    {
        $month = date('m');
        $year = date('Y');
        $event = Event::with('DetailProduk')->orderBy('tanggal_mulai', 'asc');

        if ($status == "pelaksanaan") {
            $event = $event->whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month)->get();
            $this->updateStatus($event, 'pelaksanaan');
        } else if ($status == "penyusunan") {
            $month += 1;
            $event = $event->where('tanggal_mulai', '>=', "$year-$month-01")->get();
            $this->updateStatus($event, 'penyusunan');
        } else {
            $event = $event->where('tanggal_mulai', '<', "$year-$month-01")->get();
            $this->updateStatus($event, 'selesai');
        }

        // $event = Event::all();
        return $event;
    }

    public function getDetailProduk()
    {
        $model = DetailProduk::all();

        return $model;
    }

    public function getVersionDetailProduk($id)
    {
        $query = DetailProduk::with('ProdukBillOfMaterial')->find($id);

        return $query;
    }

    public function getMaxQuantity($id)
    {
        $bom = BillOfMaterial::where('produk_bill_of_material_id', '=', $id)
            ->join('part_gudang_part_engs', 'bill_of_materials.part_eng_id', '=', 'part_gudang_part_engs.kode_eng')
            ->join('part_engs', 'part_gudang_part_engs.kode_eng', '=', 'part_engs.kode_part')
            ->join('parts', 'part_gudang_part_engs.kode_gudang', '=', 'parts.kode')
            ->select('bill_of_materials.id', 'part_engs.nama', 'bill_of_materials.jumlah', 'parts.jumlah as stok', 'parts.kode')
            ->get();

        $max_val = INF;
        foreach ($bom as $data) {
            $result = (int)($data->stok / $data->jumlah);
            if ($result < $max_val) $max_val = $result;
        }
        return $max_val;
    }

    public function addEvent(Request $request)
    {
        $data = [
            'detail_produk_id' => $request->detail_produk_id,
            'produk_bill_of_material_id' => $request->produk_bill_of_material_id,
            'jumlah_produksi' => $request->jumlah_produksi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => "penyusunan",
            'warna' => $request->warna,
            'konfirmasi' => 0,
        ];
        Event::create($data);

        return Event::with("DetailProduk")->get();
    }

    public function deleteEvent(Request $request)
    {
        Event::destroy($request->id);
        return Event::with("DetailProduk")->get();
    }

    public function updateStatus($event, $status)
    {
        foreach ($event as $data) {
            $data->status = $status;
            $data->save();
        }
    }

    public function updateConfirmation(Request $request)
    {
        $event = Event::where('status', 'penyusunan')->get();
        foreach ($event as $data) {
            $data->konfirmasi = $request->confirmation;
            $data->save();

            $this->addBppb($data);
        }

        return $this->getEvent("penyusunan");
    }

    public function resetConfirmation()
    {
        $event = Event::where('status', 'penyusunan')->get();
        foreach ($event as $data) {
            $data->konfirmasi = 0;
            $data->save();
        }

        return "success";
    }

    public function addBppb($event)
    {
        $series = $this->findSeriesBppb($event->detail_produk_id);
        $code = $this->findCodeBppb($event->detail_produk_id);
        $today = date("Y-m-d");

        $no_bppb = $series . "/" . $code . "/" . date('m') . "/" . date('y');

        Bppb::create([
            'no_bppb' => $no_bppb,
            'detail_produk_id' => $event->detail_produk_id,
            'divisi_id' => 24, //PPIC id
            'tanggal_bppb' => $today,
            'jumlah' => $event->jumlah_produksi
        ]);
    }

    public function findSeriesBppb($id)
    {
        $bppb = Bppb::where('detail_produk_id', $id)->get();
        return count($bppb);
    }

    public function findCodeBppb($id)
    {
        $produk_id = DetailProduk::find($id)->produk_id;
        $code = Produk::find($produk_id)->kode_barcode;

        return $code;
    }
}
