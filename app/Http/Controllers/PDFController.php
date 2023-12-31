<?php

namespace App\Http\Controllers;

use App\Models\User;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use \PDF;

class PDFController extends Controller
{
    //

    public function generatePDF()
    {

        // Dữ liệu hóa đơn, bạn có thể truyền dữ liệu từ database hoặc bất kỳ nguồn dữ liệu nào bạn muốn.

        $pdf = PDF::loadView('invoice');

        return $pdf->stream('invoice.pdf');
    }
    public function pdfHtml(){
        return view('invoice');
    }

    public function heroku(){
        $user = User::all();
        dd($user);
        
    }

}
