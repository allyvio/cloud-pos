<?php

namespace App\Exports;

use App\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
// use Maatwebsite\Excel\Concerns\FromCollection;

class RekapExport implements FromView
{
    public function __construct($data, $tgl_awal, $tgl_akhir, $total)
    {
        $this->data = $data;
        $this->tgl_akhir = $tgl_akhir;
        $this->tgl_awal = $tgl_awal;
        $this->total = $total;
    }
    use Exportable;

    public function view(): View
    {
        return view('admin.rekap_pdf', [
            'data' => $this->data,
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir,
            'total' => $this->total

        ]);
    }
}
