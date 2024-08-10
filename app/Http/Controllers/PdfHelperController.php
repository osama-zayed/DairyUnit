<?php

namespace App\Http\Controllers;

use App\Models\CollectingMilkFromFamily;
use App\Models\ReceiptFromAssociation;
use App\Models\ReceiptInvoiceFromStore;
use App\Models\ReturnTheQuantity;
use App\Models\TransferToFactory;
use App\Traits\FormatData;
use App\Traits\PdfTraits;


class PdfHelperController extends Controller
{
    use PdfTraits, FormatData;
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
    public static function ReceiptInvoiceFromStore()
    {
        $ReceiptInvoiceFromStore = ReceiptInvoiceFromStore::whereIn('id', request('data'))->get();
        $data = $ReceiptInvoiceFromStore->map(function ($query) {
            return self::formatReceiptInvoiceFromStoreData($query);
        });
        $html = view('report.institution.ReceiptInvoiceFromStore', [
            'data' => $data,
        ])->render();
        return  self::printPdf($html);
    }
    public static function TransferToFactory()
    {
        $TransferToFactory = TransferToFactory::whereIn('id', request('data'))->get();
        $data = $TransferToFactory->map(function ($query) {
            return self::formatTransferToFactoryData($query);
        });
        $html = view('report.institution.TransferToFactory', [
            'data' => $data,
        ])->render();
        return  self::printPdf($html);
    }
    public static function ReceiptFromAssociation()
    {
        $ReceiptFromAssociation = ReceiptFromAssociation::whereIn('id', request('data'))->get();
        $data = $ReceiptFromAssociation->map(function ($query) {
            return self::formatReceiptFromAssociationData($query);
        });
        $html = view('report.institution.ReceiptFromAssociation', [
            'data' => $data,
        ])->render();
        return  self::printPdf($html);
    }
}
