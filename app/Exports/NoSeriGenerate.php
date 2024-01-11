<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NoSeriGenerate implements FromCollection, WithHeadings
{
    protected $noseri;

    public function __construct($noseri)
    {
        $this->noseri = $noseri;
    }

    public function collection()
    {
        return collect($this->noseri);
    }

    public function headings(): array
    {
        return [
            'No Seri',
        ];
    }
}
