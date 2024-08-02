<?php

namespace App\Http\Controllers;

use App\Models\ReturnTheQuantity;
use App\Traits\PdfTraits;
use Carbon\Carbon;


class PdfHelperController extends Controller
{
    use PdfTraits;
    public static function data()
    {
        $data = ReturnTheQuantity::all();
        $today = Carbon::now()->format('Y / m / d');

        $html = view('report.index', [
            'data' => $data,
            'today' => $today,
        ])->render();
        return  self::printApiPdf($html);
    }


}
