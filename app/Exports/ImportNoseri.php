<?php

namespace App\Exports;

use App\Exports\Sheets\SheetNoseriGBJ;
use App\Exports\Sheets\SheetProdukGBJ;
use App\Models\GudangBarangJadi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportNoseri implements WithMultipleSheets
{

    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     //
    // }

    // public function view(): View
    // {
    //     $data = GudangBarangJadi::all();
    //     return view('page.gbj.noseri', compact('data'));
    // }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[] = new SheetProdukGBJ();
        $sheets[] = new SheetNoseriGBJ();

        return $sheets;
    }
}
