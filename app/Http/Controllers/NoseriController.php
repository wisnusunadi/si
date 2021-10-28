<?php

namespace App\Http\Controllers;

use App\Models\NoseriBarangJadi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class NoseriController extends Controller
{
    function UpdateNoSeri(Request $request, $id) {
        $noseri = NoseriBarangJadi::find($id);
        $noseri->noseri = $request->noseri;
        $noseri->updated_at = Carbon::now();
        $noseri->save();

        return response()->json(['msg' => 'Data berhasil diubah']);
    }

    function DestroyNoSeri($id){
        $noseri = NoseriBarangJadi::find($id);
        $noseri->delete();

        return response()->json(['msg' => 'Data berhasil dihapus']);
    }
}
