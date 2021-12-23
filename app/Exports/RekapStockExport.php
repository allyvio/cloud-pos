<?php

namespace App\Exports;

use App\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromCollection;

class RekapStockExport implements FromView
{
    public function __construct($data)
    {
        $this->data = $data;
    }
    use Exportable;

    public function view(): View
    {
        return view('admin.shop.rekap_stock', [
            'data' => $this->data,

        ]);
    }
}
