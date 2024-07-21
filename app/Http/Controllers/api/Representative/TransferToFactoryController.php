<?php

namespace App\Http\Controllers\Api\Representative;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Association\TransferToFactoryController as Transfer;
use App\Models\ReceiptFromAssociation;
use App\Models\TransferToFactory;
use Illuminate\Http\Request;

class TransferToFactoryController extends Transfer
{
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
            ->where('factory_id',  $user->factory_id);


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
            ->where('association_id',  $user->id)
            ->where('id', $id)
            ->first();

        return self::formatCollectingData($query);
    }
}
