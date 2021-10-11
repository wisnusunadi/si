<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

// model
use App\Models\Part;
use App\Models\Event;

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
        } else if ($status == "penyusunan") {
            $month += 1;
            $event = $event->where('tanggal_mulai', '>=', "$year-$month-01")->get();
        } else {
            $event = $event->where('tanggal_mulai', '<', "$year-$month-01")->get();
        }

        // $event = Event::all();
        return $event;
    }
}
