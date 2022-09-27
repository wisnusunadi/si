<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

use App\Models\inventory\Verifikasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\inventory\AlatSN;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class VerifikasiController extends Controller
{
    function index($id)
    { 
        try{

            $data = 
            DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))
            ->select(
                DB::raw('concat(a.kd_alatuji,"-",al.no_urut) as kode_alat'),
                'al.serial_number', 'al.status_pinjam_id', 'a.desk_alatuji', 'v.tgl_perawatan', 'a.gbr_alatuji'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'),'a.id_alatuji', '=', 'al.alatuji_id')
            ->leftJoin(DB::raw('erp_kalibrasi.verifikasi v'), 'v.serial_number_id', '=', 'al.alatuji_id')
            ->where('al.id_serial_number', '=', $id)
            ->first();
            
            $data->status_pinjam_id == 16 ?
            $data->status_pinjam_id = '<span class="badge w-25 bc-success"><span class="text-success">Tersedia</span></span>'
            :
            $data->status_pinjam_id = '<span class="badge w-25 bc-danger"><span class="text-danger">Tidak Tersedia</span></span>';

            $tgl = DB::table(DB::raw('erp_kalibrasi.verifikasi v'))
            ->select('tgl_perawatan')
            ->where('v.serial_number_id', $id)
            ->orderByDesc('v.tgl_perawatan')
            ->first();

            if($tgl == null){
                $tgl = 'Alat belum di lakukan Verifikasi';
                $data->tgl_perawatan = $tgl;
            }else{
                $data->tgl_perawatan = $tgl->tgl_perawatan;
            }

            $user = DB::table(DB::raw('erp_spa.users'))->select('*')->get();
            
            return view('page.lab.verifikasi', [
                'data' => $data,
                'id' => $id,
                'user' => $user,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function store_verifikasi(Request $request)
    {
        // try {

            $request->validate([
                'operator' => 'required',
                'tgl_verifikasi' => 'required',
                'pelaksanaan_kendali' => 'required',
                'cekFungsi' => 'required',
                'cekFisik' => 'required',
                'keputusan' => 'required',
                'keterangan' => 'required',
            ],[
                'required' => 'kolom :attribute harus di isi'
            ]);

            $data = DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))->select(
                'al.serial_number', 'a.nm_alatuji'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'al.alatuji_id')
            ->where('al.id_serial_number', $request->serial_number)
            ->first();
    
            $pj = DB::table('erp_spa.users')->where('id', $request->operator)->first();

            if($request->cekFisik == 10 or $request->cekFungsi == 10)
            {
                $request->validate([
                    'tindak_lanjut' => 'required'
                ],[
                    'required' => 'jika not ok, kolom :attribute harus di isi'
                ]);
            }
            
            $jadwal_perawatan = Carbon::parse($request->tgl_verifikasi)->addMonths(3)->format('Y-m-d');

            Verifikasi::create([
                'serial_number_id'  => $request->serial_number,
                'pengendalian'      => $request->pelaksanaan_kendali,
                'keputusan'         => $request->keputusan,
                'hasil_fisik'       => $request->cekFisik,
                'hasil_fungsi'      => $request->cekFungsi,
                'tgl_perawatan'     => $request->tgl_verifikasi,
                'keterangan'        => $request->keterangan,
                'tindak_lanjut'     => $request->tindak_lanjut,
                'jadwal_perawatan'  => $jadwal_perawatan,
                'created_by'        => auth()->user()->id,
            ]);

            // update tabel alat uji
            if($request->cekFungsi == '10' OR $request->cekFisik == '10'){
                AlatSN::find($request->serial_number)
                ->update([
                    'status_pinjam_id' => '10',
                    'kondisi_id' => '10',
                ]);
            }
            if($request->cekFungsi == '9' AND $request->cekFisik == '9'){
                AlatSN::find($request->serial_number)
                ->update([
                    'kondisi_id' => '9',
                    'status_pinjam_id' => '16'
                ]);
            }

            // user log
            $obj = [
                'alat_uji' => $data->nm_alatuji,
                'serial_number' => $data->serial_number,
                'tgl_perawatan' => $request->tgl_perawatan,
                'pj_dilakukan_oleh' => $pj->nama,
            ];
            
            DB::table('erp_spa.tbl_log')->insert([
                'tipe' => 'QC',
                'subjek' => 'Verifikasi alat uji - '.$data->nm_alatuji,
                'response' => json_encode($obj),
                'user_id' => auth()->user()->id,
            ]);

            return redirect()->route('detail', ['id' => $request->serial_number])->with(['success' => '1','verifSuccess' => '2']);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => true,
        //         'msg' => $e->getMessage()
        //     ]);
        // }
    }

    function verifikasi_hist($id)
    {
        try {

            $data = 
            DB::table(DB::raw('erp_kalibrasi.verifikasi v'))
            ->select(
                'v.tgl_perawatan', 'v.pengendalian', 'v.hasil_fisik', 'v.hasil_fungsi', 'v.keputusan', 'v.keterangan', 'v.tindak_lanjut'
            )
            ->where('v.serial_number_id', '=', $id)
            ->get();

            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('keterangan', function($d){
                return $d->keterangan == null ? '-' : $d->keterangan;
            })
            ->editColumn('tindak_lanjut', function($d){
                return $d->tindak_lanjut == null ? '-' : $d->tindak_lanjut;
            })
            ->editColumn('hasil_fisik', function($d){
                return $d->hasil_fisik == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                </div>'
                :
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                </svg>
                </div>'
                ;
            })
            ->editColumn('hasil_fungsi', function($d){
                return $d->hasil_fungsi == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-check-circle-fill text-success " viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                </div>'
                :
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                </svg>
                </div>'
                ;
            })
            ->rawColumns(['hasil_fisik', 'hasil_fungsi'])
            ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
