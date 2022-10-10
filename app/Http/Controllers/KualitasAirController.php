<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KualitasAirController extends Controller
{
    function getRequestMicro(Request $request)
    {
        $tds = $request->input('tds');
        $tss = $request->input('tss');

        DB::table('kualitas_air')->insert([
            'tds' => $tds,
            'tss' => $tss,
            'ph' => 0,
        ]);

        return response()->json([
            'error' => false,
            'msg' => 'Data berhasil diinput',
        ]);
    }

    function getVolume()
    {
        try {
            $volume = DB::table('tbl_volume_harian')->select([
                'hari', 'volume', 'arah', 'created_at'
            ])->latest('updated_at')->take(7)->get();

            if (count($volume) > 0) {
                return response()->json([
                    'error' => false,
                    'success' => true,
                    'msg' => 'Data Tersedia',
                    'data' => $volume,
                ]);
            } else {
                return response()->json([
                    'error' => false,
                    'success' => false,
                    'msg' => 'Data Tidak Tersedia'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }
    function getDebit()
    {
        try {
           
            $debit = DB::table('debit_air')->select([
                'debit', 'arah', 'created_at'
            ])->latest('created_at')->take(13)->get();
           
            if (count($debit) > 0) {
                return response()->json([
                    'error' => false,
                    'success' => true,
                    'msg' => 'Data Tersedia',
                    'data2' => $debit
                ]);
            } else {
                return response()->json([
                    'error' => false,
                    'success' => false,
                    'msg' => 'Data Tidak Tersedia'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }
    function getKualitas()
    {
        try {
            $kualitas = DB::table('kualitas_air')->select([
                'tds', 'tss', 'ph', 'created_at'
            ])->latest('created_at')->take(5)->get();

            if (count($kualitas) > 0) {
                return response()->json([
                    'error' => false,
                    'success' => true,
                    'msg' => 'Data Tersedia',
                    'data3' => $kualitas
                ]);
            } else {
                return response()->json([
                    'error' => false,
                    'success' => false,
                    'msg' => 'Data Tidak Tersedia'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }
}
