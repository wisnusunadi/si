<?php

namespace App\Http\Controllers;

use App\Models\Logistik;
use Illuminate\Http\Request;

class AfterSalesController extends Controller
{
    public function get_data_so()
    {
        $data = Logistik::all();
        return datatables()->of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
