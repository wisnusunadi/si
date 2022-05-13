<?php

namespace App\Exports\Sheets;

use App\Models\GudangBarangJadi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetNoseriGBJ implements FromView, WithTitle
{
    public function view(): View
    {
        return view('page.gbj.sheets.noseri');
    }

    public function title(): string
    {
        return 'Noseri';
    }
}
