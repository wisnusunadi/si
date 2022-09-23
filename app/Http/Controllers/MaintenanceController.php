<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    //AIR

    public function show_air_masuk(){
        return view('page.maintenance.air.masuk');
    }
    public function show_air_keluar(){
        return view('page.maintenance.air.keluar');
    }

    //LISTRIK

    public function show_listrik_monitoring_table(){
        return view('page.maintenance.listrik.monitoring_table');
    }
    public function show_listrik_monitoring_grafik(){
        return view('page.maintenance.listrik.monitoring_grafik');
    }
    public function show_listrik_panel(){
        return view('page.maintenance.listrik.panel');
    }
}
