<?php

namespace App\Http\Controllers;

use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptFromAssociation;
use App\Models\ReturnTheQuantity;
use App\Traits\PdfTraits;


class PdfHelperController extends Controller
{
    use PdfTraits;
    public static function ReceiptFromAssociation()
    {
        $data = ReceiptFromAssociation::whereIn('id', request('data'))->get();

        $html = view('report.institution.ReceiptFromAssociation', [
            'data' => $data,
        ])->render();
        return  self::printPdf($html);
    }
    public static function CollectingMilkFromFamily()
    {
        $data = CollectingMilkFromFamily::whereIn('id', request('data'))->get();

        $html = view('report.institution.CollectingMilkFromFamily', [
            'data' => $data,
        ])->render();
        return  self::printPdf($html);
    }


}
