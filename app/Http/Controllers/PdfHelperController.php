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
        $quantity = $CollectingMilkFromFamily->sum('quantity');
        $data = $CollectingMilkFromFamily->map(function ($query) use ($quantity) {
            return self::formatCollectingMilkFromFamilyData($query);
        });
        $html = view('report.institution.CollectingMilkFromFamily', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printPdf($html);
    }
    public static function ReceiptInvoiceFromStore()
    {
        $ReceiptInvoiceFromStore = ReceiptInvoiceFromStore::whereIn('id', request('data'))->get();
        $quantity = $ReceiptInvoiceFromStore->sum('quantity');
        $data = $ReceiptInvoiceFromStore->map(function ($query) {
            return self::formatReceiptInvoiceFromStoreData($query);
        });

        $html = view('report.institution.ReceiptInvoiceFromStore', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printPdf($html);
    }
    public static function TransferToFactory()
    {
        $TransferToFactory = TransferToFactory::whereIn('id', request('data'))->get();
        $quantity = $TransferToFactory->sum('quantity');
        $data = $TransferToFactory->map(function ($query) {
            return self::formatTransferToFactoryData($query);
        });
        $html = view('report.institution.TransferToFactory', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printPdf($html);
    }
    public static function ReceiptFromAssociation()
    {
        $ReceiptFromAssociation = ReceiptFromAssociation::whereIn('id', request('data'))->get();
        $quantity = $ReceiptFromAssociation->sum('quantity');
        $data = $ReceiptFromAssociation->map(function ($query) {
            return self::formatReceiptFromAssociationData($query);
        });
        $html = view('report.institution.ReceiptFromAssociation', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printPdf($html);
    }
    public static function ReturnTheQuantity()
    {
        $ReturnTheQuantity = ReturnTheQuantity::whereIn('id', request('data'))->where('return_to', '!=', 'association')->get();
        $quantity = $ReturnTheQuantity->sum('quantity');
        $data = $ReturnTheQuantity->map(function ($query) {
            return self::formatReturnTheQuantityData($query);
        });
        $html = view('report.institution.ReturnTheQuantity', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printPdf($html);
    }
    public static function ReturnTheQuantityToAssociation()
    {
        $ReturnTheQuantityToAssociation = ReceiptFromAssociation::whereIn('id', request('data'))->where('return_to', 'association')->get();
        $quantity = $ReturnTheQuantityToAssociation->sum('quantity');
        $data = $ReturnTheQuantityToAssociation->map(function ($query) {
            return self::formatReturnTheQuantityData($query);
        });
        $html = view('report.institution.ReturnTheQuantityToAssociation', [
            'data' => $data,
            'quantity' => $quantity,
        ])->render();
        return  self::printPdf($html);
    }
}
