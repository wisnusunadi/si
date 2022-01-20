<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class GBJExportSPB implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function view(): View
    {
        return view('page.gbj.reports.spb');
    }
}
