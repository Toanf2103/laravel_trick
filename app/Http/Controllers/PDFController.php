<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;

class PDFController extends Controller
{
    //

    public function generatePDF()
    {

        // Dữ liệu hóa đơn, bạn có thể truyền dữ liệu từ database hoặc bất kỳ nguồn dữ liệu nào bạn muốn.

        $pdf = PDF::loadView('invoice');

        return $pdf->stream('invoice.pdf');
    }

}
