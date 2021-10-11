<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

// model
use App\Models\Part;

class PpicController extends Controller
{
    // API
    public function getPart()
    {
        $model = Part::all();

        return DataTables::of($model)->addIndexColumn()->make(true);
    }
}
