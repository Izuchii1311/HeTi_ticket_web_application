<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class PresensiExport implements FromView, WithColumnWidths
{

    protected $datas;

    public function __construct($data)
    {
        $this->datas = $data;
    }


    public function view(): View
    {
        return view('export.presensi', $this->datas);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 45
        ];
    }
}
