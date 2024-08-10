<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Association\TransferToFactoryController as Transfer;
use App\Models\ReceiptFromAssociation;
use App\Models\TransferToFactory;
use App\Traits\FormatData;
use App\Traits\PdfTraits;
use Illuminate\Http\Request;

class TransferToFactoryController extends Transfer
{
    use FormatData, PdfTraits;
    public  function index(Request $request)
    {
        try {
            return self::responseSuccess(self::getTransferToFactoryPaginated($request));
        } catch (\Throwable $th) {
            return self::responseError($th);
        }
    }

    public  function show($id)
    {
        try {
            return self::responseSuccess(self::getTransferToFactoryById($id));
        } catch (\Throwable $th) {
            return self::responseError();
        }
    }
    public function getTransferToFactoryPaginated($request)
    {
        $perPage = $request->get('per_page');
        $page = $request->get('current_page');

        $user = auth('sanctum')->user();

        $query = TransferToFactory::select(
            'id',
            'quantity',
            'date_and_time',
            'factory_id',
            'status',
        )
            ->orderByDesc('id')
            ->where('factory_id',  $user->factory_id)
            ->where('status',  0);


        $TransferToFactory = $query->paginate($perPage, "", "current_page", $page);
        return self::formatPaginatedResponse($TransferToFactory, self::formatTransferToFactoryDataForDisplay($TransferToFactory->items()));
    }
    public function getTransferToFactoryById($id)
    {
        $user = auth('sanctum')->user();

        $query = TransferToFactory::select(
            'id',
            'association_id',
            'driver_id',
            'factory_id',
            'means_of_transportation',
            'quantity',
            'date_and_time',
            'status',
            'notes',
        )
            ->where('factory_id',  $user->factory_id)
            ->where('id', $id)
            ->first();

        return self::formatTransferToFactoryData($query);
    }
}
