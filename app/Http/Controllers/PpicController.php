<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// model
use App\Models\JadwalPerakitan;
use App\Models\JadwalPerakitanRencana;
use App\Models\JadwalPerakitanLog;
use App\Models\GudangBarangJadi;
use App\Models\KomentarJadwalPerakitan;

// event
use App\Models\DetailPesananProduk;
use App\Models\GudangKarantinaDetail;

use function PHPSTORM_META\type;

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
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        } else if ($status == $this->change_status("pelaksanaan")) {
            $data = JadwalPerakitan::with('Produk.produk')->where('status', $status)->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        } else {
            $data = JadwalPerakitan::with('Produk.produk')->orderBy('tanggal_mulai', 'asc')->orderBy('tanggal_selesai', 'asc')->get();
        }

        return $data;
    }

    public function get_data_perakitan_rencana()
    {
        $data = JadwalPerakitanRencana::with('JadwalPerakitan.Produk.produk')->orderBy('tanggal_mulai', 'asc')->get();
        return $data;
    }

    public function get_data_barang_jadi(Request $request)
    {
        $data = GudangBarangJadi::with('produk.KelompokProduk', 'produk.product', 'satuan');
        if (isset($request->id)) {
            $data->where('id', $request->id);
        }
        $data = $data->get();
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

    public function get_data_sparepart_gk()
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_spr) as jml'))
            ->whereNotNull('t_gk_detail.sparepart_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.sparepart_id')
            ->join('m_gs', 'm_gs.id', 't_gk_detail.sparepart_id')
            ->join('m_sparepart', 'm_sparepart.id', 'm_gs.sparepart_id')
            ->get();
        return $data;
    }

    public function get_data_unit_gk(Request $request)
    {
        $data = GudangKarantinaDetail::select('*', DB::raw('sum(qty_unit) as jml'))
            ->whereNotNull('t_gk_detail.gbj_id')
            ->where('is_draft', 0)
            ->where('is_keluar', 0)
            ->groupBy('t_gk_detail.gbj_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.id', 't_gk_detail.gbj_id')
            ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id');

        if (isset($request->id)) {
            $data->where('gbj_id', $request->id);
        }

        $data = $data->get();
        return $data;
    }

    public function get_komentar_jadwal_perakitan(Request $request)
    {
        $data = KomentarJadwalPerakitan::where('status', $this->change_status($request->status))->orderBy('tanggal_permintaan', 'desc')->get();
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
            'status_tf' => 11,
        ];
        JadwalPerakitan::create($data);

        return $this->get_data_perakitan($status);
    }

    public function create_komentar_jadwal_perakitan(Request $request)
    {
        $state = $this->change_state($request->state);
        $status = $this->change_status($request->status);
        KomentarJadwalPerakitan::create([
            'tanggal_permintaan' => $request->tanggal_permintaan,
            'tanggal_hasil' => $request->tanggal_hasil,
            'state' => $state,
            'status' => $status,
            'hasil' => $request->hasil,
            'komentar' => $request->komentar,
        ]);
    }

    public function update_komentar_jadwal_perakitan(Request $request)
    {
        $data = KomentarJadwalPerakitan::orderBy('tanggal_permintaan', 'desc')->where("status", $this->change_status($request->status))->first();
        $data->tanggal_hasil = $request->tanggal_hasil;
        $data->hasil = $request->hasil;
        $data->komentar = $request->komentar;
        $data->save();
    }

    public function update_data_perakitan(Request $request, $id)
    {
        $data = JadwalPerakitan::find($id);

        $object = new JadwalPerakitanLog();
        $object->jadwal_perakitan_id = $data->id;
        $object->tanggal_mulai = $data->tanggal_mulai;
        $object->tanggal_selesai = $data->tanggal_selesai;

        if (isset($request->tanggal_mulai)) {
            $data->tanggal_mulai = $request->tanggal_mulai;
            $object->tanggal_mulai_baru = $request->tanggal_mulai;
        } else {
            $object->tanggal_mulai_baru = $data->tanggal_mulai;
        }
        if (isset($request->tanggal_selesai)) {
            $data->tanggal_selesai = $request->tanggal_selesai;
            $object->tanggal_selesai_baru = $request->tanggal_selesai;
        } else {
            $object->tanggal_selesai_baru = $data->tanggal_selesai;
        }

        if (isset($request->state)) {
            $state = $this->change_state($request->state);
            $data->state = $state;
        }
        if (isset($request->konfirmasi)) {
            $data->konfirmasi = $request->konfirmasi;
        }
        $object->save();
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
                $object = new JadwalPerakitanLog();
                $object->jadwal_perakitan_id = $data->id;
                $object->tanggal_mulai = $data->tanggal_mulai;
                $object->tanggal_selesai = $data->tanggal_selesai;

                if (isset($request->tanggal_mulai)) {
                    $data->tanggal_mulai = $request->tanggal_mulai;
                    $object->tanggal_mulai_baru = $request->tanggal_mulai;
                } else {
                    $object->tanggal_mulai_baru = $data->tanggal_mulai;
                }

                if (isset($request->tanggal_selesai)) {
                    $data->tanggal_selesai = $request->tanggal_selesai;
                    $object->tanggal_selesai_baru = $request->tanggal_selesai;
                } else {
                    $object->tanggal_selesai_baru = $data->tanggal_selesai;
                }

                if (isset($request->state)) {
                    $state = $this->change_state($request->state);
                    $data->state = $state;
                }
                if (isset($request->konfirmasi)) {
                    $data->konfirmasi = $request->konfirmasi;
                }
                $object->save();
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
        // update jadwal_perakitan
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

        $update_rencana_jadwal = false;
        if (
            count(JadwalPerakitanRencana::all()) == 0 ||
            $month != date('m', strtotime(JadwalPerakitanRencana::first()->tanggal_mulai))
        ) {
            // empty jadwal_perakitan_rencana table
            JadwalPerakitanRencana::truncate();
            $update_rencana_jadwal = true;
        }

        $pelaksanaan = JadwalPerakitan::whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month)->get();
        foreach ($pelaksanaan as $data) {
            $data->status = $this->change_status('pelaksanaan');
            $data->save();

            if ($update_rencana_jadwal) {
                // insert data to jadwal_perakitan_rencana                
                JadwalPerakitanRencana::create([
                    'jadwal_perakitan_id' => $data->id,
                    'tanggal_mulai' => $data->tanggal_mulai,
                    'tanggal_selesai' => $data->tanggal_selesai,
                ]);
            }
        }

        $selesai = JadwalPerakitan::where('tanggal_mulai', '<', "$year-$month-01")->get();
        foreach ($selesai as $data) {
            $data->status = $this->change_status('selesai');
            $data->save();
        }
    }

    public function count_proses_jadwal()
    {
        $data = KomentarJadwalPerakitan::all();
        $permintaan = 0;
        $proses = 0;

        foreach ($data as $item) {
            if (!$item->tanggal_hasil) {
                $permintaan += 1;
            } else {
                if ((time() - (60 * 60 * 24)) < strtotime($item->tanggal_hasil)) {
                    $proses += 1;
                }
            }
        }

        return [$permintaan, $proses];
    }

    public function test_query()
    {
        $data = JadwalPerakitan::all();
        foreach ($data as $d) {
            if ($d->status == $this->change_status("penyusunan")) {
                $d->state = $this->change_state("perencanaan");
                $d->save();
            } else {
                $d->state = $this->change_state("persetujuan");
                $d->konfirmasi = 1;
                $d->save();
            }
        }
    }
}
