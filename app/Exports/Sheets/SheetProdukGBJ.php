<?php

namespace App\Exports\Sheets;

use App\Models\GudangBarangJadi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class SheetProdukGBJ implements FromView, WithTitle
{
   public function view(): View
   {
        $data = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get()->sortBy('produk.nama');
        return view('page.gbj.sheets.produk', compact('data'));
   }

    public function title(): string
    {
        return 'Produk';
    }
}
