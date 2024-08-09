<?php

namespace App\Http\Controllers;

use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptFromAssociation;
use App\Models\ReturnTheQuantity;
use App\Traits\FormatData;
use App\Traits\PdfTraits;


class PdfHelperController extends Controller
{
    use PdfTraits, FormatData;
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
        $CollectingMilkFromFamily = CollectingMilkFromFamily::whereIn('id', request('data'))->get();
        $data = $CollectingMilkFromFamily->map(function ($query) {
            return self::formatCollectingMilkFromFamilyData($query);
        });
        $html = view('report.institution.CollectingMilkFromFamily', [
            'data' => $data,
        ])->render();
        return  self::printPdf($html);
    }
}
